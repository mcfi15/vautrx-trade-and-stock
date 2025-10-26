<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('deposits', function (Blueprint $table) {
            $table->string('payment_proof_path')->nullable()->after('transaction_hash');
            $table->string('payment_proof_filename')->nullable()->after('payment_proof_path');
            $table->text('admin_notes')->nullable()->after('required_confirmations');
            $table->timestamp('reviewed_at')->nullable()->after('admin_notes');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->after('reviewed_at');
        });
    }

    public function down(): void
    {
        Schema::table('deposits', function (Blueprint $table) {
            $table->dropConstrainedForeignId('reviewed_by');
            $table->dropColumn(['payment_proof_path', 'payment_proof_filename', 'admin_notes', 'reviewed_at']);
        });
    }
};