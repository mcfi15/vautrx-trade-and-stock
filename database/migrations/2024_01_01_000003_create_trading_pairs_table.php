<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trading_pairs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('base_currency_id')->constrained('cryptocurrencies')->onDelete('cascade');
            $table->foreignId('quote_currency_id')->constrained('cryptocurrencies')->onDelete('cascade');
            $table->string('symbol'); // BTC/USDT, ETH/USDT
            $table->decimal('min_trade_amount', 20, 8)->default(10);
            $table->decimal('max_trade_amount', 20, 8)->default(1000000);
            $table->decimal('price_precision', 10, 8)->default(0.00000001);
            $table->decimal('quantity_precision', 10, 8)->default(0.00000001);
            $table->decimal('trading_fee', 5, 4)->default(0.001); // 0.1%
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['base_currency_id', 'quote_currency_id']);
            $table->index('symbol');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trading_pairs');
    }
};
