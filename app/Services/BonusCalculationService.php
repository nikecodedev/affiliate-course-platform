<?php

namespace App\Services;

use App\Models\Client;
use App\Models\Sale;
use App\Models\BonusConfiguration;
use App\Models\BonusPayment;
use App\Models\ReferralNetwork;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BonusCalculationService
{
    /**
     * Calculate and create bonus payments for a sale
     */
    public function calculateBonusesForSale(Sale $sale)
    {
        DB::beginTransaction();
        
        try {
            $buyer = $sale->client;
            $plan = $sale->plan;
            
            if (!$plan) {
                throw new \Exception('Sale has no associated plan');
            }

            // Get bonus configurations for the plan
            $bonusConfigs = BonusConfiguration::where('plan_id', $plan->id)
                ->active()
                ->get();

            foreach ($bonusConfigs as $config) {
                switch ($config->bonus_type) {
                    case 'direct_referral':
                        $this->calculateDirectReferralBonus($sale, $config);
                        break;
                    case 'unilevel':
                        $this->calculateUnilevelBonus($sale, $config);
                        break;
                    case 'forced_matrix':
                        $this->calculateMatrixBonus($sale, $config);
                        break;
                    case 'profit_sharing':
                        $this->calculateProfitSharingBonus($sale, $config);
                        break;
                }
            }

            DB::commit();
            Log::info('Bonuses calculated successfully for sale', ['sale_id' => $sale->id]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error calculating bonuses for sale', [
                'sale_id' => $sale->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Calculate direct referral bonus
     */
    private function calculateDirectReferralBonus(Sale $sale, BonusConfiguration $config)
    {
        $buyer = $sale->client;
        
        // Find the sponsor (referrer)
        $networkPosition = ReferralNetwork::where('client_id', $buyer->id)->first();
        
        if (!$networkPosition || !$networkPosition->sponsor_id) {
            return; // No sponsor found
        }

        $sponsor = Client::find($networkPosition->sponsor_id);
        
        if (!$sponsor) {
            return; // Sponsor not found
        }

        // Direct referral bonus is always paid (regardless of invoice status)
        $amount = $config->getDirectReferralBonus($sale->amount);
        
        if ($amount > 0) {
            $this->createBonusPayment([
                'client_id' => $sponsor->id,
                'sale_id' => $sale->id,
                'bonus_type' => 'direct_referral',
                'level' => 1,
                'amount' => $amount,
                'percentage' => $config->direct_referral_percentage,
                'base_amount' => $sale->amount,
                'from_client_id' => $buyer->id,
                'bonus_configuration_id' => $config->id,
                'eligibility_status' => 'eligible',
                'eligibility_reason' => 'Direct referral bonus - always eligible',
            ]);
        }
    }

    /**
     * Calculate unilevel bonus
     */
    private function calculateUnilevelBonus(Sale $sale, BonusConfiguration $config)
    {
        $buyer = $sale->client;
        $currentClient = $buyer;
        $level = 1;
        $maxLevels = count($config->unilevel_percentages ?: $config->unilevel_fixed_amounts ?: []);

        while ($level <= $maxLevels && $currentClient) {
            // Find sponsor
            $networkPosition = ReferralNetwork::where('client_id', $currentClient->id)->first();
            
            if (!$networkPosition || !$networkPosition->sponsor_id) {
                break; // No more sponsors
            }

            $sponsor = Client::find($networkPosition->sponsor_id);
            
            if (!$sponsor) {
                break; // Sponsor not found
            }

            // Check eligibility (requires active invoice for unilevel)
            $isEligible = $config->isEligible($sponsor);
            $eligibilityStatus = $isEligible ? 'eligible' : 'ineligible';
            $eligibilityReason = $isEligible ? 
                'Eligible for unilevel bonus' : 
                'No active invoice or does not meet minimum requirements';

            $amount = $config->getUnilevelBonus($sale->amount, $level);
            
            if ($amount > 0) {
                $this->createBonusPayment([
                    'client_id' => $sponsor->id,
                    'sale_id' => $sale->id,
                    'bonus_type' => 'unilevel',
                    'level' => $level,
                    'amount' => $amount,
                    'percentage' => $config->unilevel_percentages[$level - 1] ?? null,
                    'base_amount' => $sale->amount,
                    'from_client_id' => $buyer->id,
                    'bonus_configuration_id' => $config->id,
                    'eligibility_status' => $eligibilityStatus,
                    'eligibility_reason' => $eligibilityReason,
                ]);
            }

            $currentClient = $sponsor;
            $level++;
        }
    }

    /**
     * Calculate forced matrix bonus
     */
    private function calculateMatrixBonus(Sale $sale, BonusConfiguration $config)
    {
        $buyer = $sale->client;
        
        // Update volume in network
        $networkPosition = ReferralNetwork::where('client_id', $buyer->id)->first();
        
        if (!$networkPosition) {
            return; // Not in network
        }

        // Update volume for this position
        $networkPosition->updateVolume($sale->amount);

        // Calculate matrix bonuses for all levels
        $currentPosition = $networkPosition;
        $level = 1;
        $maxLevels = count($config->matrix_percentages ?: $config->matrix_fixed_amounts ?: []);

        while ($level <= $maxLevels && $currentPosition->parent_id) {
            $parentPosition = $currentPosition->parent;
            
            if (!$parentPosition) {
                break;
            }

            $parent = $parentPosition->client;
            
            if (!$parent) {
                break;
            }

            // Check eligibility (requires active invoice for matrix)
            $isEligible = $config->isEligible($parent);
            $eligibilityStatus = $isEligible ? 'eligible' : 'ineligible';
            $eligibilityReason = $isEligible ? 
                'Eligible for matrix bonus' : 
                'No active invoice or does not meet minimum requirements';

            // Get weaker leg volume for matrix calculation
            $qualification = $parentPosition->getMatrixQualification();
            $matrixVolume = $qualification['weaker_leg_volume'];
            
            $amount = $config->getMatrixBonus($matrixVolume, $level);
            
            if ($amount > 0) {
                $this->createBonusPayment([
                    'client_id' => $parent->id,
                    'sale_id' => $sale->id,
                    'bonus_type' => 'forced_matrix',
                    'level' => $level,
                    'amount' => $amount,
                    'percentage' => $config->matrix_percentages[$level - 1] ?? null,
                    'base_amount' => $matrixVolume,
                    'from_client_id' => $buyer->id,
                    'bonus_configuration_id' => $config->id,
                    'eligibility_status' => $eligibilityStatus,
                    'eligibility_reason' => $eligibilityReason,
                ]);
            }

            $currentPosition = $parentPosition;
            $level++;
        }
    }

    /**
     * Calculate profit sharing bonus
     */
    private function calculateProfitSharingBonus(Sale $sale, BonusConfiguration $config)
    {
        $buyer = $sale->client;
        
        // Find all eligible clients for profit sharing
        $eligibleClients = $this->getEligibleClientsForProfitSharing($config);
        
        $totalEligibleVolume = $eligibleClients->sum(function ($client) use ($config) {
            switch ($config->profit_sharing_basis) {
                case 'personal_volume':
                    return $client->personal_volume ?? 0;
                case 'group_volume':
                    return $client->group_volume ?? 0;
                case 'total_volume':
                default:
                    return ($client->personal_volume ?? 0) + ($client->group_volume ?? 0);
            }
        });

        if ($totalEligibleVolume == 0) {
            return; // No eligible volume
        }

        // Calculate profit sharing for each eligible client
        foreach ($eligibleClients as $client) {
            $clientVolume = $this->getClientVolumeForProfitSharing($client, $config);
            $sharePercentage = $clientVolume / $totalEligibleVolume;
            $amount = $sale->amount * $sharePercentage * ($config->profit_sharing_percentage / 100);

            if ($amount > 0) {
                $this->createBonusPayment([
                    'client_id' => $client->id,
                    'sale_id' => $sale->id,
                    'bonus_type' => 'profit_sharing',
                    'level' => null,
                    'amount' => $amount,
                    'percentage' => $config->profit_sharing_percentage,
                    'base_amount' => $sale->amount,
                    'from_client_id' => $buyer->id,
                    'bonus_configuration_id' => $config->id,
                    'eligibility_status' => 'eligible',
                    'eligibility_reason' => 'Eligible for profit sharing based on volume',
                ]);
            }
        }
    }

    /**
     * Get eligible clients for profit sharing
     */
    private function getEligibleClientsForProfitSharing(BonusConfiguration $config)
    {
        $query = Client::query();

        // Add eligibility filters
        if ($config->requires_active_invoice) {
            $query->whereHas('invoices', function ($q) {
                $q->where('status', 'active')
                  ->where('expires_at', '>', now());
            });
        }

        if ($config->minimum_volume > 0) {
            $query->where(function ($q) use ($config) {
                switch ($config->profit_sharing_basis) {
                    case 'personal_volume':
                        $q->where('personal_volume', '>=', $config->minimum_volume);
                        break;
                    case 'group_volume':
                        $q->where('group_volume', '>=', $config->minimum_volume);
                        break;
                    case 'total_volume':
                    default:
                        $q->whereRaw('(personal_volume + group_volume) >= ?', [$config->minimum_volume]);
                        break;
                }
            });
        }

        return $query->get();
    }

    /**
     * Get client volume for profit sharing
     */
    private function getClientVolumeForProfitSharing(Client $client, BonusConfiguration $config)
    {
        switch ($config->profit_sharing_basis) {
            case 'personal_volume':
                return $client->personal_volume ?? 0;
            case 'group_volume':
                return $client->group_volume ?? 0;
            case 'total_volume':
            default:
                return ($client->personal_volume ?? 0) + ($client->group_volume ?? 0);
        }
    }

    /**
     * Create bonus payment record
     */
    private function createBonusPayment(array $data)
    {
        $config = BonusConfiguration::find($data['bonus_configuration_id']);
        
        $bonusPayment = BonusPayment::create(array_merge($data, [
            'period_start' => $config->getPeriodStart(),
            'period_end' => $config->getPeriodEnd(),
            'period_key' => $config->getPeriodKey(),
        ]));

        // Check for maximum bonus limits
        $this->checkMaximumBonusLimits($bonusPayment, $config);
        
        return $bonusPayment;
    }

    /**
     * Check maximum bonus limits
     */
    private function checkMaximumBonusLimits(BonusPayment $bonusPayment, BonusConfiguration $config)
    {
        if (!$config->maximum_bonus_per_period) {
            return; // No limit set
        }

        $currentPeriodTotal = BonusPayment::where('client_id', $bonusPayment->client_id)
            ->where('bonus_type', $bonusPayment->bonus_type)
            ->where('period_key', $bonusPayment->period_key)
            ->where('eligibility_status', 'eligible')
            ->where('id', '!=', $bonusPayment->id)
            ->sum('amount');

        $totalWithNewBonus = $currentPeriodTotal + $bonusPayment->amount;

        if ($totalWithNewBonus > $config->maximum_bonus_per_period) {
            $excessAmount = $totalWithNewBonus - $config->maximum_bonus_per_period;
            $adjustedAmount = $bonusPayment->amount - $excessAmount;

            if ($adjustedAmount > 0) {
                $bonusPayment->update([
                    'amount' => $adjustedAmount,
                    'eligibility_status' => 'partial',
                    'eligibility_reason' => 'Adjusted due to maximum bonus limit',
                ]);
            } else {
                $bonusPayment->update([
                    'eligibility_status' => 'ineligible',
                    'eligibility_reason' => 'Exceeds maximum bonus limit',
                ]);
            }
        }
    }

    /**
     * Process pending bonus payments
     */
    public function processPendingBonuses()
    {
        $pendingBonuses = BonusPayment::pending()
            ->eligible()
            ->where('created_at', '<=', now()->subMinutes(5)) // 5 minute delay for safety
            ->get();

        foreach ($pendingBonuses as $bonus) {
            $bonus->approve();
        }

        Log::info('Processed pending bonuses', ['count' => $pendingBonuses->count()]);
        
        return $pendingBonuses->count();
    }

    /**
     * Get bonus statistics
     */
    public function getBonusStatistics($periodKey = null)
    {
        $query = BonusPayment::query();

        if ($periodKey) {
            $query->where('period_key', $periodKey);
        }

        return [
            'total_pending' => (clone $query)->where('status', 'pending')->sum('amount'),
            'total_approved' => (clone $query)->where('status', 'approved')->sum('amount'),
            'total_paid' => (clone $query)->where('status', 'paid')->sum('amount'),
            'total_eligible' => (clone $query)->where('eligibility_status', 'eligible')->sum('amount'),
            'total_ineligible' => (clone $query)->where('eligibility_status', 'ineligible')->sum('amount'),
            'count_by_type' => (clone $query)->selectRaw('bonus_type, COUNT(*) as count, SUM(amount) as total')
                ->groupBy('bonus_type')
                ->get()
                ->keyBy('bonus_type'),
        ];
    }
}
