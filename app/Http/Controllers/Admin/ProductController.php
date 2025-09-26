<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $plans = Plan::with('course')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.plans.index', compact('plans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $courses = \App\Models\Course::active()->ordered()->get();
        return view('admin.plans.create', compact('courses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:physical,digital,service',
            'sale_price' => 'required|numeric|min:0',
            'cost_price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'direct_bonus_enabled' => 'boolean',
            'direct_bonus_mode' => 'required_if:direct_bonus_enabled,1|in:percentage,fixed',
            'direct_bonus_value' => 'required_if:direct_bonus_enabled,1|numeric|min:0',
            'commission_unilevel' => 'nullable|array',
            'commission_matrix' => 'nullable|array',
            'commission_profit_sharing' => 'nullable|numeric|min:0|max:100',
            'course_id' => 'nullable|exists:courses,id',
            'status' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $path = $image->storeAs('plans', $filename, 'public');
            $data['image'] = $path;
        }

        $data['direct_bonus_enabled'] = $request->has('direct_bonus_enabled');
        $data['status'] = $request->has('status');

        Plan::create($data);

        return redirect()->route('admin.plans.index')
            ->with('success', 'Plan created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Plan $plan)
    {
        $plan->load(['course', 'invoices.user', 'sales.user']);
        
        return view('admin.plans.show', compact('plan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Plan $plan)
    {
        $courses = \App\Models\Course::active()->ordered()->get();
        return view('admin.plans.edit', compact('plan', 'courses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Plan $plan)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:physical,digital,service',
            'sale_price' => 'required|numeric|min:0',
            'cost_price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'direct_bonus_enabled' => 'boolean',
            'direct_bonus_mode' => 'required_if:direct_bonus_enabled,1|in:percentage,fixed',
            'direct_bonus_value' => 'required_if:direct_bonus_enabled,1|numeric|min:0',
            'commission_unilevel' => 'nullable|array',
            'commission_matrix' => 'nullable|array',
            'commission_profit_sharing' => 'nullable|numeric|min:0|max:100',
            'course_id' => 'nullable|exists:courses,id',
            'status' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($plan->image && Storage::disk('public')->exists($plan->image)) {
                Storage::disk('public')->delete($plan->image);
            }

            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $path = $image->storeAs('plans', $filename, 'public');
            $data['image'] = $path;
        }

        $data['direct_bonus_enabled'] = $request->has('direct_bonus_enabled');
        $data['status'] = $request->has('status');

        $plan->update($data);

        return redirect()->route('admin.plans.index')
            ->with('success', 'Plan updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Plan $plan)
    {
        // Check if plan has associated invoices or sales
        if ($plan->invoices()->exists() || $plan->sales()->exists()) {
            return redirect()->back()
                ->with('error', 'Cannot delete plan with associated invoices or sales.');
        }

        // Delete image if exists
        if ($plan->image && Storage::disk('public')->exists($plan->image)) {
            Storage::disk('public')->delete($plan->image);
        }

        $plan->delete();

        return redirect()->route('admin.plans.index')
            ->with('success', 'Plan deleted successfully.');
    }
}
