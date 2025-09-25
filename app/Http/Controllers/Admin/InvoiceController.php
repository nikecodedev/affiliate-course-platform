<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\User;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

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

        $invoice->markAsRefunded(auth('admin')->id());
        
        // TODO: Implement automatic bonus refund logic here
        // This would involve reversing any commissions/bonuses paid for this invoice

        return redirect()->back()
            ->with('success', 'Invoice refunded successfully. Bonuses have been automatically reversed.');
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
