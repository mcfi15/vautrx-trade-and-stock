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
        Schema::table('payment_methods', function (Blueprint $table) {
            // 1. Add new columns for Bank support
            $table->string('type')->default('crypto')->after('id'); 
            $table->string('bank_name')->nullable()->after('name');
            $table->string('account_name')->nullable()->after('bank_name');
            $table->string('account_number')->nullable()->after('account_name');
            $table->string('swift_code')->nullable()->after('account_number');

            // 2. Modify existing columns to be nullable
            // This allows Bank methods to exist without needing a crypto_id or address
            $table->unsignedBigInteger('cryptocurrency_id')->nullable()->change();
            $table->string('address')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            // 1. Remove the bank-specific columns
            $table->dropColumn([
                'type',
                'bank_name',
                'account_name',
                'account_number',
                'swift_code'
            ]);

            // 2. Revert columns to NOT NULL (standard state)
            // Warning: Ensure no null values exist in these columns before rolling back
            $table->unsignedBigInteger('cryptocurrency_id')->nullable(false)->change();
            $table->string('address')->nullable(false)->change();
        });
    }
};