<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seller_payouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 12, 2);
            $table->enum('payout_speed', ['instant', 'regular'])->default('instant');
            $table->string('bank_name');
            $table->string('account_number');
            $table->string('account_name');
            $table->string('reference_code');
            $table->enum('status', ['pending', 'processed', 'failed'])->default('processed');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seller_payouts');
    }
};
