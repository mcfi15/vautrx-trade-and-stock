<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cryptocurrencies', function (Blueprint $table) {
            $table->boolean('enable_realtime')->default(false)->after('is_tradable');
            $table->decimal('high_24h', 20, 8)->default(0)->after('current_price');
            $table->decimal('low_24h', 20, 8)->default(0)->after('high_24h');
            $table->string('binance_symbol')->nullable()->after('symbol');
        });
    }

    public function down(): void
    {
        Schema::table('cryptocurrencies', function (Blueprint $table) {
            $table->dropColumn(['enable_realtime', 'high_24h', 'low_24h', 'binance_symbol']);
        });
    }
};
