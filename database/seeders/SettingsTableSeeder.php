<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // create if not exists

        Setting::firstOrCreate([
            'code' => 'product_last_updated_at'
        ],[
            'name' => 'Ürün Son Güncelleme Tarihi',
            'value' => "2016-01-01 00:00:00",
            'code' => 'product_last_updated_at',
            'visible' => false,
        ]);

        Setting::firstOrCreate([
            'code' => 'account_last_updated_at'
        ],[
            'name' => 'Cari Son Güncelleme Tarihi',
            'value' => "2016-01-01 00:00:00",
            'code' => 'account_last_updated_at',
            'visible' => false,
        ]);

        Setting::firstOrCreate([
            'code' => 'order_last_updated_at'
        ],[
            'name' => 'Sipariş Son Güncelleme Tarihi',
            'value' => "2016-01-01 00:00:00",
            'code' => 'order_last_updated_at',
            'visible' => false,
        ]);

        Setting::firstOrCreate([
            'code' => 'invoice_last_updated_at'
        ],[
            'name' => 'Fatura Son Güncelleme Tarihi',
            'value' => "2016-01-01 00:00:00",
            'code' => 'invoice_last_updated_at',
            'visible' => false,
        ]);

        Setting::firstOrCreate([
            'code' => 'integration_last_updated_at'
        ],[
            'name' => 'Entegrasyon Son Güncelleme Tarihi',
            'value' => "2016-01-01 00:00:00",
            'code' => 'integration_last_updated_at',
            'visible' => false,
        ]);
    }
}
