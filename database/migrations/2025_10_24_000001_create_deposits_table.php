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
            $table->string('transaction_hash')->nullable(); // Blockchain transaction hash
            $table->decimal('amount', 30, 18);
            $table->decimal('fee', 30, 18)->default(0);
            $table->string('status')->default('pending'); // pending, confirmed, completed, failed
            $table->integer('confirmations')->default(0);
            $table->integer('required_confirmations')->default(3);
            $table->timestamps();

            $table->index(['user_id', 'cryptocurrency_id']);
            $table->index('transaction_hash');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deposits');
    }
};