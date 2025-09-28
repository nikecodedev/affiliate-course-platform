<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\BonusCalculationService;
use Illuminate\Support\Facades\Log;

class ProcessDailyProfitSharing extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bonus:process-daily-profit-sharing {--dry-run : Run without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process daily profit sharing bonuses for all active users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting daily profit sharing processing...');

        try {
            $bonusService = new BonusCalculationService();
            $isDryRun = $this->option('dry-run');

            if ($isDryRun) {
                $this->info('DRY RUN MODE - No changes will be made');
                $this->displayProfitSharingPreview($bonusService);
            } else {
                $result = $bonusService->processDailyProfitSharing();
                
                if ($result) {
                    $this->info('Daily profit sharing processed successfully');
                    Log::info('Daily profit sharing processed successfully');
                } else {
                    $this->error('Failed to process daily profit sharing');
                    Log::error('Failed to process daily profit sharing');
                }
            }

        } catch (\Exception $e) {
            $this->error('Error processing daily profit sharing: ' . $e->getMessage());
            Log::error('Daily profit sharing error: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }

    /**
     * Display profit sharing preview
     */
    private function displayProfitSharingPreview(BonusCalculationService $bonusService)
    {
        $this->info('Profit Sharing Preview:');
        $this->line('========================');
        
        // Get profit data
        $totalProfit = $this->calculateDailyProfit();
        $totalVolume = $this->getTotalVolume();
        $activeUsers = \App\Models\User::where('active_network', true)->count();

        $this->line("Total Daily Profit: R$ " . number_format($totalProfit, 2, ',', '.'));
        $this->line("Total Volume: R$ " . number_format($totalVolume, 2, ',', '.'));
        $this->line("Active Users: {$activeUsers}");

        if ($totalProfit > 0) {
            $this->line("\nProfit Distribution Preview:");
            $this->line("User ID | Volume | Share Amount");
            $this->line("--------|--------|-------------");

            $users = \App\Models\User::where('active_network', true)->get();
            foreach ($users as $user) {
                $userVolume = $this->calculateUserVolume($user);
                $profitShare = ($userVolume / $totalVolume) * $totalProfit;
                
                if ($profitShare > 0) {
                    $this->line("{$user->id} | R$ " . number_format($userVolume, 2, ',', '.') . " | R$ " . number_format($profitShare, 2, ',', '.'));
                }
            }
        }
    }

    /**
     * Calculate daily profit
     */
    private function calculateDailyProfit()
    {
        $today = now()->startOfDay();
        $tomorrow = $today->copy()->addDay();

        $totalRevenue = \App\Models\Sale::where('status', 'confirmed')
            ->whereBetween('created_at', [$today, $tomorrow])
            ->sum('amount');

        $totalExpenses = \App\Models\Expense::whereBetween('created_at', [$today, $tomorrow])
            ->sum('amount');

        return $totalRevenue - $totalExpenses;
    }

    /**
     * Calculate user volume
     */
    private function calculateUserVolume($user)
    {
        return \App\Models\Sale::where('user_id', $user->id)
            ->where('status', 'confirmed')
            ->sum('amount');
    }

    /**
     * Get total volume
     */
    private function getTotalVolume()
    {
        return \App\Models\Sale::where('status', 'confirmed')->sum('amount');
    }
}