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
        // Add transaction_id column to deposits table
        Schema::table('deposits', function (Blueprint $table) {
            $table->foreignId('transaction_id')->nullable()->constrained('transactions')->onDelete('set null')->after('cryptocurrency_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deposits', function (Blueprint $table) {
            $table->dropConstrainedForeignId('transaction_id');
        });
    }
};
