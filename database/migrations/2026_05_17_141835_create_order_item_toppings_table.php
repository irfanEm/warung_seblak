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
        Schema::create('order_item_toppings', function (Blueprint $table) {
            $table->foreignId('order_item_id')->constrained('order_items')->cascadeOnDelete();
            $table->foreignId('topping_id')->nullable()->constrained('toppings')->nullOnDelete();
            $table->string('topping_name', 100);
            $table->decimal('price', 10, 2);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_item_toppings');
    }
};
