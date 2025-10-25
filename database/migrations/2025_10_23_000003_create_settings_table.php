<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string'); // string, boolean, integer, json
            $table->string('group')->default('general'); // general, oauth, payment, etc.
            $table->text('description')->nullable();
            $table->boolean('is_public')->default(false); // Can be accessed by non-admin users
            $table->timestamps();
        });

        // Insert default Google OAuth settings
        DB::table('settings')->insert([
            [
                'key' => 'google_oauth_enabled',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'oauth',
                'description' => 'Enable/Disable Google OAuth authentication',
                'is_public' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'google_client_id',
                'value' => '',
                'type' => 'string',
                'group' => 'oauth',
                'description' => 'Google OAuth Client ID',
                'is_public' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'google_client_secret',
                'value' => '',
                'type' => 'string',
                'group' => 'oauth',
                'description' => 'Google OAuth Client Secret',
                'is_public' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'google_redirect_uri',
                'value' => '',
                'type' => 'string',
                'group' => 'oauth',
                'description' => 'Google OAuth Redirect URI',
                'is_public' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('settings');
    }
};