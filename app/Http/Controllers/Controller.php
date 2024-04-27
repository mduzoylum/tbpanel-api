<?php

namespace App\Http\Controllers;

abstract class Controller
{
    private function test()
    {
        echo "test";
    }
}


/*
php artisan make:migration create_products_table
php artisan make:migration create_units_table
php artisan make:migration create_attributes_table
php artisan make:migration create_attribute_options_table
php artisan make:migration create_product_attributes_table
php artisan make:migration create_products_status_table
php artisan make:migration create_supply_records_table
php artisan make:migration create_currency_table
php artisan make:migration create_currency_history_table
php artisan make:migration create_price_fields_table
php artisan make:migration create_product_prices_table
php artisan make:migration create_product_images_table
php artisan make:migration create_suppliers_table
php artisan make:migration create_brands_table
php artisan make:migration create_seasons_table
php artisan make:migration create_customers_table
php artisan make:migration create_countries_table
php artisan make:migration create_cities_table
php artisan make:migration create_towns_table
php artisan make:migration create_customer_transactions_table
php artisan make:migration create_customer_address_table
php artisan make:migration create_orders_table
php artisan make:migration create_order_products_table
php artisan make:migration create_order_address_table
php artisan make:migration create_invoices_table
php artisan make:migration create_invoice_types_table
php artisan make:migration create_invoice_details_table
php artisan make:migration create_account_table
php artisan make:migration create_users_table
php artisan make:migration create_message_table
*/

