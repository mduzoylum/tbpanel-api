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
    Schema::create('invoices', function (Blueprint $table) {
        $table->id();
        $table->string('code');
        $table->decimal('order_total', 10, 2)->default(0.00);
        $table->decimal('tax_total', 10, 2)->default(0.00);
        $table->string('currency');
        $table->integer('product_count')->default(0);
        $table->unsignedBigInteger('invoice_type_id');
        $table->unsignedBigInteger('account_id');
        $table->timestamps();

        $table->foreign('invoice_type_id')->references('id')->on('invoice_types');
        $table->foreign('account_id')->references('id')->on('account');

    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
