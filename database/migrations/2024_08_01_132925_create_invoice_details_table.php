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

        $table->unsignedBigInteger('invoice_id');
        $table->string('product_code');
        $table->float('price')->default(0);
        $table->float('tax_rate');
        $table->integer('quantity')->default(0);
        $table->unsignedBigInteger('order_id')->nullable();
        $table->unsignedBigInteger('product_id')->nullable();
        $table->decimal('amount_total', 10, 2)->default(0.00);
        $table->decimal('tax_total', 10, 2)->default(0.00);
        $table->decimal('discount_total', 10, 2)->default(0.00);
        $table->string('currency');
        $table->string('unit_name');
        $table->timestamps();

        $table->foreign('invoice_id')->references('id')->on('invoices');
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
