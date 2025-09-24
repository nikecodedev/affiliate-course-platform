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
            'commission_percentage' => 10.00,
            'commission_fixed' => 0.00,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        // Professional Plan
        Plan::create([
            'title' => 'Professional Plan',
            'description' => 'Advanced plan with enhanced features and higher bonus potential',
            'type' => 'digital',
            'sale_price' => 297.00,
            'cost_price' => 150.00,
            'commission_percentage' => 15.00,
            'commission_fixed' => 0.00,
            'is_active' => true,
            'sort_order' => 2,
        ]);

        // Premium Plan
        Plan::create([
            'title' => 'Premium Plan',
            'description' => 'Top-tier plan with maximum features and bonus potential',
            'type' => 'digital',
            'sale_price' => 497.00,
            'cost_price' => 250.00,
            'commission_percentage' => 20.00,
            'commission_fixed' => 0.00,
            'is_active' => true,
            'sort_order' => 3,
        ]);

        // VIP Plan
        Plan::create([
            'title' => 'VIP Plan',
            'description' => 'Exclusive VIP plan with maximum benefits and exclusive features',
            'type' => 'digital',
            'sale_price' => 997.00,
            'cost_price' => 500.00,
            'commission_percentage' => 25.00,
            'commission_fixed' => 0.00,
            'is_active' => true,
            'sort_order' => 4,
        ]);

        $this->command->info('Plans created successfully!');
    }
}