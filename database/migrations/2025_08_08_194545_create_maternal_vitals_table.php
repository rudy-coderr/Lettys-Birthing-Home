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
        Schema::create('maternal_vitals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prenatal_visit_id')->constrained('prenatal_visit')->onDelete('cascade');
            $table->smallInteger('fht')->nullable();
            $table->decimal('fh', 4, 1)->nullable();
            $table->decimal('weight', 5, 2)->nullable();
            $table->string('blood_pressure', 10)->nullable();
            $table->decimal('temperature', 4, 1)->nullable();
            $table->smallInteger('respiratory_rate')->nullable();
            $table->smallInteger('pulse_rate')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maternal_vitals');
    }
};
