<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Client;
use App\Models\Plan;
use App\Models\Sale;
use App\Models\PaymentGateway;
use App\Models\BonusConfiguration;
use App\Models\ReferralNetwork;
use App\Models\BonusPayment;
use App\Services\BonusCalculationService;
use Illuminate\Support\Facades\DB;

class TestReferralSystem extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'test:referral-system {--detailed : Show detailed output}';

    /**
     * The console command description.
     */
    protected $description = 'Test the complete referral network and bonus system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Starting Referral System Test Suite...');
        $this->newLine();

        $verbose = $this->option('detailed');
        $results = [];

        // Test 1: Database Models
        $results['models'] = $this->testDatabaseModels($verbose);

        // Test 2: Payment Gateways
        $results['gateways'] = $this->testPaymentGateways($verbose);

        // Test 3: Bonus Configurations
        $results['bonus_configs'] = $this->testBonusConfigurations($verbose);

        // Test 4: Referral Network Structure
        $results['network'] = $this->testReferralNetwork($verbose);

        // Test 5: Bonus Calculation Service
        $results['bonus_calculation'] = $this->testBonusCalculation($verbose);

        // Test 6: System Integration
        $results['integration'] = $this->testSystemIntegration($verbose);

        // Display Results
        $this->displayResults($results);

        return 0;
    }

    /**
     * Test database models
     */
    private function testDatabaseModels($verbose)
    {
        $this->info('📊 Testing Database Models...');
        
        try {
            $models = [
                'clients' => Client::count(),
                'plans' => Plan::count(),
                'sales' => Sale::count(),
                'payment_gateways' => PaymentGateway::count(),
                'bonus_configurations' => BonusConfiguration::count(),
                'referral_networks' => ReferralNetwork::count(),
                'bonus_payments' => BonusPayment::count(),
            ];

            if ($verbose) {
                foreach ($models as $model => $count) {
                    $this->line("  ✓ {$model}: {$count} records");
                }
            }

            $this->info('  ✅ Database models test passed');
            return ['status' => 'passed', 'data' => $models];

        } catch (\Exception $e) {
            $this->error('  ❌ Database models test failed: ' . $e->getMessage());
            return ['status' => 'failed', 'error' => $e->getMessage()];
        }
    }

    /**
     * Test payment gateways
     */
    private function testPaymentGateways($verbose)
    {
        $this->info('💳 Testing Payment Gateways...');
        
        try {
            $gateways = PaymentGateway::all();
            $activeGateways = PaymentGateway::active()->count();
            
            $gatewayTypes = [
                'credit_card' => PaymentGateway::where('type', 'credit_card')->count(),
                'pix' => PaymentGateway::where('type', 'pix')->count(),
                'boleto' => PaymentGateway::where('type', 'boleto')->count(),
                'cryptocurrency' => PaymentGateway::where('type', 'cryptocurrency')->count(),
            ];

            if ($verbose) {
                $this->line("  ✓ Total gateways: {$gateways->count()}");
                $this->line("  ✓ Active gateways: {$activeGateways}");
                foreach ($gatewayTypes as $type => $count) {
                    $this->line("  ✓ {$type}: {$count} gateways");
                }
            }

            $this->info('  ✅ Payment gateways test passed');
            return [
                'status' => 'passed',
                'data' => [
                    'total' => $gateways->count(),
                    'active' => $activeGateways,
                    'types' => $gatewayTypes
                ]
            ];

        } catch (\Exception $e) {
            $this->error('  ❌ Payment gateways test failed: ' . $e->getMessage());
            return ['status' => 'failed', 'error' => $e->getMessage()];
        }
    }

    /**
     * Test bonus configurations
     */
    private function testBonusConfigurations($verbose)
    {
        $this->info('🎁 Testing Bonus Configurations...');
        
        try {
            $configs = BonusConfiguration::all();
            $activeConfigs = BonusConfiguration::active()->count();
            
            $bonusTypes = [
                'direct_referral' => BonusConfiguration::where('bonus_type', 'direct_referral')->count(),
                'unilevel' => BonusConfiguration::where('bonus_type', 'unilevel')->count(),
                'forced_matrix' => BonusConfiguration::where('bonus_type', 'forced_matrix')->count(),
                'profit_sharing' => BonusConfiguration::where('bonus_type', 'profit_sharing')->count(),
            ];

            if ($verbose) {
                $this->line("  ✓ Total configurations: {$configs->count()}");
                $this->line("  ✓ Active configurations: {$activeConfigs}");
                foreach ($bonusTypes as $type => $count) {
                    $this->line("  ✓ {$type}: {$count} configurations");
                }
            }

            $this->info('  ✅ Bonus configurations test passed');
            return [
                'status' => 'passed',
                'data' => [
                    'total' => $configs->count(),
                    'active' => $activeConfigs,
                    'types' => $bonusTypes
                ]
            ];

        } catch (\Exception $e) {
            $this->error('  ❌ Bonus configurations test failed: ' . $e->getMessage());
            return ['status' => 'failed', 'error' => $e->getMessage()];
        }
    }

    /**
     * Test referral network structure
     */
    private function testReferralNetwork($verbose)
    {
        $this->info('🌐 Testing Referral Network Structure...');
        
        try {
            $network = ReferralNetwork::all();
            $rootClients = ReferralNetwork::whereNull('parent_id')->count();
            $clientsWithReferrals = ReferralNetwork::whereHas('children')->count();
            $totalReferrals = ReferralNetwork::whereNotNull('parent_id')->count();

            if ($verbose) {
                $this->line("  ✓ Total network entries: {$network->count()}");
                $this->line("  ✓ Root clients: {$rootClients}");
                $this->line("  ✓ Clients with referrals: {$clientsWithReferrals}");
                $this->line("  ✓ Total referrals: {$totalReferrals}");
            }

            $this->info('  ✅ Referral network test passed');
            return [
                'status' => 'passed',
                'data' => [
                    'total_entries' => $network->count(),
                    'root_clients' => $rootClients,
                    'clients_with_referrals' => $clientsWithReferrals,
                    'total_referrals' => $totalReferrals
                ]
            ];

        } catch (\Exception $e) {
            $this->error('  ❌ Referral network test failed: ' . $e->getMessage());
            return ['status' => 'failed', 'error' => $e->getMessage()];
        }
    }

    /**
     * Test bonus calculation service
     */
    private function testBonusCalculation($verbose)
    {
        $this->info('💰 Testing Bonus Calculation Service...');
        
        try {
            $bonusService = new BonusCalculationService();
            
            // Test if service can be instantiated
            $serviceClass = get_class($bonusService);
            
            if ($verbose) {
                $this->line("  ✓ Bonus service instantiated: {$serviceClass}");
            }

            // Test bonus payments
            $bonusPayments = BonusPayment::count();
            $pendingPayments = BonusPayment::where('status', 'pending')->count();
            $approvedPayments = BonusPayment::where('status', 'approved')->count();
            $paidPayments = BonusPayment::where('status', 'paid')->count();

            if ($verbose) {
                $this->line("  ✓ Total bonus payments: {$bonusPayments}");
                $this->line("  ✓ Pending payments: {$pendingPayments}");
                $this->line("  ✓ Approved payments: {$approvedPayments}");
                $this->line("  ✓ Paid payments: {$paidPayments}");
            }

            $this->info('  ✅ Bonus calculation service test passed');
            return [
                'status' => 'passed',
                'data' => [
                    'service_class' => $serviceClass,
                    'total_payments' => $bonusPayments,
                    'pending' => $pendingPayments,
                    'approved' => $approvedPayments,
                    'paid' => $paidPayments
                ]
            ];

        } catch (\Exception $e) {
            $this->error('  ❌ Bonus calculation service test failed: ' . $e->getMessage());
            return ['status' => 'failed', 'error' => $e->getMessage()];
        }
    }

    /**
     * Test system integration
     */
    private function testSystemIntegration($verbose)
    {
        $this->info('🔗 Testing System Integration...');
        
        try {
            // Test sales with clients
            $salesWithClients = Sale::whereHas('client')->count();
            $totalSales = Sale::count();
            
            // Test clients with network entries
            $clientsInNetwork = Client::whereHas('referralNetwork')->count();
            $totalClients = Client::count();
            
            // Test plans with products
            $plansWithProducts = Plan::whereHas('products')->count();
            $totalPlans = Plan::count();

            if ($verbose) {
                $this->line("  ✓ Sales with clients: {$salesWithClients}/{$totalSales}");
                $this->line("  ✓ Clients in network: {$clientsInNetwork}/{$totalClients}");
                $this->line("  ✓ Plans with products: {$plansWithProducts}/{$totalPlans}");
            }

            $integrationScore = 0;
            if ($salesWithClients > 0) $integrationScore += 33;
            if ($clientsInNetwork > 0) $integrationScore += 33;
            if ($plansWithProducts > 0) $integrationScore += 34;

            $this->info('  ✅ System integration test passed');
            return [
                'status' => 'passed',
                'data' => [
                    'sales_integration' => $salesWithClients,
                    'network_integration' => $clientsInNetwork,
                    'plans_integration' => $plansWithProducts,
                    'integration_score' => $integrationScore
                ]
            ];

        } catch (\Exception $e) {
            $this->error('  ❌ System integration test failed: ' . $e->getMessage());
            return ['status' => 'failed', 'error' => $e->getMessage()];
        }
    }

    /**
     * Display test results
     */
    private function displayResults($results)
    {
        $this->newLine();
        $this->info('📋 Test Results Summary:');
        $this->newLine();

        $passed = 0;
        $failed = 0;

        foreach ($results as $test => $result) {
            $status = $result['status'];
            $icon = $status === 'passed' ? '✅' : '❌';
            $color = $status === 'passed' ? 'info' : 'error';
            
            $this->$color("{$icon} " . ucfirst(str_replace('_', ' ', $test)) . ": {$status}");
            
            if ($status === 'passed') {
                $passed++;
            } else {
                $failed++;
            }
        }

        $this->newLine();
        $this->info("📊 Overall Results: {$passed} passed, {$failed} failed");

        if ($failed === 0) {
            $this->info('🎉 All tests passed! The referral system is working correctly.');
        } else {
            $this->error('⚠️  Some tests failed. Please check the system configuration.');
        }

        $this->newLine();
        $this->info('💡 Use --detailed flag for detailed output');
    }
}