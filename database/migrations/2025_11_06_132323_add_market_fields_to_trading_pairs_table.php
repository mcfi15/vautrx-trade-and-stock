<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('trading_pairs', function (Blueprint $table) {
            $table->decimal('last_price', 30, 12)->nullable()->after('symbol');
            $table->decimal('price_change_percent', 10, 4)->nullable()->after('last_price');
            $table->decimal('high_24h', 30, 12)->nullable()->after('price_change_percent');
            $table->decimal('low_24h', 30, 12)->nullable()->after('high_24h');
            $table->decimal('volume_24h', 30, 12)->nullable()->after('low_24h');
        });
    }

    public function down(): void
    {
        Schema::table('trading_pairs', function (Blueprint $table) {
            $table->dropColumn(['last_price', 'price_change_percent', 'high_24h', 'low_24h', 'volume_24h']);
        });
    }
};
