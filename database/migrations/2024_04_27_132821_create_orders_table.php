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
    Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->string('code')->unique();
        $table->decimal('order_total', 10, 2)->default(0.00);
        $table->decimal('tax_total', 10, 2)->default(0.00);
        $table->string('currency');
        $table->integer('product_count')->default(0);
        $table->unsignedBigInteger('customer_id');
        $table->timestamps();

        $table->foreign('customer_id')->references('id')->on('customers');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
