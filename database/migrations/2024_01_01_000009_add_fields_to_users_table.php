<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_admin')->default(false)->after('email');
            $table->boolean('is_active')->default(true)->after('is_admin');
            $table->boolean('kyc_verified')->default(false)->after('is_active');
            $table->boolean('two_factor_enabled')->default(false)->after('kyc_verified');
            $table->string('two_factor_secret')->nullable()->after('two_factor_enabled');
            $table->timestamp('last_login_at')->nullable()->after('two_factor_secret');
            $table->string('last_login_ip')->nullable()->after('last_login_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'is_admin',
                'is_active',
                'kyc_verified',
                'two_factor_enabled',
                'two_factor_secret',
                'last_login_at',
                'last_login_ip'
            ]);
        });
    }
};
