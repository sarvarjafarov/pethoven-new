<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Lunar\Models\Channel;
use Lunar\Models\Currency;
use Lunar\Models\Language;

class LunarSetupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default channel if it doesn't exist
        $channel = Channel::first();
        if (!$channel) {
            $channel = Channel::create([
                'name' => 'Webstore',
                'handle' => 'webstore',
                'default' => true,
                'url' => config('app.url'),
            ]);
            $this->command->info('Created default channel: Webstore');
        }

        // Ensure default currency exists
        $currency = Currency::getDefault();
        if (!$currency) {
            $currency = Currency::create([
                'code' => 'GBP',
                'name' => 'British Pound',
                'exchange_rate' => 1.00,
                'decimal_places' => 2,
                'default' => true,
                'enabled' => true,
            ]);
            $this->command->info('Created default currency: GBP');
        }

        // Ensure default language exists
        $language = Language::getDefault();
        if (!$language) {
            $language = Language::create([
                'code' => 'en',
                'name' => 'English',
                'default' => true,
            ]);
            $this->command->info('Created default language: English');
        }

        // Associate channel with currency and language using pivot tables
        if (\Schema::hasTable('lunar_currency_channel')) {
            \DB::table('lunar_currency_channel')->insertOrIgnore([
                'currency_id' => $currency->id,
                'channel_id' => $channel->id,
            ]);
        }
        if (\Schema::hasTable('lunar_language_channel')) {
            \DB::table('lunar_language_channel')->insertOrIgnore([
                'language_id' => $language->id,
                'channel_id' => $channel->id,
            ]);
        }
    }
}

