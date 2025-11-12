<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->string('symbol')->unique();
            $table->string('name');
            $table->decimal('current_price', 10, 2)->default(0);
            $table->decimal('opening_price', 10, 2)->nullable();
            $table->decimal('closing_price', 10, 2)->nullable();
            $table->decimal('high_price', 10, 2)->nullable();
            $table->decimal('low_price', 10, 2)->nullable();
            $table->bigInteger('volume')->default(0);
            $table->decimal('market_cap', 20, 2)->nullable();
            $table->string('sector')->nullable();
             $table->string('industry')->nullable();
            $table->text('description')->nullable();
            $table->string('website')->nullable();
            $table->string('data_source')->nullable();
            $table->string('country')->nullable();
            $table->string('exchange')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_updated')->nullable();
            $table->timestamps();
            
            $table->index(['symbol', 'is_active']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('stocks');
    }
};