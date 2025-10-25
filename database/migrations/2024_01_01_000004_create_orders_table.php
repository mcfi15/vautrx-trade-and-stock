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
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('trading_pair_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['market', 'limit', 'stop_loss', 'stop_limit']);
            $table->enum('side', ['buy', 'sell']);
            $table->decimal('price', 20, 8)->nullable(); // Null for market orders
            $table->decimal('stop_price', 20, 8)->nullable(); // For stop orders
            $table->decimal('quantity', 30, 18);
            $table->decimal('filled_quantity', 30, 18)->default(0);
            $table->decimal('remaining_quantity', 30, 18);
            $table->decimal('total_amount', 30, 8);
            $table->decimal('fee', 30, 8)->default(0);
            $table->enum('status', ['pending', 'partial', 'completed', 'cancelled', 'expired'])->default('pending');
            $table->timestamp('executed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['trading_pair_id', 'type', 'status']);
            $table->index('order_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
