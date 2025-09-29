<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('medicine_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_item_id')->constrained('inventory_items')->onDelete('cascade');
            $table->enum('medicine_type', ['tablet', 'syrup', 'injection', 'capsule', 'ointment', 'drops']);
            $table->string('dosage');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('medicine_details');
    }
};
