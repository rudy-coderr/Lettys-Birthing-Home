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
        Schema::table('patient_pdf_records', function (Blueprint $table) {
            $table->unsignedBigInteger('prenatal_visit_id')->nullable()->after('patient_id');
            $table->foreign('prenatal_visit_id')->references('id')->on('prenatal_visit')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patient_pdf_records', function (Blueprint $table) {
            //
        });
    }
};
