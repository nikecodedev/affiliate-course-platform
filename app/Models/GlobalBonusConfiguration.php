<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GlobalBonusConfiguration extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'description',
        'is_active',
        'is_percentage',
        'max_depth',
        'max_width',
        'min_sale_amount',
        'requires_active_invoice',
        'eligibility_rules',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_percentage' => 'boolean',
        'max_depth' => 'integer',
        'max_width' => 'integer',
        'min_sale_amount' => 'decimal:2',
        'requires_active_invoice' => 'boolean',
        'eligibility_rules' => 'array',
    ];

    /**
     * Get the bonus levels for this configuration
     */
    public function bonusLevels(): HasMany
    {
        return $this->hasMany(BonusLevel::class);
    }

    /**
     * Get active bonus levels ordered by level
     */
    public function activeBonusLevels()
    {
        return $this->bonusLevels()->where('is_active', true)->orderBy('level');
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
    public function scopeType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Get bonus amount for specific level
     */
    public function getBonusAmount($level, $baseAmount = 0)
    {
        $bonusLevel = $this->bonusLevels()
            ->where('level', $level)
            ->where('is_active', true)
            ->first();

        if (!$bonusLevel) {
            return 0;
        }

        if ($bonusLevel->is_percentage) {
            return ($baseAmount * $bonusLevel->amount) / 100;
        }

        return $bonusLevel->amount;
    }

    /**
     * Check if client is eligible for this bonus
     */
    public function isEligible($client)
    {
        // Check if active invoice is required
        if ($this->requires_active_invoice) {
            if (!$client->active_network) {
                return false;
            }
        }

        // Check additional eligibility rules
        if ($this->eligibility_rules) {
            foreach ($this->eligibility_rules as $rule => $value) {
                switch ($rule) {
                    case 'min_sales_count':
                        if ($client->sales()->count() < $value) {
                            return false;
                        }
                        break;
                    case 'min_total_sales':
                        if ($client->sales()->sum('amount') < $value) {
                            return false;
                        }
                        break;
                    // Add more rules as needed
                }
            }
        }

        return true;
    }

    /**
     * Get all bonus types
     */
    public static function getBonusTypes()
    {
        return [
            'unilevel' => 'Unilevel Bonus',
            'forced_matrix' => 'Forced Matrix Bonus',
            'direct_referral' => 'Direct Referral Bonus',
        ];
    }

    /**
     * Get configuration by type
     */
    public static function getByType($type)
    {
        return static::where('type', $type)->where('is_active', true)->first();
    }

    /**
     * Create default configurations
     */
    public static function createDefaults()
    {
        $defaults = [
            [
                'name' => 'Unilevel Bonus',
                'type' => 'unilevel',
                'description' => 'Unilevel bonus structure with configurable levels',
                'is_active' => true,
                'is_percentage' => false,
                'max_depth' => 10,
                'max_width' => 0,
                'min_sale_amount' => 0,
                'requires_active_invoice' => true,
            ],
            [
                'name' => 'Forced Matrix Bonus',
                'type' => 'forced_matrix',
                'description' => 'Forced matrix bonus structure with width and depth limits',
                'is_active' => true,
                'is_percentage' => false,
                'max_depth' => 10,
                'max_width' => 2,
                'min_sale_amount' => 0,
                'requires_active_invoice' => true,
            ],
        ];

        foreach ($defaults as $default) {
            static::firstOrCreate(
                ['type' => $default['type']],
                $default
            );
        }
    }
}