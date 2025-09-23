<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ClientInvoice;
use App\Models\ClientTransaction;
use App\Models\ClientWithdrawalRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class FinancialController extends Controller
{
    /**
     * Show financial dashboard
     */
    public function index()
    {
        $client = Auth::guard('client')->user();
        
        // Get financial statistics
        $stats = [
            'total_earnings' => $client->total_earnings,
            'available_balance' => $client->available_balance,
            'total_withdrawals' => $client->total_withdrawals,
            'pending_withdrawals' => $client->withdrawalRequests()->pending()->sum('amount'),
            'monthly_earnings' => $client->transactions()
                ->where('type', 'credit')
                ->where('status', 'completed')
                ->whereMonth('created_at', now()->month)
                ->sum('amount'),
        ];

        // Get recent transactions
        $recentTransactions = $client->transactions()
            ->latest()
            ->limit(10)
            ->get();

        // Get active invoices
        $activeInvoices = $client->invoices()
            ->active()
            ->with('plan')
            ->get();

        // Get recent withdrawal requests
        $recentWithdrawals = $client->withdrawalRequests()
            ->latest()
            ->limit(5)
            ->get();

        // Get monthly earnings for chart
        $monthlyEarnings = $this->getMonthlyEarningsData($client);

        // Get pending withdrawals
        $pendingWithdrawals = $client->withdrawalRequests()
            ->where('status', 'pending')
            ->latest()
            ->limit(5)
            ->get();

        return view('client.financial.index', compact(
            'client',
            'recentTransactions',
            'activeInvoices',
            'pendingWithdrawals'
        ));
    }

    /**
     * Show all transactions
     */
    public function transactions(Request $request)
    {
        $client = Auth::guard('client')->user();
        
        $query = $client->transactions();
        
        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('reference', 'like', "%{$search}%");
            });
        }
        
        // Apply sorting
        $sort = $request->get('sort', 'date_desc');
        switch ($sort) {
            case 'date_asc':
                $query->orderBy('created_at', 'asc');
                break;
            case 'amount_desc':
                $query->orderBy('amount', 'desc');
                break;
            case 'amount_asc':
                $query->orderBy('amount', 'asc');
                break;
            default:
                $query->latest();
                break;
        }
        
        $transactions = $query->paginate(20);
        
        // Calculate summary statistics
        $summary = [
            'total_credits' => $client->transactions()->where('type', 'credit')->sum('amount'),
            'total_debits' => $client->transactions()->where('type', 'debit')->sum('amount'),
            'total_transactions' => $client->transactions()->count(),
            'net_amount' => $client->total_earnings - $client->total_withdrawals,
        ];
        
        return view('client.financial.transactions', compact('transactions', 'summary'));
    }

    /**
     * Show all invoices
     */
    public function invoices(Request $request)
    {
        $client = Auth::guard('client')->user();
        
        $query = $client->invoices()->with('plan');
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $invoices = $query->latest()->paginate(15);
        
        return view('client.financial.invoices', compact('invoices', 'client'));
    }

    /**
     * Show specific invoice
     */
    public function showInvoice(ClientInvoice $invoice)
    {
        $this->authorize('view', $invoice);
        
        return view('client.financial.invoice-show', compact('invoice'));
    }

    /**
     * Upload receipt for invoice
     */
    public function uploadReceipt(Request $request, ClientInvoice $invoice)
    {
        $this->authorize('update', $invoice);
        
        $validator = Validator::make($request->all(), [
            'receipt' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        // Delete old receipt if exists
        if ($invoice->receipt_path && Storage::exists($invoice->receipt_path)) {
            Storage::delete($invoice->receipt_path);
        }

        // Store new receipt
        $path = $request->file('receipt')->store('receipts', 'public');
        
        $invoice->update(['receipt_path' => $path]);

        return redirect()->back()
            ->with('success', 'Comprovante enviado com sucesso!');
    }

    /**
     * Show withdrawal requests
     */
    public function withdrawals()
    {
        $client = Auth::guard('client')->user();
        
        $withdrawals = $client->withdrawalRequests()
            ->latest()
            ->paginate(15);
        
        return view('client.financial.withdrawals', compact('withdrawals', 'client'));
    }

    /**
     * Show create withdrawal form
     */
    public function createWithdrawal()
    {
        $client = Auth::guard('client')->user();
        
        // Check if client has available balance
        if ($client->available_balance <= 0) {
            return redirect()->route('client.financial.index')
                ->with('error', 'Você não possui saldo disponível para saque.');
        }
        
        return view('client.financial.withdrawal-create');
    }

    /**
     * Store withdrawal request
     */
    public function storeWithdrawal(Request $request)
    {
        $client = Auth::guard('client')->user();
        
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:50|max:' . $client->available_balance,
            'cpf_verification' => 'required|string|size:4',
            'withdrawal_method' => 'required|in:bank_transfer,pix',
            'bank_details' => 'required_if:withdrawal_method,bank_transfer|array',
            'bank_details.bank_name' => 'required_if:withdrawal_method,bank_transfer',
            'bank_details.account_type' => 'required_if:withdrawal_method,bank_transfer',
            'bank_details.account_number' => 'required_if:withdrawal_method,bank_transfer',
            'bank_details.agency' => 'required_if:withdrawal_method,bank_transfer',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Verify CPF (last 4 digits)
        $cpfLast4 = substr(preg_replace('/[^0-9]/', '', $client->cpf), -4);
        if ($request->cpf_verification !== $cpfLast4) {
            return redirect()->back()
                ->withErrors(['cpf_verification' => 'Os últimos 4 dígitos do CPF não conferem.'])
                ->withInput();
        }

        // Check if client has sufficient balance
        if ($request->amount > $client->available_balance) {
            return redirect()->back()
                ->withErrors(['amount' => 'Valor solicitado excede o saldo disponível.'])
                ->withInput();
        }

        // Create withdrawal request
        $withdrawal = $client->withdrawalRequests()->create([
            'amount' => $request->amount,
            'cpf_verification' => $request->cpf_verification,
            'withdrawal_method' => $request->withdrawal_method,
            'bank_details' => $request->bank_details,
            'requested_at' => now(),
        ]);

        return redirect()->route('client.financial.withdrawals')
            ->with('success', 'Solicitação de saque enviada com sucesso!');
    }

    /**
     * Show specific withdrawal request
     */
    public function showWithdrawal(ClientWithdrawalRequest $withdrawal)
    {
        $this->authorize('view', $withdrawal);
        
        return view('client.financial.withdrawal-show', compact('withdrawal'));
    }

    /**
     * Show financial statements
     */
    public function statements(Request $request)
    {
        $client = Auth::guard('client')->user();
        
        $query = $client->transactions();
        
        // Filter by date range
        $dateFrom = $request->get('date_from', now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->endOfMonth()->format('Y-m-d'));
        
        $query->whereBetween('created_at', [$dateFrom, $dateTo]);
        
        $transactions = $query->orderBy('created_at', 'desc')->get();
        
        // Calculate totals
        $totals = [
            'credits' => $transactions->where('type', 'credit')->where('status', 'completed')->sum('amount'),
            'debits' => $transactions->where('type', 'debit')->where('status', 'completed')->sum('amount'),
            'pending_credits' => $transactions->where('type', 'credit')->where('status', 'pending')->sum('amount'),
            'pending_debits' => $transactions->where('type', 'debit')->where('status', 'pending')->sum('amount'),
        ];
        
        $totals['balance'] = $totals['credits'] - $totals['debits'];
        
        return view('client.financial.statements', compact(
            'transactions',
            'totals',
            'dateFrom',
            'dateTo'
        ));
    }

    /**
     * Download financial statement
     */
    public function downloadStatement(Request $request)
    {
        $client = Auth::guard('client')->user();
        
        $dateFrom = $request->get('date_from', now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->endOfMonth()->format('Y-m-d'));
        
        $transactions = $client->transactions()
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->orderBy('created_at', 'desc')
            ->get();
        
        $filename = "extrato_{$dateFrom}_a_{$dateTo}.pdf";
        
        // TODO: Implement PDF generation
        // For now, return CSV
        $filename = "extrato_{$dateFrom}_a_{$dateTo}.csv";
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($transactions, $client, $dateFrom, $dateTo) {
            $file = fopen('php://output', 'w');
            
            // Header
            fputcsv($file, [
                'EXTRATO FINANCEIRO',
                $client->name,
                "Período: {$dateFrom} a {$dateTo}"
            ]);
            fputcsv($file, []); // Empty line
            
            fputcsv($file, [
                'Data', 'Tipo', 'Descrição', 'Valor', 'Status', 'Referência'
            ]);

            // Data
            foreach ($transactions as $transaction) {
                fputcsv($file, [
                    $transaction->created_at->format('d/m/Y H:i'),
                    $transaction->type_badge_text,
                    $transaction->description,
                    $transaction->amount,
                    $transaction->status_badge_text,
                    $transaction->reference ?? '-',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get monthly earnings data for chart
     */
    private function getMonthlyEarningsData($client)
    {
        $months = [];
        $earnings = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $month = $date->format('M/Y');
            
            $monthlyEarnings = $client->transactions()
                ->where('type', 'credit')
                ->where('status', 'completed')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('amount');

            $months[] = $month;
            $earnings[] = $monthlyEarnings;
        }

        return [
            'months' => $months,
            'earnings' => $earnings,
        ];
    }

    /**
     * Get financial statistics for dashboard
     */
    public function getStats()
    {
        $client = Auth::guard('client')->user();
        
        return response()->json([
            'total_earnings' => number_format($client->total_earnings, 2, ',', '.'),
            'available_balance' => number_format($client->available_balance, 2, ',', '.'),
            'monthly_earnings' => number_format(
                $client->transactions()
                    ->where('type', 'credit')
                    ->where('status', 'completed')
                    ->whereMonth('created_at', now()->month)
                    ->sum('amount'),
                2, ',', '.'
            ),
            'pending_withdrawals' => $client->withdrawalRequests()->pending()->count(),
        ]);
    }
}
