<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Sale;
use App\Models\User;
use App\Models\BonusPayment;
use App\Models\Expense;
use App\Models\Withdrawal;
use App\Models\ClientLead;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class GenerateDailyReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reports:generate-daily {--date= : Generate report for specific date (Y-m-d format)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate daily financial and performance reports';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $date = $this->option('date') ? Carbon::parse($this->option('date')) : now()->subDay();
        $startDate = $date->startOfDay();
        $endDate = $date->copy()->endOfDay();

        $this->info("Generating daily report for {$date->format('Y-m-d')}...");

        try {
            $report = $this->generateDailyReport($startDate, $endDate);
            $this->saveReport($report, $date);
            
            $this->info('Daily report generated successfully');
            Log::info('Daily report generated', ['date' => $date->format('Y-m-d')]);

        } catch (\Exception $e) {
            $this->error('Failed to generate daily report: ' . $e->getMessage());
            Log::error('Daily report generation failed', [
                'date' => $date->format('Y-m-d'),
                'error' => $e->getMessage()
            ]);
            return 1;
        }

        return 0;
    }

    /**
     * Generate daily report data
     */
    private function generateDailyReport($startDate, $endDate)
    {
        return [
            'date' => $startDate->format('Y-m-d'),
            'revenue' => $this->getDailyRevenue($startDate, $endDate),
            'commissions' => $this->getDailyCommissions($startDate, $endDate),
            'expenses' => $this->getDailyExpenses($startDate, $endDate),
            'withdrawals' => $this->getDailyWithdrawals($startDate, $endDate),
            'users' => $this->getDailyUsers($startDate, $endDate),
            'leads' => $this->getDailyLeads($startDate, $endDate),
            'conversions' => $this->getDailyConversions($startDate, $endDate),
            'matrix_stats' => $this->getDailyMatrixStats($startDate, $endDate),
        ];
    }

    /**
     * Get daily revenue data
     */
    private function getDailyRevenue($startDate, $endDate)
    {
        $totalRevenue = Sale::where('status', 'confirmed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');

        $salesCount = Sale::where('status', 'confirmed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $averageSale = $salesCount > 0 ? $totalRevenue / $salesCount : 0;

        return [
            'total' => $totalRevenue,
            'count' => $salesCount,
            'average' => $averageSale,
        ];
    }

    /**
     * Get daily commissions data
     */
    private function getDailyCommissions($startDate, $endDate)
    {
        $totalCommissions = BonusPayment::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');

        $pendingCommissions = BonusPayment::where('status', 'pending')
            ->sum('amount');

        $commissionByType = BonusPayment::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('bonus_type, SUM(amount) as total')
            ->groupBy('bonus_type')
            ->get();

        return [
            'total' => $totalCommissions,
            'pending' => $pendingCommissions,
            'by_type' => $commissionByType,
        ];
    }

    /**
     * Get daily expenses data
     */
    private function getDailyExpenses($startDate, $endDate)
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
        ];
    }

    /**
     * Get daily withdrawals data
     */
    private function getDailyWithdrawals($startDate, $endDate)
    {
        $totalWithdrawals = Withdrawal::whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');

        $withdrawalsByStatus = Withdrawal::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('status, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('status')
            ->get();

        return [
            'total' => $totalWithdrawals,
            'by_status' => $withdrawalsByStatus,
        ];
    }

    /**
     * Get daily users data
     */
    private function getDailyUsers($startDate, $endDate)
    {
        $newUsers = User::whereBetween('created_at', [$startDate, $endDate])->count();
        $activeUsers = User::where('active_network', true)
            ->whereBetween('updated_at', [$startDate, $endDate])
            ->count();

        return [
            'new' => $newUsers,
            'active' => $activeUsers,
        ];
    }

    /**
     * Get daily leads data
     */
    private function getDailyLeads($startDate, $endDate)
    {
        $totalLeads = ClientLead::whereBetween('created_at', [$startDate, $endDate])->count();
        $convertedLeads = ClientLead::whereBetween('created_at', [$startDate, $endDate])
            ->where('converted', true)
            ->count();

        return [
            'total' => $totalLeads,
            'converted' => $convertedLeads,
            'conversion_rate' => $totalLeads > 0 ? ($convertedLeads / $totalLeads) * 100 : 0,
        ];
    }

    /**
     * Get daily conversions data
     */
    private function getDailyConversions($startDate, $endDate)
    {
        $totalSales = Sale::where('status', 'confirmed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $totalUsers = User::whereBetween('created_at', [$startDate, $endDate])->count();

        return [
            'sales' => $totalSales,
            'users' => $totalUsers,
            'conversion_rate' => $totalUsers > 0 ? ($totalSales / $totalUsers) * 100 : 0,
        ];
    }

    /**
     * Get daily matrix statistics
     */
    private function getDailyMatrixStats($startDate, $endDate)
    {
        $matrixStats = User::selectRaw('
                COUNT(*) as total_users,
                COUNT(CASE WHEN active_network = 1 THEN 1 END) as active_users,
                AVG(matrix_level) as average_level,
                MAX(matrix_level) as max_level
            ')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->first();

        return [
            'total_users' => $matrixStats->total_users,
            'active_users' => $matrixStats->active_users,
            'average_level' => $matrixStats->average_level,
            'max_level' => $matrixStats->max_level,
        ];
    }

    /**
     * Save report to database or file
     */
    private function saveReport($report, $date)
    {
        // Save to database (you might want to create a reports table)
        DB::table('daily_reports')->insert([
            'date' => $date->format('Y-m-d'),
            'data' => json_encode($report),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Also save to log file for backup
        Log::info('Daily Report Generated', [
            'date' => $date->format('Y-m-d'),
            'report' => $report
        ]);
    }
}
