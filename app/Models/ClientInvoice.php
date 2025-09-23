<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientInvoice extends Model
{
    use HasFactory;

    protected $table = 'client_invoices';

    protected $fillable = [
        'client_id',
        'plan_id',
        'invoice_number',
        'amount',
        'status',
        'payment_method',
        'payment_reference',
        'paid_at',
        'expires_at',
        'notes',
        'receipt_path',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'expires_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    /**
     * Get the client that owns the invoice
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the plan associated with the invoice
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Get the client's transactions for this invoice
     */
    public function transactions()
    {
        return $this->hasMany(ClientTransaction::class);
    }

    /**
     * Check if invoice is active
     */
    public function isActive()
    {
        return $this->status === 'active' && 
               $this->expires_at && 
               $this->expires_at->isFuture();
    }

    /**
     * Check if invoice is expired
     */
    public function isExpired()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Get days remaining
     */
    public function getDaysRemainingAttribute()
    {
        if (!$this->expires_at) return 0;
        return max(0, now()->diffInDays($this->expires_at, false));
    }

    /**
     * Get progress percentage (based on time)
     */
    public function getProgressPercentageAttribute()
    {
        if (!$this->expires_at) return 0;
        
        $totalDays = $this->created_at->diffInDays($this->expires_at);
        $remainingDays = $this->days_remaining;
        
        if ($totalDays <= 0) return 100;
        
        return round((($totalDays - $remainingDays) / $totalDays) * 100);
    }

    /**
     * Scope for active invoices
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
                    ->where('expires_at', '>', now());
    }

    /**
     * Scope for expired invoices
     */
    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<', now());
    }
}
