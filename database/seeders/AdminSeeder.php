<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Super Admin
        Admin::create([
            'name' => 'Super Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
            'is_active' => true,
            'email_verified_at' => now(),
            'permissions' => [
                'manage_users',
                'manage_cryptocurrencies',
                'manage_orders',
                'manage_transactions',
                'manage_settings',
                'manage_admins',
                'view_reports',
                'approve_withdrawals',
            ],
        ]);

        // Create Regular Admin
        Admin::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
            'permissions' => [
                'manage_cryptocurrencies',
                'manage_orders',
                'manage_transactions',
                'view_reports',
            ],
        ]);

        // Create Moderator
        Admin::create([
            'name' => 'Moderator',
            'email' => 'moderator@example.com',
            'password' => Hash::make('password'),
            'role' => 'moderator',
            'is_active' => true,
            'email_verified_at' => now(),
            'permissions' => [
                'manage_orders',
                'view_reports',
            ],
        ]);
    }
}
