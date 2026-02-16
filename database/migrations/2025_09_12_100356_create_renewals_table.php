<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('renewals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained('vehicles')->cascadeOnDelete();
            $table->foreignId(column: 'renewal_type_id')->constrained('renewal_types')->cascadeOnDelete();

            // Polymorphic columns: renewable_type = class name, renewable_id = primary key
            // $table->string('renewable_type');  // e.g., App\Models\RoadPermit
            // $table->unsignedBigInteger('renewable_id');

            $table->morphs('renewable');

            // Start Date
            $table->string('start_date_bs')->nullable();
            $table->date('start_date_ad')->nullable();

            // Expiry Date
            $table->string('expiry_date_bs')->nullable();
            $table->date('expiry_date_ad')->nullable();

            // Optional reminder
            $table->date('reminder_date')->nullable();

            $table->enum('status', ['valid', 'expired', 'renewed', 'cancelled'])->default('valid');

            $table->boolean('is_paid')->default(false);

            $table->text('remarks')->nullable();

            // Index for polymorphic lookup optimization
            // $table->index(['renewable_type', 'renewable_id']);
            $table->index('expiry_date_ad');
            $table->index('status');

            $table->softDeletes();
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
