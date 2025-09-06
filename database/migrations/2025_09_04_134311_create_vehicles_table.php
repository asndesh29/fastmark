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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->string('registration_no')->unique(); // e.g., Ba 2 Pa 1234
            $table->string('chassis_no')->nullable();
            $table->string('engine_no')->nullable();
            $table->enum('type', ['two_wheeler','four_wheeler','heavy','other'])->index();
            $table->integer('engine_cc')->nullable();
            $table->date('last_renewed_at')->nullable();
            $table->date('expiry_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
