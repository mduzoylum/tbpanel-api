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
    Schema::create('towns', function (Blueprint $table) {
        $table->id();
        $table->string('code')->unique();
        $table->string('name');
        $table->unsignedBigInteger('country_id');
        $table->unsignedBigInteger('city_id');
        $table->timestamps();

        $table->foreign('country_id')->references('id')->on('countries');
        $table->foreign('city_id')->references('id')->on('cities');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('towns');
    }
};
