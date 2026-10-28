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
        // 1. Clean Section Translations (Testimonials, FAQs, Rich Text)
        DB::statement("UPDATE section_translations SET title = REPLACE(title, 'AgroVision', 'Chauhan Agro') WHERE title LIKE '%AgroVision%'");
        DB::statement("UPDATE section_translations SET subtitle = REPLACE(subtitle, 'AgroVision', 'Chauhan Agro') WHERE subtitle LIKE '%AgroVision%'");
        DB::statement("UPDATE section_translations SET content = REPLACE(content, 'AgroVision', 'Chauhan Agro') WHERE content LIKE '%AgroVision%'");
        DB::statement("UPDATE section_translations SET data_json = REPLACE(data_json, 'AgroVision', 'Chauhan Agro') WHERE data_json LIKE '%AgroVision%'");

        // 2. Clean Banners
        DB::statement("UPDATE banners SET button_url = REPLACE(button_url, 'agrovision-', 'chauhan-') WHERE button_url LIKE '%agrovision-%'");

        // 3. Clean Menu Items
        DB::statement("UPDATE menu_items SET target_url = REPLACE(target_url, 'agrovision-', 'chauhan-') WHERE target_url LIKE '%agrovision-%'");
        DB::statement("UPDATE menu_item_translations SET url = REPLACE(url, 'agrovision-', 'chauhan-') WHERE url LIKE '%agrovision-%'");

        // 4. Clean Product Images
        DB::statement("UPDATE product_images SET caption = REPLACE(caption, 'AgroVision', 'Chauhan Agro') WHERE caption LIKE '%AgroVision%'");

        // 5. Clean Products & Translations
        DB::statement("UPDATE products SET slug = REPLACE(slug, 'agrovision-', 'chauhan-'), brochure_pdf = REPLACE(brochure_pdf, 'agrovision-', 'chauhan-') WHERE slug LIKE '%agrovision-%' OR brochure_pdf LIKE '%agrovision-%'");
        DB::statement("UPDATE product_translations SET name = REPLACE(name, 'AgroVision', 'Chauhan Agro'), slug = REPLACE(slug, 'agrovision-', 'chauhan-') WHERE name LIKE '%AgroVision%' OR slug LIKE '%agrovision-%'");

        // 6. Clean Users email
        DB::statement("UPDATE users SET email = REPLACE(email, '@agrovision.com', '@chauhanagroindustries.com') WHERE email LIKE '%@agrovision.com'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No reverse needed
    }
};
