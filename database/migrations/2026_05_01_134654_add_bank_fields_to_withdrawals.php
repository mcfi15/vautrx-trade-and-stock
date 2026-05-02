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
        Schema::table('withdrawals', function (Blueprint $table) {
            // Determines if the withdrawal is via 'crypto' or 'bank'
            $table->string('withdrawal_type')->default('crypto')->after('id');
            
            // Bank-specific details (nullable because they aren't used for crypto)
            $table->string('bank_name')->nullable()->after('withdrawal_type');
            $table->string('account_name')->nullable()->after('bank_name');
            $table->string('account_number')->nullable()->after('account_name');
            $table->string('swift_code')->nullable()->after('account_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('withdrawals', function (Blueprint $table) {
            $table->dropColumn([
                'withdrawal_type',
                'bank_name',
                'account_name',
                'account_number',
                'swift_code'
            ]);
        });
    }
};