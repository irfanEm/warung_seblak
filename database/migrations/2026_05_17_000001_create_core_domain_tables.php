<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Outlets
        Schema::create('outlets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address')->nullable();
            $table->decimal('lat', 10, 8)->nullable();
            $table->decimal('lon', 11, 8)->nullable();
            $table->timestamps();
        });

        // 2. Menu Categories
        Schema::create('menu_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('outlet_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->integer('sort')->default(0);
            $table->timestamps();
            
            $table->unique(['outlet_id', 'slug']); // slug unik per outlet
        });

        // 3. Menus
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('outlet_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('menu_categories')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->string('image')->nullable();
            $table->boolean('is_available')->default(true);
            $table->integer('stock_quantity')->nullable();
            $table->timestamps();
            
            $table->unique(['outlet_id', 'slug']);
            // Indeks pada kolom yang sering di-query
            $table->index('category_id');
            $table->index('outlet_id');
        });

        // 4. Toppings
        Schema::create('toppings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('outlet_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->decimal('price', 10, 2);
            $table->timestamps();
            
            $table->index('outlet_id');
        });

        // 5. Menu Topping Pivot
        Schema::create('menu_topping', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained()->cascadeOnDelete();
            $table->foreignId('topping_id')->constrained()->cascadeOnDelete();
            $table->decimal('price', 10, 2)->nullable(); // Harga khusus jika override
        });

        // 6. Spiciness Levels
        Schema::create('spiciness_levels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('outlet_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->timestamps();
        });

        // 7. Menu Spiciness Pivot
        Schema::create('menu_spiciness_level', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained()->cascadeOnDelete();
            $table->foreignId('spiciness_level_id')->constrained()->cascadeOnDelete();
        });

        // 8. Tables
        Schema::create('tables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('outlet_id')->constrained()->cascadeOnDelete();
            $table->string('table_number');
            $table->string('token', 64)->unique()->nullable();
            $table->enum('status', ['available', 'occupied'])->default('available');
            $table->timestamps();
            
            $table->index('outlet_id');
            $table->index('token');
        });

        // 9. Delivery Settings
        Schema::create('delivery_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('outlet_id')->unique()->constrained()->cascadeOnDelete();
            $table->decimal('base_rate_per_km', 10, 2);
            $table->decimal('minimum_charge', 10, 2);
            $table->decimal('free_delivery_min_order', 10, 2)->nullable();
            $table->decimal('max_delivery_distance', 8, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_settings');
        Schema::dropIfExists('tables');
        Schema::dropIfExists('menu_spiciness_level');
        Schema::dropIfExists('spiciness_levels');
        Schema::dropIfExists('menu_topping');
        Schema::dropIfExists('toppings');
        Schema::dropIfExists('menus');
        Schema::dropIfExists('menu_categories');
        Schema::dropIfExists('outlets');
    }
};
