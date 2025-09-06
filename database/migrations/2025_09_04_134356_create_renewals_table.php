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
        Schema::create('renewals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained('customers');
            $table->enum('status', ['draft','submitted','under_review','correction_required','approved','rejected','issued'])->default('draft')->index();
            // $table->date('requested_for_year'); // Nepali fiscal or Gregorian year start date; you choose
            $table->unsignedInteger('late_days')->default(0);
            $table->decimal('base_fee', 12, 2)->default(0);
            $table->decimal('penalty_fee', 12, 2)->default(0);
            $table->decimal('service_fee', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('renewals');
    }
};
