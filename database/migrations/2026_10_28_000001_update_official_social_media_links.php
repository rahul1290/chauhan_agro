<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('settings')->updateOrInsert(
            ['key' => 'social_facebook'],
            ['value' => 'https://www.facebook.com/share/1GpUiRYvWb/', 'group' => 'general', 'updated_at' => now()]
        );

        DB::table('settings')->updateOrInsert(
            ['key' => 'social_youtube'],
            ['value' => 'https://youtube.com/@chauhangroupindia?si=UnQCzy4DrxsMjZdc', 'group' => 'general', 'updated_at' => now()]
        );

        DB::table('settings')->updateOrInsert(
            ['key' => 'social_instagram'],
            ['value' => 'https://www.instagram.com/chauhangroup_india?utm_source=qr&exln=dnZxdXdkYjc1Z2Vs', 'group' => 'general', 'updated_at' => now()]
        );

        DB::table('settings')->whereIn('key', ['social_linkedin', 'social_twitter'])->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No reverse needed
    }
};
