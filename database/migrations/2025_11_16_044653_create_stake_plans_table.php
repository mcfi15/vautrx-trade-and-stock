<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('stake_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('cryptocurrency_id')->constrained()->cascadeOnDelete();
            $table->decimal('min_amount', 30, 8);
            $table->decimal('percent', 8, 2); // APY %
            $table->json('durations'); // [30,60,90]
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stake_plans');
    }
};
