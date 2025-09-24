<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GlobalBonusConfiguration;
use App\Models\BonusLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class GlobalBonusConfigurationController extends Controller
{
    /**
     * Display a listing of global bonus configurations
     */
    public function index()
    {
        $configurations = GlobalBonusConfiguration::with('bonusLevels')
            ->orderBy('type')
            ->get();

        return view('admin.bonus-configurations.global.index', compact('configurations'));
    }

    /**
     * Show the form for creating a new bonus configuration
     */
    public function create()
    {
        $bonusTypes = GlobalBonusConfiguration::getBonusTypes();
        return view('admin.bonus-configurations.global.create', compact('bonusTypes'));
    }

    /**
     * Store a newly created bonus configuration
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:unilevel,forced_matrix,direct_referral',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'is_percentage' => 'boolean',
            'max_depth' => 'required|integer|min:1|max:20',
            'max_width' => 'required|integer|min:1|max:10',
            'min_sale_amount' => 'required|numeric|min:0',
            'requires_active_invoice' => 'boolean',
            'levels' => 'required|array|min:1',
            'levels.*.level' => 'required|integer|min:1',
            'levels.*.amount' => 'required|numeric|min:0',
            'levels.*.is_percentage' => 'boolean',
            'levels.*.description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Create the bonus configuration
            $configuration = GlobalBonusConfiguration::create([
                'name' => $request->name,
                'type' => $request->type,
                'description' => $request->description,
                'is_active' => $request->boolean('is_active'),
                'is_percentage' => $request->boolean('is_percentage'),
                'max_depth' => $request->max_depth,
                'max_width' => $request->max_width,
                'min_sale_amount' => $request->min_sale_amount,
                'requires_active_invoice' => $request->boolean('requires_active_invoice'),
            ]);

            // Create bonus levels
            foreach ($request->levels as $levelData) {
                BonusLevel::create([
                    'bonus_configuration_id' => $configuration->id,
                    'level' => $levelData['level'],
                    'amount' => $levelData['amount'],
                    'is_percentage' => $levelData['is_percentage'] ?? false,
                    'description' => $levelData['description'] ?? null,
                    'is_active' => true,
                ]);
            }

            DB::commit();

            return redirect()->route('admin.bonus-configurations.global.index')
                ->with('success', 'Bonus configuration created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors(['error' => 'Failed to create bonus configuration: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Display the specified bonus configuration
     */
    public function show(GlobalBonusConfiguration $globalBonusConfiguration)
    {
        $globalBonusConfiguration->load('bonusLevels');
        return view('admin.bonus-configurations.global.show', compact('globalBonusConfiguration'));
    }

    /**
     * Show the form for editing the specified bonus configuration
     */
    public function edit(GlobalBonusConfiguration $globalBonusConfiguration)
    {
        $globalBonusConfiguration->load('bonusLevels');
        $bonusTypes = GlobalBonusConfiguration::getBonusTypes();
        return view('admin.bonus-configurations.global.edit', compact('globalBonusConfiguration', 'bonusTypes'));
    }

    /**
     * Update the specified bonus configuration
     */
    public function update(Request $request, GlobalBonusConfiguration $globalBonusConfiguration)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:unilevel,forced_matrix,direct_referral',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'is_percentage' => 'boolean',
            'max_depth' => 'required|integer|min:1|max:20',
            'max_width' => 'required|integer|min:1|max:10',
            'min_sale_amount' => 'required|numeric|min:0',
            'requires_active_invoice' => 'boolean',
            'levels' => 'required|array|min:1',
            'levels.*.level' => 'required|integer|min:1',
            'levels.*.amount' => 'required|numeric|min:0',
            'levels.*.is_percentage' => 'boolean',
            'levels.*.description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Update the bonus configuration
            $globalBonusConfiguration->update([
                'name' => $request->name,
                'type' => $request->type,
                'description' => $request->description,
                'is_active' => $request->boolean('is_active'),
                'is_percentage' => $request->boolean('is_percentage'),
                'max_depth' => $request->max_depth,
                'max_width' => $request->max_width,
                'min_sale_amount' => $request->min_sale_amount,
                'requires_active_invoice' => $request->boolean('requires_active_invoice'),
            ]);

            // Delete existing levels
            $globalBonusConfiguration->bonusLevels()->delete();

            // Create new bonus levels
            foreach ($request->levels as $levelData) {
                BonusLevel::create([
                    'bonus_configuration_id' => $globalBonusConfiguration->id,
                    'level' => $levelData['level'],
                    'amount' => $levelData['amount'],
                    'is_percentage' => $levelData['is_percentage'] ?? false,
                    'description' => $levelData['description'] ?? null,
                    'is_active' => true,
                ]);
            }

            DB::commit();

            return redirect()->route('admin.bonus-configurations.global.index')
                ->with('success', 'Bonus configuration updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors(['error' => 'Failed to update bonus configuration: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Remove the specified bonus configuration
     */
    public function destroy(GlobalBonusConfiguration $globalBonusConfiguration)
    {
        try {
            $globalBonusConfiguration->delete();
            return redirect()->route('admin.bonus-configurations.global.index')
                ->with('success', 'Bonus configuration deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to delete bonus configuration: ' . $e->getMessage()]);
        }
    }

    /**
     * Toggle the active status of a bonus configuration
     */
    public function toggle(GlobalBonusConfiguration $globalBonusConfiguration)
    {
        try {
            $globalBonusConfiguration->update([
                'is_active' => !$globalBonusConfiguration->is_active
            ]);

            $status = $globalBonusConfiguration->is_active ? 'activated' : 'deactivated';
            return redirect()->back()
                ->with('success', "Bonus configuration {$status} successfully!");
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to toggle bonus configuration: ' . $e->getMessage()]);
        }
    }

    /**
     * Add a new level to a bonus configuration
     */
    public function addLevel(Request $request, GlobalBonusConfiguration $globalBonusConfiguration)
    {
        $validator = Validator::make($request->all(), [
            'level' => 'required|integer|min:1|max:20',
            'amount' => 'required|numeric|min:0',
            'is_percentage' => 'boolean',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $level = BonusLevel::create([
                'bonus_configuration_id' => $globalBonusConfiguration->id,
                'level' => $request->level,
                'amount' => $request->amount,
                'is_percentage' => $request->boolean('is_percentage'),
                'description' => $request->description,
                'is_active' => true,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Level added successfully!',
                'level' => $level
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add level: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove a level from a bonus configuration
     */
    public function removeLevel(BonusLevel $bonusLevel)
    {
        try {
            $bonusLevel->delete();
            return response()->json([
                'success' => true,
                'message' => 'Level removed successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove level: ' . $e->getMessage()
            ], 500);
        }
    }
}