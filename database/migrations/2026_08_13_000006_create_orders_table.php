<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('buyer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('store_id')->constrained('stores')->onDelete('cascade');
            $table->foreignId('store_tracking_link_id')->nullable()->constrained('store_tracking_links')->onDelete('set null');
            $table->decimal('total_product_amount', 12, 2);
            $table->decimal('shipping_fee', 12, 2)->default(15000);
            $table->decimal('platform_fee', 12, 2)->default(2000); // Transparent service fee
            $table->decimal('total_paid_amount', 12, 2);
            $table->enum('status', ['pending', 'paid', 'processing', 'shipped', 'completed', 'disputed', 'cancelled'])->default('paid');
            $table->string('shipping_courier')->default('J&T Express Reguler');
            $table->string('tracking_number')->nullable();
            $table->string('recipient_name');
            $table->string('recipient_phone');
            $table->text('shipping_address');
            $table->timestamp('escrow_released_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
