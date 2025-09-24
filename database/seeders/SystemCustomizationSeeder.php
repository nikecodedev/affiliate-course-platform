<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SystemCustomization;

class SystemCustomizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultCustomizations = [
            // Company Information
            [
                'key' => 'company_name',
                'value' => 'Your Platform',
                'type' => 'text',
                'description' => 'The name of your company/platform',
            ],
            [
                'key' => 'company_email',
                'value' => 'contact@yourplatform.com',
                'type' => 'text',
                'description' => 'Company contact email address',
            ],
            [
                'key' => 'company_phone',
                'value' => '+1 (555) 123-4567',
                'type' => 'text',
                'description' => 'Company contact phone number',
            ],
            [
                'key' => 'company_address',
                'value' => '123 Business St, City, State 12345',
                'type' => 'text',
                'description' => 'Company physical address',
            ],
            
            // Brand Colors
            [
                'key' => 'primary_color',
                'value' => '#007bff',
                'type' => 'color',
                'description' => 'Primary brand color used throughout the platform',
            ],
            [
                'key' => 'secondary_color',
                'value' => '#6c757d',
                'type' => 'color',
                'description' => 'Secondary brand color used for accents',
            ],
            
            // Brand Assets (these will be null initially)
            [
                'key' => 'logo_path',
                'value' => null,
                'type' => 'image',
                'description' => 'Path to the company logo image',
            ],
            [
                'key' => 'favicon_path',
                'value' => null,
                'type' => 'image',
                'description' => 'Path to the favicon image',
            ],
            [
                'key' => 'background_path',
                'value' => null,
                'type' => 'image',
                'description' => 'Path to the background image',
            ],
            
            // Additional Settings
            [
                'key' => 'login_background_enabled',
                'value' => 'true',
                'type' => 'boolean',
                'description' => 'Whether to show background image on login page',
            ],
            [
                'key' => 'dashboard_background_enabled',
                'value' => 'false',
                'type' => 'boolean',
                'description' => 'Whether to show background image on dashboard',
            ],
            [
                'key' => 'maintenance_mode',
                'value' => 'false',
                'type' => 'boolean',
                'description' => 'Enable maintenance mode for the platform',
            ],
            [
                'key' => 'maintenance_message',
                'value' => 'We are currently performing scheduled maintenance. Please check back soon.',
                'type' => 'text',
                'description' => 'Message shown during maintenance mode',
            ],
        ];

        foreach ($defaultCustomizations as $customization) {
            SystemCustomization::updateOrCreate(
                ['key' => $customization['key']],
                [
                    'value' => $customization['value'],
                    'type' => $customization['type'],
                    'description' => $customization['description'],
                    'is_active' => true,
                ]
            );
        }

        $this->command->info('System customization settings seeded successfully!');
    }
}
