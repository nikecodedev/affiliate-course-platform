<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\User;
use App\Models\Plan;
use App\Models\Course;
use App\Models\Withdrawal;
use App\Models\CommissionPayment;
use App\Models\Expense;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Show admin dashboard
     */
    public function index()
    {
        $stats = [
            'total_sales' => Sale::confirmed()->count(),
            'total_revenue' => Sale::confirmed()->sum('amount'),
            'total_commissions' => Sale::confirmed()->sum('commission_amount'),
            'total_users' => User::count(),
            'total_affiliates' => User::affiliates()->count(),
            'total_plans' => Plan::active()->count(),
            'total_courses' => Course::active()->count(),
            'pending_withdrawals' => Withdrawal::pending()->count(),
            'pending_commission_payments' => CommissionPayment::pending()->count(),
        ];

        // Recent sales
        $recent_sales = Sale::with(['plan', 'user', 'affiliate'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Monthly revenue chart data
        $monthly_revenue = Sale::confirmed()
            ->selectRaw('DATE_FORMAT(sale_date, "%Y-%m") as month, SUM(amount) as revenue')
            ->where('sale_date', '>=', Carbon::now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Top affiliates
        $top_affiliates = User::affiliates()
            ->selectRaw('users.id, users.name, users.email, COUNT(sales.id) as sales_count, SUM(sales.amount) as total_sales, SUM(sales.commission_amount) as total_commission')
            ->leftJoin('sales', function($join) {
                $join->on('users.id', '=', 'sales.affiliate_id')
                     ->where('sales.status', '=', 'confirmed');
            })
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderBy('total_commission', 'desc')
            ->limit(10)
            ->get();

        // Recent expenses
        $recent_expenses = Expense::with('creator')
            ->orderBy('expense_date', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'recent_sales',
            'monthly_revenue',
            'top_affiliates',
            'recent_expenses'
        ));
    }

    /**
     * Get dashboard statistics for AJAX requests
     */
    public function getStats(Request $request)
    {
        $period = $request->get('period', 'month');
        $startDate = $this->getStartDate($period);
        $endDate = Carbon::now();

        $stats = [
            'sales' => Sale::confirmed()
                ->whereBetween('sale_date', [$startDate, $endDate])
                ->count(),
            'revenue' => Sale::confirmed()
                ->whereBetween('sale_date', [$startDate, $endDate])
                ->sum('amount'),
            'commissions' => Sale::confirmed()
                ->whereBetween('sale_date', [$startDate, $endDate])
                ->sum('commission_amount'),
            'expenses' => Expense::whereBetween('expense_date', [$startDate, $endDate])
                ->sum('amount'),
        ];

        $stats['profit'] = $stats['revenue'] - $stats['commissions'] - $stats['expenses'];

        return response()->json($stats);
    }

    /**
     * Get chart data for different periods
     */
    public function getChartData(Request $request)
    {
        $period = $request->get('period', 'month');
        $type = $request->get('type', 'revenue');

        $startDate = $this->getStartDate($period);
        $endDate = Carbon::now();

        $data = [];

        switch ($type) {
            case 'revenue':
                $data = Sale::confirmed()
                    ->selectRaw($this->getDateGroupBy($period) . ' as period, SUM(amount) as value')
                    ->whereBetween('sale_date', [$startDate, $endDate])
                    ->groupBy('period')
                    ->orderBy('period')
                    ->get();
                break;

            case 'commissions':
                $data = Sale::confirmed()
                    ->selectRaw($this->getDateGroupBy($period) . ' as period, SUM(commission_amount) as value')
                    ->whereBetween('sale_date', [$startDate, $endDate])
                    ->groupBy('period')
                    ->orderBy('period')
                    ->get();
                break;

            case 'sales_count':
                $data = Sale::confirmed()
                    ->selectRaw($this->getDateGroupBy($period) . ' as period, COUNT(*) as value')
                    ->whereBetween('sale_date', [$startDate, $endDate])
                    ->groupBy('period')
                    ->orderBy('period')
                    ->get();
                break;
        }

        return response()->json($data);
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

    /**
     * Get date group by clause based on period
     */
    private function getDateGroupBy($period)
    {
        switch ($period) {
            case 'week':
                return 'DATE_FORMAT(sale_date, "%Y-%u")';
            case 'month':
                return 'DATE_FORMAT(sale_date, "%Y-%m")';
            case 'quarter':
                return 'DATE_FORMAT(sale_date, "%Y-%q")';
            case 'year':
                return 'DATE_FORMAT(sale_date, "%Y")';
            default:
                return 'DATE_FORMAT(sale_date, "%Y-%m")';
        }
    }
}

