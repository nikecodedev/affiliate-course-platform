<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create default admin user if not exists
        if (!Admin::where('email', 'admin@example.com')->exists()) {
            Admin::create([
                'name' => 'Administrator',
                'email' => 'admin@example.com',
                'password' => Hash::make('password123'),
                'is_active' => true,
                'two_factor_enabled' => true,
                'two_factor_verified' => false,
            ]);
        }

        // Create default system settings
        $this->createDefaultSettings();

        // Create plans
        $this->call(PlanSeeder::class);
        
        // Create sample client data
        $this->call(ClientSeeder::class);
        
        // Create bonus configurations and payment gateways
        $this->call(BonusConfigurationSeeder::class);
        
        // Create system customizations
        $this->call(SystemCustomizationSeeder::class);

        // Sample data for core tables
        $this->call(SampleDataSeeder::class);
    }

    /**
     * Create default system settings
     */
    private function createDefaultSettings()
    {
        // SEO Settings
        SystemSetting::set('site_title', 'Affiliate Course Platform', 'string', 'seo', 'Site title for SEO');
        SystemSetting::set('site_description', 'Professional affiliate and course management platform', 'string', 'seo', 'Site meta description');
        SystemSetting::set('site_keywords', 'affiliate, courses, e-learning, digital products', 'string', 'seo', 'Site meta keywords');

        // Appearance Settings
        SystemSetting::set('logo_path', null, 'string', 'appearance', 'Main logo path');
        SystemSetting::set('logo_dark_path', null, 'string', 'appearance', 'Dark logo path');
        SystemSetting::set('favicon_path', null, 'string', 'appearance', 'Favicon path');
        SystemSetting::set('background_path', null, 'string', 'appearance', 'Background image path');

        // Tracking Settings
        SystemSetting::set('google_analytics_id', null, 'string', 'tracking', 'Google Analytics ID');
        SystemSetting::set('google_tag_manager_id', null, 'string', 'tracking', 'Google Tag Manager ID');
        SystemSetting::set('meta_pixel_id', null, 'string', 'tracking', 'Meta Pixel ID');
        SystemSetting::set('google_analytics_code', null, 'text', 'tracking', 'Google Analytics tracking code');
        SystemSetting::set('google_tag_manager_code', null, 'text', 'tracking', 'Google Tag Manager code');
        SystemSetting::set('meta_pixel_code', null, 'text', 'tracking', 'Meta Pixel tracking code');

        // reCAPTCHA Settings
        SystemSetting::set('recaptcha_site_key', null, 'string', 'recaptcha', 'reCAPTCHA site key');
        SystemSetting::set('recaptcha_secret_key', null, 'string', 'recaptcha', 'reCAPTCHA secret key');
        SystemSetting::set('recaptcha_enabled', '1', 'boolean', 'recaptcha', 'Enable reCAPTCHA');

        // Financial Settings
        SystemSetting::set('withdrawal_days', '1,2,3,4,5', 'string', 'financial', 'Days when withdrawals are allowed (comma-separated)');
        SystemSetting::set('withdrawal_start_time', '09:00', 'string', 'financial', 'Withdrawal start time');
        SystemSetting::set('withdrawal_end_time', '18:00', 'string', 'financial', 'Withdrawal end time');
        SystemSetting::set('withdrawal_fee_type', 'percentage', 'string', 'financial', 'Withdrawal fee type (percentage or fixed)');
        SystemSetting::set('withdrawal_fee_value', '5.00', 'decimal', 'financial', 'Withdrawal fee value');
    }
}

