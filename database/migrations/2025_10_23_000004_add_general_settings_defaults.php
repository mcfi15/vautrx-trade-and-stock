<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Insert default general settings
        $defaultSettings = [
            [
                'key' => 'site_name',
                'value' => config('app.name', 'Crypto Trading Platform'),
                'type' => 'string',
                'group' => 'general',
                'description' => 'Website name displayed in header and title',
                'is_public' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'site_address',
                'value' => "Enter Address Here",
                'type' => 'string',
                'group' => 'general',
                'description' => 'Website address',
                'is_public' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'site_phone',
                'value' => 'Phone details here',
                'type' => 'string',
                'group' => 'general',
                'description' => 'Phone details here',
                'is_public' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'site_description',
                'value' => 'Professional cryptocurrency trading platform',
                'type' => 'string',
                'group' => 'general',
                'description' => 'Website description for SEO',
                'is_public' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'contact_email',
                'value' => 'admin@example.com',
                'type' => 'string',
                'group' => 'general',
                'description' => 'Contact email address',
                'is_public' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'site_logo',
                'value' => '',
                'type' => 'string',
                'group' => 'general',
                'description' => 'Path to site logo',
                'is_public' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'site_favicon',
                'value' => '',
                'type' => 'string',
                'group' => 'general',
                'description' => 'Path to site favicon',
                'is_public' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'maintenance_mode',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'general',
                'description' => 'Enable/disable maintenance mode',
                'is_public' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($defaultSettings as $setting) {
            DB::table('settings')->updateOrInsert(
                ['key' => $setting['key']],
                $setting
            );
        }
    }

    public function down()
    {
        DB::table('settings')->whereIn('key', [
            'site_name',
            'site_description',
            'site_address',
            'site_phone',
            'contact_email',
            'site_logo',
            'site_favicon',
            'maintenance_mode',
        ])->delete();
    }
};
