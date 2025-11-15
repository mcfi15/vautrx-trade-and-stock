<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            // KYC basic info
            $table->string('kyc_full_name')->nullable();
            $table->string('kyc_document_type')->nullable(); // nationalid, passport, drivers_license, etc.
            $table->string('kyc_document_number')->nullable();

            // Uploaded files
            $table->string('kyc_front')->nullable();
            $table->string('kyc_back')->nullable();
            $table->string('kyc_selfie')->nullable();
            $table->string('kyc_proof')->nullable(); // Utility bill / bank statement

            // KYC status
            $table->enum('kyc_status', ['not_submitted', 'pending', 'approved', 'rejected'])
                ->default('not_submitted');

            $table->text('kyc_rejection_reason')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropColumn([
                'kyc_full_name',
                'kyc_document_type',
                'kyc_document_number',
                'kyc_front',
                'kyc_back',
                'kyc_selfie',
                'kyc_proof',
                'kyc_status',
                'kyc_rejection_reason',
            ]);
        });
    }
};
