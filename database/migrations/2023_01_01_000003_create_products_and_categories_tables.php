<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('product_categories')->nullOnDelete();
            $table->string('slug')->unique();
            $table->string('image')->nullable();
            $table->string('icon')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });

        Schema::create('product_category_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('product_categories')->cascadeOnDelete();
            $table->string('locale', 10)->index();
            $table->string('name');
            $table->string('slug')->index();
            $table->text('description')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->timestamps();

            $table->unique(['category_id', 'locale']);
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('product_categories')->cascadeOnDelete();
            $table->string('sku')->nullable();
            $table->string('slug')->unique();
            $table->string('featured_image')->nullable();
            $table->string('brochure_pdf')->nullable();
            $table->integer('tractor_hp_min')->nullable();
            $table->integer('tractor_hp_max')->nullable();
            $table->string('working_width')->nullable();
            $table->string('weight_approx')->nullable();
            $table->integer('blades_count')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('product_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('locale', 10)->index();
            $table->string('name');
            $table->string('slug')->index();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->json('features_json')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->timestamps();

            $table->unique(['product_id', 'locale']);
        });

        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('image_path');
            $table->string('caption')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('product_specifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('product_specification_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('specification_id')->constrained('product_specifications')->cascadeOnDelete();
            $table->string('locale', 10)->index();
            $table->string('spec_key');
            $table->string('spec_value');
            $table->timestamps();

            $table->unique(['specification_id', 'locale'], 'prod_spec_trans_unique');
        });

        Schema::create('related_products', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('related_id')->constrained('products')->cascadeOnDelete();
            $table->primary(['product_id', 'related_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('related_products');
        Schema::dropIfExists('product_specification_translations');
        Schema::dropIfExists('product_specifications');
        Schema::dropIfExists('product_images');
        Schema::dropIfExists('product_translations');
        Schema::dropIfExists('products');
        Schema::dropIfExists('product_category_translations');
        Schema::dropIfExists('product_categories');
    }
};
