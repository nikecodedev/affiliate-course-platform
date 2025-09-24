<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BonusSetting;

class BonusSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Direct Referral Bonus Setting
        BonusSetting::create([
            'bonus_type' => 'direct_referral',
            'is_active' => true,
            'payment_mode' => 'fixed',
            'levels' => [
                '1' => [
                    'mode' => 'fixed',
                    'value' => 25.00
                ]
            ]
        ]);

        // Create Unilevel Bonus Setting
        BonusSetting::create([
            'bonus_type' => 'unilevel',
            'is_active' => true,
            'payment_mode' => 'fixed',
            'levels' => [
                '1' => [
                    'mode' => 'fixed',
                    'value' => 50.00
                ],
                '2' => [
                    'mode' => 'fixed',
                    'value' => 30.00
                ],
                '3' => [
                    'mode' => 'fixed',
                    'value' => 20.00
                ],
                '4' => [
                    'mode' => 'fixed',
                    'value' => 15.00
                ],
                '5' => [
                    'mode' => 'fixed',
                    'value' => 10.00
                ]
            ]
        ]);

        // Create Matrix Bonus Setting
        BonusSetting::create([
            'bonus_type' => 'matrix',
            'is_active' => true,
            'payment_mode' => 'fixed',
            'width' => 2,
            'depth' => 5,
            'levels' => [
                '1' => [
                    'mode' => 'fixed',
                    'value' => 100.00
                ],
                '2' => [
                    'mode' => 'fixed',
                    'value' => 80.00
                ],
                '3' => [
                    'mode' => 'fixed',
                    'value' => 60.00
                ],
                '4' => [
                    'mode' => 'fixed',
                    'value' => 40.00
                ],
                '5' => [
                    'mode' => 'fixed',
                    'value' => 20.00
                ]
            ]
        ]);

        $this->command->info('Bonus settings seeded successfully!');
    }
}