<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plan;
use App\Models\Course;
use App\Models\PlanProduct;
use App\Models\PlanCourse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PlanController extends Controller
{
    /**
     * Display a listing of plans
     */
    public function index()
    {
        $plans = Plan::with(['course'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.plans.index', compact('plans'));
    }

    /**
     * Show the form for creating a new plan
     */
    public function create()
    {
        $courses = Course::active()->ordered()->get();
        return view('admin.plans.create', compact('courses'));
    }

    /**
     * Store a newly created plan
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'type' => 'required|in:physical,digital,service',
            'sale_price' => 'required|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'direct_bonus_enabled' => 'boolean',
            'direct_bonus_mode' => 'nullable|in:fixed,percentage',
            'direct_bonus_value' => 'nullable|numeric|min:0',
            'commission_unilevel' => 'nullable|array',
            'commission_unilevel.*.mode' => 'required|in:fixed,percentage',
            'commission_unilevel.*.value' => 'required|numeric|min:0',
            'commission_matrix' => 'nullable|array',
            'commission_matrix.width' => 'nullable|integer|min:1|max:10',
            'commission_matrix.depth' => 'nullable|integer|min:1|max:20',
            'commission_matrix.levels' => 'nullable|array',
            'commission_profit_sharing' => 'nullable|numeric|min:0|max:100',
            'external_url' => 'nullable|url',
            'course_id' => 'nullable|exists:courses,id',
            'status' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $planData = $request->except(['image']);
        
        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('plans', 'public');
            $planData['image'] = $imagePath;
        }

        // Create the plan
        $plan = Plan::create($planData);

        return redirect()->route('admin.plans.index')
            ->with('success', 'Plan created successfully.');
    }

    /**
     * Display the specified plan
     */
    public function show(Plan $plan)
    {
        $plan->load(['products', 'courses.modules.lessons']);
        return view('admin.plans.show', compact('plan'));
    }

    /**
     * Show the form for editing the plan
     */
    public function edit(Plan $plan)
    {
        $plan->load(['products', 'courses']);
        $courses = Course::active()->ordered()->get();
        $selectedCourses = $plan->courses->pluck('id')->toArray();

        return view('admin.plans.edit', compact('plan', 'courses', 'selectedCourses'));
    }

    /**
     * Update the specified plan
     */
    public function update(Request $request, Plan $plan)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'type' => 'required|in:physical,digital,service',
            'sale_price' => 'required|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'direct_bonus_enabled' => 'boolean',
            'direct_bonus_mode' => 'nullable|in:fixed,percentage',
            'direct_bonus_value' => 'nullable|numeric|min:0',
            'commission_unilevel' => 'nullable|array',
            'commission_unilevel.*.mode' => 'required|in:fixed,percentage',
            'commission_unilevel.*.value' => 'required|numeric|min:0',
            'commission_matrix' => 'nullable|array',
            'commission_matrix.width' => 'nullable|integer|min:1|max:10',
            'commission_matrix.depth' => 'nullable|integer|min:1|max:20',
            'commission_matrix.levels' => 'nullable|array',
            'commission_profit_sharing' => 'nullable|numeric|min:0|max:100',
            'external_url' => 'nullable|url',
            'course_id' => 'nullable|exists:courses,id',
            'status' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $planData = $request->except(['image']);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($plan->image && Storage::disk('public')->exists($plan->image)) {
                Storage::disk('public')->delete($plan->image);
            }

            $imagePath = $request->file('image')->store('plans', 'public');
            $planData['image'] = $imagePath;
        }

        $plan->update($planData);

        return redirect()->route('admin.plans.index')
            ->with('success', 'Plan updated successfully.');
    }

    /**
     * Remove the specified plan
     */
    public function destroy(Plan $plan)
    {
        // Check if plan has sales
        if ($plan->sales()->exists()) {
            return redirect()->back()
                ->with('error', 'Cannot delete plan with existing sales.');
        }

        // Delete image
        if ($plan->image && Storage::disk('public')->exists($plan->image)) {
            Storage::disk('public')->delete($plan->image);
        }

        $plan->delete();

        return redirect()->route('admin.plans.index')
            ->with('success', 'Plan deleted successfully.');
    }

    /**
     * Toggle plan active status
     */
    public function toggleStatus(Plan $plan)
    {
        $plan->is_active = !$plan->is_active;
        $plan->save();

        return response()->json([
            'success' => true,
            'is_active' => $plan->is_active,
        ]);
    }
}

