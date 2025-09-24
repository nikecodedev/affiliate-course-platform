<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'plan_id',
        'name',
        'description',
        'download_url',
        'file_size',
        'file_type',
        'is_active',
        'direct_referral_enabled',
        'direct_referral_amount',
        'direct_referral_percentage',
        'direct_referral_type',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'file_size' => 'integer',
        'direct_referral_enabled' => 'boolean',
        'direct_referral_amount' => 'decimal:2',
        'direct_referral_percentage' => 'decimal:2',
    ];

    /**
     * Associated plan
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Scope for active products
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get formatted file size
     */
    public function getFormattedFileSizeAttribute()
    {
        if (!$this->file_size) {
            return 'Unknown';
        }

        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Get direct referral bonus amount for a sale
     */
    public function getDirectReferralBonusAmount($saleAmount)
    {
        if (!$this->direct_referral_enabled) {
            return 0;
        }

        if ($this->direct_referral_type === 'percentage') {
            return ($saleAmount * $this->direct_referral_percentage) / 100;
        }

        return $this->direct_referral_amount;
    }

    /**
     * Check if direct referral bonus is enabled
     */
    public function hasDirectReferralBonus()
    {
        return $this->direct_referral_enabled;
    }
}

