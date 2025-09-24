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
        $plans = Plan::with(['products', 'courses'])
            ->ordered()
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
            'commission_percentage' => 'nullable|numeric|min:0|max:100',
            'commission_fixed' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'has_direct_referral_bonus' => 'boolean',
            'direct_referral_is_percentage' => 'boolean',
            'direct_referral_amount' => 'nullable|numeric|min:0',
            'courses' => 'nullable|array',
            'courses.*' => 'exists:courses,id',
            'products' => 'nullable|array',
            'products.*.name' => 'required|string|max:255',
            'products.*.description' => 'nullable|string',
            'products.*.download_url' => 'nullable|url',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $plan = new Plan($request->except(['image', 'courses', 'products']));
        
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('plans', 'public');
            $plan->image = $imagePath;
        }

        $plan->save();

        // Attach courses
        if ($request->courses) {
            $plan->courses()->attach($request->courses);
        }

        // Create products
        if ($request->products) {
            foreach ($request->products as $productData) {
                $plan->products()->create($productData);
            }
        }

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
            'commission_percentage' => 'nullable|numeric|min:0|max:100',
            'commission_fixed' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'has_direct_referral_bonus' => 'boolean',
            'direct_referral_is_percentage' => 'boolean',
            'direct_referral_amount' => 'nullable|numeric|min:0',
            'courses' => 'nullable|array',
            'courses.*' => 'exists:courses,id',
            'products' => 'nullable|array',
            'products.*.id' => 'nullable|exists:plan_products,id',
            'products.*.name' => 'required|string|max:255',
            'products.*.description' => 'nullable|string',
            'products.*.download_url' => 'nullable|url',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $plan->fill($request->except(['image', 'courses', 'products']));

        if ($request->hasFile('image')) {
            // Delete old image
            if ($plan->image && Storage::disk('public')->exists($plan->image)) {
                Storage::disk('public')->delete($plan->image);
            }

            $imagePath = $request->file('image')->store('plans', 'public');
            $plan->image = $imagePath;
        }

        $plan->save();

        // Sync courses
        if ($request->courses) {
            $plan->courses()->sync($request->courses);
        } else {
            $plan->courses()->detach();
        }

        // Update products
        if ($request->products) {
            $existingProductIds = [];
            
            foreach ($request->products as $productData) {
                if (isset($productData['id'])) {
                    $product = $plan->products()->find($productData['id']);
                    if ($product) {
                        $product->update($productData);
                        $existingProductIds[] = $product->id;
                    }
                } else {
                    $newProduct = $plan->products()->create($productData);
                    $existingProductIds[] = $newProduct->id;
                }
            }

            // Delete products not in the request
            $plan->products()->whereNotIn('id', $existingProductIds)->delete();
        } else {
            $plan->products()->delete();
        }

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

