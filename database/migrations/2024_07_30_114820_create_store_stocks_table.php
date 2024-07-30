<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('store_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained();
            $table->foreignId('product_id')->constrained();
            $table->integer('quantity');

            $table->unique(['store_id', 'product_id']);


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_stocks');
    }
};
