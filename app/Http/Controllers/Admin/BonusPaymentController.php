<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BonusPayment;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class BonusPaymentController extends Controller
{
    /**
     * Display a listing of bonus payments
     */
    public function index(Request $request)
    {
        $query = BonusPayment::with(['client', 'sale']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by bonus type
        if ($request->filled('bonus_type')) {
            $query->where('bonus_type', $request->bonus_type);
        }

        // Filter by client
        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $bonusPayments = $query->orderBy('created_at', 'desc')->paginate(20);
        $clients = Client::active()->get();
        $bonusTypes = BonusPayment::getBonusTypes();

        return view('admin.bonus-payments.index', compact('bonusPayments', 'clients', 'bonusTypes'));
    }

    /**
     * Show the form for creating a new bonus payment
     */
    public function create()
    {
        $clients = Client::active()->get();
        $bonusTypes = BonusPayment::getBonusTypes();
        
        return view('admin.bonus-payments.create', compact('clients', 'bonusTypes'));
    }

    /**
     * Store a newly created bonus payment
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'client_id' => 'required|exists:clients,id',
            'bonus_type' => 'required|in:direct_referral,unilevel,matrix,profit_sharing',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|in:pending,approved,paid,cancelled',
            'eligibility_status' => 'required|in:eligible,ineligible,pending',
            'level' => 'nullable|integer|min:1',
            'notes' => 'nullable|string',
            'period_start' => 'nullable|date',
            'period_end' => 'nullable|date|after_or_equal:period_start',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            BonusPayment::create($request->all());

            return redirect()->route('admin.bonus-payments.index')
                ->with('success', 'Bonus payment created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to create bonus payment: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Display the specified bonus payment
     */
    public function show(BonusPayment $bonusPayment)
    {
        $bonusPayment->load(['client', 'sale']);
        return view('admin.bonus-payments.show', compact('bonusPayment'));
    }

    /**
     * Show the form for editing the specified bonus payment
     */
    public function edit(BonusPayment $bonusPayment)
    {
        $clients = Client::active()->get();
        $bonusTypes = BonusPayment::getBonusTypes();
        
        return view('admin.bonus-payments.edit', compact('bonusPayment', 'clients', 'bonusTypes'));
    }

    /**
     * Update the specified bonus payment
     */
    public function update(Request $request, BonusPayment $bonusPayment)
    {
        $validator = Validator::make($request->all(), [
            'client_id' => 'required|exists:clients,id',
            'bonus_type' => 'required|in:direct_referral,unilevel,matrix,profit_sharing',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|in:pending,approved,paid,cancelled',
            'eligibility_status' => 'required|in:eligible,ineligible,pending',
            'level' => 'nullable|integer|min:1',
            'notes' => 'nullable|string',
            'period_start' => 'nullable|date',
            'period_end' => 'nullable|date|after_or_equal:period_start',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $bonusPayment->update($request->all());

            return redirect()->route('admin.bonus-payments.show', $bonusPayment)
                ->with('success', 'Bonus payment updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to update bonus payment: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Remove the specified bonus payment
     */
    public function destroy(BonusPayment $bonusPayment)
    {
        // Only allow deletion of pending bonus payments
        if ($bonusPayment->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Only pending bonus payments can be deleted.');
        }

        try {
            $bonusPayment->delete();

            return redirect()->route('admin.bonus-payments.index')
                ->with('success', 'Bonus payment deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to delete bonus payment: ' . $e->getMessage()]);
        }
    }

    /**
     * Approve a bonus payment
     */
    public function approve(BonusPayment $bonusPayment)
    {
        if ($bonusPayment->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Only pending bonus payments can be approved.');
        }

        try {
            $bonusPayment->update(['status' => 'approved']);

            return redirect()->back()
                ->with('success', 'Bonus payment approved successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to approve bonus payment: ' . $e->getMessage()]);
        }
    }

    /**
     * Mark bonus payment as paid
     */
    public function markAsPaid(BonusPayment $bonusPayment)
    {
        if ($bonusPayment->status !== 'approved') {
            return redirect()->back()
                ->with('error', 'Only approved bonus payments can be marked as paid.');
        }

        try {
            $bonusPayment->update([
                'status' => 'paid',
                'paid_at' => now()
            ]);

            return redirect()->back()
                ->with('success', 'Bonus payment marked as paid successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to mark bonus payment as paid: ' . $e->getMessage()]);
        }
    }

    /**
     * Cancel a bonus payment
     */
    public function cancel(BonusPayment $bonusPayment)
    {
        if (!in_array($bonusPayment->status, ['pending', 'approved'])) {
            return redirect()->back()
                ->with('error', 'Only pending or approved bonus payments can be cancelled.');
        }

        try {
            $bonusPayment->update(['status' => 'cancelled']);

            return redirect()->back()
                ->with('success', 'Bonus payment cancelled successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to cancel bonus payment: ' . $e->getMessage()]);
        }
    }

    /**
     * Bulk approve bonus payments
     */
    public function bulkApprove(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bonus_payment_ids' => 'required|array|min:1',
            'bonus_payment_ids.*' => 'exists:bonus_payments,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        try {
            $count = BonusPayment::whereIn('id', $request->bonus_payment_ids)
                ->where('status', 'pending')
                ->update(['status' => 'approved']);

            return redirect()->back()
                ->with('success', "{$count} bonus payments approved successfully!");
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to approve bonus payments: ' . $e->getMessage()]);
        }
    }

    /**
     * Bulk mark as paid
     */
    public function bulkMarkAsPaid(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bonus_payment_ids' => 'required|array|min:1',
            'bonus_payment_ids.*' => 'exists:bonus_payments,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        try {
            $count = BonusPayment::whereIn('id', $request->bonus_payment_ids)
                ->where('status', 'approved')
                ->update([
                    'status' => 'paid',
                    'paid_at' => now()
                ]);

            return redirect()->back()
                ->with('success', "{$count} bonus payments marked as paid successfully!");
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to mark bonus payments as paid: ' . $e->getMessage()]);
        }
    }

    /**
     * Get bonus payment statistics
     */
    public function getStats(Request $request)
    {
        $period = $request->get('period', 'month');
        $startDate = $this->getStartDate($period);
        $endDate = now();

        $stats = [
            'total_payments' => BonusPayment::whereBetween('created_at', [$startDate, $endDate])->count(),
            'total_amount' => BonusPayment::whereBetween('created_at', [$startDate, $endDate])->sum('amount'),
            'pending_amount' => BonusPayment::where('status', 'pending')->whereBetween('created_at', [$startDate, $endDate])->sum('amount'),
            'approved_amount' => BonusPayment::where('status', 'approved')->whereBetween('created_at', [$startDate, $endDate])->sum('amount'),
            'paid_amount' => BonusPayment::where('status', 'paid')->whereBetween('created_at', [$startDate, $endDate])->sum('amount'),
            'cancelled_amount' => BonusPayment::where('status', 'cancelled')->whereBetween('created_at', [$startDate, $endDate])->sum('amount'),
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
