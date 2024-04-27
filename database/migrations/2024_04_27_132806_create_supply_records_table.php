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
    Schema::create('supply_records', function (Blueprint $table) {
        $table->id();
        $table->date('planned_date');
        $table->unsignedBigInteger('product_id');
        $table->timestamps();

        $table->foreign('product_id')->references('id')->on('products');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supply_records');
    }
};
