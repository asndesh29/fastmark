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
        Schema::create('vehicle_passes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
            $table->string('invoice_no')->nullable();
            $table->string('issue_date')->nullable();
            $table->string('last_expiry_date')->nullable();
            $table->string('expiry_date')->nullable();
            // $table->enum('inspection_result', ['pass', 'fail']);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('renewal_charge', 12, 2)->default(0);
            $table->decimal('income_tax', 12, 2)->default(0);
            $table->enum('status', ['paid', 'unpaid'])->default('unpaid');
            $table->string('remarks')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_passes');
    }
};
