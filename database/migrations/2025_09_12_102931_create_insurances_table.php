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
        Schema::create('insurances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->onDelete('cascade');
            $table->foreignId('provider_id')->constrained('insurance_providers')->onDelete('cascade');

            $table->string('invoice_no')->nullable()->index();
            $table->string('policy_number')->nullable();

            $table->string('issue_date_bs')->nullable();
            $table->date('issue_date_ad')->nullable();

            $table->string('expiry_date_bs')->nullable();
            $table->date('expiry_date_ad')->nullable();

            $table->string('renewed_expiry_date_bs')->nullable();
            $table->date('renewed_expiry_date_ad')->nullable();

            $table->enum('insurance_type', ['general', 'third', 'partial'])->default('general')->index();
            $table->decimal('renewal_charge', 12, 2)->default(0);

            $table->enum('payment_status', ['paid', 'unpaid', 'partial'])
                ->default('unpaid')
                ->index();
            $table->string('remarks')->nullable();

            // Indexing for performance
            $table->index('vehicle_id');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insurances');
    }
};
