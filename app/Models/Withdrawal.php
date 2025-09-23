<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Withdrawal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'fee_amount',
        'net_amount',
        'status',
        'request_date',
        'processed_date',
        'payment_method',
        'payment_reference',
        'bank_account',
        'notes',
        'processed_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'fee_amount' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'request_date' => 'datetime',
        'processed_date' => 'datetime',
        'bank_account' => 'array',
    ];

    /**
     * Withdrawal statuses
     */
    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_REJECTED = 'rejected';

    /**
     * Get withdrawal statuses
     */
    public static function getStatuses()
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_PROCESSING => 'Processing',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_CANCELLED => 'Cancelled',
            self::STATUS_REJECTED => 'Rejected',
        ];
    }

    /**
     * Payment methods
     */
    const PAYMENT_PIX = 'pix';
    const PAYMENT_BANK_TRANSFER = 'bank_transfer';

    /**
     * Get payment methods
     */
    public static function getPaymentMethods()
    {
        return [
            self::PAYMENT_PIX => 'PIX',
            self::PAYMENT_BANK_TRANSFER => 'Bank Transfer',
        ];
    }

    /**
     * Associated user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Admin who processed the withdrawal
     */
    public function processor(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'processed_by');
    }

    /**
     * Scope for pending withdrawals
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope for completed withdrawals
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    /**
     * Get formatted amount
     */
    public function getFormattedAmountAttribute()
    {
        return 'R$ ' . number_format($this->amount, 2, ',', '.');
    }

    /**
     * Get formatted fee amount
     */
    public function getFormattedFeeAmountAttribute()
    {
        return 'R$ ' . number_format($this->fee_amount, 2, ',', '.');
    }

    /**
     * Get formatted net amount
     */
    public function getFormattedNetAmountAttribute()
    {
        return 'R$ ' . number_format($this->net_amount, 2, ',', '.');
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            self::STATUS_PENDING => 'warning',
            self::STATUS_PROCESSING => 'info',
            self::STATUS_COMPLETED => 'success',
            self::STATUS_CANCELLED => 'danger',
            self::STATUS_REJECTED => 'danger',
            default => 'secondary',
        };
    }

    /**
     * Check if withdrawal can be cancelled
     */
    public function canBeCancelled()
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_PROCESSING]);
    }

    /**
     * Check if withdrawal can be processed
     */
    public function canBeProcessed()
    {
        return $this->status === self::STATUS_PENDING;
    }
}

