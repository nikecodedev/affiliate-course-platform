<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientTransaction extends Model
{
    use HasFactory;

    protected $table = 'client_transactions';

    protected $fillable = [
        'client_id',
        'invoice_id',
        'type',
        'amount',
        'description',
        'status',
        'reference',
        'payment_method',
        'processed_at',
        'notes',
        'metadata',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'processed_at' => 'datetime',
        'metadata' => 'array',
    ];

    /**
     * Get the client that owns the transaction
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the invoice associated with the transaction
     */
    public function invoice()
    {
        return $this->belongsTo(ClientInvoice::class);
    }

    /**
     * Scope for credit transactions
     */
    public function scopeCredit($query)
    {
        return $query->where('type', 'credit');
    }

    /**
     * Scope for debit transactions
     */
    public function scopeDebit($query)
    {
        return $query->where('type', 'debit');
    }

    /**
     * Scope for completed transactions
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for pending transactions
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for failed transactions
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Get transaction type badge color
     */
    public function getTypeBadgeColorAttribute()
    {
        return match($this->type) {
            'credit' => 'success',
            'debit' => 'danger',
            default => 'secondary'
        };
    }

    /**
     * Get transaction type badge text
     */
    public function getTypeBadgeTextAttribute()
    {
        return match($this->type) {
            'credit' => 'Crédito',
            'debit' => 'Débito',
            default => ucfirst($this->type)
        };
    }

    /**
     * Get status badge color
     */
    public function getStatusBadgeColorAttribute()
    {
        return match($this->status) {
            'completed' => 'success',
            'pending' => 'warning',
            'failed' => 'danger',
            default => 'secondary'
        };
    }

    /**
     * Get status badge text
     */
    public function getStatusBadgeTextAttribute()
    {
        return match($this->status) {
            'completed' => 'Concluído',
            'pending' => 'Pendente',
            'failed' => 'Falhou',
            default => ucfirst($this->status)
        };
    }

    /**
     * Get formatted amount with sign
     */
    public function getFormattedAmountAttribute()
    {
        $sign = $this->type === 'credit' ? '+' : '-';
        return $sign . 'R$ ' . number_format($this->amount, 2, ',', '.');
    }
}
