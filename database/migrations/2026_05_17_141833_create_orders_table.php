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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 20)->unique();
            $table->foreignId('outlet_id')->constrained('outlets');
            $table->enum('type', ['dine_in', 'delivery', 'pos'])->default('dine_in');
            $table->foreignId('table_id')->nullable()->constrained('tables')->nullOnDelete();
            $table->foreignId('delivery_address_id')->nullable(); // Ignore constraints for now
            $table->string('customer_name', 100)->nullable();
            $table->string('customer_phone', 20)->nullable();
            $table->decimal('subtotal', 10, 2);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('delivery_fee', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->enum('status', ['pending', 'payment_pending', 'paid', 'confirmed', 'preparing', 'ready', 'completed', 'cancelled'])->default('pending');
            $table->text('notes')->nullable();
            $table->foreignId('applied_promo_id')->nullable(); // Ignore constraints for now
            $table->foreignId('assigned_driver_id')->nullable(); // Ignore constraints for now
            $table->timestamps();

            // Indexes
            $table->index('outlet_id');
            $table->index('status');
            $table->index('order_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
