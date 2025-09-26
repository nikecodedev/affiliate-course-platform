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
            'external_product_url' => 'nullable|url',
            'course_id' => 'nullable|exists:courses,id',
            'course_ids' => 'nullable|array',
            'course_ids.*' => 'exists:courses,id',
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

        // Sync associated courses (many-to-many)
        if ($request->filled('course_ids')) {
            $plan->courses()->sync($request->course_ids);
        }

        return redirect()->route('admin.plans.index')
            ->with('success', 'Plan created successfully.');
    }

    /**
     * Display the specified plan
     */
    public function show(Plan $plan)
    {
        $plan->load(['course']);
        return view('admin.plans.show', compact('plan'));
    }

    /**
     * Show the form for editing the plan
     */
    public function edit(Plan $plan)
    {
        $courses = Course::active()->ordered()->get();
        return view('admin.plans.edit', compact('plan', 'courses'));
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
            'external_product_url' => 'nullable|url',
            'course_id' => 'nullable|exists:courses,id',
            'course_ids' => 'nullable|array',
            'course_ids.*' => 'exists:courses,id',
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

        // Sync associated courses (many-to-many)
        $plan->courses()->sync($request->input('course_ids', []));

        return redirect()->route('admin.plans.index')
            ->with('success', 'Plan updated successfully.');
    }

    /**
     * Remove the specified plan
     */
    public function destroy(Plan $plan)
    {
        // Check for related records that prevent deletion
        $relatedRecords = [];
        $relatedCounts = [];
        
        $invoiceCount = $plan->invoices()->count();
        if ($invoiceCount > 0) {
            $relatedRecords[] = 'invoices';
            $relatedCounts['invoices'] = $invoiceCount;
        }
        
        $salesCount = $plan->sales()->count();
        if ($salesCount > 0) {
            $relatedRecords[] = 'sales';
            $relatedCounts['sales'] = $salesCount;
        }
        
        $productsCount = $plan->products()->count();
        if ($productsCount > 0) {
            $relatedRecords[] = 'products';
            $relatedCounts['products'] = $productsCount;
        }
        
        if ($relatedRecords) {
            $message = 'Cannot delete plan with existing ' . implode(', ', $relatedRecords) . '. ';
            $message .= 'This plan has: ';
            foreach ($relatedCounts as $type => $count) {
                $message .= "{$count} {$type}, ";
            }
            $message = rtrim($message, ', ') . '. ';
            $message .= 'Use "Force Delete" to delete the plan and all related records.';
            
            // Add action buttons to the error message
            $message .= '<br><br><div class="btn-group" role="group">';
            $message .= '<a href="' . route('admin.plans.confirm-delete', $plan) . '" class="btn btn-warning btn-sm me-2">';
            $message .= '<i class="fas fa-exclamation-triangle"></i> Delete with Options</a>';
            $message .= '<a href="' . route('admin.plans.force-delete', $plan) . '" class="btn btn-danger btn-sm" ';
            $message .= 'onclick="return confirm(\'⚠️ WARNING: This will permanently delete the plan and ALL related records. This action cannot be undone!\\n\\nAre you absolutely sure you want to continue?\')">';
            $message .= '<i class="fas fa-exclamation-triangle"></i> Force Delete All</a>';
            $message .= '</div>';
            
            return redirect()->back()
                ->with('error', $message);
        }

        try {
            // Detach courses (many-to-many relationship)
            $plan->courses()->detach();
            
            // Delete image
            if ($plan->image && Storage::disk('public')->exists($plan->image)) {
                Storage::disk('public')->delete($plan->image);
            }

            $plan->delete();

            return redirect()->route('admin.plans.index')
                ->with('success', 'Plan deleted successfully.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete plan: ' . $e->getMessage());
        }
    }

    /**
     * Show deletion confirmation with options
     */
    public function confirmDelete(Plan $plan)
    {
        $relatedCounts = [
            'invoices' => $plan->invoices()->count(),
            'sales' => $plan->sales()->count(),
            'products' => $plan->products()->count(),
        ];
        
        return view('admin.plans.confirm-delete', compact('plan', 'relatedCounts'));
    }

    /**
     * Force delete a plan (with related records)
     */
    public function forceDelete(Plan $plan)
    {
        try {
            // Delete related records first
            $plan->invoices()->delete();
            $plan->sales()->delete();
            $plan->products()->delete();
            
            // Detach courses (many-to-many relationship)
            $plan->courses()->detach();
            
            // Delete image
            if ($plan->image && Storage::disk('public')->exists($plan->image)) {
                Storage::disk('public')->delete($plan->image);
            }

            $plan->delete();

            return redirect()->route('admin.plans.index')
                ->with('success', 'Plan and all related records deleted successfully.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to force delete plan: ' . $e->getMessage());
        }
    }

    /**
     * Delete plan with specific handling of related records
     */
    public function deleteWithOptions(Request $request, Plan $plan)
    {
        $validator = Validator::make($request->all(), [
            'handle_invoices' => 'required|in:delete,keep,transfer',
            'handle_sales' => 'required|in:delete,keep,transfer',
            'transfer_plan_id' => 'required_if:handle_invoices,transfer|required_if:handle_sales,transfer|exists:plans,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Handle invoices
            if ($request->handle_invoices === 'delete') {
                $plan->invoices()->delete();
            } elseif ($request->handle_invoices === 'transfer') {
                $plan->invoices()->update(['plan_id' => $request->transfer_plan_id]);
            }

            // Handle sales
            if ($request->handle_sales === 'delete') {
                $plan->sales()->delete();
            } elseif ($request->handle_sales === 'transfer') {
                $plan->sales()->update(['plan_id' => $request->transfer_plan_id]);
            }

            // Always delete products (they are plan-specific)
            $plan->products()->delete();
            
            // Detach courses (many-to-many relationship)
            $plan->courses()->detach();
            
            // Delete image
            if ($plan->image && Storage::disk('public')->exists($plan->image)) {
                Storage::disk('public')->delete($plan->image);
            }

            $plan->delete();

            $message = 'Plan deleted successfully.';
            if ($request->handle_invoices === 'transfer' || $request->handle_sales === 'transfer') {
                $message .= ' Related records transferred to another plan.';
            }

            return redirect()->route('admin.plans.index')
                ->with('success', $message);
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete plan: ' . $e->getMessage());
        }
    }

    /**
     * Toggle plan active status
     */
    public function toggleStatus(Plan $plan)
    {
        $plan->status = !$plan->status;
        $plan->save();

        return response()->json([
            'success' => true,
            'status' => $plan->status,
        ]);
    }
}

