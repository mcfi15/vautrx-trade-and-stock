<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cryptocurrencies', function (Blueprint $table) {
            $table->id();
            $table->string('symbol')->unique(); // BTC, ETH, BNB
            $table->string('name'); // Bitcoin, Ethereum
            $table->string('coingecko_id')->unique(); // bitcoin, ethereum
            $table->string('contract_address')->nullable(); // Smart contract address
            $table->string('blockchain')->default('ethereum'); // ethereum, bsc, polygon
            $table->integer('decimals')->default(18);
            $table->string('logo_url')->nullable();
            $table->decimal('current_price', 20, 8)->default(0);
            $table->decimal('price_change_24h', 10, 2)->default(0);
            $table->decimal('market_cap', 30, 2)->default(0);
            $table->decimal('volume_24h', 30, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_tradable')->default(true);
            $table->timestamp('price_updated_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['symbol', 'is_active']);
            $table->index('coingecko_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cryptocurrencies');
    }
};
