<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'plan_id',
        'user_id',
        'affiliate_id',
        'amount',
        'commission_amount',
        'status',
        'payment_method',
        'payment_reference',
        'notes',
        'sale_date',
        'created_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'sale_date' => 'datetime',
    ];

    /**
     * Sale statuses
     */
    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_REFUNDED = 'refunded';

    /**
     * Get sale statuses
     */
    public static function getStatuses()
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_CONFIRMED => 'Confirmed',
            self::STATUS_CANCELLED => 'Cancelled',
            self::STATUS_REFUNDED => 'Refunded',
        ];
    }

    /**
     * Payment methods
     */
    const PAYMENT_PIX = 'pix';
    const PAYMENT_CREDIT_CARD = 'credit_card';
    const PAYMENT_BANK_TRANSFER = 'bank_transfer';
    const PAYMENT_CASH = 'cash';

    /**
     * Get payment methods
     */
    public static function getPaymentMethods()
    {
        return [
            self::PAYMENT_PIX => 'PIX',
            self::PAYMENT_CREDIT_CARD => 'Credit Card',
            self::PAYMENT_BANK_TRANSFER => 'Bank Transfer',
            self::PAYMENT_CASH => 'Cash',
        ];
    }

    /**
     * Associated plan
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Customer
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Affiliate who made the sale
     */
    public function affiliate(): BelongsTo
    {
        return $this->belongsTo(User::class, 'affiliate_id');
    }

    /**
     * Admin who created the sale
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    /**
     * Scope for confirmed sales
     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', self::STATUS_CONFIRMED);
    }

    /**
     * Scope for pending sales
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope for refunded sales
     */
    public function scopeRefunded($query)
    {
        return $query->where('status', self::STATUS_REFUNDED);
    }

    /**
     * Scope for sales by date range
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('sale_date', [$startDate, $endDate]);
    }

    /**
     * Get formatted amount
     */
    public function getFormattedAmountAttribute()
    {
        return 'R$ ' . number_format($this->amount, 2, ',', '.');
    }

    /**
     * Get formatted commission amount
     */
    public function getFormattedCommissionAmountAttribute()
    {
        return 'R$ ' . number_format($this->commission_amount, 2, ',', '.');
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            self::STATUS_PENDING => 'warning',
            self::STATUS_CONFIRMED => 'success',
            self::STATUS_CANCELLED => 'danger',
            self::STATUS_REFUNDED => 'info',
            default => 'secondary',
        };
    }

    /**
     * Check if sale can be refunded
     */
    public function canBeRefunded()
    {
        return $this->status === self::STATUS_CONFIRMED;
    }

    /**
     * Check if sale can be cancelled
     */
    public function canBeCancelled()
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_CONFIRMED]);
    }
}

