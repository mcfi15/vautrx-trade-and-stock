<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('deposits', function (Blueprint $table) {

            // Add only if they don't exist already
            if (!Schema::hasColumn('deposits', 'reviewed_by_admin')) {
                $table->unsignedBigInteger('reviewed_by_admin')->nullable()->after('status');
            }

           
        });
    }

    public function down()
    {
        Schema::table('deposits', function (Blueprint $table) {
            $table->dropColumn([
                'reviewed_by_admin'
            ]);
        });
    }
};
