<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stock_price_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->decimal('open_price', 10, 2);
            $table->decimal('high_price', 10, 2);
            $table->decimal('low_price', 10, 2);
            $table->decimal('close_price', 10, 2);
            $table->bigInteger('volume')->default(0);
            $table->decimal('adjusted_close', 10, 2)->nullable();
            $table->timestamps();
            
            // Unique constraint to prevent duplicate entries for same stock and date
            $table->unique(['stock_id', 'date']);
            
            // Index for faster queries
            $table->index(['stock_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_price_history');
    }
};
