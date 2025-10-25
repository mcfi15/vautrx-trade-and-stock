<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deposits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('cryptocurrency_id')->constrained()->onDelete('cascade');
            $table->foreignId('transaction_id')->nullable()->constrained()->onDelete('set null');
            $table->string('deposit_address');
            $table->decimal('amount', 30, 18);
            $table->string('blockchain_tx_hash')->unique();
            $table->integer('confirmations')->default(0);
            $table->integer('required_confirmations')->default(12);
            $table->enum('status', ['pending', 'confirming', 'completed', 'failed'])->default('pending');
            $table->timestamp('detected_at')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index('blockchain_tx_hash');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deposits');
    }
};
