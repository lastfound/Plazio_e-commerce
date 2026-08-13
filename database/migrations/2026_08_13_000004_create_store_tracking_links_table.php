<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('store_tracking_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('set null');
            $table->string('name'); // e.g. "Iklan Facebook Sepatu Lokal"
            $table->string('code')->unique(); // e.g. ref=fb_shoe_99
            $table->string('channel')->default('meta_ads'); // meta_ads, tiktok_ads, google_ads, instagram, direct
            $table->enum('target_type', ['store', 'product'])->default('store');
            $table->integer('clicks_count')->default(0);
            $table->integer('conversions_count')->default(0);
            $table->decimal('total_revenue', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('store_tracking_links');
    }
};
