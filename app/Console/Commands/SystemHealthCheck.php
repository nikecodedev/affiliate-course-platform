<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use App\Models\Client;
use App\Models\Sale;
use App\Models\PaymentGateway;
use App\Models\BonusPayment;

class SystemHealthCheck extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'system:health-check {--detailed : Show detailed health information}';

    /**
     * The console command description.
     */
    protected $description = 'Perform comprehensive system health check';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🏥 Starting System Health Check...');
        $this->newLine();

        $detailed = $this->option('detailed');
        $results = [];

        // Check Database
        $results['database'] = $this->checkDatabase($detailed);

        // Check Storage
        $results['storage'] = $this->checkStorage($detailed);

        // Check Cache
        $results['cache'] = $this->checkCache($detailed);

        // Check Models
        $results['models'] = $this->checkModels($detailed);

        // Check Payment Gateways
        $results['gateways'] = $this->checkPaymentGateways($detailed);

        // Display Results
        $this->displayResults($results);

        return 0;
    }

    /**
     * Check database connectivity
     */
    private function checkDatabase($detailed)
    {
        $this->info('🗄️  Checking Database...');
        
        try {
            $startTime = microtime(true);
            DB::connection()->getPdo();
            $connectionTime = round((microtime(true) - $startTime) * 1000, 2);

            $queryStartTime = microtime(true);
            $clientCount = Client::count();
            $queryTime = round((microtime(true) - $queryStartTime) * 1000, 2);

            if ($detailed) {
                $this->line("  ✓ Connection time: {$connectionTime}ms");
                $this->line("  ✓ Query time: {$queryTime}ms");
                $this->line("  ✓ Client records: {$clientCount}");
            }

            $status = 'healthy';
            if ($connectionTime > 100 || $queryTime > 500) {
                $status = 'warning';
            }

            $this->info("  ✅ Database: {$status}");
            return ['status' => $status];

        } catch (\Exception $e) {
            $this->error('  ❌ Database check failed: ' . $e->getMessage());
            return ['status' => 'critical', 'error' => $e->getMessage()];
        }
    }

    /**
     * Check storage systems
     */
    private function checkStorage($detailed)
    {
        $this->info('💾 Checking Storage...');
        
        try {
            $testFile = 'health-check-' . time() . '.txt';
            Storage::disk('public')->put($testFile, 'test');
            Storage::disk('public')->delete($testFile);

            if ($detailed) {
                $this->line('  ✓ Storage is writable');
            }

            $this->info('  ✅ Storage: healthy');
            return ['status' => 'healthy'];

        } catch (\Exception $e) {
            $this->error('  ❌ Storage check failed: ' . $e->getMessage());
            return ['status' => 'critical', 'error' => $e->getMessage()];
        }
    }

    /**
     * Check cache systems
     */
    private function checkCache($detailed)
    {
        $this->info('⚡ Checking Cache...');
        
        try {
            $cacheKey = 'health-check-' . time();
            Cache::put($cacheKey, 'test', 60);
            $value = Cache::get($cacheKey);
            Cache::forget($cacheKey);

            if ($value !== 'test') {
                throw new \Exception('Cache read/write mismatch');
            }

            if ($detailed) {
                $this->line('  ✓ Cache is working');
            }

            $this->info('  ✅ Cache: healthy');
            return ['status' => 'healthy'];

        } catch (\Exception $e) {
            $this->error('  ❌ Cache check failed: ' . $e->getMessage());
            return ['status' => 'warning', 'error' => $e->getMessage()];
        }
    }

    /**
     * Check application models
     */
    private function checkModels($detailed)
    {
        $this->info('📊 Checking Models...');
        
        try {
            $models = [
                'Client' => Client::count(),
                'Sale' => Sale::count(),
                'PaymentGateway' => PaymentGateway::count(),
                'BonusPayment' => BonusPayment::count(),
            ];

            if ($detailed) {
                foreach ($models as $model => $count) {
                    $this->line("  ✓ {$model}: {$count} records");
                }
            }

            $this->info('  ✅ Models: healthy');
            return ['status' => 'healthy'];

        } catch (\Exception $e) {
            $this->error('  ❌ Models check failed: ' . $e->getMessage());
            return ['status' => 'critical', 'error' => $e->getMessage()];
        }
    }

    /**
     * Check payment gateways
     */
    private function checkPaymentGateways($detailed)
    {
        $this->info('💳 Checking Payment Gateways...');
        
        try {
            $totalGateways = PaymentGateway::count();
            $activeGateways = PaymentGateway::active()->count();

            if ($detailed) {
                $this->line("  ✓ Total gateways: {$totalGateways}");
                $this->line("  ✓ Active gateways: {$activeGateways}");
            }

            $status = 'healthy';
            if ($totalGateways === 0) {
                $status = 'warning';
            } elseif ($activeGateways === 0 && $totalGateways > 0) {
                $status = 'critical';
            }

            $this->info("  ✅ Payment gateways: {$status}");
            return ['status' => $status];

        } catch (\Exception $e) {
            $this->error('  ❌ Payment gateways check failed: ' . $e->getMessage());
            return ['status' => 'critical', 'error' => $e->getMessage()];
        }
    }

    /**
     * Display results
     */
    private function displayResults($results)
    {
        $this->newLine();
        $this->info('📋 Health Check Summary:');
        $this->newLine();

        $healthy = 0;
        $warning = 0;
        $critical = 0;

        foreach ($results as $component => $result) {
            $status = $result['status'];
            $icon = $status === 'healthy' ? '✅' : ($status === 'warning' ? '⚠️' : '🚨');
            $color = $status === 'healthy' ? 'info' : ($status === 'warning' ? 'warn' : 'error');
            
            $this->$color("{$icon} " . ucfirst($component) . ": {$status}");
            
            if ($status === 'healthy') $healthy++;
            elseif ($status === 'warning') $warning++;
            else $critical++;
        }

        $this->newLine();
        $this->info("📊 Overall: {$healthy} healthy, {$warning} warnings, {$critical} critical");

        if ($critical > 0) {
            $this->error('🚨 Critical issues detected!');
        } elseif ($warning > 0) {
            $this->warn('⚠️  Warnings detected.');
        } else {
            $this->info('🎉 All systems healthy!');
        }
    }
}