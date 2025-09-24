<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BonusSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'bonus_type',
        'is_active',
        'payment_mode',
        'width',
        'depth',
        'levels',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'levels' => 'array',
        'width' => 'integer',
        'depth' => 'integer',
    ];

    /**
     * Get bonus setting by type
     */
    public static function getByType($type)
    {
        return static::where('bonus_type', $type)->first();
    }

    /**
     * Get or create bonus setting by type
     */
    public static function getOrCreateByType($type)
    {
        return static::firstOrCreate(
            ['bonus_type' => $type],
            [
                'is_active' => false,
                'payment_mode' => 'fixed',
                'levels' => []
            ]
        );
    }

    /**
     * Get all bonus types
     */
    public static function getBonusTypes()
    {
        return [
            'direct_referral' => 'Direct Referral',
            'unilevel' => 'Unilevel',
            'matrix' => 'Matrix',
        ];
    }

    /**
     * Get payment modes
     */
    public static function getPaymentModes()
    {
        return [
            'fixed' => 'Fixed Amount',
            'percentage' => 'Percentage',
        ];
    }

    /**
     * Get level configuration for a specific level
     */
    public function getLevelConfig($level)
    {
        $levels = $this->levels ?? [];
        return $levels[$level] ?? null;
    }

    /**
     * Set level configuration
     */
    public function setLevelConfig($level, $mode, $value)
    {
        $levels = $this->levels ?? [];
        $levels[$level] = [
            'mode' => $mode,
            'value' => $value
        ];
        $this->levels = $levels;
    }

    /**
     * Remove level configuration
     */
    public function removeLevelConfig($level)
    {
        $levels = $this->levels ?? [];
        unset($levels[$level]);
        $this->levels = $levels;
    }

    /**
     * Get all configured levels
     */
    public function getConfiguredLevels()
    {
        return $this->levels ?? [];
    }

    /**
     * Calculate bonus amount for a specific level
     */
    public function calculateBonusAmount($level, $baseAmount = 0)
    {
        $levelConfig = $this->getLevelConfig($level);
        
        if (!$levelConfig) {
            return 0;
        }

        if ($levelConfig['mode'] === 'percentage') {
            return ($baseAmount * $levelConfig['value']) / 100;
        }

        return $levelConfig['value'];
    }

    /**
     * Get formatted level configuration
     */
    public function getFormattedLevelConfig($level)
    {
        $levelConfig = $this->getLevelConfig($level);
        
        if (!$levelConfig) {
            return 'Not configured';
        }

        if ($levelConfig['mode'] === 'percentage') {
            return $levelConfig['value'] . '%';
        }

        return 'R$ ' . number_format($levelConfig['value'], 2, ',', '.');
    }

    /**
     * Check if bonus is active
     */
    public function isActive()
    {
        return $this->is_active;
    }

    /**
     * Get bonus type display name
     */
    public function getTypeDisplayName()
    {
        $types = static::getBonusTypes();
        return $types[$this->bonus_type] ?? ucfirst($this->bonus_type);
    }

    /**
     * Get payment mode display name
     */
    public function getPaymentModeDisplayName()
    {
        $modes = static::getPaymentModes();
        return $modes[$this->payment_mode] ?? ucfirst($this->payment_mode);
    }
}