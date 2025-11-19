<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMiningPoolsTable extends Migration
{
    public function up()
    {
        Schema::create('mining_pools', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('symbol');
            $table->decimal('price', 18, 8);
            $table->integer('total');
            $table->integer('available');
            $table->decimal('daily_reward', 18, 8);
            $table->integer('duration_days');
            $table->integer('user_limit')->default(0);
            $table->string('power');
            $table->string('logo_url')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('user_mining_machines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('mining_pool_id')->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('total_cost', 18, 8);
            $table->decimal('daily_reward', 18, 8);
            $table->datetime('start_date');
            $table->datetime('end_date');
            $table->boolean('is_active')->default(true);
            $table->string('status')->default('active'); // active, completed, cancelled
            $table->timestamps();
        });

        Schema::create('mining_rewards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('mining_pool_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_mining_machine_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 18, 8);
            $table->date('reward_date');
            $table->boolean('is_paid')->default(false);
            $table->datetime('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mining_rewards');
        Schema::dropIfExists('user_mining_machines');
        Schema::dropIfExists('mining_pools');
    }
}