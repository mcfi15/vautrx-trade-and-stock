<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('cryptocurrency_id')->constrained()->onDelete('cascade');
            $table->string('address')->nullable(); // Blockchain wallet address
            $table->decimal('balance', 30, 18)->default(0);
            $table->decimal('locked_balance', 30, 18)->default(0); // For pending orders
            $table->timestamps();

            $table->unique(['user_id', 'cryptocurrency_id']);
            $table->index('address');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallets');
    }
};
