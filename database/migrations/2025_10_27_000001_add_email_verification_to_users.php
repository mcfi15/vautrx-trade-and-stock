<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Email verification fields
            // $table->timestamp('email_verified_at')->nullable()->after('email');
            $table->string('email_verification_token')->nullable()->after('email_verified_at');
            $table->timestamp('email_verification_token_expires_at')->nullable()->after('email_verification_token');
            
            // Update last_login_at column if it doesn't have proper default
            $table->timestamp('last_login_at')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['email_verification_token', 'email_verification_token_expires_at']);
        });
    }
};