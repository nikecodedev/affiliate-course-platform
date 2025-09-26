<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image',
        'type',
        'sale_price',
        'cost_price',
        'direct_bonus_enabled',
        'direct_bonus_mode',
        'direct_bonus_value',
        'commission_unilevel',
        'commission_matrix',
        'commission_profit_sharing',
        'external_url',
        'external_product_url',
        'course_id',
        'status',
    ];

    protected $casts = [
        'sale_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'direct_bonus_enabled' => 'boolean',
        'direct_bonus_value' => 'decimal:2',
        'commission_unilevel' => 'array',
        'commission_matrix' => 'array',
        'commission_profit_sharing' => 'decimal:2',
        'status' => 'boolean',
    ];

    /**
     * Get the course that owns this plan
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the invoices for this plan
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Plan types
     */
    const TYPE_PHYSICAL = 'physical';
    const TYPE_DIGITAL = 'digital';
    const TYPE_SERVICE = 'service';

    /**
     * Get plan types
     */
    public static function getTypes()
    {
        return [
            self::TYPE_PHYSICAL => 'Physical Product',
            self::TYPE_DIGITAL => 'Digital Product',
            self::TYPE_SERVICE => 'Service',
        ];
    }

    /**
     * Associated products
     */
    public function products(): HasMany
    {
        return $this->hasMany(PlanProduct::class);
    }

    /**
     * Associated courses
     */
    public function courses(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'plan_courses');
    }

    /**
     * Sales for this plan
     */
    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    /**
     * Calculate commission amount
     */
    public function calculateCommission($saleAmount)
    {
        if ($this->commission_percentage > 0) {
            return ($saleAmount * $this->commission_percentage) / 100;
        }

        return $this->commission_fixed ?? 0;
    }

    /**
     * Calculate direct referral bonus
     */
    public function calculateDirectReferralBonus($saleAmount)
    {
        if (!$this->direct_bonus_enabled) {
            return 0;
        }

        if ($this->direct_bonus_mode === 'percentage') {
            return ($saleAmount * $this->direct_bonus_value) / 100;
        }

        return $this->direct_bonus_value;
    }

    /**
     * Get formatted direct referral bonus
     */
    public function getFormattedDirectReferralBonusAttribute()
    {
        if (!$this->direct_bonus_enabled) {
            return 'Not configured';
        }

        if ($this->direct_bonus_mode === 'percentage') {
            return $this->direct_bonus_value . '%';
        }

        return 'R$ ' . number_format($this->direct_bonus_value, 2, ',', '.');
    }

    /**
     * Calculate unilevel commission for a specific level
     */
    public function calculateUnilevelCommission($level, $saleAmount)
    {
        $unilevelConfig = $this->commission_unilevel ?? [];
        
        if (!isset($unilevelConfig[$level])) {
            return 0;
        }

        $config = $unilevelConfig[$level];
        
        if ($config['mode'] === 'percentage') {
            return ($saleAmount * $config['value']) / 100;
        }

        return $config['value'];
    }

    /**
     * Calculate matrix commission for a specific level
     */
    public function calculateMatrixCommission($level, $saleAmount)
    {
        $matrixConfig = $this->commission_matrix ?? [];
        
        if (!isset($matrixConfig['levels'][$level])) {
            return 0;
        }

        $value = $matrixConfig['levels'][$level];
        
        // Assuming matrix commissions are always percentage
        return ($saleAmount * $value) / 100;
    }

    /**
     * Get matrix configuration
     */
    public function getMatrixConfig()
    {
        return $this->commission_matrix ?? [
            'width' => 2,
            'depth' => 5,
            'levels' => []
        ];
    }

    /**
     * Get unilevel configuration
     */
    public function getUnilevelConfig()
    {
        return $this->commission_unilevel ?? [];
    }

    /**
     * Get formatted commission summary
     */
    public function getCommissionSummary()
    {
        $summary = [];
        
        if ($this->direct_bonus_enabled) {
            $summary['direct_referral'] = $this->getFormattedDirectReferralBonusAttribute();
        }
        
        $unilevelConfig = $this->getUnilevelConfig();
        if (!empty($unilevelConfig)) {
            $summary['unilevel'] = count($unilevelConfig) . ' levels configured';
        }
        
        $matrixConfig = $this->getMatrixConfig();
        if (!empty($matrixConfig['levels'])) {
            $summary['matrix'] = $matrixConfig['width'] . 'x' . $matrixConfig['depth'] . ' matrix';
        }
        
        if ($this->commission_profit_sharing) {
            $summary['profit_sharing'] = $this->commission_profit_sharing . '%';
        }
        
        return $summary;
    }

    /**
     * Scope for active plans
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope for ordered plans
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('title');
    }

    /**
     * Get formatted sale price
     */
    public function getFormattedSalePriceAttribute()
    {
        return 'R$ ' . number_format($this->sale_price, 2, ',', '.');
    }

    /**
     * Get formatted cost price
     */
    public function getFormattedCostPriceAttribute()
    {
        return 'R$ ' . number_format($this->cost_price, 2, ',', '.');
    }
}

