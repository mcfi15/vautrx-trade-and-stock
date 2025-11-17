<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserStakesTable extends Migration
{
    public function up()
    {
        Schema::create('user_stakes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('stake_plan_id')->constrained()->onDelete('cascade');
            $table->foreignId('cryptocurrency_id')->constrained()->onDelete('cascade');

            $table->decimal('amount', 24, 8);

            $table->integer('duration'); // 30, 60, 90 days
            $table->decimal('yield_percent', 10, 2);

            $table->enum('status', ['pending', 'approved', 'completed', 'rejected'])
                  ->default('pending');

            $table->timestamp('started_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            $table->text('rejection_reason')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_stakes');
    }
}
