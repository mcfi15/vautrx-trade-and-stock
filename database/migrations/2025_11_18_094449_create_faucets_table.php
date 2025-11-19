<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('faucets', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->unsignedBigInteger('cryptocurrency_id')->nullable(); // coin given
            $table->decimal('amount', 30, 8)->default(0);
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();
            $table->integer('cooldown_seconds')->default(86400); // default 24h
            $table->integer('max_claims_per_user')->default(1); // total allowed claims per user
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('cryptocurrency_id')->references('id')->on('cryptocurrencies')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faucets');
    }
};
