<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Providers\DatabaseServiceProvider;
use Illuminate\Support\Facades\DB;

class TestDatabaseSecurity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:test-security {--connection=mysql : Database connection to test}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test database connection security and configuration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $connection = $this->option('connection');
        
        $this->info("Testing database security for connection: {$connection}");
        $this->line('');
        
        // Test connection security
        $results = DatabaseServiceProvider::testConnectionSecurity($connection);
        
        // Display results
        if ($results['secure']) {
            $this->info('✅ Database connection is secure!');
        } else {
            $this->error('❌ Database connection has security issues:');
            
            foreach ($results['issues'] as $issue) {
                $this->line("  • {$issue}");
            }
        }
        
        $this->line('');
        
        // Display recommendations
        if (!empty($results['recommendations'])) {
            $this->warn('Recommendations:');
            foreach ($results['recommendations'] as $recommendation) {
                $this->line("  • {$recommendation}");
            }
            $this->line('');
        }
        
        // Test basic connectivity
        $this->info('Testing basic connectivity...');
        try {
            DB::connection($connection)->getPdo();
            $this->info('✅ Database connection successful');
        } catch (\Exception $e) {
            $this->error('❌ Database connection failed: ' . $e->getMessage());
            return 1;
        }
        
        // Test prepared statements
        $this->info('Testing prepared statements...');
        try {
            $result = DB::connection($connection)->select('SELECT ? as test', [1]);
            if ($result && $result[0]->test == 1) {
                $this->info('✅ Prepared statements working correctly');
            } else {
                $this->error('❌ Prepared statements not working correctly');
            }
        } catch (\Exception $e) {
            $this->error('❌ Prepared statement test failed: ' . $e->getMessage());
        }
        
        // Test SQL mode
        $this->info('Testing SQL mode...');
        try {
            $sqlMode = DB::connection($connection)->select('SELECT @@sql_mode as sql_mode')[0]->sql_mode;
            $this->line("Current SQL mode: {$sqlMode}");
            
            $requiredModes = ['STRICT_TRANS_TABLES', 'NO_ZERO_DATE'];
            $missingModes = [];
            
            foreach ($requiredModes as $mode) {
                if (strpos($sqlMode, $mode) === false) {
                    $missingModes[] = $mode;
                }
            }
            
            if (empty($missingModes)) {
                $this->info('✅ SQL mode is secure');
            } else {
                $this->warn('⚠️  Missing SQL modes: ' . implode(', ', $missingModes));
            }
        } catch (\Exception $e) {
            $this->error('❌ SQL mode test failed: ' . $e->getMessage());
        }
        
        // Test SSL connection
        $this->info('Testing SSL connection...');
        try {
            $sslStatus = DB::connection($connection)->select("SHOW STATUS LIKE 'Ssl_cipher'");
            if ($sslStatus && !empty($sslStatus[0]->Value)) {
                $this->info('✅ SSL connection is active');
                $this->line("SSL Cipher: {$sslStatus[0]->Value}");
            } else {
                $this->warn('⚠️  SSL connection not detected');
                $this->line('Consider enabling SSL for production environments');
            }
        } catch (\Exception $e) {
            $this->error('❌ SSL test failed: ' . $e->getMessage());
        }
        
        // Display configuration summary
        $this->line('');
        $this->info('Configuration Summary:');
        $this->table(
            ['Setting', 'Value'],
            [
                ['Connection', $connection],
                ['Host', config("database.connections.{$connection}.host")],
                ['Database', config("database.connections.{$connection}.database")],
                ['Username', config("database.connections.{$connection}.username")],
                ['Charset', config("database.connections.{$connection}.charset")],
                ['Collation', config("database.connections.{$connection}.collation")],
                ['Engine', config("database.connections.{$connection}.engine")],
                ['Strict Mode', config("database.connections.{$connection}.strict") ? 'Enabled' : 'Disabled'],
                ['Persistent', config("database.connections.{$connection}.options.PDO::ATTR_PERSISTENT") ? 'Enabled' : 'Disabled'],
            ]
        );
        
        return 0;
    }
}
