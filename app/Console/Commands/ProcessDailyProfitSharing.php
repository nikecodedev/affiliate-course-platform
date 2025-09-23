<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Sale;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessDailyProfitSharing extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'profit:process-daily-sharing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process daily profit sharing among active affiliates';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting daily profit sharing processing...');

        try {
            DB::beginTransaction();

            // Get yesterday's confirmed sales
            $yesterday = now()->subDay()->toDateString();
            $totalRevenue = Sale::confirmed()
                ->whereDate('sale_date', $yesterday)
                ->sum('amount');

            if ($totalRevenue <= 0) {
                $this->info('No revenue for yesterday. Skipping profit sharing.');
                return 0;
            }

            // Get profit sharing percentage from settings
            $profitSharingPercentage = SystemSetting::get('daily_profit_sharing_percentage', 5.0);
            $totalProfitShare = $totalRevenue * ($profitSharingPercentage / 100);

            // Get all active affiliates with sales in the last 30 days
            $activeAffiliates = User::affiliates()
                ->active()
                ->whereHas('affiliateSales', function ($query) {
                    $query->confirmed()
                          ->where('sale_date', '>=', now()->subDays(30));
                })
                ->withCount(['affiliateSales as recent_sales_count' => function ($query) {
                    $query->confirmed()
                          ->where('sale_date', '>=', now()->subDays(30));
                }])
                ->withSum(['affiliateSales as recent_sales_amount' => function ($query) {
                    $query->confirmed()
                          ->where('sale_date', '>=', now()->subDays(30));
                }], 'amount')
                ->get();

            if ($activeAffiliates->isEmpty()) {
                $this->info('No active affiliates found. Skipping profit sharing.');
                return 0;
            }

            $totalWeight = $activeAffiliates->sum(function ($affiliate) {
                return $affiliate->recent_sales_count * $affiliate->recent_sales_amount;
            });

            $distributedAmount = 0;

            foreach ($activeAffiliates as $affiliate) {
                $weight = $affiliate->recent_sales_count * $affiliate->recent_sales_amount;
                $sharePercentage = $totalWeight > 0 ? ($weight / $totalWeight) : 0;
                $shareAmount = $totalProfitShare * $sharePercentage;

                if ($shareAmount > 0) {
                    // Create profit share record
                    // You might want to create a separate profit_shares table
                    Log::info("Profit share: User: {$affiliate->name}, Amount: R$ {$shareAmount}, Weight: {$weight}");
                    $distributedAmount += $shareAmount;
                }
            }

            DB::commit();

            $this->info("Daily profit sharing completed. Total distributed: R$ {$distributedAmount}");
            Log::info("Daily profit sharing completed. Total distributed: R$ {$distributedAmount}");

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Error processing daily profit sharing: ' . $e->getMessage());
            Log::error('Error processing daily profit sharing: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}

