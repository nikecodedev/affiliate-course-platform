<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BonusConfiguration;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BonusConfigurationController extends Controller
{
    /**
     * Display a listing of bonus configurations
     */
    public function index(Request $request)
    {
        $query = BonusConfiguration::with('plan');

        // Filter by plan
        if ($request->filled('plan_id')) {
            $query->where('plan_id', $request->plan_id);
        }

        // Filter by bonus type
        if ($request->filled('bonus_type')) {
            $query->where('bonus_type', $request->bonus_type);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $configurations = $query->orderBy('plan_id')->orderBy('bonus_type')->paginate(20);
        $plans = Plan::active()->get();
        $bonusTypes = BonusConfiguration::getBonusTypes();

        return view('admin.bonus-configurations.index', compact('configurations', 'plans', 'bonusTypes'));
    }

    /**
     * Show the form for creating a new bonus configuration
     */
    public function create()
    {
        $plans = Plan::active()->get();
        $bonusTypes = BonusConfiguration::getBonusTypes();
        $profitSharingBases = BonusConfiguration::getProfitSharingBases();
        $periods = BonusConfiguration::getPeriods();

        return view('admin.bonus-configurations.create', compact('plans', 'bonusTypes', 'profitSharingBases', 'periods'));
    }

    /**
     * Store a newly created bonus configuration
     */
    public function store(Request $request)
    {
        $validator = $this->validateBonusConfiguration($request);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $this->prepareConfigurationData($request);
        
        BonusConfiguration::create($data);

        return redirect()->route('admin.bonus-configurations.index')
            ->with('success', 'Bonus configuration created successfully!');
    }

    /**
     * Display the specified bonus configuration
     */
    public function show(BonusConfiguration $bonusConfiguration)
    {
        $bonusConfiguration->load('plan');
        
        return view('admin.bonus-configurations.show', compact('bonusConfiguration'));
    }

    /**
     * Show the form for editing the specified bonus configuration
     */
    public function edit(BonusConfiguration $bonusConfiguration)
    {
        $plans = Plan::active()->get();
        $bonusTypes = BonusConfiguration::getBonusTypes();
        $profitSharingBases = BonusConfiguration::getProfitSharingBases();
        $periods = BonusConfiguration::getPeriods();

        return view('admin.bonus-configurations.edit', compact('bonusConfiguration', 'plans', 'bonusTypes', 'profitSharingBases', 'periods'));
    }

    /**
     * Update the specified bonus configuration
     */
    public function update(Request $request, BonusConfiguration $bonusConfiguration)
    {
        $validator = $this->validateBonusConfiguration($request, $bonusConfiguration);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $this->prepareConfigurationData($request);
        
        $bonusConfiguration->update($data);

        return redirect()->route('admin.bonus-configurations.index')
            ->with('success', 'Bonus configuration updated successfully!');
    }

    /**
     * Remove the specified bonus configuration
     */
    public function destroy(BonusConfiguration $bonusConfiguration)
    {
        // Check if there are any bonus payments using this configuration
        $hasPayments = $bonusConfiguration->bonusPayments()->exists();
        
        if ($hasPayments) {
            return redirect()->back()
                ->with('error', 'Cannot delete bonus configuration that has associated bonus payments.');
        }

        $bonusConfiguration->delete();

        return redirect()->route('admin.bonus-configurations.index')
            ->with('success', 'Bonus configuration deleted successfully!');
    }

    /**
     * Toggle active status
     */
    public function toggle(BonusConfiguration $bonusConfiguration)
    {
        $bonusConfiguration->update([
            'is_active' => !$bonusConfiguration->is_active
        ]);

        $status = $bonusConfiguration->is_active ? 'activated' : 'deactivated';
        
        return redirect()->back()
            ->with('success', "Bonus configuration {$status} successfully!");
    }

    /**
     * Validate bonus configuration data
     */
    private function validateBonusConfiguration(Request $request, $configuration = null)
    {
        $rules = [
            'plan_id' => 'required|exists:plans,id',
            'bonus_type' => 'required|in:direct_referral,unilevel,forced_matrix,profit_sharing',
            'requires_active_invoice' => 'boolean',
            'minimum_volume' => 'nullable|numeric|min:0',
            'maximum_bonus_per_period' => 'nullable|numeric|min:0',
            'period' => 'nullable|in:daily,weekly,monthly',
            'is_active' => 'boolean',
        ];

        // Add specific validation rules based on bonus type
        $bonusType = $request->bonus_type;
        
        switch ($bonusType) {
            case 'direct_referral':
                $rules['direct_referral_percentage'] = 'nullable|numeric|min:0|max:100';
                $rules['direct_referral_fixed'] = 'nullable|numeric|min:0';
                break;
                
            case 'unilevel':
                $rules['unilevel_percentages'] = 'nullable|array|max:10';
                $rules['unilevel_percentages.*'] = 'numeric|min:0|max:100';
                $rules['unilevel_fixed_amounts'] = 'nullable|array|max:10';
                $rules['unilevel_fixed_amounts.*'] = 'numeric|min:0';
                break;
                
            case 'forced_matrix':
                $rules['matrix_width'] = 'required|integer|min:2|max:10';
                $rules['matrix_depth'] = 'required|integer|min:1|max:20';
                $rules['matrix_percentages'] = 'nullable|array|max:20';
                $rules['matrix_percentages.*'] = 'numeric|min:0|max:100';
                $rules['matrix_fixed_amounts'] = 'nullable|array|max:20';
                $rules['matrix_fixed_amounts.*'] = 'numeric|min:0';
                break;
                
            case 'profit_sharing':
                $rules['profit_sharing_percentage'] = 'required|numeric|min:0|max:100';
                $rules['profit_sharing_basis'] = 'required|in:total_volume,personal_volume,group_volume';
                break;
        }

        return Validator::make($request->all(), $rules);
    }

    /**
     * Prepare configuration data for storage
     */
    private function prepareConfigurationData(Request $request)
    {
        $data = [
            'plan_id' => $request->plan_id,
            'bonus_type' => $request->bonus_type,
            'requires_active_invoice' => $request->boolean('requires_active_invoice'),
            'minimum_volume' => $request->minimum_volume ?? 0,
            'maximum_bonus_per_period' => $request->maximum_bonus_per_period,
            'period' => $request->period ?? 'monthly',
            'is_active' => $request->boolean('is_active'),
        ];

        // Add bonus type specific data
        switch ($request->bonus_type) {
            case 'direct_referral':
                $data['direct_referral_percentage'] = $request->direct_referral_percentage;
                $data['direct_referral_fixed'] = $request->direct_referral_fixed;
                break;
                
            case 'unilevel':
                $data['unilevel_percentages'] = $request->unilevel_percentages;
                $data['unilevel_fixed_amounts'] = $request->unilevel_fixed_amounts;
                break;
                
            case 'forced_matrix':
                $data['matrix_width'] = $request->matrix_width;
                $data['matrix_depth'] = $request->matrix_depth;
                $data['matrix_percentages'] = $request->matrix_percentages;
                $data['matrix_fixed_amounts'] = $request->matrix_fixed_amounts;
                break;
                
            case 'profit_sharing':
                $data['profit_sharing_percentage'] = $request->profit_sharing_percentage;
                $data['profit_sharing_basis'] = $request->profit_sharing_basis;
                break;
        }

        return $data;
    }

    /**
     * Get bonus statistics
     */
    public function statistics(Request $request)
    {
        $periodKey = $request->get('period', now()->format('Y-m'));
        
        $statistics = [
            'total_configurations' => BonusConfiguration::count(),
            'active_configurations' => BonusConfiguration::active()->count(),
            'configurations_by_type' => BonusConfiguration::selectRaw('bonus_type, COUNT(*) as count')
                ->groupBy('bonus_type')
                ->get()
                ->keyBy('bonus_type'),
            'configurations_by_plan' => BonusConfiguration::with('plan')
                ->selectRaw('plan_id, COUNT(*) as count')
                ->groupBy('plan_id')
                ->get()
                ->mapWithKeys(function ($item) {
                    return [$item->plan->title ?? 'Unknown' => $item->count];
                }),
        ];

        return view('admin.bonus-configurations.statistics', compact('statistics', 'periodKey'));
    }

    /**
     * Duplicate bonus configuration
     */
    public function duplicate(BonusConfiguration $bonusConfiguration)
    {
        $newConfiguration = $bonusConfiguration->replicate();
        $newConfiguration->is_active = false;
        $newConfiguration->save();

        return redirect()->route('admin.bonus-configurations.edit', $newConfiguration)
            ->with('success', 'Bonus configuration duplicated successfully! You can now edit it.');
    }
}