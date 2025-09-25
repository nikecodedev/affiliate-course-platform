<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Plan;
use App\Models\Course;
use App\Models\Invoice;
use App\Models\Commission;
use App\Models\Expense;

class SampleDataSeeder extends Seeder
{
    public function run(): void
    {
        // Users
        if (User::count() < 10) {
            for ($i = 1; $i <= 10; $i++) {
                $email = "user{$i}@example.com";
                if (User::where('email', $email)->exists()) continue;
                User::create([
                    'name' => "Sample User {$i}",
                    'email' => $email,
                    'password' => Hash::make('password123'),
                    'phone' => "+55 11 9{$i}{$i}{$i}{$i}-000{$i}",
                    'is_affiliate' => $i % 2 === 0,
                    'affiliate_code' => Str::upper(Str::random(8)),
                    'referral_code' => null,
                    'is_active' => true,
                ]);
            }
        }

        // Plans
        if (Plan::count() < 10) {
            for ($i = 1; $i <= 10; $i++) {
                Plan::firstOrCreate(
                    ['title' => "Plan {$i}"],
                    [
                        'description' => 'Sample plan description',
                        'type' => 'digital',
                        'sale_price' => 100 + $i,
                        'cost_price' => 50 + $i,
                        'direct_bonus_enabled' => true,
                        'direct_bonus_mode' => 'percentage',
                        'direct_bonus_value' => 10,
                        'commission_unilevel' => null,
                        'commission_matrix' => null,
                        'commission_profit_sharing' => 0,
                        'status' => true,
                    ]
                );
            }
        }

        // Courses
        if (Course::count() < 10) {
            for ($i = 1; $i <= 10; $i++) {
                Course::firstOrCreate(
                    ['title' => "Course {$i}"],
                    [
                        'description' => 'Sample course',
                        'is_active' => true,
                        'sort_order' => $i,
                    ]
                );
            }
        }

        // Invoices + Commissions
        $users = User::limit(10)->get();
        $plans = Plan::limit(10)->get();
        foreach ($users as $idx => $user) {
            $plan = $plans[$idx % max(count($plans), 1)] ?? Plan::first();
            if (!$plan) break;
            $invoice = Invoice::create([
                'invoice_number' => Invoice::generateInvoiceNumber(),
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'amount' => $plan->sale_price,
                'cost_price' => $plan->cost_price,
                'discount_amount' => 0,
                'final_amount' => $plan->sale_price,
                'status' => 'confirmed',
                'paid_at' => now(),
                'confirmed_at' => now(),
            ]);

            // Commission for sponsor if any
            if (method_exists($user, 'referrer') && $user->referrer) {
                Commission::create([
                    'invoice_id' => $invoice->id,
                    'user_id' => $user->referrer->id,
                    'amount' => round($invoice->final_amount * 0.1, 2),
                    'status' => 'paid',
                ]);
            }
        }

        // Expenses
        if (Expense::count() < 10) {
            $categories = array_keys(Expense::getCategories());
            for ($i = 1; $i <= 10; $i++) {
                Expense::create([
                    'title' => "Expense {$i}",
                    'description' => 'Sample expense',
                    'amount' => 20 + $i,
                    'category' => $categories[$i % count($categories)],
                    'expense_date' => now()->subDays($i),
                    'receipt_path' => null,
                    'notes' => null,
                    'created_by' => optional(\App\Models\Admin::first())->id,
                ]);
            }
        }

        // Withdrawal Settings (single row) using DB in case model not autoloaded
        $exists = DB::table('withdrawal_settings')->count() > 0;
        if (!$exists) {
            DB::table('withdrawal_settings')->insert([
                'available_days' => json_encode(['mon','tue','wed','thu','fri']),
                'available_times' => json_encode(['09:00','17:00']),
                'fee_type' => 'percent',
                'fee_value' => 5.00,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
