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
        Schema::create('renewal_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('slug', 255)->unique()->nullable();

            $table->enum('target_type', ['vehicle', 'customer']);

            // Private validity
            $table->integer('private_validity_value')->nullable();
            $table->enum('private_validity_unit', ['days', 'months', 'years'])->nullable();

            // Commercial validity
            $table->integer('commercial_validity_value')->nullable();
            $table->enum('commercial_validity_unit', ['days', 'months', 'years'])->nullable();

            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('renewal_types');
    }
};
