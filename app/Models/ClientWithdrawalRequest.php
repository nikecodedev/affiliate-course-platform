<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientWithdrawalRequest extends Model
{
    use HasFactory;

    protected $table = 'client_withdrawal_requests';

    protected $fillable = [
        'client_id',
        'amount',
        'status',
        'cpf_verification',
        'requested_at',
        'processed_at',
        'processed_by',
        'processing_notes',
        'rejection_reason',
        'bank_details',
        'withdrawal_method',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'requested_at' => 'datetime',
        'processed_at' => 'datetime',
        'bank_details' => 'array',
    ];

    /**
     * Get the client that owns the withdrawal request
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the admin who processed the request
     */
    public function processor()
    {
        return $this->belongsTo(Admin::class, 'processed_by');
    }

    /**
     * Scope for pending requests
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for approved requests
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for rejected requests
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Scope for processed requests
     */
    public function scopeProcessed($query)
    {
        return $query->where('status', 'processed');
    }

    /**
     * Get status badge color
     */
    public function getStatusBadgeColorAttribute()
    {
        return match($this->status) {
            'pending' => 'warning',
            'approved' => 'info',
            'rejected' => 'danger',
            'processed' => 'success',
            default => 'secondary'
        };
    }

    /**
     * Get status badge text
     */
    public function getStatusBadgeTextAttribute()
    {
        return match($this->status) {
            'pending' => 'Pendente',
            'approved' => 'Aprovado',
            'rejected' => 'Rejeitado',
            'processed' => 'Processado',
            default => ucfirst($this->status)
        };
    }

    /**
     * Get withdrawal fee
     */
    public function getWithdrawalFeeAttribute()
    {
        $feeType = config('app.withdrawal_fee_type', 'percentage');
        $feeValue = config('app.withdrawal_fee_value', 5.00);
        
        if ($feeType === 'percentage') {
            return ($this->amount * $feeValue) / 100;
        }
        
        return $feeValue;
    }

    /**
     * Get net amount after fees
     */
    public function getNetAmountAttribute()
    {
        return $this->amount - $this->withdrawal_fee;
    }

    /**
     * Approve withdrawal request
     */
    public function approve($adminId, $notes = null)
    {
        $this->update([
            'status' => 'approved',
            'processed_at' => now(),
            'processed_by' => $adminId,
            'processing_notes' => $notes
        ]);
    }

    /**
     * Reject withdrawal request
     */
    public function reject($adminId, $reason)
    {
        $this->update([
            'status' => 'rejected',
            'processed_at' => now(),
            'processed_by' => $adminId,
            'rejection_reason' => $reason
        ]);
    }

    /**
     * Mark as processed
     */
    public function markAsProcessed($adminId, $notes = null)
    {
        $this->update([
            'status' => 'processed',
            'processed_at' => now(),
            'processed_by' => $adminId,
            'processing_notes' => $notes
        ]);
    }
}
