<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trades', function (Blueprint $table) {
            $table->id();
            $table->string('trade_number')->unique();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('trading_pair_id')->constrained()->onDelete('cascade');
            $table->foreignId('buyer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('seller_id')->constrained('users')->onDelete('cascade');
            $table->decimal('price', 20, 8);
            $table->decimal('quantity', 30, 18);
            $table->decimal('total_amount', 30, 8);
            $table->decimal('buyer_fee', 30, 8);
            $table->decimal('seller_fee', 30, 8);
            $table->string('blockchain_tx_hash')->nullable();
            $table->enum('blockchain_status', ['pending', 'confirmed', 'failed'])->default('pending');
            $table->timestamps();

            $table->index(['buyer_id', 'created_at']);
            $table->index(['seller_id', 'created_at']);
            $table->index('trade_number');
            $table->index('blockchain_tx_hash');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trades');
    }
};
