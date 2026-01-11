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
            $table->foreignId('merchant_id')->constrained('merchants')->onDelete('cascade');
            $table->decimal('food_amount', 10, 2)->default(0);
            $table->decimal('food_vat', 10, 2)->default(0);
            $table->decimal('non_food_amount', 10, 2)->default(0);
            $table->decimal('non_food_vat', 10, 2)->default(0);
            $table->decimal('delivery_fee', 10, 2)->default(0);
            $table->decimal('service_fee', 10, 2)->default(0);
            $table->decimal('packaging_fee', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->enum('status', ['pending', 'delivered', 'refunded', 'cancelled'])->default('pending');
            $table->foreignId('payout_period_id')->nullable()->constrained('merchant_payout_periods')->onDelete('set null');
            $table->dateTime('delivered_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
