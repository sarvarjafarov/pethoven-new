<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Lunar\Models\Channel;
use Lunar\Models\Currency;
use Lunar\Models\Language;
use Lunar\Models\TaxZone;
use Lunar\Models\TaxClass;
use Lunar\Models\TaxRate;
use Lunar\Models\TaxRateAmount;
use Lunar\Models\Country;

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
            $exists = \DB::table('lunar_currency_channel')
                ->where('currency_id', $currency->id)
                ->where('channel_id', $channel->id)
                ->exists();
            
            if (!$exists) {
                \DB::table('lunar_currency_channel')->insert([
                    'currency_id' => $currency->id,
                    'channel_id' => $channel->id,
                ]);
                $this->command->info('Associated currency with channel');
            }
        }
        
        if (\Schema::hasTable('lunar_language_channel')) {
            $exists = \DB::table('lunar_language_channel')
                ->where('language_id', $language->id)
                ->where('channel_id', $channel->id)
                ->exists();
            
            if (!$exists) {
                \DB::table('lunar_language_channel')->insert([
                    'language_id' => $language->id,
                    'channel_id' => $channel->id,
                ]);
                $this->command->info('Associated language with channel');
            }
        }
        
        // Ensure default TaxZone exists (CRITICAL for tax calculation)
        $taxZone = TaxZone::where('default', true)->first();
        if (!$taxZone) {
            $taxZone = TaxZone::create([
                'name' => 'Default Tax Zone',
                'zone_type' => 'country',
                'price_display' => 'tax_exclusive',
                'default' => true,
                'active' => true,
            ]);
            
            // Add all countries to the tax zone
            if (class_exists(Country::class)) {
                $taxZone->countries()->createMany(
                    Country::get()->map(fn ($country) => [
                        'country_id' => $country->id,
                    ])
                );
            }
            $this->command->info('Created default tax zone');
        }

        // Ensure TaxClass exists
        $taxClass = TaxClass::first();
        if (!$taxClass) {
            $taxClass = TaxClass::create([
                'name' => 'Default Tax',
            ]);
            $this->command->info('Created default tax class');
        }

        // Ensure TaxRate exists for the tax zone
        $taxRate = TaxRate::where('tax_zone_id', $taxZone->id)->first();
        
        if (!$taxRate) {
            $taxRate = TaxRate::create([
                'tax_zone_id' => $taxZone->id,
                'name' => 'Standard Rate',
                'priority' => 1,
            ]);
            $this->command->info('Created default tax rate');
        }

        // Ensure TaxRateAmount exists linking TaxRate to TaxClass
        $taxRateAmount = TaxRateAmount::where('tax_rate_id', $taxRate->id)
            ->where('tax_class_id', $taxClass->id)
            ->first();
        
        if (!$taxRateAmount) {
            TaxRateAmount::create([
                'tax_rate_id' => $taxRate->id,
                'tax_class_id' => $taxClass->id,
                'percentage' => 0, // 0% tax by default, can be changed in admin
            ]);
            $this->command->info('Created default tax rate amount');
        }

        // Verify associations
        $this->command->info('Channel ID: ' . $channel->id);
        $this->command->info('Currency ID: ' . $currency->id);
        $this->command->info('Language ID: ' . $language->id);
        $this->command->info('Tax Zone ID: ' . $taxZone->id);
        $this->command->info('Tax Class ID: ' . $taxClass->id);
    }
}

