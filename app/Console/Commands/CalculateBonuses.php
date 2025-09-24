<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\BonusCalculationService;
use App\Models\Sale;
use App\Models\BonusPayment;
use Carbon\Carbon;

class CalculateBonuses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bonus:calculate 
                            {--period= : Period key (YYYY-MM format)}
                            {--sale-id= : Calculate bonuses for specific sale}
                            {--force : Force recalculation even if already processed}
                            {--dry-run : Show what would be calculated without creating records}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate bonus payments for sales and update referral network';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $bonusService = new BonusCalculationService();
        
        $this->info('Starting bonus calculation process...');
        
        // Handle specific sale
        if ($saleId = $this->option('sale-id')) {
            return $this->calculateBonusesForSale($saleId, $bonusService);
        }
        
        // Handle period-based calculation
        if ($period = $this->option('period')) {
            return $this->calculateBonusesForPeriod($period, $bonusService);
        }
        
        // Default: calculate for recent sales
        return $this->calculateBonusesForRecentSales($bonusService);
    }

    /**
     * Calculate bonuses for a specific sale
     */
    private function calculateBonusesForSale($saleId, BonusCalculationService $bonusService)
    {
        $sale = Sale::find($saleId);
        
        if (!$sale) {
            $this->error("Sale with ID {$saleId} not found.");
            return 1;
        }
        
        $this->info("Calculating bonuses for sale #{$sale->id}...");
        
        // Check if already processed
        $existingBonuses = BonusPayment::where('sale_id', $sale->id)->count();
        
        if ($existingBonuses > 0 && !$this->option('force')) {
            $this->warn("Sale #{$sale->id} already has {$existingBonuses} bonus payments. Use --force to recalculate.");
            return 1;
        }
        
        if ($this->option('dry-run')) {
            $this->info('DRY RUN: Would calculate bonuses for this sale');
            return 0;
        }
        
        try {
            $bonusService->calculateBonusesForSale($sale);
            $this->info("✅ Successfully calculated bonuses for sale #{$sale->id}");
            return 0;
        } catch (\Exception $e) {
            $this->error("❌ Error calculating bonuses for sale #{$sale->id}: " . $e->getMessage());
            return 1;
        }
    }

    /**
     * Calculate bonuses for a specific period
     */
    private function calculateBonusesForPeriod($period, BonusCalculationService $bonusService)
    {
        $this->info("Calculating bonuses for period: {$period}");
        
        // Validate period format
        try {
            $periodDate = Carbon::createFromFormat('Y-m', $period);
        } catch (\Exception $e) {
            $this->error("Invalid period format. Use YYYY-MM format (e.g., 2024-01)");
            return 1;
        }
        
        $startDate = $periodDate->startOfMonth();
        $endDate = $periodDate->copy()->endOfMonth();
        
        $this->info("Period: {$startDate->format('Y-m-d')} to {$endDate->format('Y-m-d')}");
        
        // Get sales for the period
        $sales = Sale::where('status', 'confirmed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();
        
        if ($sales->isEmpty()) {
            $this->warn("No confirmed sales found for period {$period}");
            return 0;
        }
        
        $this->info("Found {$sales->count()} sales to process");
        
        if ($this->option('dry-run')) {
            $this->info('DRY RUN: Would calculate bonuses for these sales');
            return 0;
        }
        
        $processed = 0;
        $errors = 0;
        
        foreach ($sales as $sale) {
            try {
                $bonusService->calculateBonusesForSale($sale);
                $processed++;
                $this->line("✅ Processed sale #{$sale->id}");
            } catch (\Exception $e) {
                $errors++;
                $this->error("❌ Error processing sale #{$sale->id}: " . $e->getMessage());
            }
        }
        
        $this->info("Period calculation completed: {$processed} processed, {$errors} errors");
        return $errors > 0 ? 1 : 0;
    }

    /**
     * Calculate bonuses for recent sales (default behavior)
     */
    private function calculateBonusesForRecentSales(BonusCalculationService $bonusService)
    {
        $this->info("Calculating bonuses for recent sales...");
        
        // Get sales from last 7 days that haven't been processed
        $startDate = now()->subDays(7);
        
        $sales = Sale::where('status', 'confirmed')
            ->where('created_at', '>=', $startDate)
            ->whereDoesntHave('bonusPayments')
            ->get();
        
        if ($sales->isEmpty()) {
            $this->info("No unprocessed sales found in the last 7 days");
            return 0;
        }
        
        $this->info("Found {$sales->count()} unprocessed sales");
        
        if ($this->option('dry-run')) {
            $this->info('DRY RUN: Would calculate bonuses for these sales');
            $this->table(
                ['Sale ID', 'Client', 'Plan', 'Amount', 'Date'],
                $sales->map(function ($sale) {
                    return [
                        $sale->id,
                        $sale->client->name ?? 'Unknown',
                        $sale->plan->title ?? 'Unknown',
                        'R$ ' . number_format($sale->amount, 2),
                        $sale->created_at->format('Y-m-d H:i')
                    ];
                })
            );
            return 0;
        }
        
        $processed = 0;
        $errors = 0;
        
        $progressBar = $this->output->createProgressBar($sales->count());
        $progressBar->start();
        
        foreach ($sales as $sale) {
            try {
                $bonusService->calculateBonusesForSale($sale);
                $processed++;
            } catch (\Exception $e) {
                $errors++;
                $this->newLine();
                $this->error("❌ Error processing sale #{$sale->id}: " . $e->getMessage());
            }
            
            $progressBar->advance();
        }
        
        $progressBar->finish();
        $this->newLine();
        
        $this->info("Recent sales calculation completed: {$processed} processed, {$errors} errors");
        
        // Process pending bonuses
        $this->info("Processing pending bonuses...");
        $pendingProcessed = $bonusService->processPendingBonuses();
        $this->info("Processed {$pendingProcessed} pending bonuses");
        
        return $errors > 0 ? 1 : 0;
    }

    /**
     * Show bonus statistics
     */
    private function showStatistics()
    {
        $this->info('Bonus Statistics:');
        
        $stats = BonusPayment::selectRaw('
            bonus_type,
            status,
            COUNT(*) as count,
            SUM(amount) as total_amount
        ')
        ->groupBy('bonus_type', 'status')
        ->get();
        
        $table = [];
        foreach ($stats as $stat) {
            $table[] = [
                ucfirst(str_replace('_', ' ', $stat->bonus_type)),
                ucfirst($stat->status),
                $stat->count,
                'R$ ' . number_format($stat->total_amount, 2)
            ];
        }
        
        $this->table(['Bonus Type', 'Status', 'Count', 'Total Amount'], $table);
    }
}