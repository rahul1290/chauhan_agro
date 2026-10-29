<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('promotional_popups', function (Blueprint $table) {
            $table->id();
            $table->string('title')->default('Chauhan Agro Machinery Exhibition & Showcase');
            $table->string('image_path');
            $table->string('link_url')->nullable();
            $table->boolean('target_blank')->default(false);
            $table->string('border_color')->default('#16a34a');
            $table->string('display_frequency')->default('session'); // session, always, daily
            $table->unsignedInteger('display_delay')->default(1); // seconds
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotional_popups');
    }
};
