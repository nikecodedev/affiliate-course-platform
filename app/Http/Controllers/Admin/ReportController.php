<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\User;
use App\Models\BonusPayment;
use App\Models\Expense;
use App\Models\Withdrawal;
use App\Models\ClientInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Display financial reports
     */
    public function index(Request $request)
    {
        $period = $request->get('period', 'month');
        $startDate = $this->getStartDate($period);
        $endDate = now();

        $reports = [
            'revenue' => $this->getRevenueReport($startDate, $endDate),
            'commissions' => $this->getCommissionReport($startDate, $endDate),
            'expenses' => $this->getExpenseReport($startDate, $endDate),
            'withdrawals' => $this->getWithdrawalReport($startDate, $endDate),
            'top_performers' => $this->getTopPerformersReport($startDate, $endDate),
            'conversion_rates' => $this->getConversionRatesReport($startDate, $endDate),
            'matrix_performance' => $this->getMatrixPerformanceReport($startDate, $endDate),
        ];

        return view('admin.financial.reports', compact('reports', 'period'));
    }

    /**
     * Generate comprehensive financial report
     */
    public function generateFinancialReport(Request $request)
    {
        $period = $request->get('period', 'month');
        $format = $request->get('format', 'pdf');
        $startDate = $this->getStartDate($period);
        $endDate = now();

        $data = [
            'period' => $period,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'revenue' => $this->getRevenueReport($startDate, $endDate),
            'commissions' => $this->getCommissionReport($startDate, $endDate),
            'expenses' => $this->getExpenseReport($startDate, $endDate),
            'withdrawals' => $this->getWithdrawalReport($startDate, $endDate),
            'top_performers' => $this->getTopPerformersReport($startDate, $endDate),
            'matrix_performance' => $this->getMatrixPerformanceReport($startDate, $endDate),
        ];

        if ($format === 'excel') {
            return $this->exportToExcel($data);
        } elseif ($format === 'csv') {
            return $this->exportToCsv($data);
        } else {
            return $this->exportToPdf($data);
        }
    }

    /**
     * Get revenue report
     */
    private function getRevenueReport($startDate, $endDate)
    {
        $totalRevenue = Sale::where('status', 'confirmed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');

        $dailyRevenue = Sale::where('status', 'confirmed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, SUM(amount) as revenue')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $monthlyRevenue = Sale::where('status', 'confirmed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(amount) as revenue')
            ->groupBy('year', 'month')
            ->orderBy('year', 'month')
            ->get();

        return [
            'total' => $totalRevenue,
            'daily' => $dailyRevenue,
            'monthly' => $monthlyRevenue,
            'average_daily' => $dailyRevenue->count() > 0 ? $totalRevenue / $dailyRevenue->count() : 0,
        ];
    }

    /**
     * Get commission report
     */
    private function getCommissionReport($startDate, $endDate)
    {
        $totalCommissions = BonusPayment::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');

        $commissionByType = BonusPayment::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('bonus_type, SUM(amount) as total')
            ->groupBy('bonus_type')
            ->get();

        $pendingCommissions = BonusPayment::where('status', 'pending')
            ->sum('amount');

        return [
            'total' => $totalCommissions,
            'by_type' => $commissionByType,
            'pending' => $pendingCommissions,
            'average_per_user' => $this->getAverageCommissionPerUser($startDate, $endDate),
        ];
    }

    /**
     * Get expense report
     */
    private function getExpenseReport($startDate, $endDate)
    {
        $totalExpenses = Expense::whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');

        $expensesByCategory = Expense::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->get();

        return [
            'total' => $totalExpenses,
            'by_category' => $expensesByCategory,
            'average_daily' => $this->getAverageDailyExpenses($startDate, $endDate),
        ];
    }

    /**
     * Get withdrawal report
     */
    private function getWithdrawalReport($startDate, $endDate)
    {
        $totalWithdrawals = Withdrawal::whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');

        $withdrawalsByStatus = Withdrawal::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('status, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('status')
            ->get();

        $pendingWithdrawals = Withdrawal::where('status', 'pending')
            ->sum('amount');

        return [
            'total' => $totalWithdrawals,
            'by_status' => $withdrawalsByStatus,
            'pending' => $pendingWithdrawals,
        ];
    }

    /**
     * Get top performers report
     */
    private function getTopPerformersReport($startDate, $endDate)
    {
        $topSales = User::withCount(['sales' => function ($query) use ($startDate, $endDate) {
                $query->where('status', 'confirmed')
                    ->whereBetween('created_at', [$startDate, $endDate]);
            }])
            ->withSum(['sales' => function ($query) use ($startDate, $endDate) {
                $query->where('status', 'confirmed')
                    ->whereBetween('created_at', [$startDate, $endDate]);
            }], 'amount')
            ->orderBy('sales_sum_amount', 'desc')
            ->limit(10)
            ->get();

        $topEarners = User::withSum(['bonusPayments' => function ($query) use ($startDate, $endDate) {
                $query->where('status', 'completed')
                    ->whereBetween('created_at', [$startDate, $endDate]);
            }], 'amount')
            ->orderBy('bonus_payments_sum_amount', 'desc')
            ->limit(10)
            ->get();

        return [
            'top_sales' => $topSales,
            'top_earners' => $topEarners,
        ];
    }

    /**
     * Get conversion rates report
     */
    private function getConversionRatesReport($startDate, $endDate)
    {
        $totalLeads = \App\Models\ClientLead::whereBetween('created_at', [$startDate, $endDate])->count();
        $convertedLeads = \App\Models\ClientLead::whereBetween('created_at', [$startDate, $endDate])
            ->where('converted', true)
            ->count();

        $totalSales = Sale::where('status', 'confirmed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $totalUsers = User::whereBetween('created_at', [$startDate, $endDate])->count();

        return [
            'lead_conversion_rate' => $totalLeads > 0 ? ($convertedLeads / $totalLeads) * 100 : 0,
            'sales_conversion_rate' => $totalUsers > 0 ? ($totalSales / $totalUsers) * 100 : 0,
            'total_leads' => $totalLeads,
            'converted_leads' => $convertedLeads,
            'total_sales' => $totalSales,
            'total_users' => $totalUsers,
        ];
    }

    /**
     * Get matrix performance report
     */
    private function getMatrixPerformanceReport($startDate, $endDate)
    {
        $matrixStats = User::selectRaw('
                COUNT(*) as total_users,
                COUNT(CASE WHEN active_network = 1 THEN 1 END) as active_users,
                AVG(matrix_level) as average_level,
                MAX(matrix_level) as max_level
            ')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->first();

        $matrixDistribution = User::selectRaw('matrix_level, COUNT(*) as count')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('matrix_level')
            ->groupBy('matrix_level')
            ->orderBy('matrix_level')
            ->get();

        return [
            'stats' => $matrixStats,
            'distribution' => $matrixDistribution,
        ];
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
     * Get average commission per user
     */
    private function getAverageCommissionPerUser($startDate, $endDate)
    {
        $totalCommissions = BonusPayment::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');

        $uniqueUsers = BonusPayment::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->distinct('user_id')
            ->count('user_id');

        return $uniqueUsers > 0 ? $totalCommissions / $uniqueUsers : 0;
    }

    /**
     * Get average daily expenses
     */
    private function getAverageDailyExpenses($startDate, $endDate)
    {
        $totalExpenses = Expense::whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');

        $days = $startDate->diffInDays($endDate) + 1;
        return $days > 0 ? $totalExpenses / $days : 0;
    }

    /**
     * Export to Excel
     */
    private function exportToExcel($data)
    {
        // Implementation for Excel export
        return response()->json(['message' => 'Excel export not implemented yet']);
    }

    /**
     * Export to CSV
     */
    private function exportToCsv($data)
    {
        $filename = 'financial_report_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            
            // Write headers
            fputcsv($file, ['Financial Report', $data['start_date']->format('Y-m-d'), $data['end_date']->format('Y-m-d')]);
            fputcsv($file, []);
            
            // Write revenue data
            fputcsv($file, ['Revenue Report']);
            fputcsv($file, ['Total Revenue', $data['revenue']['total']]);
            fputcsv($file, ['Average Daily', $data['revenue']['average_daily']]);
            fputcsv($file, []);
            
            // Write commission data
            fputcsv($file, ['Commission Report']);
            fputcsv($file, ['Total Commissions', $data['commissions']['total']]);
            fputcsv($file, ['Pending Commissions', $data['commissions']['pending']]);
            fputcsv($file, []);
            
            // Write expense data
            fputcsv($file, ['Expense Report']);
            fputcsv($file, ['Total Expenses', $data['expenses']['total']]);
            fputcsv($file, ['Average Daily', $data['expenses']['average_daily']]);
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export to PDF
     */
    private function exportToPdf($data)
    {
        // Implementation for PDF export
        return response()->json(['message' => 'PDF export not implemented yet']);
    }
}