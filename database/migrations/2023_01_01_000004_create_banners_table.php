<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('page_slug')->default('home');
            $table->string('image_path');
            $table->string('mobile_image_path')->nullable();
            $table->string('button_url')->nullable();
            $table->string('secondary_button_url')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('banner_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('banner_id')->constrained('banners')->cascadeOnDelete();
            $table->string('locale', 10)->index();
            $table->string('title');
            $table->text('subtitle')->nullable();
            $table->string('badge_text')->nullable();
            $table->string('button_text')->nullable();
            $table->string('secondary_button_text')->nullable();
            $table->timestamps();

            $table->unique(['banner_id', 'locale']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('banner_translations');
        Schema::dropIfExists('banners');
    }
};
