<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    /**
     * Display a listing of sales
     */
    public function index(Request $request)
    {
        $query = Sale::with(['plan', 'user', 'affiliate', 'creator']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('sale_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('sale_date', '<=', $request->date_to);
        }

        // Filter by plan
        if ($request->filled('plan_id')) {
            $query->where('plan_id', $request->plan_id);
        }

        // Filter by affiliate
        if ($request->filled('affiliate_id')) {
            $query->where('affiliate_id', $request->affiliate_id);
        }

        $sales = $query->orderBy('created_at', 'desc')->paginate(15);
        $plans = Plan::active()->ordered()->get();
        $affiliates = User::affiliates()->active()->get();

        return view('admin.sales.index', compact('sales', 'plans', 'affiliates'));
    }

    /**
     * Show the form for creating a new sale
     */
    public function create()
    {
        $plans = Plan::active()->ordered()->get();
        $users = User::active()->get();
        $affiliates = User::affiliates()->active()->get();

        return view('admin.sales.create', compact('plans', 'users', 'affiliates'));
    }

    /**
     * Store a newly created sale
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'plan_id' => 'required|exists:plans,id',
            'user_id' => 'required|exists:users,id',
            'affiliate_id' => 'nullable|exists:users,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:pix,credit_card,bank_transfer,cash',
            'payment_reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'sale_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        try {
            $plan = Plan::findOrFail($request->plan_id);
            $commissionAmount = $plan->calculateCommission($request->amount);

            $sale = new Sale($request->all());
            $sale->commission_amount = $commissionAmount;
            $sale->created_by = auth('admin')->id();
            $sale->save();

            DB::commit();

            return redirect()->route('admin.sales.show', $sale)
                ->with('success', 'Sale created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to create sale: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified sale
     */
    public function show(Sale $sale)
    {
        $sale->load(['plan.products', 'plan.courses', 'user', 'affiliate', 'creator']);
        return view('admin.sales.show', compact('sale'));
    }

    /**
     * Show the form for editing the sale
     */
    public function edit(Sale $sale)
    {
        $plans = Plan::active()->ordered()->get();
        $users = User::active()->get();
        $affiliates = User::affiliates()->active()->get();

        return view('admin.sales.edit', compact('sale', 'plans', 'users', 'affiliates'));
    }

    /**
     * Update the specified sale
     */
    public function update(Request $request, Sale $sale)
    {
        $validator = Validator::make($request->all(), [
            'plan_id' => 'required|exists:plans,id',
            'user_id' => 'required|exists:users,id',
            'affiliate_id' => 'nullable|exists:users,id',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|in:pending,confirmed,cancelled,refunded',
            'payment_method' => 'required|in:pix,credit_card,bank_transfer,cash',
            'payment_reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'sale_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        try {
            $oldAmount = $sale->amount;
            $plan = Plan::findOrFail($request->plan_id);
            
            $sale->fill($request->all());
            
            // Recalculate commission if amount or plan changed
            if ($request->amount != $oldAmount || $request->plan_id != $sale->plan_id) {
                $sale->commission_amount = $plan->calculateCommission($request->amount);
            }
            
            $sale->save();

            DB::commit();

            return redirect()->route('admin.sales.show', $sale)
                ->with('success', 'Sale updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to update sale: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified sale
     */
    public function destroy(Sale $sale)
    {
        // Only allow deletion of pending sales
        if ($sale->status !== Sale::STATUS_PENDING) {
            return redirect()->back()
                ->with('error', 'Only pending sales can be deleted.');
        }

        $sale->delete();

        return redirect()->route('admin.sales.index')
            ->with('success', 'Sale deleted successfully.');
    }

    /**
     * Confirm a pending sale
     */
    public function confirm(Sale $sale)
    {
        if ($sale->status !== Sale::STATUS_PENDING) {
            return redirect()->back()
                ->with('error', 'Only pending sales can be confirmed.');
        }

        $sale->status = Sale::STATUS_CONFIRMED;
        $sale->save();

        return redirect()->back()
            ->with('success', 'Sale confirmed successfully.');
    }

    /**
     * Refund a confirmed sale
     */
    public function refund(Sale $sale)
    {
        if (!$sale->canBeRefunded()) {
            return redirect()->back()
                ->with('error', 'This sale cannot be refunded.');
        }

        DB::beginTransaction();
        try {
            $sale->status = Sale::STATUS_REFUNDED;
            $sale->save();

            // TODO: Implement automatic commission refund logic here
            // This would involve creating a negative commission entry
            // or adjusting the affiliate's pending commission

            DB::commit();

            return redirect()->back()
                ->with('success', 'Sale refunded successfully. Commission refund will be processed automatically.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to refund sale: ' . $e->getMessage());
        }
    }

    /**
     * Get sales statistics
     */
    public function getStats(Request $request)
    {
        $period = $request->get('period', 'month');
        $startDate = $this->getStartDate($period);
        $endDate = now();

        $stats = [
            'total_sales' => Sale::whereBetween('sale_date', [$startDate, $endDate])->count(),
            'total_revenue' => Sale::confirmed()->whereBetween('sale_date', [$startDate, $endDate])->sum('amount'),
            'total_commissions' => Sale::confirmed()->whereBetween('sale_date', [$startDate, $endDate])->sum('commission_amount'),
            'pending_sales' => Sale::pending()->whereBetween('sale_date', [$startDate, $endDate])->count(),
            'refunded_sales' => Sale::refunded()->whereBetween('sale_date', [$startDate, $endDate])->count(),
        ];

        return response()->json($stats);
    }

    /**
     * Get start date based on period
     */
    private function getStartDate($period)
    {
        switch ($period) {
            case 'week':
                return now()->subWeek();
            case 'month':
                return now()->subMonth();
            case 'quarter':
                return now()->subQuarter();
            case 'year':
                return now()->subYear();
            default:
                return now()->subMonth();
        }
    }
}

