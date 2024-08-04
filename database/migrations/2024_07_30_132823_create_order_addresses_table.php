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
    Schema::create('order_addresses', function (Blueprint $table) {
        $table->id();
        $table->string('order_type');
        $table->string('address');
        $table->string('company')->nullable();
        $table->string('phone');
        $table->unsignedBigInteger('order_id');
        $table->unsignedBigInteger('county_id');
        $table->unsignedBigInteger('city_id');
        $table->unsignedBigInteger('town_id');
        $table->timestamps();

        $table->foreign('order_id')->references('id')->on('orders');
        $table->foreign('county_id')->references('id')->on('town');
        $table->foreign('city_id')->references('id')->on('cities');
        $table->foreign('town_id')->references('id')->on('town');

    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_addresses');
    }
};
