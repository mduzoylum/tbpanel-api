<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('target')->nullable(); // customer/supplier
            $table->string('code')->unique();
            $table->unsignedBigInteger('target_id')->nullable();
            $table->string('name')->nullable();
            $table->string('company')->nullable();
            $table->string('tax_number')->nullable();
            $table->string('tax_office')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('town')->nullable();
            $table->string('country')->nullable();
            $table->string('post_code')->nullable();
            $table->integer('risk_limit')->default(0);
            $table->integer('credit_limit')->default(0);
            $table->integer('discount_rate')->default(0);
            $table->string('currency')->nullable();
            $table->string('identity_number')->nullable();
            $table->string('iban')->nullable();

            $table->foreignId('seller_id')->nullable();
            $table->foreign('seller_id')->references('id')->on('users')->onDelete('set null');

            $table->foreignId('working_method_id')->nullable();
            $table->foreign('working_method_id')->references('id')->on('working_methods')->onDelete('set null');

            $table->foreignId('account_type_id')->nullable();
            $table->foreign('account_type_id')->references('id')->on('account_types')->onDelete('set null');

            $table->foreignId('account_status_id')->nullable();
            $table->foreign('account_status_id')->references('id')->on('account_statuses')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
