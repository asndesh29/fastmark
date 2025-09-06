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
        Schema::create('fee_slabs', function (Blueprint $table) {
            $table->id();
            $table->enum('vehicle_type', ['two_wheeler','four_wheeler','heavy','other']);
            $table->integer('min_cc')->nullable();
            $table->integer('max_cc')->nullable();
            $table->decimal('base_fee', 12, 2);
            $table->decimal('late_per_day', 12, 2)->default(0); // simple model
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fee_slabs');
    }
};
