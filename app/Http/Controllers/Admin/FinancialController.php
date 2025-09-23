<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Withdrawal;
use App\Models\CommissionPayment;
use App\Models\Expense;
use App\Models\User;
use App\Models\Sale;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FinancialController extends Controller
{
    /**
     * Display financial dashboard
     */
    public function index()
    {
        $stats = [
            'total_revenue' => Sale::confirmed()->sum('amount'),
            'total_commissions' => Sale::confirmed()->sum('commission_amount'),
            'total_expenses' => Expense::sum('amount'),
            'pending_withdrawals' => Withdrawal::pending()->sum('amount'),
            'pending_commission_payments' => CommissionPayment::pending()->sum('amount'),
            'completed_withdrawals' => Withdrawal::completed()->sum('amount'),
            'paid_commissions' => CommissionPayment::paid()->sum('amount'),
        ];

        $stats['net_profit'] = $stats['total_revenue'] - $stats['total_commissions'] - $stats['total_expenses'];

        // Recent transactions
        $recentWithdrawals = Withdrawal::with('user')->orderBy('created_at', 'desc')->limit(5)->get();
        $recentExpenses = Expense::with('creator')->orderBy('created_at', 'desc')->limit(5)->get();
        $recentCommissionPayments = CommissionPayment::with('user')->orderBy('created_at', 'desc')->limit(5)->get();
        $recentSales = Sale::with(['user', 'plan'])->orderBy('created_at', 'desc')->limit(5)->get();

        // Combine all recent transactions
        $recent_transactions = collect();
        
        // Add withdrawals
        $recentWithdrawals->each(function ($withdrawal) use ($recent_transactions) {
            $recent_transactions->push((object)[
                'type' => 'withdrawal',
                'description' => 'Withdrawal by ' . $withdrawal->user->name,
                'amount' => -$withdrawal->amount,
                'status' => $withdrawal->status,
                'created_at' => $withdrawal->created_at,
            ]);
        });
        
        // Add expenses
        $recentExpenses->each(function ($expense) use ($recent_transactions) {
            $recent_transactions->push((object)[
                'type' => 'expense',
                'description' => $expense->title,
                'amount' => -$expense->amount,
                'status' => 'completed',
                'created_at' => $expense->created_at,
            ]);
        });
        
        // Add commission payments
        $recentCommissionPayments->each(function ($payment) use ($recent_transactions) {
            $recent_transactions->push((object)[
                'type' => 'commission',
                'description' => 'Commission payment to ' . $payment->user->name,
                'amount' => -$payment->amount,
                'status' => $payment->status,
                'created_at' => $payment->created_at,
            ]);
        });
        
        // Add sales
        $recentSales->each(function ($sale) use ($recent_transactions) {
            $recent_transactions->push((object)[
                'type' => 'sale',
                'description' => 'Sale: ' . $sale->plan->name ?? 'Unknown Plan',
                'amount' => $sale->amount,
                'status' => $sale->status,
                'created_at' => $sale->created_at,
            ]);
        });

        // Sort by created_at desc and take only 10 most recent
        $recent_transactions = $recent_transactions->sortByDesc('created_at')->take(10);

        // Monthly financial data for charts
        $monthlyData = $this->getMonthlyFinancialData();

        return view('admin.financial.index', compact(
            'stats', 
            'recentWithdrawals', 
            'recentExpenses', 
            'recentCommissionPayments',
            'recent_transactions',
            'monthlyData'
        ));
    }

    /**
     * Display financial reports
     */
    public function reports(Request $request)
    {
        $period = $request->get('period', 'month');
        $startDate = $this->getStartDate($period);
        $endDate = Carbon::now();

        $reports = [
            'revenue' => Sale::confirmed()
                ->whereBetween('sale_date', [$startDate, $endDate])
                ->sum('amount'),
            'commissions' => Sale::confirmed()
                ->whereBetween('sale_date', [$startDate, $endDate])
                ->sum('commission_amount'),
            'expenses' => Expense::whereBetween('expense_date', [$startDate, $endDate])
                ->sum('amount'),
            'withdrawals' => Withdrawal::completed()
                ->whereBetween('processed_date', [$startDate, $endDate])
                ->sum('net_amount'),
            'commission_payments' => CommissionPayment::paid()
                ->whereBetween('payment_date', [$startDate, $endDate])
                ->sum('amount'),
        ];

        $reports['profit'] = $reports['revenue'] - $reports['commissions'] - $reports['expenses'];

        // Category-wise expense breakdown
        $expenseByCategory = Expense::selectRaw('category, SUM(amount) as total')
            ->whereBetween('expense_date', [$startDate, $endDate])
            ->groupBy('category')
            ->get();

        // Top affiliates by commission
        $topAffiliates = User::affiliates()
            ->selectRaw('users.name, SUM(sales.commission_amount) as total_commission, COUNT(sales.id) as sales_count')
            ->join('sales', 'users.id', '=', 'sales.affiliate_id')
            ->where('sales.status', 'confirmed')
            ->whereBetween('sales.sale_date', [$startDate, $endDate])
            ->groupBy('users.id', 'users.name')
            ->orderBy('total_commission', 'desc')
            ->limit(10)
            ->get();

        return view('admin.financial.reports', compact(
            'reports', 
            'expenseByCategory', 
            'topAffiliates',
            'period',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Display withdrawals management
     */
    public function withdrawals(Request $request)
    {
        $query = Withdrawal::with('user');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('request_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('request_date', '<=', $request->date_to);
        }

        $withdrawals = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.financial.withdrawals', compact('withdrawals'));
    }

    /**
     * Process a withdrawal
     */
    public function processWithdrawal(Request $request, Withdrawal $withdrawal)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:completed,rejected',
            'payment_reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        if (!$withdrawal->canBeProcessed()) {
            return redirect()->back()
                ->with('error', 'This withdrawal cannot be processed.');
        }

        DB::beginTransaction();
        try {
            $withdrawal->status = $request->status;
            $withdrawal->processed_date = now();
            $withdrawal->processed_by = auth('admin')->id();
            $withdrawal->payment_reference = $request->payment_reference;
            $withdrawal->notes = $request->notes;
            $withdrawal->save();

            DB::commit();

            return redirect()->back()
                ->with('success', 'Withdrawal processed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to process withdrawal: ' . $e->getMessage());
        }
    }

    /**
     * Display expenses management
     */
    public function expenses(Request $request)
    {
        $query = Expense::with('creator');

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('expense_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('expense_date', '<=', $request->date_to);
        }

        $expenses = $query->orderBy('expense_date', 'desc')->paginate(15);
        $categories = Expense::getCategories();

        return view('admin.financial.expenses.index', compact('expenses', 'categories'));
    }

    /**
     * Show the form for creating a new expense
     */
    public function create()
    {
        $categories = Expense::getCategories();
        return view('admin.financial.expenses.create', compact('categories'));
    }

    /**
     * Store a newly created expense
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'category' => 'required|in:' . implode(',', array_keys(Expense::getCategories())),
            'expense_date' => 'required|date',
            'receipt' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $expense = new Expense($request->except(['receipt']));
        $expense->created_by = auth('admin')->id();

        if ($request->hasFile('receipt')) {
            $receiptPath = $request->file('receipt')->store('expenses', 'public');
            $expense->receipt_path = $receiptPath;
        }

        $expense->save();

        return redirect()->route('admin.financial.expenses.index')
            ->with('success', 'Expense created successfully.');
    }

    /**
     * Show the form for editing the expense
     */
    public function edit(Expense $expense)
    {
        $categories = Expense::getCategories();
        return view('admin.financial.expenses.edit', compact('expense', 'categories'));
    }

    /**
     * Update the specified expense
     */
    public function update(Request $request, Expense $expense)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'category' => 'required|in:' . implode(',', array_keys(Expense::getCategories())),
            'expense_date' => 'required|date',
            'receipt' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $expense->fill($request->except(['receipt']));

        if ($request->hasFile('receipt')) {
            // Delete old receipt
            if ($expense->receipt_path && Storage::disk('public')->exists($expense->receipt_path)) {
                Storage::disk('public')->delete($expense->receipt_path);
            }

            $receiptPath = $request->file('receipt')->store('expenses', 'public');
            $expense->receipt_path = $receiptPath;
        }

        $expense->save();

        return redirect()->route('admin.financial.expenses.index')
            ->with('success', 'Expense updated successfully.');
    }

    /**
     * Remove the specified expense
     */
    public function destroy(Expense $expense)
    {
        // Delete receipt file
        if ($expense->receipt_path && Storage::disk('public')->exists($expense->receipt_path)) {
            Storage::disk('public')->delete($expense->receipt_path);
        }

        $expense->delete();

        return redirect()->route('admin.financial.expenses.index')
            ->with('success', 'Expense deleted successfully.');
    }

    /**
     * Display commission payments management
     */
    public function commissionPayments(Request $request)
    {
        $query = CommissionPayment::with('user');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $commissionPayments = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.financial.commission-payments.index', compact('commissionPayments'));
    }

    /**
     * Show the form for creating a new commission payment
     */
    public function createCommissionPayment()
    {
        $users = User::affiliates()->active()->get();
        return view('admin.financial.commission-payments.create', compact('users'));
    }

    /**
     * Store a newly created commission payment
     */
    public function storeCommissionPayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:pix,bank_transfer',
            'payment_reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::findOrFail($request->user_id);

        // Check if user has enough pending commission
        if ($user->pending_commission < $request->amount) {
            return redirect()->back()
                ->with('error', 'Insufficient pending commission for this user.')
                ->withInput();
        }

        DB::beginTransaction();
        try {
            $payment = new CommissionPayment($request->all());
            $payment->created_by = auth('admin')->id();
            $payment->save();

            DB::commit();

            return redirect()->route('admin.financial.commission-payments.index')
                ->with('success', 'Commission payment created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to create commission payment: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified commission payment
     */
    public function showCommissionPayment(CommissionPayment $commissionPayment)
    {
        $commissionPayment->load('user');
        return view('admin.financial.commission-payments.show', compact('commissionPayment'));
    }

    /**
     * Get monthly financial data for charts
     */
    private function getMonthlyFinancialData()
    {
        $startDate = Carbon::now()->subMonths(12);
        $endDate = Carbon::now();

        return [
            'revenue' => Sale::confirmed()
                ->selectRaw('DATE_FORMAT(sale_date, "%Y-%m") as month, SUM(amount) as total')
                ->whereBetween('sale_date', [$startDate, $endDate])
                ->groupBy('month')
                ->orderBy('month')
                ->get(),
            'expenses' => Expense::selectRaw('DATE_FORMAT(expense_date, "%Y-%m") as month, SUM(amount) as total')
                ->whereBetween('expense_date', [$startDate, $endDate])
                ->groupBy('month')
                ->orderBy('month')
                ->get(),
            'commissions' => Sale::confirmed()
                ->selectRaw('DATE_FORMAT(sale_date, "%Y-%m") as month, SUM(commission_amount) as total')
                ->whereBetween('sale_date', [$startDate, $endDate])
                ->groupBy('month')
                ->orderBy('month')
                ->get(),
        ];
    }

    /**
     * Get start date based on period
     */
    private function getStartDate($period)
    {
        switch ($period) {
            case 'week':
                return Carbon::now()->subWeek();
            case 'month':
                return Carbon::now()->subMonth();
            case 'quarter':
                return Carbon::now()->subQuarter();
            case 'year':
                return Carbon::now()->subYear();
            default:
                return Carbon::now()->subMonth();
        }
    }
}

