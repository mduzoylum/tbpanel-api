<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CurrencyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Currency::firstOrCreate([
            'code' => 'TRY'
        ],[
            'name' => 'Türk Lirası',
            'code' => 'TRY',
            'rate' => 1,
            'is_default' => true,
        ]);

        Currency::firstOrCreate([
            'code' => 'USD'
        ],[
            'name' => 'Dolar',
            'code' => 'USD',
            'rate' => 1,
            'is_default' => false,
        ]);

        Currency::firstOrCreate([
            'code' => 'EUR'
        ],[
            'name' => 'Euro',
            'code' => 'EUR',
            'rate' => 1,
            'is_default' => false,
        ]);
    }
}
