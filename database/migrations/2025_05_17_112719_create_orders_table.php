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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->foreignId('user_id')->references('id')->on('users');
            $table->foreignId('car_id')->references('id')->on('cars');
            $table->string('vin_code');
            $table->string('gos_number');
            $table->integer('mileage');
            $table->decimal('total_cost');
            $table->decimal('details_cost');
            $table->decimal('operations_cost');
            $table->timestamps();
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
