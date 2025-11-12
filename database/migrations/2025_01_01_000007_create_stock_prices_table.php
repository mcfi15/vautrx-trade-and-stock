<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('stock_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_id')->constrained()->onDelete('cascade');
            $table->decimal('price', 10, 2);
            $table->bigInteger('volume')->default(0);
            $table->timestamp('timestamp');
            $table->timestamps();
            
            $table->index(['stock_id', 'timestamp']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('stock_prices');
    }
};