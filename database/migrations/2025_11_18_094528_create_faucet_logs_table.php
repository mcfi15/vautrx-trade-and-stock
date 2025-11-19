<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('faucet_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('faucet_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 30, 8);
            $table->unsignedBigInteger('cryptocurrency_id')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->enum('status', ['success','failed'])->default('success');
            $table->text('reason')->nullable();
            $table->timestamp('claimed_at')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faucet_logs');
    }
};
