<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\User;
use App\Models\Plan;
use App\Services\BonusCalculationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InvoiceController extends Controller
{
    /**
     * Display a listing of invoices with filters
     */
    public function index(Request $request)
    {
        $query = Invoice::with(['user', 'plan', 'confirmedBy', 'refundedBy']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('plan_id')) {
            $query->where('plan_id', $request->plan_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  })
                  ->orWhereHas('plan', function($planQuery) use ($search) {
                      $planQuery->where('title', 'like', "%{$search}%");
                  });
            });
        }

        $invoices = $query->orderBy('created_at', 'desc')->paginate(20);
        
        // Get filter options
        $users = User::select('id', 'name', 'email')->orderBy('name')->get();
        $plans = Plan::select('id', 'title')->orderBy('title')->get();
        
        return view('admin.invoices.index', compact('invoices', 'users', 'plans'));
    }

    /**
     * Show the form for creating a new invoice
     */
    public function create()
    {
        $users = User::select('id', 'name', 'email')->orderBy('name')->get();
        $plans = Plan::select('id', 'title', 'sale_price', 'cost_price')->orderBy('title')->get();
        
        return view('admin.invoices.create', compact('users', 'plans'));
    }

    /**
     * Store a newly created invoice
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'plan_id' => 'required|exists:plans,id',
            'amount' => 'required|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $plan = Plan::findOrFail($request->plan_id);
        $discountAmount = $request->discount_amount ?? 0;
        $finalAmount = $request->amount - $discountAmount;

        $invoice = Invoice::create([
            'invoice_number' => Invoice::generateInvoiceNumber(),
            'user_id' => $request->user_id,
            'plan_id' => $request->plan_id,
            'amount' => $request->amount,
            'cost_price' => $plan->cost_price,
            'discount_amount' => $discountAmount,
            'final_amount' => $finalAmount,
            'status' => 'pending',
            'notes' => $request->notes,
        ]);

        return redirect()->route('admin.invoices.show', $invoice)
            ->with('success', 'Invoice created successfully.');
    }

    /**
     * Display the specified invoice
     */
    public function show(Invoice $invoice)
    {
        $invoice->load(['user', 'plan', 'confirmedBy', 'refundedBy']);
        return view('admin.invoices.show', compact('invoice'));
    }

    /**
     * Show the form for editing the specified invoice
     */
    public function edit(Invoice $invoice)
    {
        $users = User::select('id', 'name', 'email')->orderBy('name')->get();
        $plans = Plan::select('id', 'title', 'sale_price', 'cost_price')->orderBy('title')->get();
        
        return view('admin.invoices.edit', compact('invoice', 'users', 'plans'));
    }

    /**
     * Update the specified invoice
     */
    public function update(Request $request, Invoice $invoice)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'plan_id' => 'required|exists:plans,id',
            'amount' => 'required|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $plan = Plan::findOrFail($request->plan_id);
        $discountAmount = $request->discount_amount ?? 0;
        $finalAmount = $request->amount - $discountAmount;

        $invoice->update([
            'user_id' => $request->user_id,
            'plan_id' => $request->plan_id,
            'amount' => $request->amount,
            'cost_price' => $plan->cost_price,
            'discount_amount' => $discountAmount,
            'final_amount' => $finalAmount,
            'notes' => $request->notes,
        ]);

        return redirect()->route('admin.invoices.show', $invoice)
            ->with('success', 'Invoice updated successfully.');
    }

    /**
     * Mark invoice as paid
     */
    public function markAsPaid(Request $request, Invoice $invoice)
    {
        if (!$invoice->canBePaid()) {
            return redirect()->back()
                ->with('error', 'Invoice cannot be marked as paid in its current status.');
        }

        $validator = Validator::make($request->all(), [
            'payment_method' => 'required|in:bank_transfer,gateway,manual',
            'payment_reference' => 'nullable|string|max:255',
            'payment_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120', // 5MB max
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        $paymentProofUrl = null;
        if ($request->hasFile('payment_proof')) {
            $paymentProofUrl = $request->file('payment_proof')->store('invoices/payment-proofs', 'public');
        }

        $invoice->markAsPaid(
            $request->payment_method,
            $request->payment_reference,
            $paymentProofUrl
        );

        return redirect()->back()
            ->with('success', 'Invoice marked as paid successfully.');
    }

    /**
     * Mark invoice as confirmed
     */
    public function markAsConfirmed(Invoice $invoice)
    {
        if (!$invoice->canBeConfirmed()) {
            return redirect()->back()
                ->with('error', 'Invoice cannot be confirmed in its current status.');
        }

        $invoice->markAsConfirmed(auth('admin')->id());

        return redirect()->back()
            ->with('success', 'Invoice confirmed successfully.');
    }

    /**
     * Mark invoice as refunded
     */
    public function markAsRefunded(Request $request, Invoice $invoice)
    {
        if (!$invoice->canBeRefunded()) {
            return redirect()->back()
                ->with('error', 'Invoice cannot be refunded in its current status.');
        }

        $validator = Validator::make($request->all(), [
            'refund_reason' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        try {
            DB::beginTransaction();

            // Mark invoice as refunded
            $invoice->markAsRefunded(auth('admin')->id());

            // Process automatic bonus refunds
            $this->processAutomaticBonusRefunds($invoice);

            DB::commit();

            return redirect()->back()
                ->with('success', 'Invoice refunded successfully. All related bonuses have been automatically reversed.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Invoice refund failed: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Failed to process refund. Please try again or contact support.');
        }
    }

    /**
     * Process automatic bonus refunds for refunded invoice
     */
    private function processAutomaticBonusRefunds(Invoice $invoice)
    {
        try {
            $bonusService = new BonusCalculationService();
            
            // Get all bonus payments related to this invoice
            $bonusPayments = \App\Models\BonusPayment::where('invoice_id', $invoice->id)
                ->where('status', 'completed')
                ->get();

            $totalRefunded = 0;
            $refundedCount = 0;

            foreach ($bonusPayments as $bonusPayment) {
                // Create reversal entry
                $reversal = \App\Models\BonusPayment::create([
                    'user_id' => $bonusPayment->user_id,
                    'sale_id' => $bonusPayment->sale_id,
                    'invoice_id' => $bonusPayment->invoice_id,
                    'bonus_type' => $bonusPayment->bonus_type,
                    'amount' => -$bonusPayment->amount, // Negative amount for reversal
                    'level' => $bonusPayment->level,
                    'description' => "Refund reversal for invoice #{$invoice->id}: " . $bonusPayment->description,
                    'status' => 'completed',
                    'processed_at' => now(),
                ]);

                // Update user's balance
                $user = User::find($bonusPayment->user_id);
                if ($user) {
                    $user->decrement('available_balance', $bonusPayment->amount);
                    $user->decrement('total_earnings', $bonusPayment->amount);
                    
                    $totalRefunded += $bonusPayment->amount;
                    $refundedCount++;
                }

                // Mark original bonus as reversed
                $bonusPayment->update([
                    'status' => 'reversed',
                    'reversed_at' => now(),
                ]);
            }

            // Log the refund process
            Log::info('Automatic bonus refunds processed', [
                'invoice_id' => $invoice->id,
                'refunded_count' => $refundedCount,
                'total_refunded' => $totalRefunded,
                'user_id' => $invoice->user_id
            ]);

            // Send notification to affected users
            $this->notifyUsersOfBonusRefunds($bonusPayments, $invoice);

        } catch (\Exception $e) {
            Log::error('Automatic bonus refund processing failed', [
                'invoice_id' => $invoice->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Notify users of bonus refunds
     */
    private function notifyUsersOfBonusRefunds($bonusPayments, Invoice $invoice)
    {
        $affectedUsers = $bonusPayments->pluck('user_id')->unique();
        
        foreach ($affectedUsers as $userId) {
            $user = User::find($userId);
            if ($user) {
                // Send email notification
                $this->sendBonusRefundNotification($user, $invoice);
                
                // Create system notification
                $this->createSystemNotification($user, $invoice);
            }
        }
    }

    /**
     * Send bonus refund notification email
     */
    private function sendBonusRefundNotification(User $user, Invoice $invoice)
    {
        // Implementation for email notification
        // You can use Laravel's notification system here
        Log::info('Bonus refund notification sent', [
            'user_id' => $user->id,
            'invoice_id' => $invoice->id
        ]);
    }

    /**
     * Create system notification
     */
    private function createSystemNotification(User $user, Invoice $invoice)
    {
        // Create notification record
        \App\Models\Notification::create([
            'user_id' => $user->id,
            'type' => 'bonus_refund',
            'title' => 'Bonus Refund Processed',
            'message' => "Bonuses related to invoice #{$invoice->invoice_number} have been refunded due to invoice cancellation.",
            'data' => json_encode([
                'invoice_id' => $invoice->id,
                'invoice_number' => $invoice->invoice_number,
                'refund_date' => now()->format('Y-m-d H:i:s')
            ]),
            'read_at' => null,
        ]);
    }

    /**
     * Download payment proof
     */
    public function downloadPaymentProof(Invoice $invoice)
    {
        if (!$invoice->payment_proof_url || !Storage::disk('public')->exists($invoice->payment_proof_url)) {
            abort(404, 'Payment proof not found.');
        }

        return Storage::disk('public')->download($invoice->payment_proof_url);
    }
}
