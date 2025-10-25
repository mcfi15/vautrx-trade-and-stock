<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_hash')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('cryptocurrency_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['deposit', 'withdrawal', 'trade_buy', 'trade_sell', 'fee', 'bonus']);
            $table->decimal('amount', 30, 18);
            $table->decimal('fee', 30, 18)->default(0);
            $table->decimal('balance_before', 30, 18);
            $table->decimal('balance_after', 30, 18);
            $table->string('from_address')->nullable();
            $table->string('to_address')->nullable();
            $table->string('blockchain_tx_hash')->nullable();
            $table->enum('status', ['pending', 'processing', 'completed', 'failed', 'cancelled'])->default('pending');
            $table->text('description')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'type', 'status']);
            $table->index('blockchain_tx_hash');
            $table->index('transaction_hash');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
