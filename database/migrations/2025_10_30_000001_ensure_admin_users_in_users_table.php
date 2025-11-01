<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // This migration ensures admin users also exist in the users table
        // to satisfy foreign key constraints for reviewed_by field
        
        // Get all admin users
        $admins = DB::table('admins')->get();
        
        foreach ($admins as $admin) {
            // Check if admin user exists in users table
            $userExists = DB::table('users')->where('id', $admin->id)->exists();
            
            if (!$userExists) {
                // Create corresponding user record for admin
                DB::table('users')->insert([
                    'id' => $admin->id,
                    'name' => $admin->name,
                    'email' => $admin->email,
                    'email_verified_at' => $admin->email_verified_at,
                    'password' => $admin->password,
                    'is_active' => $admin->is_active,
                    'role' => 'user', // Regular user role
                    'created_at' => $admin->created_at,
                    'updated_at' => $admin->updated_at,
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Note: We don't delete user records for admins in down()
        // as this could cause data loss
    }
};
