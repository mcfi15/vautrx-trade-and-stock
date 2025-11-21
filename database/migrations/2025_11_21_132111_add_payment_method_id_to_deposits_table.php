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
    Schema::table('deposits', function (Blueprint $table) {
        $table->unsignedBigInteger('payment_method_id')->nullable()->after('id');
        $table->foreign('payment_method_id')->references('id')->on('payment_methods')->onDelete('set null');
    });
}

public function down()
{
    Schema::table('deposits', function (Blueprint $table) {
        $table->dropForeign(['payment_method_id']);
        $table->dropColumn('payment_method_id');
    });
}

};
