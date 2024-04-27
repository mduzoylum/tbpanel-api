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
    Schema::create('invoice_details', function (Blueprint $table) {
        $table->id();
        $table->string('product_name');
        $table->float('list_price')->default(0);
        $table->float('sale_price')->default(0);
        $table->smallInteger('tax_rate');
        $table->integer('quantity')->default(0);
        $table->unsignedBigInteger('order_id');
        $table->unsignedBigInteger('product_id');
        $table->timestamps();

        $table->foreign('order_id')->references('id')->on('orders');
        $table->foreign('product_id')->references('id')->on('products');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_details');
    }
};
