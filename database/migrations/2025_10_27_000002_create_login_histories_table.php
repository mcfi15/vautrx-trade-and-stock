<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('login_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('ip_address');
            $table->string('user_agent')->nullable();
            $table->string('browser')->nullable();
            $table->string('device')->nullable();
            $table->string('platform')->nullable();
            $table->string('location')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->boolean('success')->default(true);
            $table->text('failure_reason')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('isp')->nullable();
            $table->timestamp('login_at');
            $table->timestamps();
            
            $table->index(['user_id', 'login_at']);
            $table->index('ip_address');
        });
    }

    public function down()
    {
        Schema::dropIfExists('login_histories');
    }
};