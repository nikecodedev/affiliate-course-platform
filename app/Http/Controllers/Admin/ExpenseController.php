<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = Expense::with('creator');

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
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

    public function create()
    {
        $categories = Expense::getCategories();
        return view('admin.financial.expenses.create', compact('categories'));
    }

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
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $expense = new Expense($request->except(['receipt']));
        $expense->created_by = auth('admin')->id();
        if ($request->hasFile('receipt')) {
            $expense->receipt_path = $request->file('receipt')->store('expenses', 'public');
        }
        $expense->save();

        return redirect()->route('admin.financial.expenses.index')->with('success', 'Expense created successfully.');
    }

    public function show(Expense $expense)
    {
        return view('admin.financial.expenses.show', compact('expense'));
    }

    public function edit(Expense $expense)
    {
        $categories = Expense::getCategories();
        return view('admin.financial.expenses.edit', compact('expense', 'categories'));
    }

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
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $expenseData = $request->except(['receipt']);

        if ($request->hasFile('receipt')) {
            // Delete old receipt
            if ($expense->receipt_path && Storage::disk('public')->exists($expense->receipt_path)) {
                Storage::disk('public')->delete($expense->receipt_path);
            }
            $expenseData['receipt_path'] = $request->file('receipt')->store('expenses', 'public');
        }

        $expense->update($expenseData);

        return redirect()->route('admin.financial.expenses.index')->with('success', 'Expense updated successfully.');
    }

    public function destroy(Expense $expense)
    {
        // Delete receipt if exists
        if ($expense->receipt_path && Storage::disk('public')->exists($expense->receipt_path)) {
            Storage::disk('public')->delete($expense->receipt_path);
        }

        $expense->delete();

        return redirect()->route('admin.financial.expenses.index')->with('success', 'Expense deleted successfully.');
    }

    public function downloadReceipt(Expense $expense)
    {
        if (!$expense->receipt_path || !Storage::disk('public')->exists($expense->receipt_path)) {
            abort(404, 'Receipt not found.');
        }
        return Storage::disk('public')->download($expense->receipt_path);
    }

    /**
     * Get expense statistics
     */
    public function getStats(Request $request)
    {
        $period = $request->get('period', 'month');
        $startDate = $this->getStartDate($period);
        $endDate = now();

        $stats = [
            'total_expenses' => Expense::whereBetween('expense_date', [$startDate, $endDate])->count(),
            'total_amount' => Expense::whereBetween('expense_date', [$startDate, $endDate])->sum('amount'),
            'by_category' => Expense::selectRaw('category, SUM(amount) as total')
                ->whereBetween('expense_date', [$startDate, $endDate])
                ->groupBy('category')
                ->get()
                ->keyBy('category'),
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


