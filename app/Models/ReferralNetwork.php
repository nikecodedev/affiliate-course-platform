<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReferralNetwork extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'sponsor_id',
        'parent_id',
        'position',
        'level',
        'left_count',
        'right_count',
        'left_volume',
        'right_volume',
        'is_active',
        'activated_at',
    ];

    protected $casts = [
        'activated_at' => 'datetime',
        'left_volume' => 'decimal:2',
        'right_volume' => 'decimal:2',
    ];

    /**
     * Get the client that owns this network position
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the sponsor client
     */
    public function sponsor(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'sponsor_id');
    }

    /**
     * Get the parent network position
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(ReferralNetwork::class, 'parent_id');
    }

    /**
     * Get child network positions
     */
    public function children(): HasMany
    {
        return $this->hasMany(ReferralNetwork::class, 'parent_id');
    }

    /**
     * Get left child
     */
    public function leftChild(): BelongsTo
    {
        return $this->belongsTo(ReferralNetwork::class, 'parent_id')
            ->where('position', 'left');
    }

    /**
     * Get right child
     */
    public function rightChild(): BelongsTo
    {
        return $this->belongsTo(ReferralNetwork::class, 'parent_id')
            ->where('position', 'right');
    }

    /**
     * Get all descendants
     */
    public function descendants(): HasMany
    {
        return $this->hasMany(ReferralNetwork::class, 'sponsor_id', 'client_id');
    }

    /**
     * Scope for active network positions
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for specific level
     */
    public function scopeLevel($query, $level)
    {
        return $query->where('level', $level);
    }

    /**
     * Scope for specific position
     */
    public function scopePosition($query, $position)
    {
        return $query->where('position', $position);
    }

    /**
     * Get network statistics
     */
    public function getNetworkStats()
    {
        return [
            'total_children' => $this->left_count + $this->right_count,
            'total_volume' => $this->left_volume + $this->right_volume,
            'stronger_leg' => $this->left_volume >= $this->right_volume ? 'left' : 'right',
            'weaker_leg' => $this->left_volume < $this->right_volume ? 'left' : 'right',
            'weaker_leg_volume' => min($this->left_volume, $this->right_volume),
            'imbalance' => abs($this->left_volume - $this->right_volume),
        ];
    }

    /**
     * Check if position is balanced
     */
    public function isBalanced()
    {
        return $this->left_count > 0 && $this->right_count > 0;
    }

    /**
     * Get next available position for new member
     */
    public static function getNextPosition($sponsorId)
    {
        $sponsor = self::where('client_id', $sponsorId)->first();
        
        if (!$sponsor) {
            return null;
        }

        // Forced matrix positioning: top to bottom, left to right
        return self::findNextAvailablePosition($sponsor);
    }

    /**
     * Find next available position using forced matrix algorithm
     */
    private static function findNextAvailablePosition($sponsor)
    {
        // If sponsor has no children, position goes to left
        if ($sponsor->left_count === 0 && $sponsor->right_count === 0) {
            return [
                'parent_id' => $sponsor->id,
                'position' => 'left',
                'level' => $sponsor->level + 1,
            ];
        }

        // If sponsor has only left child, position goes to right
        if ($sponsor->left_count > 0 && $sponsor->right_count === 0) {
            return [
                'parent_id' => $sponsor->id,
                'position' => 'right',
                'level' => $sponsor->level + 1,
            ];
        }

        // If sponsor has both children, find the next available position in the tree
        return self::findNextPositionInTree($sponsor);
    }

    /**
     * Find next position in the tree using BFS (Breadth-First Search)
     */
    private static function findNextPositionInTree($rootSponsor)
    {
        $queue = [$rootSponsor];
        
        while (!empty($queue)) {
            $current = array_shift($queue);
            
            // Check left position
            $leftChild = self::where('parent_id', $current->id)
                ->where('position', 'left')
                ->first();
                
            if (!$leftChild) {
                return [
                    'parent_id' => $current->id,
                    'position' => 'left',
                    'level' => $current->level + 1,
                ];
            }
            
            // Check right position
            $rightChild = self::where('parent_id', $current->id)
                ->where('position', 'right')
                ->first();
                
            if (!$rightChild) {
                return [
                    'parent_id' => $current->id,
                    'position' => 'right',
                    'level' => $current->level + 1,
                ];
            }
            
            // Add children to queue for further processing
            $queue[] = $leftChild;
            $queue[] = $rightChild;
        }
        
        return null; // Tree is full
    }

    /**
     * Add new member to network
     */
    public static function addMember($clientId, $sponsorId)
    {
        $position = self::getNextPosition($sponsorId);
        
        if (!$position) {
            throw new \Exception('No available position found in the network');
        }

        // Create network position
        $networkPosition = self::create([
            'client_id' => $clientId,
            'sponsor_id' => $sponsorId,
            'parent_id' => $position['parent_id'],
            'position' => $position['position'],
            'level' => $position['level'],
            'activated_at' => now(),
        ]);

        // Update parent counts
        self::updateParentCounts($position['parent_id'], $position['position']);

        return $networkPosition;
    }

    /**
     * Update parent counts after adding new member
     */
    private static function updateParentCounts($parentId, $position)
    {
        $parent = self::find($parentId);
        
        if ($parent) {
            if ($position === 'left') {
                $parent->increment('left_count');
            } else {
                $parent->increment('right_count');
            }
            
            // Update all ancestors
            self::updateParentCounts($parent->parent_id, $parent->position);
        }
    }

    /**
     * Update volume in network
     */
    public function updateVolume($amount, $position = null)
    {
        if ($position === 'left') {
            $this->increment('left_volume', $amount);
        } elseif ($position === 'right') {
            $this->increment('right_volume', $amount);
        } else {
            // Update based on this member's position
            $this->increment($this->position . '_volume', $amount);
        }

        // Update all ancestors
        if ($this->parent) {
            $this->parent->updateVolume($amount, $this->position);
        }
    }

    /**
     * Get matrix bonus qualification
     */
    public function getMatrixQualification()
    {
        $stats = $this->getNetworkStats();
        
        return [
            'qualified' => $this->isActive() && $stats['total_children'] >= 2,
            'weaker_leg_volume' => $stats['weaker_leg_volume'],
            'total_volume' => $stats['total_volume'],
            'imbalance' => $stats['imbalance'],
        ];
    }
}