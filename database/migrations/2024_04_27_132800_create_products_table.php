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
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->string('stock_code')->unique();
        $table->string('name');
        $table->text('description');
        $table->string('model_code')->nullable();
        $table->integer('quantity')->default(0);
        $table->integer('box_quantity')->default(0);
        $table->integer('target_quantity')->default(0);
        $table->decimal('buying_price', 10, 2)->default(0.00);
        $table->float('list_price')->default(0);
        $table->float('sale_price')->default(0);
        $table->string('currency');
        $table->string('barcode')->nullable();
        $table->smallInteger('tax_rate');
        $table->unsignedBigInteger('status_id')->nullable();
        $table->unsignedBigInteger('unit_id');
        $table->unsignedBigInteger('supplier_id');
        $table->unsignedBigInteger('brand_id');
        $table->unsignedBigInteger('season_id');
        $table->unsignedBigInteger('group_id');
        $table->unsignedBigInteger('type_id');
        $table->timestamps();

        $table->foreign('unit_id')->references('id')->on('units');
        $table->foreign('status_id')->references('id')->on('products_status');
        $table->foreign('supplier_id')->references('id')->on('suppliers');
        $table->foreign('brand_id')->references('id')->on('brands');
        $table->foreign('season_id')->references('id')->on('seasons');
        $table->foreign('group_id')->references('id')->on('product_groups');
        $table->foreign('type_id')->references('id')->on('product_types');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
