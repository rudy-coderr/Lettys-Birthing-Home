<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('patient_pdf_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id');
            $table->string('file_name');
            $table->binary('file_data'); // dito i-store ang PDF mismo
            $table->timestamps();

            $table->foreign('patient_id')->references('id')->on('patient')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('patient_pdf_records');
    }

};
