<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('airdrops', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->unsignedBigInteger('holding_currency_id')->nullable(); // currency user must hold
            $table->decimal('min_hold_amount', 30, 8)->default(0); // minimum holding required
            $table->unsignedBigInteger('airdrop_currency_id'); // currency to distribute
            $table->decimal('airdrop_amount', 30, 8); // amount per eligible user (or total if you later change)
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('holding_currency_id')->references('id')->on('cryptocurrencies')->nullOnDelete();
            $table->foreign('airdrop_currency_id')->references('id')->on('cryptocurrencies')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('airdrops');
    }
};
