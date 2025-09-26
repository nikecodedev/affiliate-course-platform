<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WithdrawalSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'available_days',
        'available_times',
        'fee_type',
        'fee_value',
    ];

    protected $casts = [
        'available_days' => 'array',
        'available_times' => 'array',
        'fee_value' => 'decimal:2',
    ];
}


