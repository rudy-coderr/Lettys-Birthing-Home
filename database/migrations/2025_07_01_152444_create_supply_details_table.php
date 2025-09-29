<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('supply_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_item_id')->constrained('inventory_items')->onDelete('cascade');
            $table->enum('supply_type', ['disposable', 'equipment', 'consumable', 'instrument', 'safety']);
            $table->string('unit_size');
            $table->string('unit_measure')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('supply_details');
    }
};
