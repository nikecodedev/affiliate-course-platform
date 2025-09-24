<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BonusPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'sale_id',
        'bonus_type',
        'level',
        'amount',
        'percentage',
        'base_amount',
        'status',
        'eligibility_status',
        'eligibility_reason',
        'from_client_id',
        'bonus_configuration_id',
        'approved_at',
        'paid_at',
        'approved_by',
        'notes',
        'period_start',
        'period_end',
        'period_key',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'percentage' => 'decimal:2',
        'base_amount' => 'decimal:2',
        'approved_at' => 'datetime',
        'paid_at' => 'datetime',
        'period_start' => 'date',
        'period_end' => 'date',
    ];

    /**
     * Get the client that receives this bonus
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the sale that generated this bonus
     */
    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    /**
     * Get the client that generated this bonus
     */
    public function fromClient(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'from_client_id');
    }

    /**
     * Get the bonus configuration used
     */
    public function bonusConfiguration(): BelongsTo
    {
        return $this->belongsTo(BonusConfiguration::class);
    }

    /**
     * Get the admin who approved this bonus
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'approved_by');
    }

    /**
     * Scope for pending payments
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for approved payments
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for paid payments
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    /**
     * Scope for specific bonus type
     */
    public function scopeBonusType($query, $type)
    {
        return $query->where('bonus_type', $type);
    }

    /**
     * Scope for eligible payments
     */
    public function scopeEligible($query)
    {
        return $query->where('eligibility_status', 'eligible');
    }

    /**
     * Scope for specific period
     */
    public function scopePeriod($query, $start, $end)
    {
        return $query->whereBetween('period_start', [$start, $end]);
    }

    /**
     * Scope for specific period key
     */
    public function scopePeriodKey($query, $key)
    {
        return $query->where('period_key', $key);
    }

    /**
     * Get bonus type label
     */
    public function getBonusTypeLabel()
    {
        $types = BonusConfiguration::getBonusTypes();
        return $types[$this->bonus_type] ?? ucfirst(str_replace('_', ' ', $this->bonus_type));
    }

    /**
     * Get status label
     */
    public function getStatusLabel()
    {
        $labels = [
            'pending' => 'Pending',
            'approved' => 'Approved',
            'paid' => 'Paid',
            'cancelled' => 'Cancelled',
        ];

        return $labels[$this->status] ?? ucfirst($this->status);
    }

    /**
     * Get eligibility status label
     */
    public function getEligibilityStatusLabel()
    {
        $labels = [
            'eligible' => 'Eligible',
            'ineligible' => 'Ineligible',
            'partial' => 'Partial',
        ];

        return $labels[$this->eligibility_status] ?? ucfirst($this->eligibility_status);
    }

    /**
     * Check if payment can be approved
     */
    public function canBeApproved()
    {
        return $this->status === 'pending' && $this->eligibility_status === 'eligible';
    }

    /**
     * Check if payment can be paid
     */
    public function canBePaid()
    {
        return $this->status === 'approved';
    }

    /**
     * Check if payment can be cancelled
     */
    public function canBeCancelled()
    {
        return in_array($this->status, ['pending', 'approved']);
    }

    /**
     * Approve the bonus payment
     */
    public function approve($adminId = null)
    {
        if (!$this->canBeApproved()) {
            throw new \Exception('Bonus payment cannot be approved');
        }

        $this->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => $adminId,
        ]);

        return $this;
    }

    /**
     * Mark as paid
     */
    public function markAsPaid()
    {
        if (!$this->canBePaid()) {
            throw new \Exception('Bonus payment cannot be marked as paid');
        }

        $this->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        return $this;
    }

    /**
     * Cancel the bonus payment
     */
    public function cancel($reason = null)
    {
        if (!$this->canBeCancelled()) {
            throw new \Exception('Bonus payment cannot be cancelled');
        }

        $this->update([
            'status' => 'cancelled',
            'notes' => $reason ? ($this->notes . "\nCancelled: " . $reason) : $this->notes,
        ]);

        return $this;
    }

    /**
     * Get total amount for client in period
     */
    public static function getTotalForClientInPeriod($clientId, $periodKey, $status = null)
    {
        $query = self::where('client_id', $clientId)
            ->where('period_key', $periodKey)
            ->where('eligibility_status', 'eligible');

        if ($status) {
            $query->where('status', $status);
        }

        return $query->sum('amount');
    }

    /**
     * Get pending amount for client
     */
    public static function getPendingAmountForClient($clientId)
    {
        return self::where('client_id', $clientId)
            ->where('status', 'pending')
            ->where('eligibility_status', 'eligible')
            ->sum('amount');
    }

    /**
     * Get approved amount for client
     */
    public static function getApprovedAmountForClient($clientId)
    {
        return self::where('client_id', $clientId)
            ->where('status', 'approved')
            ->where('eligibility_status', 'eligible')
            ->sum('amount');
    }

    /**
     * Get paid amount for client
     */
    public static function getPaidAmountForClient($clientId)
    {
        return self::where('client_id', $clientId)
            ->where('status', 'paid')
            ->where('eligibility_status', 'eligible')
            ->sum('amount');
    }

    /**
     * Get statistics for admin dashboard
     */
    public static function getStatistics($periodKey = null)
    {
        $query = self::where('eligibility_status', 'eligible');

        if ($periodKey) {
            $query->where('period_key', $periodKey);
        }

        return [
            'total_pending' => (clone $query)->where('status', 'pending')->sum('amount'),
            'total_approved' => (clone $query)->where('status', 'approved')->sum('amount'),
            'total_paid' => (clone $query)->where('status', 'paid')->sum('amount'),
            'count_pending' => (clone $query)->where('status', 'pending')->count(),
            'count_approved' => (clone $query)->where('status', 'approved')->count(),
            'count_paid' => (clone $query)->where('status', 'paid')->count(),
        ];
    }
}