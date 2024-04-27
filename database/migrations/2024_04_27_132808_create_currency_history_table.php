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
    Schema::create('currency_history', function (Blueprint $table) {
        $table->id();
        $table->date('date');
        $table->decimal('rate', 10, 6)->default(1.000000);
        $table->unsignedBigInteger('currency_id');
        $table->unsignedBigInteger('default_currency_id');
        $table->timestamps();

        $table->foreign('currency_id')->references('id')->on('currency');
        $table->foreign('default_currency_id')->references('id')->on('currency');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('currency_history');
    }
};
