<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BonusSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BonusController extends Controller
{
    /**
     * Display a listing of bonus configurations
     */
    public function index()
    {
        $bonusTypes = BonusSetting::getBonusTypes();
        $bonusSettings = [];

        foreach ($bonusTypes as $type => $name) {
            $bonusSettings[$type] = BonusSetting::getOrCreateByType($type);
        }

        return view('admin.bonus.index', compact('bonusSettings', 'bonusTypes'));
    }

    /**
     * Show the form for editing a bonus configuration
     */
    public function edit($type)
    {
        $validTypes = array_keys(BonusSetting::getBonusTypes());
        
        if (!in_array($type, $validTypes)) {
            return redirect()->route('admin.bonus.index')
                ->with('error', 'Invalid bonus type.');
        }

        $bonusSetting = BonusSetting::getOrCreateByType($type);
        $bonusTypes = BonusSetting::getBonusTypes();
        $paymentModes = BonusSetting::getPaymentModes();

        return view('admin.bonus.edit', compact('bonusSetting', 'bonusTypes', 'paymentModes'));
    }

    /**
     * Update the specified bonus configuration
     */
    public function update(Request $request, $type)
    {
        $validTypes = array_keys(BonusSetting::getBonusTypes());
        
        if (!in_array($type, $validTypes)) {
            return redirect()->route('admin.bonus.index')
                ->with('error', 'Invalid bonus type.');
        }

        $bonusSetting = BonusSetting::getOrCreateByType($type);

        // Validation rules based on bonus type
        $rules = [
            'is_active' => 'boolean',
        ];

        if ($type === 'direct_referral') {
            $rules['payment_mode'] = 'required|in:fixed,percentage';
            $rules['value'] = 'required|numeric|min:0';
        } elseif ($type === 'unilevel') {
            $rules['levels'] = 'required|array|min:1';
            $rules['levels.*.mode'] = 'required|in:fixed,percentage';
            $rules['levels.*.value'] = 'required|numeric|min:0';
        } elseif ($type === 'matrix') {
            $rules['width'] = 'required|integer|min:1|max:10';
            $rules['depth'] = 'required|integer|min:1|max:20';
            $rules['levels'] = 'required|array|min:1';
            $rules['levels.*.mode'] = 'required|in:fixed,percentage';
            $rules['levels.*.value'] = 'required|numeric|min:0';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Update basic settings
            $bonusSetting->update([
                'is_active' => $request->boolean('is_active'),
            ]);

            // Update type-specific settings
            if ($type === 'direct_referral') {
                $bonusSetting->update([
                    'payment_mode' => $request->payment_mode,
                ]);
                
                // Set the single level for direct referral
                $bonusSetting->setLevelConfig(1, $request->payment_mode, $request->value);
            } elseif ($type === 'unilevel') {
                // Update unilevel levels
                $levels = [];
                foreach ($request->levels as $level => $config) {
                    $levels[$level] = [
                        'mode' => $config['mode'],
                        'value' => $config['value']
                    ];
                }
                $bonusSetting->update(['levels' => $levels]);
            } elseif ($type === 'matrix') {
                // Update matrix settings
                $bonusSetting->update([
                    'width' => $request->width,
                    'depth' => $request->depth,
                ]);
                
                // Update matrix levels
                $levels = [];
                foreach ($request->levels as $level => $config) {
                    $levels[$level] = [
                        'mode' => $config['mode'],
                        'value' => $config['value']
                    ];
                }
                $bonusSetting->update(['levels' => $levels]);
            }

            $bonusTypeName = $bonusSetting->getTypeDisplayName();
            return redirect()->route('admin.bonus.index')
                ->with('success', "{$bonusTypeName} bonus configuration updated successfully!");

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to update bonus configuration: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Toggle bonus setting active status
     */
    public function toggle($type)
    {
        $validTypes = array_keys(BonusSetting::getBonusTypes());
        
        if (!in_array($type, $validTypes)) {
            return redirect()->route('admin.bonus.index')
                ->with('error', 'Invalid bonus type.');
        }

        $bonusSetting = BonusSetting::getOrCreateByType($type);
        
        $bonusSetting->update([
            'is_active' => !$bonusSetting->is_active
        ]);

        $status = $bonusSetting->is_active ? 'activated' : 'deactivated';
        $bonusTypeName = $bonusSetting->getTypeDisplayName();
        
        return redirect()->back()
            ->with('success', "{$bonusTypeName} bonus {$status} successfully!");
    }

    /**
     * Add a new level to unilevel or matrix bonus
     */
    public function addLevel(Request $request, $type)
    {
        $validTypes = ['unilevel', 'matrix'];
        
        if (!in_array($type, $validTypes)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid bonus type for adding levels.'
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'level' => 'required|integer|min:1|max:20',
            'mode' => 'required|in:fixed,percentage',
            'value' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $bonusSetting = BonusSetting::getOrCreateByType($type);
            $bonusSetting->setLevelConfig($request->level, $request->mode, $request->value);

            return response()->json([
                'success' => true,
                'message' => 'Level added successfully!',
                'level' => $bonusSetting->getLevelConfig($request->level)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add level: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove a level from unilevel or matrix bonus
     */
    public function removeLevel(Request $request, $type)
    {
        $validTypes = ['unilevel', 'matrix'];
        
        if (!in_array($type, $validTypes)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid bonus type for removing levels.'
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'level' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $bonusSetting = BonusSetting::getOrCreateByType($type);
            $bonusSetting->removeLevelConfig($request->level);

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