<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'user_id',
        'plan_id',
        'amount',
        'cost_price',
        'discount_amount',
        'final_amount',
        'status',
        'due_date',
        'payment_method',
        'payment_reference',
        'payment_proof_url',
        'notes',
        'paid_at',
        'confirmed_at',
        'irreversible',
        'refunded_at',
        'confirmed_by',
        'refunded_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'final_amount' => 'decimal:2',
        'due_date' => 'date',
        'paid_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'refunded_at' => 'datetime',
        'irreversible' => 'boolean',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function commissions(): HasMany
    {
        return $this->hasMany(Commission::class);
    }

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    public function confirmedBy(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'confirmed_by');
    }

    public function refundedBy(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'refunded_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeRefunded($query)
    {
        return $query->where('status', 'refunded');
    }

    // Methods
    public function canBeConfirmed(): bool
    {
        return $this->status === 'paid';
    }

    public function canBeRefunded(): bool
    {
        return in_array($this->status, ['paid', 'confirmed']);
    }

    public function canBePaid(): bool
    {
        return $this->status === 'pending';
    }

    public function markAsPaid(string $paymentMethod = null, string $paymentReference = null, string $paymentProofUrl = null): void
    {
        $this->update([
            'status' => 'paid',
            'payment_method' => $paymentMethod,
            'payment_reference' => $paymentReference,
            'payment_proof_url' => $paymentProofUrl,
            'paid_at' => now(),
        ]);
    }

    public function markAsConfirmed(int $adminId): void
    {
        $this->update([
            'status' => 'confirmed',
            'confirmed_at' => now(),
            'confirmed_by' => $adminId,
        ]);
    }

    public function markAsRefunded(int $adminId): void
    {
        $this->update([
            'status' => 'refunded',
            'refunded_at' => now(),
            'refunded_by' => $adminId,
        ]);
    }

    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            'pending' => 'bg-warning',
            'paid' => 'bg-info',
            'confirmed' => 'bg-success',
            'refunded' => 'bg-danger',
            default => 'bg-secondary',
        };
    }

    public function getStatusText(): string
    {
        return match($this->status) {
            'pending' => 'Pending Payment',
            'paid' => 'Payment Received',
            'confirmed' => 'Confirmed',
            'refunded' => 'Refunded',
            default => 'Unknown',
        };
    }

    public static function generateInvoiceNumber(): string
    {
        $prefix = 'INV';
        $year = date('Y');
        $month = date('m');
        
        $lastInvoice = static::where('invoice_number', 'like', "{$prefix}{$year}{$month}%")
            ->orderBy('invoice_number', 'desc')
            ->first();
        
        if ($lastInvoice) {
            $lastNumber = (int) substr($lastInvoice->invoice_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return $prefix . $year . $month . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}
