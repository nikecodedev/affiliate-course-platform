<?php

/**
 * Affiliate Course Platform Setup Script
 * 
 * This script helps set up the Laravel application with all necessary configurations
 */

echo "🚀 Affiliate Course Platform Setup\n";
echo "==================================\n\n";

// Check PHP version
if (version_compare(PHP_VERSION, '8.1.0', '<')) {
    echo "❌ PHP 8.1 or higher is required. Current version: " . PHP_VERSION . "\n";
    exit(1);
}

echo "✅ PHP version: " . PHP_VERSION . "\n";

// Check if Composer is installed
if (!file_exists('composer.json')) {
    echo "❌ composer.json not found. Please run this script from the project root.\n";
    exit(1);
}

echo "✅ Project structure found\n";

// Check if .env file exists
if (!file_exists('.env')) {
    if (file_exists('env.example')) {
        copy('env.example', '.env');
        echo "✅ Created .env file from env.example\n";
    } else {
        echo "❌ env.example not found. Please create .env file manually.\n";
        exit(1);
    }
} else {
    echo "✅ .env file exists\n";
}

// Check database configuration
$envContent = file_get_contents('.env');
if (!str_contains($envContent, 'DB_DATABASE=')) {
    echo "⚠️  Please configure database settings in .env file\n";
}

echo "\n📋 Setup Instructions:\n";
echo "=====================\n\n";

echo "1. Configure Environment Variables:\n";
echo "   - Update database credentials in .env file\n";
echo "   - Set APP_KEY (run: php artisan key:generate)\n";
echo "   - Configure reCAPTCHA keys if needed\n";
echo "   - Set Google Analytics and Meta Pixel IDs\n\n";

echo "2. Install Dependencies:\n";
echo "   composer install\n\n";

echo "3. Create Database:\n";
echo "   Create MySQL database: affiliate_platform\n\n";

echo "4. Run Migrations:\n";
echo "   php artisan migrate\n\n";

echo "5. Seed Database:\n";
echo "   php artisan db:seed\n\n";

echo "6. Create Storage Link:\n";
echo "   php artisan storage:link\n\n";

echo "7. Set Permissions (Linux/Mac):\n";
echo "   chmod -R 775 storage bootstrap/cache\n\n";

echo "8. Start Development Server:\n";
echo "   php artisan serve\n\n";

echo "🔐 Default Admin Credentials:\n";
echo "============================\n";
echo "Email: admin@example.com\n";
echo "Password: password123\n\n";

echo "📁 Important Directories:\n";
echo "========================\n";
echo "- Storage: storage/app/public (for file uploads)\n";
echo "- Logs: storage/logs\n";
echo "- Cache: storage/framework/cache\n\n";

echo "🛠️  Available Artisan Commands:\n";
echo "===============================\n";
echo "- php artisan commission:process-payments (Daily commission processing)\n";
echo "- php artisan bonus:process-network (Network bonus processing)\n";
echo "- php artisan profit:process-daily-sharing (Daily profit sharing)\n\n";

echo "📊 Admin Panel Features:\n";
echo "========================\n";
echo "✅ Secure login with 2FA and reCAPTCHA\n";
echo "✅ System settings management\n";
echo "✅ Plan and product management\n";
echo "✅ Course (EAD) system\n";
echo "✅ Sales and invoice management\n";
echo "✅ User and affiliate management\n";
echo "✅ Financial reports and analytics\n";
echo "✅ Automated commission processing\n";
echo "✅ Audit logging and security\n\n";

echo "🔧 Configuration Files:\n";
echo "=======================\n";
echo "- .env (Environment variables)\n";
echo "- config/app.php (Application settings)\n";
echo "- config/auth.php (Authentication settings)\n";
echo "- config/recaptcha.php (reCAPTCHA settings)\n\n";

echo "📚 Documentation:\n";
echo "=================\n";
echo "- README.md (Project overview)\n";
echo "- Database migrations in database/migrations/\n";
echo "- Models in app/Models/\n";
echo "- Controllers in app/Http/Controllers/Admin/\n";
echo "- Views in resources/views/admin/\n\n";

echo "🎯 Next Steps:\n";
echo "==============\n";
echo "1. Run the commands above to complete setup\n";
echo "2. Access admin panel at: http://localhost:8000/admin\n";
echo "3. Configure system settings\n";
echo "4. Create your first plan and course\n";
echo "5. Set up your tracking codes\n\n";

echo "🚀 Setup complete! Happy coding!\n";
