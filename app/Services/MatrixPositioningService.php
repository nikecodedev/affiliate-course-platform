<?php

namespace App\Services;

use App\Models\User;
use App\Models\ReferralNetwork;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MatrixPositioningService
{
    /**
     * Position a new user in the forced matrix
     */
    public function positionUserInMatrix(User $newUser, User $sponsor = null)
    {
        try {
            DB::beginTransaction();

            // If no sponsor provided, position at root level
            if (!$sponsor) {
                $this->positionAtRoot($newUser);
            } else {
                $this->positionUnderSponsor($newUser, $sponsor);
            }

            // Update user's matrix position
            $newUser->update([
                'matrix_position' => $this->calculateMatrixPosition($newUser),
                'matrix_level' => $this->calculateMatrixLevel($newUser),
            ]);

            DB::commit();
            Log::info('User positioned in matrix', [
                'user_id' => $newUser->id,
                'sponsor_id' => $sponsor ? $sponsor->id : null
            ]);

            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Matrix positioning failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Position user at root level
     */
    private function positionAtRoot(User $user)
    {
        // Find the first available position at root level
        $rootPosition = $this->findNextRootPosition();
        
        $user->update([
            'matrix_parent_id' => null,
            'matrix_position' => $rootPosition,
            'matrix_level' => 1,
        ]);
    }

    /**
     * Position user under sponsor
     */
    private function positionUnderSponsor(User $newUser, User $sponsor)
    {
        // Find the next available position under the sponsor
        $nextPosition = $this->findNextPositionUnderSponsor($sponsor);
        
        if (!$nextPosition) {
            // If no direct position available, find the next available position in the tree
            $nextPosition = $this->findNextAvailablePosition();
        }

        $newUser->update([
            'matrix_parent_id' => $sponsor->id,
            'matrix_position' => $nextPosition,
            'matrix_level' => $sponsor->matrix_level + 1,
        ]);
    }

    /**
     * Find next root position
     */
    private function findNextRootPosition()
    {
        $lastRootPosition = User::whereNull('matrix_parent_id')
            ->orderBy('matrix_position', 'desc')
            ->value('matrix_position');

        return ($lastRootPosition ?? 0) + 1;
    }

    /**
     * Find next position under sponsor
     */
    private function findNextPositionUnderSponsor(User $sponsor)
    {
        // Get sponsor's direct children
        $children = User::where('matrix_parent_id', $sponsor->id)
            ->orderBy('matrix_position')
            ->get();

        // Check for available positions (binary tree: max 2 children per parent)
        $maxChildren = 2; // Binary tree
        if ($children->count() < $maxChildren) {
            return $sponsor->matrix_position * 10 + $children->count() + 1;
        }

        return null; // No direct position available
    }

    /**
     * Find next available position in the entire matrix
     */
    private function findNextAvailablePosition()
    {
        // Find the user with the least number of children
        $users = User::withCount('matrixChildren')
            ->orderBy('matrix_children_count')
            ->orderBy('matrix_position')
            ->get();

        foreach ($users as $user) {
            if ($user->matrix_children_count < 2) { // Binary tree
                return $user->matrix_position * 10 + $user->matrix_children_count + 1;
            }
        }

        // If all positions are full, create new level
        return $this->createNewLevel();
    }

    /**
     * Create new level in matrix
     */
    private function createNewLevel()
    {
        $maxPosition = User::max('matrix_position') ?? 0;
        return $maxPosition + 1;
    }

    /**
     * Calculate matrix position based on tree structure
     */
    private function calculateMatrixPosition(User $user)
    {
        if (!$user->matrix_parent_id) {
            return $user->matrix_position;
        }

        $parent = User::find($user->matrix_parent_id);
        if (!$parent) {
            return $user->matrix_position;
        }

        // Calculate position based on parent's position and child index
        $siblingIndex = User::where('matrix_parent_id', $parent->id)
            ->where('id', '!=', $user->id)
            ->count();

        return $parent->matrix_position * 10 + $siblingIndex + 1;
    }

    /**
     * Calculate matrix level
     */
    private function calculateMatrixLevel(User $user)
    {
        if (!$user->matrix_parent_id) {
            return 1;
        }

        $parent = User::find($user->matrix_parent_id);
        return $parent ? $parent->matrix_level + 1 : 1;
    }

    /**
     * Get matrix structure for a user
     */
    public function getMatrixStructure(User $user, $depth = 3)
    {
        $structure = [
            'user' => $user,
            'children' => [],
            'level' => $user->matrix_level,
            'position' => $user->matrix_position,
        ];

        if ($depth > 0) {
            $children = User::where('matrix_parent_id', $user->id)
                ->orderBy('matrix_position')
                ->get();

            foreach ($children as $child) {
                $structure['children'][] = $this->getMatrixStructure($child, $depth - 1);
            }
        }

        return $structure;
    }

    /**
     * Get matrix statistics for a user
     */
    public function getMatrixStatistics(User $user)
    {
        $totalChildren = $this->countTotalChildren($user);
        $activeChildren = $this->countActiveChildren($user);
        $matrixVolume = $this->calculateMatrixVolume($user);

        return [
            'total_children' => $totalChildren,
            'active_children' => $activeChildren,
            'matrix_volume' => $matrixVolume,
            'matrix_level' => $user->matrix_level,
            'matrix_position' => $user->matrix_position,
        ];
    }

    /**
     * Count total children in matrix
     */
    private function countTotalChildren(User $user)
    {
        return User::where('matrix_parent_id', $user->id)->count() +
               User::where('matrix_parent_id', $user->id)
                   ->with('matrixChildren')
                   ->get()
                   ->sum(function ($child) {
                       return $this->countTotalChildren($child);
                   });
    }

    /**
     * Count active children in matrix
     */
    private function countActiveChildren(User $user)
    {
        return User::where('matrix_parent_id', $user->id)
            ->where('active_network', true)
            ->count() +
            User::where('matrix_parent_id', $user->id)
                ->where('active_network', true)
                ->with('matrixChildren')
                ->get()
                ->sum(function ($child) {
                    return $this->countActiveChildren($child);
                });
    }

    /**
     * Calculate matrix volume
     */
    private function calculateMatrixVolume(User $user)
    {
        $directVolume = \App\Models\Sale::where('user_id', $user->id)
            ->where('status', 'confirmed')
            ->sum('amount');

        $childrenVolume = User::where('matrix_parent_id', $user->id)
            ->get()
            ->sum(function ($child) {
                return $this->calculateMatrixVolume($child);
            });

        return $directVolume + $childrenVolume;
    }

    /**
     * Rebalance matrix structure
     */
    public function rebalanceMatrix()
    {
        try {
            DB::beginTransaction();

            // Get all users ordered by registration date
            $users = User::orderBy('created_at')->get();
            
            // Clear existing matrix positions
            User::query()->update([
                'matrix_parent_id' => null,
                'matrix_position' => null,
                'matrix_level' => null,
            ]);

            // Reposition all users
            foreach ($users as $user) {
                $this->positionUserInMatrix($user);
            }

            DB::commit();
            Log::info('Matrix rebalanced successfully');

            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Matrix rebalancing failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get matrix visualization data
     */
    public function getMatrixVisualization($rootUserId = null)
    {
        if (!$rootUserId) {
            $rootUser = User::whereNull('matrix_parent_id')
                ->orderBy('matrix_position')
                ->first();
        } else {
            $rootUser = User::find($rootUserId);
        }

        if (!$rootUser) {
            return null;
        }

        return $this->buildMatrixTree($rootUser, 5); // Max depth of 5 levels
    }

    /**
     * Build matrix tree structure
     */
    private function buildMatrixTree(User $user, $maxDepth = 5, $currentDepth = 0)
    {
        if ($currentDepth >= $maxDepth) {
            return null;
        }

        $children = User::where('matrix_parent_id', $user->id)
            ->orderBy('matrix_position')
            ->get();

        $tree = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'position' => $user->matrix_position,
            'level' => $user->matrix_level,
            'active' => $user->active_network,
            'children' => []
        ];

        foreach ($children as $child) {
            $childTree = $this->buildMatrixTree($child, $maxDepth, $currentDepth + 1);
            if ($childTree) {
                $tree['children'][] = $childTree;
            }
        }

        return $tree;
    }
}
