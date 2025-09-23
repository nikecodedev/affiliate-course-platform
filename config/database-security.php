<?php

/**
 * Database Security Configuration
 * Enhanced PDO settings for maximum security
 */

return [
    
    /*
    |--------------------------------------------------------------------------
    | PDO Security Options
    |--------------------------------------------------------------------------
    |
    | These options enhance the security of PDO connections by:
    | - Enabling error reporting
    | - Disabling prepared statement emulation
    | - Setting proper fetch modes
    | - Configuring SSL connections
    |
    */
    
    'pdo_options' => [
        // Error handling
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        
        // Security settings
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_STRINGIFY_FETCHES => false,
        
        // Connection settings
        PDO::ATTR_PERSISTENT => env('DB_PERSISTENT', false),
        PDO::ATTR_TIMEOUT => env('DB_CONNECT_TIMEOUT', 10),
        
        // MySQL specific options
        PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci",
    ],
    
    /*
    |--------------------------------------------------------------------------
    | SSL Configuration
    |--------------------------------------------------------------------------
    |
    | Configure SSL connections for production environments
    | Uncomment and configure these for secure database connections
    |
    */
    
    'ssl_options' => [
        PDO::MYSQL_ATTR_SSL_CA => env('DB_SSL_CA'),
        PDO::MYSQL_ATTR_SSL_CERT => env('DB_SSL_CERT'),
        PDO::MYSQL_ATTR_SSL_KEY => env('DB_SSL_KEY'),
        PDO::MYSQL_ATTR_SSL_CIPHER => env('DB_SSL_CIPHER'),
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => env('DB_SSL_VERIFY', false),
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Connection Pool Settings
    |--------------------------------------------------------------------------
    |
    | Configure connection pooling for better performance
    |
    */
    
    'pool_settings' => [
        'min_connections' => env('DB_POOL_MIN', 1),
        'max_connections' => env('DB_POOL_MAX', 10),
        'connect_timeout' => env('DB_CONNECT_TIMEOUT', 10),
        'wait_timeout' => env('DB_WAIT_TIMEOUT', 60),
        'heartbeat' => env('DB_HEARTBEAT', -1),
        'max_idle_time' => env('DB_MAX_IDLE_TIME', 3600),
    ],
    
    /*
    |--------------------------------------------------------------------------
    | SQL Mode Configuration
    |--------------------------------------------------------------------------
    |
    | Configure strict SQL modes for data integrity
    |
    */
    
    'sql_modes' => [
        'STRICT_TRANS_TABLES',
        'NO_ZERO_DATE',
        'NO_ZERO_IN_DATE',
        'ERROR_FOR_DIVISION_BY_ZERO',
        'NO_AUTO_CREATE_USER',
        'NO_ENGINE_SUBSTITUTION',
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Security Headers
    |--------------------------------------------------------------------------
    |
    | Database security headers and settings
    |
    */
    
    'security_headers' => [
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'engine' => env('DB_ENGINE', 'InnoDB'),
        'timezone' => env('DB_TIMEZONE', '+00:00'),
        'prefix' => env('DB_PREFIX', ''),
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Backup Configuration
    |--------------------------------------------------------------------------
    |
    | Database backup settings
    |
    */
    
    'backup' => [
        'enabled' => env('DB_BACKUP_ENABLED', true),
        'schedule' => env('DB_BACKUP_SCHEDULE', '0 2 * * *'),
        'retention_days' => env('DB_BACKUP_RETENTION_DAYS', 30),
        'storage' => env('DB_BACKUP_STORAGE', 'local'),
        'compress' => env('DB_BACKUP_COMPRESS', true),
        'encrypt' => env('DB_BACKUP_ENCRYPT', false),
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Monitoring Configuration
    |--------------------------------------------------------------------------
    |
    | Database monitoring and logging settings
    |
    */
    
    'monitoring' => [
        'enabled' => env('DB_MONITORING_ENABLED', true),
        'slow_query_threshold' => env('DB_SLOW_QUERY_THRESHOLD', 1000),
        'log_queries' => env('DB_LOG_QUERIES', false),
        'log_slow_queries' => env('DB_LOG_SLOW_QUERIES', true),
        'log_errors' => env('DB_LOG_ERRORS', true),
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Performance Configuration
    |--------------------------------------------------------------------------
    |
    | Database performance optimization settings
    |
    */
    
    'performance' => [
        'query_cache' => env('DB_QUERY_CACHE', true),
        'query_cache_size' => env('DB_QUERY_CACHE_SIZE', '64M'),
        'query_cache_type' => env('DB_QUERY_CACHE_TYPE', 'ON'),
        'innodb_buffer_pool_size' => env('DB_INNODB_BUFFER_POOL_SIZE', '128M'),
        'innodb_log_file_size' => env('DB_INNODB_LOG_FILE_SIZE', '64M'),
        'innodb_flush_log_at_trx_commit' => env('DB_INNODB_FLUSH_LOG_AT_TRX_COMMIT', 1),
    ],
    
];
