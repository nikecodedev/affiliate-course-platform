<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\GlobalBonusConfiguration;
use App\Models\BonusLevel;

class GlobalBonusConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Unilevel Bonus Configuration
        $unilevelConfig = GlobalBonusConfiguration::create([
            'name' => 'Unilevel Bonus',
            'type' => 'unilevel',
            'description' => 'Unilevel bonus structure with configurable levels and amounts',
            'is_active' => true,
            'is_percentage' => false,
            'max_depth' => 10,
            'max_width' => 0,
            'min_sale_amount' => 0,
            'requires_active_invoice' => true,
        ]);

        // Create Unilevel Bonus Levels
        $unilevelLevels = [
            ['level' => 1, 'amount' => 50.00, 'is_percentage' => false, 'description' => 'Level 1 - Direct referrals'],
            ['level' => 2, 'amount' => 30.00, 'is_percentage' => false, 'description' => 'Level 2 - Second generation'],
            ['level' => 3, 'amount' => 20.00, 'is_percentage' => false, 'description' => 'Level 3 - Third generation'],
            ['level' => 4, 'amount' => 15.00, 'is_percentage' => false, 'description' => 'Level 4 - Fourth generation'],
            ['level' => 5, 'amount' => 10.00, 'is_percentage' => false, 'description' => 'Level 5 - Fifth generation'],
        ];

        foreach ($unilevelLevels as $levelData) {
            BonusLevel::create([
                'bonus_configuration_id' => $unilevelConfig->id,
                'level' => $levelData['level'],
                'amount' => $levelData['amount'],
                'is_percentage' => $levelData['is_percentage'],
                'description' => $levelData['description'],
                'is_active' => true,
            ]);
        }

        // Create Forced Matrix Bonus Configuration
        $matrixConfig = GlobalBonusConfiguration::create([
            'name' => 'Forced Matrix Bonus',
            'type' => 'forced_matrix',
            'description' => 'Forced matrix bonus structure with width and depth limits',
            'is_active' => true,
            'is_percentage' => false,
            'max_depth' => 10,
            'max_width' => 2,
            'min_sale_amount' => 0,
            'requires_active_invoice' => true,
        ]);

        // Create Forced Matrix Bonus Levels
        $matrixLevels = [
            ['level' => 1, 'amount' => 100.00, 'is_percentage' => false, 'description' => 'Level 1 - First 2 positions'],
            ['level' => 2, 'amount' => 80.00, 'is_percentage' => false, 'description' => 'Level 2 - Next 4 positions'],
            ['level' => 3, 'amount' => 60.00, 'is_percentage' => false, 'description' => 'Level 3 - Next 8 positions'],
            ['level' => 4, 'amount' => 40.00, 'is_percentage' => false, 'description' => 'Level 4 - Next 16 positions'],
            ['level' => 5, 'amount' => 20.00, 'is_percentage' => false, 'description' => 'Level 5 - Next 32 positions'],
        ];

        foreach ($matrixLevels as $levelData) {
            BonusLevel::create([
                'bonus_configuration_id' => $matrixConfig->id,
                'level' => $levelData['level'],
                'amount' => $levelData['amount'],
                'is_percentage' => $levelData['is_percentage'],
                'description' => $levelData['description'],
                'is_active' => true,
            ]);
        }

        // Create Direct Referral Bonus Configuration
        $directReferralConfig = GlobalBonusConfiguration::create([
            'name' => 'Direct Referral Bonus',
            'type' => 'direct_referral',
            'description' => 'Direct referral bonus for immediate referrals',
            'is_active' => true,
            'is_percentage' => false,
            'max_depth' => 1,
            'max_width' => 0,
            'min_sale_amount' => 0,
            'requires_active_invoice' => false, // Direct referral bonus is always paid
        ]);

        // Create Direct Referral Bonus Level
        BonusLevel::create([
            'bonus_configuration_id' => $directReferralConfig->id,
            'level' => 1,
            'amount' => 25.00,
            'is_percentage' => false,
            'description' => 'Direct referral bonus amount',
            'is_active' => true,
        ]);

        $this->command->info('Global bonus configurations seeded successfully!');
    }
}