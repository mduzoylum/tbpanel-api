<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::create('product_prices', function (Blueprint $table) {
        $table->id();
        $table->float('list_price')->default(0);
        $table->float('sale_price')->default(0);
        $table->string('currency')->nullable();
        $table->smallInteger('tax_rate')->nullable();
        $table->unsignedBigInteger('product_id');
        $table->unsignedBigInteger('price_field_id');
        $table->timestamps();

        $table->foreign('product_id')->references('id')->on('products');
        $table->foreign('price_field_id')->references('id')->on('price_fields');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_prices');
    }
};
