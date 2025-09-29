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
        Schema::create('prenatal_visit', function (Blueprint $table) {
            $table->id();

            // Match INT UNSIGNED of client.id
            $table->unsignedInteger('client_id');
            $table->foreign('client_id')
                ->references('id')
                ->on('client')
                ->onDelete('cascade');

            $table->tinyInteger('visit_number');
            $table->date('visit_date');
            $table->date('next_visit_date')->nullable();
            $table->date('lmp')->nullable();
            $table->date('edc')->nullable();
            $table->string('aog', 20)->nullable();
            $table->tinyInteger('gravida')->nullable();
            $table->tinyInteger('para')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prenatal_visit');
    }

};
