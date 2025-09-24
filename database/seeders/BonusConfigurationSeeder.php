<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BonusConfiguration;
use App\Models\Plan;
use App\Models\PaymentGateway;

class BonusConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = Plan::all();
        
        if ($plans->isEmpty()) {
            $this->command->warn('No plans found. Please run PlanSeeder first.');
            return;
        }

        foreach ($plans as $plan) {
            // Direct Referral Bonus
            BonusConfiguration::create([
                'plan_id' => $plan->id,
                'bonus_type' => 'direct_referral',
                'direct_referral_percentage' => 10.00, // 10% of sale
                'direct_referral_fixed' => null,
                'requires_active_invoice' => false, // Always paid
                'is_active' => true,
            ]);

            // Unilevel Bonus (up to 10 levels)
            BonusConfiguration::create([
                'plan_id' => $plan->id,
                'bonus_type' => 'unilevel',
                'unilevel_percentages' => [
                    5.0,  // Level 1: 5%
                    3.0,  // Level 2: 3%
                    2.0,  // Level 3: 2%
                    1.5,  // Level 4: 1.5%
                    1.0,  // Level 5: 1%
                    0.8,  // Level 6: 0.8%
                    0.6,  // Level 7: 0.6%
                    0.4,  // Level 8: 0.4%
                    0.2,  // Level 9: 0.2%
                    0.1,  // Level 10: 0.1%
                ],
                'unilevel_fixed_amounts' => null,
                'requires_active_invoice' => true,
                'minimum_volume' => 100.00, // Minimum R$ 100 in volume
                'is_active' => true,
            ]);

            // Forced Matrix Bonus (2x2 matrix)
            BonusConfiguration::create([
                'plan_id' => $plan->id,
                'bonus_type' => 'forced_matrix',
                'matrix_width' => 2,
                'matrix_depth' => 10,
                'matrix_percentages' => [
                    8.0,  // Level 1: 8%
                    6.0,  // Level 2: 6%
                    4.0,  // Level 3: 4%
                    3.0,  // Level 4: 3%
                    2.0,  // Level 5: 2%
                    1.5,  // Level 6: 1.5%
                    1.0,  // Level 7: 1%
                    0.8,  // Level 8: 0.8%
                    0.6,  // Level 9: 0.6%
                    0.4,  // Level 10: 0.4%
                ],
                'matrix_fixed_amounts' => null,
                'requires_active_invoice' => true,
                'minimum_volume' => 200.00, // Minimum R$ 200 in weaker leg
                'is_active' => true,
            ]);

            // Profit Sharing
            BonusConfiguration::create([
                'plan_id' => $plan->id,
                'bonus_type' => 'profit_sharing',
                'profit_sharing_percentage' => 2.0, // 2% of total volume
                'profit_sharing_basis' => 'total_volume',
                'requires_active_invoice' => true,
                'minimum_volume' => 500.00, // Minimum R$ 500 in total volume
                'maximum_bonus_per_period' => 1000.00, // Maximum R$ 1000 per month
                'period' => 'monthly',
                'is_active' => true,
            ]);
        }

        $this->command->info('Bonus configurations created successfully!');

        // Create Payment Gateways
        $this->createPaymentGateways();
    }

    /**
     * Create payment gateways
     */
    private function createPaymentGateways()
    {
        // Asaas Gateway
        PaymentGateway::create([
            'name' => 'Asaas',
            'code' => 'asaas',
            'type' => 'credit_card',
            'is_active' => true,
            'sort_order' => 1,
            'api_key' => 'demo_api_key_asaas',
            'api_secret' => 'demo_api_secret_asaas',
            'webhook_secret' => 'demo_webhook_secret_asaas',
            'api_url' => 'https://api.asaas.com/v3',
            'webhook_url' => 'https://yourdomain.com/webhook/asaas',
            'fee_percentage' => 3.49,
            'fee_fixed' => 0.39,
            'fee_charged_to_customer' => false,
            'supported_currencies' => ['BRL'],
            'supported_countries' => ['BR'],
            'processing_time_days' => 1,
            'auto_approve' => true,
            'requires_webhook' => true,
            'min_amount' => 1.00,
            'max_amount' => 50000.00,
        ]);

        // Stone Gateway
        PaymentGateway::create([
            'name' => 'Stone',
            'code' => 'stone',
            'type' => 'credit_card',
            'is_active' => true,
            'sort_order' => 2,
            'api_key' => 'demo_api_key_stone',
            'api_secret' => 'demo_api_secret_stone',
            'webhook_secret' => 'demo_webhook_secret_stone',
            'api_url' => 'https://api.stone.com.br',
            'webhook_url' => 'https://yourdomain.com/webhook/stone',
            'fee_percentage' => 3.99,
            'fee_fixed' => 0.49,
            'fee_charged_to_customer' => false,
            'supported_currencies' => ['BRL'],
            'supported_countries' => ['BR'],
            'processing_time_days' => 2,
            'auto_approve' => false,
            'requires_webhook' => true,
            'min_amount' => 1.00,
            'max_amount' => 100000.00,
        ]);

        // PagSeguro Gateway
        PaymentGateway::create([
            'name' => 'PagSeguro',
            'code' => 'pagseguro',
            'type' => 'credit_card',
            'is_active' => true,
            'sort_order' => 3,
            'api_key' => 'demo_api_key_pagseguro',
            'api_secret' => 'demo_api_secret_pagseguro',
            'webhook_secret' => 'demo_webhook_secret_pagseguro',
            'api_url' => 'https://api.pagseguro.com',
            'webhook_url' => 'https://yourdomain.com/webhook/pagseguro',
            'fee_percentage' => 4.99,
            'fee_fixed' => 0.39,
            'fee_charged_to_customer' => false,
            'supported_currencies' => ['BRL'],
            'supported_countries' => ['BR'],
            'processing_time_days' => 3,
            'auto_approve' => false,
            'requires_webhook' => true,
            'min_amount' => 1.00,
            'max_amount' => 75000.00,
        ]);

        // PIX Gateway
        PaymentGateway::create([
            'name' => 'PIX',
            'code' => 'pix',
            'type' => 'pix',
            'is_active' => true,
            'sort_order' => 4,
            'api_key' => 'demo_api_key_pix',
            'api_secret' => 'demo_api_secret_pix',
            'webhook_secret' => 'demo_webhook_secret_pix',
            'api_url' => 'https://api.pix.com.br',
            'webhook_url' => 'https://yourdomain.com/webhook/pix',
            'fee_percentage' => 0.0,
            'fee_fixed' => 0.0,
            'fee_charged_to_customer' => false,
            'supported_currencies' => ['BRL'],
            'supported_countries' => ['BR'],
            'processing_time_days' => 0, // Instant
            'auto_approve' => true,
            'requires_webhook' => true,
            'min_amount' => 0.01,
            'max_amount' => 500000.00,
        ]);

        // Bitcoin Gateway
        PaymentGateway::create([
            'name' => 'Bitcoin',
            'code' => 'bitcoin',
            'type' => 'cryptocurrency',
            'is_active' => true,
            'sort_order' => 5,
            'api_key' => 'demo_api_key_bitcoin',
            'api_secret' => 'demo_api_secret_bitcoin',
            'webhook_secret' => 'demo_webhook_secret_bitcoin',
            'api_url' => 'https://api.bitcoin.com',
            'webhook_url' => 'https://yourdomain.com/webhook/bitcoin',
            'fee_percentage' => 1.0,
            'fee_fixed' => 0.0,
            'fee_charged_to_customer' => false,
            'supported_currencies' => ['BTC', 'BRL'],
            'supported_countries' => ['BR', 'US', 'CA', 'GB', 'DE', 'FR', 'ES', 'IT'],
            'processing_time_days' => 1,
            'auto_approve' => false,
            'requires_webhook' => true,
            'min_amount' => 0.0001, // Minimum Bitcoin amount
            'max_amount' => 10.0, // Maximum Bitcoin amount
        ]);

        $this->command->info('Payment gateways created successfully!');
    }
}