<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BonusConfiguration extends Model
{
    use HasFactory;

    protected $fillable = [
        'plan_id',
        'bonus_type',
        'direct_referral_percentage',
        'direct_referral_fixed',
        'unilevel_percentages',
        'unilevel_fixed_amounts',
        'unilevel_levels',
        'unilevel_payment_type',
        'matrix_width',
        'matrix_depth',
        'matrix_percentages',
        'matrix_fixed_amounts',
        'matrix_levels',
        'matrix_payment_type',
        'profit_sharing_percentage',
        'profit_sharing_basis',
        'requires_active_invoice',
        'minimum_volume',
        'maximum_bonus_per_period',
        'period',
        'is_active',
        'direct_referral_enabled',
        'direct_referral_payment_type',
    ];

    protected $casts = [
        'direct_referral_percentage' => 'decimal:2',
        'direct_referral_fixed' => 'decimal:2',
        'unilevel_percentages' => 'array',
        'unilevel_fixed_amounts' => 'array',
        'unilevel_levels' => 'array',
        'matrix_percentages' => 'array',
        'matrix_fixed_amounts' => 'array',
        'matrix_levels' => 'array',
        'profit_sharing_percentage' => 'decimal:2',
        'minimum_volume' => 'decimal:2',
        'maximum_bonus_per_period' => 'decimal:2',
        'is_active' => 'boolean',
        'requires_active_invoice' => 'boolean',
        'direct_referral_enabled' => 'boolean',
    ];

    /**
     * Get the plan that owns this bonus configuration
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Get bonus payments using this configuration
     */
    public function bonusPayments(): HasMany
    {
        return $this->hasMany(BonusPayment::class, 'bonus_configuration_id');
    }

    /**
     * Scope for active configurations
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for specific bonus type
     */
    public function scopeBonusType($query, $type)
    {
        return $query->where('bonus_type', $type);
    }

    /**
     * Get direct referral bonus amount
     */
    public function getDirectReferralBonus($saleAmount)
    {
        if (!$this->direct_referral_percentage && !$this->direct_referral_fixed) {
            return 0;
        }

        $percentageAmount = $this->direct_referral_percentage ? 
            ($saleAmount * $this->direct_referral_percentage / 100) : 0;
        
        $fixedAmount = $this->direct_referral_fixed ?: 0;

        return max($percentageAmount, $fixedAmount);
    }

    /**
     * Get unilevel bonus for specific level
     */
    public function getUnilevelBonus($saleAmount, $level)
    {
        if (!$this->unilevel_percentages && !$this->unilevel_fixed_amounts) {
            return 0;
        }

        $percentages = $this->unilevel_percentages ?: [];
        $fixedAmounts = $this->unilevel_fixed_amounts ?: [];

        // Check if level is within configured levels
        if ($level > count($percentages) && $level > count($fixedAmounts)) {
            return 0;
        }

        $percentageAmount = isset($percentages[$level - 1]) ? 
            ($saleAmount * $percentages[$level - 1] / 100) : 0;
        
        $fixedAmount = isset($fixedAmounts[$level - 1]) ? $fixedAmounts[$level - 1] : 0;

        return max($percentageAmount, $fixedAmount);
    }

    /**
     * Get matrix bonus for specific level
     */
    public function getMatrixBonus($volume, $level)
    {
        if (!$this->matrix_percentages && !$this->matrix_fixed_amounts) {
            return 0;
        }

        $percentages = $this->matrix_percentages ?: [];
        $fixedAmounts = $this->matrix_fixed_amounts ?: [];

        // Check if level is within configured levels
        if ($level > count($percentages) && $level > count($fixedAmounts)) {
            return 0;
        }

        $percentageAmount = isset($percentages[$level - 1]) ? 
            ($volume * $percentages[$level - 1] / 100) : 0;
        
        $fixedAmount = isset($fixedAmounts[$level - 1]) ? $fixedAmounts[$level - 1] : 0;

        return max($percentageAmount, $fixedAmount);
    }

    /**
     * Get profit sharing bonus
     */
    public function getProfitSharingBonus($volume)
    {
        if (!$this->profit_sharing_percentage) {
            return 0;
        }

        return $volume * $this->profit_sharing_percentage / 100;
    }

    /**
     * Check if client is eligible for bonus
     */
    public function isEligible($client)
    {
        // Check if active invoice is required
        if ($this->requires_active_invoice) {
            $activeInvoice = $client->activeInvoice();
            if (!$activeInvoice) {
                return false;
            }
        }

        // Check minimum volume requirement
        if ($this->minimum_volume > 0) {
            $clientVolume = $this->getClientVolume($client);
            if ($clientVolume < $this->minimum_volume) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get client volume based on profit sharing basis
     */
    private function getClientVolume($client)
    {
        switch ($this->profit_sharing_basis) {
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
     * Get maximum bonus for period
     */
    public function getMaxBonusForPeriod()
    {
        return $this->maximum_bonus_per_period;
    }

    /**
     * Get period start date
     */
    public function getPeriodStart()
    {
        $now = now();
        
        switch ($this->period) {
            case 'daily':
                return $now->startOfDay();
            case 'weekly':
                return $now->startOfWeek();
            case 'monthly':
            default:
                return $now->startOfMonth();
        }
    }

    /**
     * Get period end date
     */
    public function getPeriodEnd()
    {
        $now = now();
        
        switch ($this->period) {
            case 'daily':
                return $now->endOfDay();
            case 'weekly':
                return $now->endOfWeek();
            case 'monthly':
            default:
                return $now->endOfMonth();
        }
    }

    /**
     * Get period key for tracking
     */
    public function getPeriodKey()
    {
        $now = now();
        
        switch ($this->period) {
            case 'daily':
                return $now->format('Y-m-d');
            case 'weekly':
                return $now->format('Y-W');
            case 'monthly':
            default:
                return $now->format('Y-m');
        }
    }

    /**
     * Get all bonus types
     */
    public static function getBonusTypes()
    {
        return [
            'direct_referral' => 'Direct Referral',
            'unilevel' => 'Unilevel',
            'forced_matrix' => 'Forced Matrix',
            'profit_sharing' => 'Profit Sharing',
        ];
    }

    /**
     * Get profit sharing bases
     */
    public static function getProfitSharingBases()
    {
        return [
            'total_volume' => 'Total Volume',
            'personal_volume' => 'Personal Volume',
            'group_volume' => 'Group Volume',
        ];
    }

    /**
     * Get periods
     */
    public static function getPeriods()
    {
        return [
            'daily' => 'Daily',
            'weekly' => 'Weekly',
            'monthly' => 'Monthly',
        ];
    }
}