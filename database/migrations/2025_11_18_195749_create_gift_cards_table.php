<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGiftCardsTable extends Migration
{
    public function up()
    {
        Schema::create('gift_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('cryptocurrency_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->decimal('amount', 18, 8);
            $table->string('public_code')->unique();
            $table->string('secret_code')->unique();
            $table->text('message')->nullable();
            $table->string('status')->default('active'); // active, redeemed, expired
            $table->foreignId('redeemed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->datetime('redeemed_at')->nullable();
            $table->datetime('expires_at')->nullable();
            $table->timestamps();
            
            $table->index('public_code');
            $table->index('secret_code');
            $table->index('status');
        });

        Schema::create('gift_card_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gift_card_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type'); // creation, redemption
            $table->decimal('amount', 18, 8);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('gift_card_transactions');
        Schema::dropIfExists('gift_cards');
    }
}