<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessNetworkBonuses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bonus:process-network';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process network bonuses for multi-level affiliates';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting network bonus processing...');

        try {
            DB::beginTransaction();

            // Get all confirmed sales from yesterday
            $yesterday = now()->subDay()->toDateString();
            $sales = Sale::confirmed()
                ->whereDate('sale_date', $yesterday)
                ->whereNotNull('affiliate_id')
                ->get();

            $processedBonuses = 0;

            foreach ($sales as $sale) {
                $this->processNetworkBonus($sale);
                $processedBonuses++;
            }

            DB::commit();

            $this->info("Network bonus processing completed. {$processedBonuses} sales processed.");
            Log::info("Network bonus processing completed. {$processedBonuses} sales processed.");

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Error processing network bonuses: ' . $e->getMessage());
            Log::error('Error processing network bonuses: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }

    /**
     * Process network bonus for a sale
     */
    private function processNetworkBonus(Sale $sale)
    {
        $affiliate = $sale->affiliate;
        $levels = [
            1 => 0.05, // 5% for direct referral
            2 => 0.03, // 3% for second level
            3 => 0.02, // 2% for third level
        ];

        $currentUser = $affiliate;
        $level = 1;

        // Process up to 3 levels
        while ($currentUser && $level <= 3) {
            $referrer = $currentUser->referrer;

            if ($referrer && $referrer->is_affiliate) {
                $bonusPercentage = $levels[$level];
                $bonusAmount = $sale->amount * $bonusPercentage;

                // Create bonus record (you might want to create a separate bonuses table)
                // For now, we'll log the bonus
                Log::info("Network bonus: Level {$level}, User: {$referrer->name}, Amount: R$ {$bonusAmount}");

                $currentUser = $referrer;
                $level++;
            } else {
                break;
            }
        }
    }
}

