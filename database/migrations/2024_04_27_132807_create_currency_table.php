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
    Schema::create('currency', function (Blueprint $table) {
        $table->id();
        $table->string('code')->unique();
        $table->string('name');
        $table->boolean('is_default')->default(false);
        $table->decimal('rate', 10, 6)->default(1.000000);
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('currency');
    }
};
