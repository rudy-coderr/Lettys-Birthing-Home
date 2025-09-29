<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('patient_pdf_records', function (Blueprint $table) {
            // Add prenatal_visit_id column
            $table->unsignedBigInteger('prenatal_visit_id')->after('patient_id');

            // Foreign key reference corrected
            $table->foreign('prenatal_visit_id')
                ->references('id')
                ->on('prenatal_visit') // ✅ correct table name
                ->onDelete('cascade');
        });

    }

    public function down(): void
    {
        Schema::table('patient_pdf_records', function (Blueprint $table) {
            $table->dropForeign(['prenatal_visit_id']);
            $table->dropColumn('prenatal_visit_id');
        });
    }
};
