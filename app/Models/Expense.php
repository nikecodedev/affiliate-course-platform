<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'amount',
        'category',
        'expense_date',
        'receipt_path',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'expense_date' => 'date',
    ];

    /**
     * Expense categories
     */
    const CATEGORY_MARKETING = 'marketing';
    const CATEGORY_OPERATIONS = 'operations';
    const CATEGORY_TECHNOLOGY = 'technology';
    const CATEGORY_OFFICE = 'office';
    const CATEGORY_TRAVEL = 'travel';
    const CATEGORY_OTHER = 'other';

    /**
     * Get expense categories
     */
    public static function getCategories()
    {
        return [
            self::CATEGORY_MARKETING => 'Marketing',
            self::CATEGORY_OPERATIONS => 'Operations',
            self::CATEGORY_TECHNOLOGY => 'Technology',
            self::CATEGORY_OFFICE => 'Office',
            self::CATEGORY_TRAVEL => 'Travel',
            self::CATEGORY_OTHER => 'Other',
        ];
    }

    /**
     * Admin who created the expense
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    /**
     * Scope for expenses by date range
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('expense_date', [$startDate, $endDate]);
    }

    /**
     * Scope for expenses by category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Get formatted amount
     */
    public function getFormattedAmountAttribute()
    {
        return 'R$ ' . number_format($this->amount, 2, ',', '.');
    }

    /**
     * Get category badge class
     */
    public function getCategoryBadgeClassAttribute()
    {
        return match($this->category) {
            self::CATEGORY_MARKETING => 'primary',
            self::CATEGORY_OPERATIONS => 'success',
            self::CATEGORY_TECHNOLOGY => 'info',
            self::CATEGORY_OFFICE => 'warning',
            self::CATEGORY_TRAVEL => 'secondary',
            self::CATEGORY_OTHER => 'dark',
            default => 'secondary',
        };
    }

    /**
     * Get receipt URL
     */
    public function getReceiptUrlAttribute()
    {
        if ($this->receipt_path) {
            return asset('storage/' . $this->receipt_path);
        }

        return null;
    }
}

