<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Basic Plan
        Plan::create([
            'title' => 'Basic Plan',
            'description' => 'Entry-level plan with basic features and bonus structure',
            'type' => 'digital',
            'sale_price' => 97.00,
            'cost_price' => 50.00,
            'direct_bonus_enabled' => true,
            'direct_bonus_mode' => 'percentage',
            'direct_bonus_value' => 20.00,
            'commission_unilevel' => [
                '1' => ['mode' => 'fixed', 'value' => 10.00],
                '2' => ['mode' => 'fixed', 'value' => 5.00],
            ],
            'commission_matrix' => [
                'width' => 2,
                'depth' => 3,
                'levels' => [
                    '1' => 5,
                    '2' => 3,
                    '3' => 2,
                ]
            ],
            'commission_profit_sharing' => 5.00,
            'external_url' => 'https://example.com/basic-download',
            'status' => true,
        ]);

        // Professional Plan
        Plan::create([
            'title' => 'Professional Plan',
            'description' => 'Advanced plan with enhanced features and higher bonus potential',
            'type' => 'digital',
            'sale_price' => 297.00,
            'cost_price' => 150.00,
            'direct_bonus_enabled' => true,
            'direct_bonus_mode' => 'percentage',
            'direct_bonus_value' => 25.00,
            'commission_unilevel' => [
                '1' => ['mode' => 'fixed', 'value' => 25.00],
                '2' => ['mode' => 'fixed', 'value' => 15.00],
                '3' => ['mode' => 'fixed', 'value' => 10.00],
            ],
            'commission_matrix' => [
                'width' => 3,
                'depth' => 4,
                'levels' => [
                    '1' => 8,
                    '2' => 5,
                    '3' => 3,
                    '4' => 2,
                ]
            ],
            'commission_profit_sharing' => 8.00,
            'external_url' => 'https://example.com/professional-download',
            'status' => true,
        ]);

        // Premium Plan
        Plan::create([
            'title' => 'Premium Plan',
            'description' => 'Top-tier plan with maximum features and bonus potential',
            'type' => 'digital',
            'sale_price' => 497.00,
            'cost_price' => 250.00,
            'direct_bonus_enabled' => true,
            'direct_bonus_mode' => 'percentage',
            'direct_bonus_value' => 30.00,
            'commission_unilevel' => [
                '1' => ['mode' => 'fixed', 'value' => 50.00],
                '2' => ['mode' => 'fixed', 'value' => 30.00],
                '3' => ['mode' => 'fixed', 'value' => 20.00],
                '4' => ['mode' => 'fixed', 'value' => 15.00],
                '5' => ['mode' => 'fixed', 'value' => 10.00],
            ],
            'commission_matrix' => [
                'width' => 3,
                'depth' => 5,
                'levels' => [
                    '1' => 12,
                    '2' => 8,
                    '3' => 5,
                    '4' => 3,
                    '5' => 2,
                ]
            ],
            'commission_profit_sharing' => 10.00,
            'external_url' => 'https://example.com/premium-download',
            'status' => true,
            'sort_order' => 3,
        ]);

        // VIP Plan
        Plan::create([
            'title' => 'VIP Plan',
            'description' => 'Exclusive VIP plan with maximum benefits and exclusive features',
            'type' => 'digital',
            'sale_price' => 997.00,
            'cost_price' => 500.00,
            'direct_bonus_enabled' => true,
            'direct_bonus_mode' => 'percentage',
            'direct_bonus_value' => 35.00,
            'commission_unilevel' => [
                '1' => ['mode' => 'fixed', 'value' => 100.00],
                '2' => ['mode' => 'fixed', 'value' => 75.00],
                '3' => ['mode' => 'fixed', 'value' => 50.00],
                '4' => ['mode' => 'fixed', 'value' => 25.00],
                '5' => ['mode' => 'fixed', 'value' => 15.00],
            ],
            'commission_matrix' => [
                'width' => 4,
                'depth' => 6,
                'levels' => [
                    '1' => 15,
                    '2' => 10,
                    '3' => 7,
                    '4' => 5,
                    '5' => 3,
                    '6' => 2,
                ]
            ],
            'commission_profit_sharing' => 15.00,
            'external_url' => 'https://example.com/vip-download',
            'status' => true,
        ]);

        // Physical Product Example
        Plan::create([
            'title' => 'Physical Product Bundle',
            'description' => 'Physical product with shipping and handling',
            'type' => 'physical',
            'sale_price' => 199.00,
            'cost_price' => 100.00,
            'direct_bonus_enabled' => true,
            'direct_bonus_mode' => 'fixed',
            'direct_bonus_value' => 25.00,
            'commission_unilevel' => [
                '1' => ['mode' => 'fixed', 'value' => 15.00],
                '2' => ['mode' => 'fixed', 'value' => 10.00],
            ],
            'commission_matrix' => [
                'width' => 2,
                'depth' => 3,
                'levels' => [
                    '1' => 8,
                    '2' => 5,
                    '3' => 3,
                ]
            ],
            'commission_profit_sharing' => 5.00,
            'status' => true,
        ]);

        // Service Example
        Plan::create([
            'title' => 'Consulting Service',
            'description' => 'One-on-one consulting service',
            'type' => 'service',
            'sale_price' => 500.00,
            'cost_price' => 200.00,
            'direct_bonus_enabled' => true,
            'direct_bonus_mode' => 'percentage',
            'direct_bonus_value' => 20.00,
            'commission_unilevel' => [
                '1' => ['mode' => 'percentage', 'value' => 10.00],
                '2' => ['mode' => 'percentage', 'value' => 5.00],
            ],
            'commission_matrix' => [
                'width' => 2,
                'depth' => 4,
                'levels' => [
                    '1' => 12,
                    '2' => 8,
                    '3' => 5,
                    '4' => 3,
                ]
            ],
            'commission_profit_sharing' => 8.00,
            'status' => true,
        ]);

        $this->command->info('Plans created successfully!');
    }
}