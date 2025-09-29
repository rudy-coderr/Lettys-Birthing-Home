<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('patient', function (Blueprint $table) {
            $table->id();
            // Make sure this matches the type of client.id (unsigned big integer by default in Laravel's id())
            $table->unsignedInteger('client_id');
            $table->foreign('client_id')->references('id')->on('client')->onDelete('cascade');

            $table->tinyInteger('age')->nullable();
            $table->string('spouse_name', 150)->nullable();
            $table->enum('marital_status', ['Married', 'Single', 'Widowed', 'Divorced'])->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('patient');
    }
};
