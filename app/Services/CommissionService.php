<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Commission;
use App\Models\User;

class CommissionService
{
    public static function distributeCommission(Invoice $invoice): void
    {
        // Simple example: pay direct referral to the buyer's sponsor if exists
        $buyer = $invoice->user;
        if (!$buyer instanceof User) {
            return;
        }

        $sponsor = $buyer->sponsor ?? null;
        if (!$sponsor) {
            return;
        }

        $amount = $invoice->final_amount ?? $invoice->amount;
        $commissionValue = round($amount * 0.1, 2); // 10% example

        Commission::create([
            'invoice_id' => $invoice->id,
            'user_id' => $sponsor->id,
            'amount' => $commissionValue,
            'status' => 'paid',
        ]);
    }

    public static function refundCommissions(Invoice $invoice): void
    {
        // Mark related commissions as refunded
        Commission::where('invoice_id', $invoice->id)
            ->update(['status' => 'refunded']);
    }

    /**
     * Process commission payments for a user
     */
    public static function processCommissionPayments(User $user, $amount = null): void
    {
        $pendingCommissions = $user->commissions()->pending()->get();
        
        if ($pendingCommissions->isEmpty()) {
            return;
        }

        $totalAmount = $amount ?? $pendingCommissions->sum('amount');

        // Create commission payment record
        \App\Models\CommissionPayment::create([
            'user_id' => $user->id,
            'amount' => $totalAmount,
            'status' => 'pending',
            'payment_method' => 'bank_transfer',
            'processed_at' => now(),
        ]);

        // Mark commissions as paid
        $pendingCommissions->each(function ($commission) {
            $commission->update(['status' => 'paid']);
        });
    }

    /**
     * Get commission statistics for a user
     */
    public static function getUserCommissionStats(User $user, $period = 'month')
    {
        $startDate = now()->startOfMonth();
        $endDate = now()->endOfMonth();

        if ($period === 'week') {
            $startDate = now()->startOfWeek();
            $endDate = now()->endOfWeek();
        } elseif ($period === 'year') {
            $startDate = now()->startOfYear();
            $endDate = now()->endOfYear();
        }

        $commissions = Commission::where('user_id', $user->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        return [
            'total_earned' => $commissions->sum('amount'),
            'total_paid' => $commissions->where('status', 'paid')->sum('amount'),
            'total_pending' => $commissions->where('status', 'pending')->sum('amount'),
            'count_total' => $commissions->count(),
            'count_paid' => $commissions->where('status', 'paid')->count(),
            'count_pending' => $commissions->where('status', 'pending')->count(),
        ];
    }

    /**
     * Calculate commission for a sale
     */
    public static function calculateCommission(Sale $sale): float
    {
        $plan = $sale->plan;
        $affiliate = $sale->affiliate;

        if (!$plan || !$affiliate) {
            return 0;
        }

        // Calculate based on plan commission settings
        $commissionPercentage = $plan->commission_percentage ?? 0;
        $commissionFixed = $plan->commission_fixed ?? 0;

        if ($commissionPercentage > 0) {
            return ($sale->amount * $commissionPercentage) / 100;
        }

        return $commissionFixed;
    }
}


