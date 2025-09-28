<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BonusPayment;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessCommissionPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bonus:process-commissions {--dry-run : Run without making changes} {--user= : Process for specific user ID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process pending commission payments and update user balances';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting commission payment processing...');

        try {
            $isDryRun = $this->option('dry-run');
            $userId = $this->option('user');

            if ($isDryRun) {
                $this->info('DRY RUN MODE - No changes will be made');
                $this->displayCommissionPreview($userId);
            } else {
                $result = $this->processCommissions($userId);
                
                if ($result) {
                    $this->info('Commission payments processed successfully');
                    Log::info('Commission payments processed successfully');
                } else {
                    $this->error('Failed to process commission payments');
                    Log::error('Failed to process commission payments');
                }
            }

        } catch (\Exception $e) {
            $this->error('Error processing commission payments: ' . $e->getMessage());
            Log::error('Commission payment error: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }

    /**
     * Process commission payments
     */
    private function processCommissions($userId = null)
    {
        try {
            DB::beginTransaction();

            $query = BonusPayment::where('status', 'pending');
            
            if ($userId) {
                $query->where('user_id', $userId);
            }

            $pendingPayments = $query->get();
            $processedCount = 0;
            $totalAmount = 0;

            foreach ($pendingPayments as $payment) {
                // Update user balance
                $user = User::find($payment->user_id);
                if ($user) {
                    $user->increment('available_balance', $payment->amount);
                    $user->increment('total_earnings', $payment->amount);
                    
                    // Update payment status
                    $payment->update([
                        'status' => 'completed',
                        'processed_at' => now(),
                    ]);

                    $processedCount++;
                    $totalAmount += $payment->amount;

                    Log::info('Commission payment processed', [
                        'user_id' => $user->id,
                        'amount' => $payment->amount,
                        'bonus_type' => $payment->bonus_type
                    ]);
                }
            }

            DB::commit();

            $this->info("Processed {$processedCount} commission payments totaling R$ " . number_format($totalAmount, 2, ',', '.'));
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Commission processing failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Display commission preview
     */
    private function displayCommissionPreview($userId = null)
    {
        $this->info('Commission Payment Preview:');
        $this->line('==========================');
        
        $query = BonusPayment::where('status', 'pending');
        
        if ($userId) {
            $query->where('user_id', $userId);
        }

        $pendingPayments = $query->get();
        $totalAmount = $pendingPayments->sum('amount');

        $this->line("Pending Payments: {$pendingPayments->count()}");
        $this->line("Total Amount: R$ " . number_format($totalAmount, 2, ',', '.'));

        if ($pendingPayments->count() > 0) {
            $this->line("\nPayment Details:");
            $this->line("User ID | Type | Amount | Description");
            $this->line("--------|------|--------|------------");

            foreach ($pendingPayments as $payment) {
                $this->line("{$payment->user_id} | {$payment->bonus_type} | R$ " . number_format($payment->amount, 2, ',', '.') . " | {$payment->description}");
            }
        }
    }
}