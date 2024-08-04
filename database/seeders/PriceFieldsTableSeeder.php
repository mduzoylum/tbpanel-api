<?php

namespace Database\Seeders;

use App\Models\PriceField;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PriceFieldsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PriceField::firstOrCreate([
            'code' => 'cost_price',
            'name' => 'Alış Fiyatı',
        ]);

        PriceField::firstOrCreate([
            'code' => 'market_cost_price',
            'name' => 'Piyasa Alış Fiyatı'
        ]);

        PriceField::firstOrCreate([
            'code' => 'online_sale_price',
            'name' => 'İnternet Satış Fiyatı'
        ]);

        PriceField::firstOrCreate([
            'code' => 'discounted_online_sale_price',
            'name' => 'İndirimli İnternet Satış Fiyatı'
        ]);

        PriceField::firstOrCreate([
            'code' => 'credit_card_sale_price',
            'name' => 'Kredi Kartı Satış Fiyatı'
        ]);

        PriceField::firstOrCreate([
            'code' => 'store_sale_price',
            'name' => 'Mağaza Satış Fiyatı'
        ]);


    }
}
