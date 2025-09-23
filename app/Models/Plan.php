<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'commission_percentage',
        'commission_fixed',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'sale_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'commission_percentage' => 'decimal:2',
        'commission_fixed' => 'decimal:2',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

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
    public function courses(): HasMany
    {
        return $this->hasMany(PlanCourse::class);
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
     * Scope for active plans
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
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

