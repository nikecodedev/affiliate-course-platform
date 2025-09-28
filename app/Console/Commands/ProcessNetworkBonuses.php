<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Sale;
use App\Models\User;
use App\Services\BonusCalculationService;
use Illuminate\Support\Facades\Log;

class ProcessNetworkBonuses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bonus:process-network {--dry-run : Run without making changes} {--sale= : Process for specific sale ID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process network bonuses for confirmed sales';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting network bonus processing...');

        try {
            $isDryRun = $this->option('dry-run');
            $saleId = $this->option('sale');

            if ($isDryRun) {
                $this->info('DRY RUN MODE - No changes will be made');
                $this->displayNetworkBonusPreview($saleId);
            } else {
                $result = $this->processNetworkBonuses($saleId);
                
                if ($result) {
                    $this->info('Network bonuses processed successfully');
                    Log::info('Network bonuses processed successfully');
                } else {
                    $this->error('Failed to process network bonuses');
                    Log::error('Failed to process network bonuses');
                }
            }

        } catch (\Exception $e) {
            $this->error('Error processing network bonuses: ' . $e->getMessage());
            Log::error('Network bonus error: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }

    /**
     * Process network bonuses
     */
    private function processNetworkBonuses($saleId = null)
    {
        try {
            $query = Sale::where('status', 'confirmed')
                ->where('bonuses_processed', false);

            if ($saleId) {
                $query->where('id', $saleId);
            }

            $sales = $query->get();
            $processedCount = 0;

            foreach ($sales as $sale) {
                $bonusService = new BonusCalculationService();
                $result = $bonusService->processBonusesForInvoice($sale->invoice);

                if ($result) {
                    $sale->update(['bonuses_processed' => true]);
                    $processedCount++;

                    Log::info('Network bonuses processed for sale', [
                        'sale_id' => $sale->id,
                        'amount' => $sale->amount
                    ]);
                }
            }

            $this->info("Processed network bonuses for {$processedCount} sales");
            return true;

        } catch (\Exception $e) {
            Log::error('Network bonus processing failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Display network bonus preview
     */
    private function displayNetworkBonusPreview($saleId = null)
    {
        $this->info('Network Bonus Preview:');
        $this->line('======================');
        
        $query = Sale::where('status', 'confirmed')
            ->where('bonuses_processed', false);

        if ($saleId) {
            $query->where('id', $saleId);
        }

        $sales = $query->get();
        $totalAmount = $sales->sum('amount');

        $this->line("Pending Sales: {$sales->count()}");
        $this->line("Total Amount: R$ " . number_format($totalAmount, 2, ',', '.'));

        if ($sales->count() > 0) {
            $this->line("\nSale Details:");
            $this->line("Sale ID | User | Amount | Plan");
            $this->line("--------|------|--------|-----");

            foreach ($sales as $sale) {
                $this->line("{$sale->id} | {$sale->user->name} | R$ " . number_format($sale->amount, 2, ',', '.') . " | {$sale->plan->title}");
            }
        }
    }
}