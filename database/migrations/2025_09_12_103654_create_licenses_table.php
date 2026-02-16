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
        Schema::create('licenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('license_type_id')->constrained('license_type')->cascadeOnDelete();

            $table->string('invoice_no')->nullable()->index();
            $table->string('license_number')->nullable();

            $table->string('issue_date_bs')->nullable();
            $table->date('issue_date_ad')->nullable();

            $table->string('expiry_date_bs')->nullable();
            $table->date('expiry_date_ad')->nullable();

            $table->string('renewed_expiry_date_bs')->nullable();
            $table->date('renewed_expiry_date_ad')->nullable();

            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('renewal_charge', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2)->virtualAs(
                'tax_amount + renewal_charge'
            );

            $table->enum('payment_status', ['paid', 'unpaid', 'partial'])
                ->default('unpaid')
                ->index();
            $table->string('remarks')->nullable();

            // Indexing for performance
            $table->index('customer_id');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('licenses');
    }
};
