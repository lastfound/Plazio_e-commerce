<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('tagline')->nullable();
            $table->text('description')->nullable();
            $table->string('logo')->nullable();
            $table->string('banner')->nullable();
            $table->string('city')->default('Jakarta');
            $table->boolean('is_verified')->default(true);
            $table->boolean('is_local_umkm')->default(true);
            $table->decimal('rating', 3, 2)->default(4.9);
            $table->enum('subscription_tier', ['free', 'pro', 'premium'])->default('free');
            $table->boolean('instant_payout_enabled')->default(true);
            $table->decimal('balance', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};
