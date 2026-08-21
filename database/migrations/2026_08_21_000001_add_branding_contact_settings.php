<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        $defaultSettings = [
            [
                'key' => 'support_email',
                'value' => 'support@vautrx.com',
                'type' => 'string',
                'group' => 'general',
                'description' => 'Support email displayed in the website footer',
                'is_public' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'info_email',
                'value' => 'info@vautrx.com',
                'type' => 'string',
                'group' => 'general',
                'description' => 'Info email displayed in the website footer',
                'is_public' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'mail_from_address',
                'value' => env('MAIL_FROM_ADDRESS', 'hello@example.com'),
                'type' => 'string',
                'group' => 'general',
                'description' => 'Email address used as sender for outgoing emails',
                'is_public' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'admin_email',
                'value' => env('MAIL_ADMIN_EMAIL', ''),
                'type' => 'string',
                'group' => 'general',
                'description' => 'Admin notification email address',
                'is_public' => false,
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
            'support_email',
            'info_email',
            'mail_from_address',
            'admin_email',
        ])->delete();
    }
};
