<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BonusLevel extends Model
{
    use HasFactory;

    protected $fillable = [
        'bonus_configuration_id',
        'level',
        'amount',
        'is_percentage',
        'description',
        'is_active',
    ];

    protected $casts = [
        'level' => 'integer',
        'amount' => 'decimal:2',
        'is_percentage' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get the bonus configuration that owns this level
     */
    public function bonusConfiguration(): BelongsTo
    {
        return $this->belongsTo(GlobalBonusConfiguration::class, 'bonus_configuration_id');
    }

    /**
     * Scope for active levels
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for specific level
     */
    public function scopeLevel($query, $level)
    {
        return $query->where('level', $level);
    }

    /**
     * Calculate bonus amount based on base amount
     */
    public function calculateBonus($baseAmount = 0)
    {
        if ($this->is_percentage) {
            return ($baseAmount * $this->amount) / 100;
        }

        return $this->amount;
    }

    /**
     * Get formatted amount
     */
    public function getFormattedAmountAttribute()
    {
        if ($this->is_percentage) {
            return $this->amount . '%';
        }

        return 'R$ ' . number_format($this->amount, 2, ',', '.');
    }

    /**
     * Get level display name
     */
    public function getLevelNameAttribute()
    {
        return 'Level ' . $this->level;
    }
}