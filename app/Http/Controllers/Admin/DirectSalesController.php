<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Plan;
use App\Models\Sale;
use App\Models\Invoice;
use App\Services\BonusCalculationService;
use App\Services\MatrixPositioningService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DirectSalesController extends Controller
{
    /**
     * Display direct sales dashboard
     */
    public function index(Request $request)
    {
        $query = Sale::with(['user', 'plan', 'invoice']);

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

        $sales = $query->orderBy('created_at', 'desc')->paginate(20);
        $users = User::where('is_active', true)->get();
        $plans = Plan::where('status', true)->get();

        return view('admin.sales.direct', compact('sales', 'users', 'plans'));
    }

    /**
     * Show form to create direct sale
     */
    public function create()
    {
        $users = User::where('is_active', true)->get();
        $plans = Plan::where('status', true)->get();
        
        return view('admin.sales.create-direct', compact('users', 'plans'));
    }

    /**
     * Store direct sale
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'plan_id' => 'required|exists:plans,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string|max:255',
            'payment_reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
            'auto_confirm' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $user = User::findOrFail($request->user_id);
            $plan = Plan::findOrFail($request->plan_id);

            // Create sale record
            $sale = Sale::create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'amount' => $request->amount,
                'status' => 'pending',
                'payment_method' => $request->payment_method,
                'payment_reference' => $request->payment_reference,
                'notes' => $request->notes,
                'created_by' => auth('admin')->id(),
            ]);

            // Create invoice
            $invoice = Invoice::create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'sale_id' => $sale->id,
                'amount' => $request->amount,
                'status' => 'pending',
                'invoice_number' => $this->generateInvoiceNumber(),
                'due_date' => now()->addDays(30),
                'created_by' => auth('admin')->id(),
            ]);

            // Update sale with invoice reference
            $sale->update(['invoice_id' => $invoice->id]);

            // If auto confirm is enabled, process the sale
            if ($request->has('auto_confirm')) {
                $this->processDirectSale($sale, $invoice);
            }

            DB::commit();

            $message = $request->has('auto_confirm') 
                ? 'Direct sale created and confirmed successfully. Bonuses have been processed.'
                : 'Direct sale created successfully. You can confirm it from the sales list.';

            return redirect()->route('admin.sales.direct')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Direct sale creation failed: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Failed to create direct sale. Please try again.')
                ->withInput();
        }
    }

    /**
     * Show direct sale details
     */
    public function show(Sale $sale)
    {
        $sale->load(['user', 'plan', 'invoice', 'createdBy']);
        
        return view('admin.sales.show-direct', compact('sale'));
    }

    /**
     * Confirm direct sale
     */
    public function confirm(Request $request, Sale $sale)
    {
        if ($sale->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Sale cannot be confirmed in its current status.');
        }

        try {
            DB::beginTransaction();

            $this->processDirectSale($sale, $sale->invoice);

            DB::commit();

            return redirect()->back()
                ->with('success', 'Sale confirmed successfully. All bonuses have been processed.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Sale confirmation failed: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Failed to confirm sale. Please try again.');
        }
    }

    /**
     * Cancel direct sale
     */
    public function cancel(Request $request, Sale $sale)
    {
        if ($sale->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Sale cannot be cancelled in its current status.');
        }

        $validator = Validator::make($request->all(), [
            'cancellation_reason' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        try {
            $sale->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'cancelled_by' => auth('admin')->id(),
                'cancellation_reason' => $request->cancellation_reason,
            ]);

            // Also cancel the invoice
            if ($sale->invoice) {
                $sale->invoice->update([
                    'status' => 'cancelled',
                    'cancelled_at' => now(),
                    'cancelled_by' => auth('admin')->id(),
                ]);
            }

            return redirect()->back()
                ->with('success', 'Sale cancelled successfully.');

        } catch (\Exception $e) {
            Log::error('Sale cancellation failed: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Failed to cancel sale. Please try again.');
        }
    }

    /**
     * Process direct sale (confirm and process bonuses)
     */
    private function processDirectSale(Sale $sale, Invoice $invoice)
    {
        // Update sale status
        $sale->update([
            'status' => 'confirmed',
            'confirmed_at' => now(),
            'confirmed_by' => auth('admin')->id(),
        ]);

        // Update invoice status
        $invoice->update([
            'status' => 'paid',
            'paid_at' => now(),
            'is_active' => true,
        ]);

        // Position user in matrix if not already positioned
        $matrixService = new MatrixPositioningService();
        if (!$sale->user->matrix_position) {
            $matrixService->positionUserInMatrix($sale->user);
        }

        // Process bonuses
        $bonusService = new BonusCalculationService();
        $bonusService->processBonusesForInvoice($invoice);

        // Log the direct sale
        Log::info('Direct sale processed', [
            'sale_id' => $sale->id,
            'user_id' => $sale->user_id,
            'amount' => $sale->amount,
            'plan_id' => $sale->plan_id
        ]);
    }

    /**
     * Generate unique invoice number
     */
    private function generateInvoiceNumber()
    {
        $prefix = 'INV-';
        $year = now()->year;
        $month = now()->format('m');
        
        $lastInvoice = Invoice::where('invoice_number', 'like', $prefix . $year . $month . '%')
            ->orderBy('invoice_number', 'desc')
            ->first();

        if ($lastInvoice) {
            $lastNumber = (int) substr($lastInvoice->invoice_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . $year . $month . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get sales statistics
     */
    public function getStatistics(Request $request)
    {
        $period = $request->get('period', 'month');
        $startDate = $this->getStartDate($period);
        $endDate = now();

        $stats = [
            'total_sales' => Sale::whereBetween('created_at', [$startDate, $endDate])->count(),
            'confirmed_sales' => Sale::where('status', 'confirmed')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count(),
            'total_revenue' => Sale::where('status', 'confirmed')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('amount'),
            'average_sale' => Sale::where('status', 'confirmed')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->avg('amount'),
            'conversion_rate' => $this->calculateConversionRate($startDate, $endDate),
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

    /**
     * Calculate conversion rate
     */
    private function calculateConversionRate($startDate, $endDate)
    {
        $totalSales = Sale::whereBetween('created_at', [$startDate, $endDate])->count();
        $confirmedSales = Sale::where('status', 'confirmed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        return $totalSales > 0 ? ($confirmedSales / $totalSales) * 100 : 0;
    }
}
