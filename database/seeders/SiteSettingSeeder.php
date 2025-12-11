<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MainSetting;
use App\Helpers\SettingsHelper;

class SiteSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Starting site settings import...');

        // Define default site settings
        $defaultSettings = [
            'site_phone' => [
                'value' => '+71122334455',
                'type' => 'text',
                'label' => 'Phone',
                'description' => 'Site contact phone number',
                'order' => 1
            ],
            'site_email' => [
                'value' => 'example@example.com',
                'type' => 'text',
                'label' => 'Email',
                'description' => 'Site contact email address',
                'order' => 2
            ],
            'site_address' => [
                'value' => 'г. Бишкек, ул. Ахунбаева 1/1',
                'type' => 'text',
                'label' => 'Address',
                'description' => 'Site contact address',
                'order' => 3
            ],
            'site_instagram' => [
                'value' => 'https://www.instagram.com',
                'type' => 'text',
                'label' => 'Instagram',
                'description' => 'Instagram social media link',
                'order' => 4
            ],
        ];

        $updatedCount = 0;
        $createdCount = 0;

        foreach ($defaultSettings as $key => $settingData) {
            try {
                // Check if setting already exists
                $existingSetting = MainSetting::where('key', $key)->first();

                if ($existingSetting) {
                    // Update existing setting if needed
                    $existingSetting->update([
                        'type' => $settingData['type'],
                        'label' => $settingData['label'],
                        'description' => $settingData['description'],
                        'order' => $settingData['order'],
                        'is_active' => true
                    ]);
                    $updatedCount++;
                    $this->command->info("Updated setting: {$key}");
                } else {
                    // Create new setting
                    MainSetting::create([
                        'key' => $key,
                        'value' => $settingData['value'],
                        'type' => $settingData['type'],
                        'label' => $settingData['label'],
                        'description' => $settingData['description'],
                        'order' => $settingData['order'],
                        'is_active' => true
                    ]);
                    $createdCount++;
                    $this->command->info("Created setting: {$key} = {$settingData['value']}");
                }

            } catch (\Exception $e) {
                $this->command->error("Failed to process setting {$key}: " . $e->getMessage());
            }
        }

        // Clear settings cache
        try {
            SettingsHelper::clearCache();
            MainSetting::clearAllCache();
            $this->command->info('Settings cache cleared successfully.');
        } catch (\Exception $e) {
            $this->command->warn('Failed to clear cache: ' . $e->getMessage());
        }

        $this->command->info("Site settings import completed!");
        $this->command->info("Created: {$createdCount} settings");
        $this->command->info("Updated: {$updatedCount} settings");
    }
}
