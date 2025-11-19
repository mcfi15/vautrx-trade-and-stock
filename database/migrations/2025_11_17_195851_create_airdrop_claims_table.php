<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('airdrop_claims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('airdrop_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->decimal('claim_amount', 30, 8);
            $table->enum('status', ['pending','approved','rejected','cancelled'])->default('pending');
            $table->text('admin_reason')->nullable();
            $table->timestamp('claimed_at')->nullable();
            $table->timestamps();
            $table->unique(['airdrop_id','user_id']); // prevent duplicate claims
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('airdrop_claims');
    }
};
