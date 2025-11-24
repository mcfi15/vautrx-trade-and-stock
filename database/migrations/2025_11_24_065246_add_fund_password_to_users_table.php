<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('users', function (Blueprint $table) {
        $table->string('fund_password')->nullable();
        $table->string('fund_password_otp')->nullable();
        $table->timestamp('fund_password_otp_expires_at')->nullable();
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn([
            'fund_password',
            'fund_password_otp',
            'fund_password_otp_expires_at'
        ]);
    });
}

};
