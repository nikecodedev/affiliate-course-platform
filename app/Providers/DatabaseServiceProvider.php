<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use PDO;
use PDOException;

class DatabaseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register database security configurations
        $this->app->singleton('database.security', function ($app) {
            return config('database-security');
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Configure database connection with enhanced security
        $this->configureDatabaseSecurity();
        
        // Set up database monitoring
        $this->setupDatabaseMonitoring();
        
        // Configure connection pooling
        $this->configureConnectionPooling();
    }

    /**
     * Configure database security settings
     */
    private function configureDatabaseSecurity(): void
    {
        // Get security configuration
        $securityConfig = config('database-security');
        
        // Configure PDO options for all connections
        $this->app->bind('database.connection.mysql', function ($app) use ($securityConfig) {
            $config = $app['config']['database.connections.mysql'];
            
            // Merge security options with existing PDO options
            $pdoOptions = array_merge(
                $securityConfig['pdo_options'] ?? [],
                $config['options'] ?? []
            );
            
            // Add SSL options if configured
            if (env('DB_SSL_CA') || env('DB_SSL_CERT')) {
                $pdoOptions = array_merge($pdoOptions, $securityConfig['ssl_options'] ?? []);
            }
            
            // Set SQL mode for strict data handling
            if (!empty($securityConfig['sql_modes'])) {
                $sqlModes = implode(',', $securityConfig['sql_modes']);
                $pdoOptions[PDO::MYSQL_ATTR_INIT_COMMAND] = 
                    "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci, sql_mode='{$sqlModes}'";
            }
            
            $config['options'] = $pdoOptions;
            
            return $config;
        });
    }

    /**
     * Set up database monitoring
     */
    private function setupDatabaseMonitoring(): void
    {
        $monitoringConfig = config('database-security.monitoring');
        
        if (!$monitoringConfig['enabled']) {
            return;
        }
        
        // Monitor slow queries
        if ($monitoringConfig['log_slow_queries']) {
            DB::listen(function ($query) use ($monitoringConfig) {
                $executionTime = $query->time;
                $threshold = $monitoringConfig['slow_query_threshold'];
                
                if ($executionTime > $threshold) {
                    \Log::warning('Slow Query Detected', [
                        'sql' => $query->sql,
                        'bindings' => $query->bindings,
                        'time' => $executionTime,
                        'threshold' => $threshold
                    ]);
                }
            });
        }
        
        // Monitor database errors
        if ($monitoringConfig['log_errors']) {
            DB::connection()->getPdo()->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
    }

    /**
     * Configure connection pooling
     */
    private function configureConnectionPooling(): void
    {
        $poolConfig = config('database-security.pool_settings');
        
        // Set connection timeouts
        if (isset($poolConfig['connect_timeout'])) {
            ini_set('mysql.connect_timeout', $poolConfig['connect_timeout']);
        }
        
        if (isset($poolConfig['wait_timeout'])) {
            ini_set('mysql.wait_timeout', $poolConfig['wait_timeout']);
        }
    }

    /**
     * Get secure database connection
     */
    public static function getSecureConnection($connectionName = 'mysql')
    {
        try {
            $config = config("database.connections.{$connectionName}");
            $securityConfig = config('database-security');
            
            // Build DSN
            $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']};charset=utf8mb4";
            
            // Add SSL options to DSN if configured
            if (env('DB_SSL_CA')) {
                $dsn .= ";sslmode=require";
            }
            
            // Create PDO connection with security options
            $pdo = new PDO(
                $dsn,
                $config['username'],
                $config['password'],
                $securityConfig['pdo_options']
            );
            
            // Set additional security attributes
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            
            return $pdo;
            
        } catch (PDOException $e) {
            \Log::error('Database connection failed', [
                'error' => $e->getMessage(),
                'connection' => $connectionName
            ]);
            throw $e;
        }
    }

    /**
     * Test database connection security
     */
    public static function testConnectionSecurity($connectionName = 'mysql'): array
    {
        $results = [
            'connection' => $connectionName,
            'secure' => true,
            'issues' => [],
            'recommendations' => []
        ];
        
        try {
            $pdo = self::getSecureConnection($connectionName);
            
            // Test SSL connection
            $sslStatus = $pdo->query("SHOW STATUS LIKE 'Ssl_cipher'")->fetch();
            if (!$sslStatus || empty($sslStatus['Value'])) {
                $results['issues'][] = 'SSL connection not established';
                $results['recommendations'][] = 'Enable SSL for database connections';
            }
            
            // Test SQL mode
            $sqlMode = $pdo->query("SELECT @@sql_mode")->fetchColumn();
            $requiredModes = ['STRICT_TRANS_TABLES', 'NO_ZERO_DATE'];
            foreach ($requiredModes as $mode) {
                if (strpos($sqlMode, $mode) === false) {
                    $results['issues'][] = "SQL mode '{$mode}' not enabled";
                    $results['recommendations'][] = "Enable SQL mode '{$mode}' for data integrity";
                }
            }
            
            // Test prepared statements
            $stmt = $pdo->prepare("SELECT 1 as test");
            $stmt->execute();
            $result = $stmt->fetch();
            if (!$result || $result['test'] !== 1) {
                $results['issues'][] = 'Prepared statements not working correctly';
                $results['recommendations'][] = 'Check PDO configuration';
            }
            
            if (!empty($results['issues'])) {
                $results['secure'] = false;
            }
            
        } catch (PDOException $e) {
            $results['secure'] = false;
            $results['issues'][] = 'Connection failed: ' . $e->getMessage();
        }
        
        return $results;
    }
}
