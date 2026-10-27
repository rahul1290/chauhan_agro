<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('template')->default('default'); // default, about, contact, products, custom
            $table->string('featured_image')->nullable();
            $table->boolean('is_published')->default(true);
            $table->integer('order')->default(0);
            $table->boolean('show_in_nav')->default(false);
            $table->timestamps();
        });

        Schema::create('page_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained('pages')->cascadeOnDelete();
            $table->string('locale', 10)->index();
            $table->string('title');
            $table->string('slug')->index();
            $table->string('subtitle')->nullable();
            $table->longText('content')->nullable();
            $table->text('excerpt')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->timestamps();

            $table->unique(['page_id', 'locale']);
            $table->index(['locale', 'slug']);
        });

        Schema::create('page_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained('pages')->cascadeOnDelete();
            $table->string('type', 50); // hero_slider, text_image, feature_cards, stats_counter, category_grid, product_grid, testimonials, cta_banner, faq_accordion, rich_text, contact_info, downloadable_docs
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->json('settings_json')->nullable();
            $table->timestamps();
        });

        Schema::create('section_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')->constrained('page_sections')->cascadeOnDelete();
            $table->string('locale', 10)->index();
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->longText('content')->nullable();
            $table->string('button_text')->nullable();
            $table->string('button_url')->nullable();
            $table->json('data_json')->nullable();
            $table->timestamps();

            $table->unique(['section_id', 'locale']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('section_translations');
        Schema::dropIfExists('page_sections');
        Schema::dropIfExists('page_translations');
        Schema::dropIfExists('pages');
    }
};
