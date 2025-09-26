<?php

namespace App\Services;

use App\Models\BonusSetting;
use App\Models\User;
use App\Models\Sale;
use App\Models\Invoice;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BonusCalculationService
{
    /**
     * Process bonuses when a user pays an invoice
     */
    public function processBonusesForInvoice(Invoice $invoice)
    {
        try {
            DB::beginTransaction();

            $user = $invoice->user;
            $sale = $invoice->sale;

            // Update user's active_network status if it's their first payment
            if (!$user->active_network) {
                $user->update(['active_network' => true]);
                Log::info("User {$user->id} activated network status");
            }

            // Process Direct Referral bonus (always paid if active_network == 1)
            $this->processDirectReferralBonus($user, $sale);

            // Process other bonuses only if invoice is active
            if ($invoice->is_active) {
                $this->processUnilevelBonuses($user, $sale);
                $this->processMatrixBonuses($user, $sale);
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bonus calculation failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Process Direct Referral bonus
     */
    protected function processDirectReferralBonus(User $user, Sale $sale)
    {
        $directReferralSetting = BonusSetting::getByType('direct_referral');
        
        if (!$directReferralSetting || !$directReferralSetting->is_active) {
            return;
        }

        // Find the referrer (sponsor)
        $referrer = $user->sponsor;
        if (!$referrer) {
            return;
        }

        // Check if referrer has active network status
        if (!$referrer->active_network) {
            return;
        }

        // Calculate bonus amount
        $bonusAmount = $directReferralSetting->calculateBonusAmount(1, $sale->amount);

        if ($bonusAmount > 0) {
            $this->createBonusPayment([
                'user_id' => $referrer->id,
                'sale_id' => $sale->id,
                'bonus_type' => 'direct_referral',
                'amount' => $bonusAmount,
                'level' => 1,
                'description' => "Direct referral bonus for sale #{$sale->id}",
                'status' => 'pending',
            ]);
        }
    }

    /**
     * Process Unilevel bonuses
     */
    protected function processUnilevelBonuses(User $user, Sale $sale)
    {
        $unilevelSetting = BonusSetting::getByType('unilevel');
        
        if (!$unilevelSetting || !$unilevelSetting->is_active) {
            return;
        }

        $currentUser = $user;
        $level = 1;
        $levels = $unilevelSetting->getConfiguredLevels();

        while ($currentUser && $level <= count($levels)) {
            $sponsor = $currentUser->sponsor;
            if (!$sponsor) {
                break;
            }

            // Check if sponsor has active network status
            if (!$sponsor->active_network) {
                $currentUser = $sponsor;
                $level++;
                continue;
            }

            // Calculate bonus amount for this level
            $bonusAmount = $unilevelSetting->calculateBonusAmount($level, $sale->amount);

            if ($bonusAmount > 0) {
                $this->createBonusPayment([
                    'user_id' => $sponsor->id,
                    'sale_id' => $sale->id,
                    'bonus_type' => 'unilevel',
                    'amount' => $bonusAmount,
                    'level' => $level,
                    'description' => "Unilevel bonus level {$level} for sale #{$sale->id}",
                    'status' => 'pending',
                ]);
            }

            $currentUser = $sponsor;
            $level++;
        }
    }

    /**
     * Process Matrix bonuses
     */
    protected function processMatrixBonuses(User $user, Sale $sale)
    {
        $matrixSetting = BonusSetting::getByType('matrix');
        
        if (!$matrixSetting || !$matrixSetting->is_active) {
            return;
        }

        // Get the matrix structure for the user
        $matrixStructure = $this->getMatrixStructure($user, $matrixSetting->width, $matrixSetting->depth);
        $levels = $matrixSetting->getConfiguredLevels();

        foreach ($matrixStructure as $level => $users) {
            if ($level > count($levels)) {
                break;
            }

            foreach ($users as $matrixUser) {
                // Check if user has active network status
                if (!$matrixUser->active_network) {
                    continue;
                }

                // Calculate bonus amount for this level
                $bonusAmount = $matrixSetting->calculateBonusAmount($level, $sale->amount);

                if ($bonusAmount > 0) {
                    $this->createBonusPayment([
                        'user_id' => $matrixUser->id,
                        'sale_id' => $sale->id,
                        'bonus_type' => 'matrix',
                        'amount' => $bonusAmount,
                        'level' => $level,
                        'description' => "Matrix bonus level {$level} for sale #{$sale->id}",
                        'status' => 'pending',
                    ]);
                }
            }
        }
    }

    /**
     * Get matrix structure for a user
     */
    protected function getMatrixStructure(User $user, $width, $depth)
    {
        $matrix = [];
        $currentLevel = [$user];
        $level = 1;

        while (!empty($currentLevel) && $level <= $depth) {
            $nextLevel = [];
            $levelUsers = [];

            foreach ($currentLevel as $currentUser) {
                // Get direct referrals (children) for this user
                $children = $currentUser->referrals()->limit($width)->get();
                
                foreach ($children as $child) {
                    $levelUsers[] = $child;
                    $nextLevel[] = $child;
                }
            }

            if (!empty($levelUsers)) {
                $matrix[$level] = $levelUsers;
            }

            $currentLevel = $nextLevel;
            $level++;
        }

        return $matrix;
    }

    /**
     * Create a bonus payment record
     */
    protected function createBonusPayment(array $data)
    {
        try {
            $bonusPayment = \App\Models\BonusPayment::create([
                'client_id' => $data['user_id'],
                'sale_id' => $data['sale_id'],
                'bonus_type' => $data['bonus_type'],
                'level' => $data['level'],
                'amount' => $data['amount'],
                'status' => $data['status'],
                'eligibility_status' => 'eligible',
                'from_client_id' => $data['user_id'],
                'notes' => $data['description'],
                'period_start' => now()->startOfMonth(),
                'period_end' => now()->endOfMonth(),
                'period_key' => now()->format('Y-m'),
            ]);

            Log::info('Bonus payment created: ' . $bonusPayment->id);
            return $bonusPayment;
        } catch (\Exception $e) {
            Log::error('Failed to create bonus payment: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get bonus statistics for a user
     */
    public function getUserBonusStats(User $user, $period = 'month')
    {
        $startDate = $this->getPeriodStartDate($period);
        $endDate = $this->getPeriodEndDate($period);

        $bonusPayments = \App\Models\BonusPayment::where('client_id', $user->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $stats = [
            'total_bonuses' => $bonusPayments->sum('amount'),
            'direct_referral' => $bonusPayments->where('bonus_type', 'direct_referral')->sum('amount'),
            'unilevel' => $bonusPayments->where('bonus_type', 'unilevel')->sum('amount'),
            'matrix' => $bonusPayments->where('bonus_type', 'matrix')->sum('amount'),
            'pending' => $bonusPayments->where('status', 'pending')->sum('amount'),
            'processed' => $bonusPayments->whereIn('status', ['approved', 'paid'])->sum('amount'),
            'count_total' => $bonusPayments->count(),
            'count_pending' => $bonusPayments->where('status', 'pending')->count(),
            'count_approved' => $bonusPayments->where('status', 'approved')->count(),
            'count_paid' => $bonusPayments->where('status', 'paid')->count(),
        ];

        return $stats;
    }

    /**
     * Get period start date
     */
    protected function getPeriodStartDate($period)
    {
        switch ($period) {
            case 'week':
                return now()->startOfWeek();
            case 'month':
                return now()->startOfMonth();
            case 'year':
                return now()->startOfYear();
            default:
                return now()->startOfMonth();
        }
    }

    /**
     * Get period end date
     */
    protected function getPeriodEndDate($period)
    {
        switch ($period) {
            case 'week':
                return now()->endOfWeek();
            case 'month':
                return now()->endOfMonth();
            case 'year':
                return now()->endOfYear();
            default:
                return now()->endOfMonth();
        }
    }

    /**
     * Calculate potential bonus for a user
     */
    public function calculatePotentialBonus(User $user, $saleAmount)
    {
        $potentialBonuses = [
            'direct_referral' => 0,
            'unilevel' => 0,
            'matrix' => 0,
        ];

        // Calculate direct referral potential
        $directReferralSetting = BonusSetting::getByType('direct_referral');
        if ($directReferralSetting && $directReferralSetting->is_active) {
            $potentialBonuses['direct_referral'] = $directReferralSetting->calculateBonusAmount(1, $saleAmount);
        }

        // Calculate unilevel potential
        $unilevelSetting = BonusSetting::getByType('unilevel');
        if ($unilevelSetting && $unilevelSetting->is_active) {
            $totalUnilevel = 0;
            $levels = $unilevelSetting->getConfiguredLevels();
            foreach ($levels as $level => $config) {
                $totalUnilevel += $unilevelSetting->calculateBonusAmount($level, $saleAmount);
            }
            $potentialBonuses['unilevel'] = $totalUnilevel;
        }

        // Calculate matrix potential
        $matrixSetting = BonusSetting::getByType('matrix');
        if ($matrixSetting && $matrixSetting->is_active) {
            $totalMatrix = 0;
            $levels = $matrixSetting->getConfiguredLevels();
            foreach ($levels as $level => $config) {
                $totalMatrix += $matrixSetting->calculateBonusAmount($level, $saleAmount);
            }
            $potentialBonuses['matrix'] = $totalMatrix;
        }

        return $potentialBonuses;
    }
}