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
        Setting::firstOrCreate([
            'name' => 'Ürün Son Güncelleme Tarihi',
            'value' => "2023-01-01 00:00:00",
            'code' => 'product_last_updated_at',
            'visible' => false,
        ]);

        Setting::firstOrCreate([
            'name' => 'Cari Son Güncelleme Tarihi',
            'value' => "2023-01-01 00:00:00",
            'code' => 'account_last_updated_at',
            'visible' => false,
        ]);
    }
}
