<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Dedicated Centre Landing Pages
        if (!Schema::hasTable('centre_landing_pages')) {
            Schema::create('centre_landing_pages', function (Blueprint $table) {
                $table->id();
                $table->foreignId('centre_id')->constrained('centres')->onDelete('cascade');
                $table->string('slug', 150)->unique(); // Unique landing page URL slug e.g. /centre/patna-tech-era
                $table->string('tagline')->nullable();
                $table->string('theme_color', 20)->default('#0d2238');   // Primary branding color
                $table->string('accent_color', 20)->default('#8f9f1b');  // Secondary/Accent color
                $table->string('custom_logo')->nullable();               // Custom logo if different from centre_logo
                // About & Director Block
                $table->string('about_heading')->nullable();
                $table->longText('about_content')->nullable();
                $table->string('owner_name', 191)->nullable();
                $table->string('owner_title', 191)->nullable(); // e.g. Managing Director & Certified Educator
                $table->text('owner_message')->nullable();
                $table->string('owner_photo')->nullable();
                // Facilities & Features
                $table->json('facilities')->nullable(); // e.g. ["High-Speed Wi-Fi", "1:1 PC Ratio", "AC Lab", "Doubt Classes"]
                // Contact & Location
                $table->string('custom_phone', 50)->nullable();
                $table->string('custom_whatsapp', 50)->nullable();
                $table->string('custom_email', 191)->nullable();
                $table->text('address_display')->nullable();
                $table->text('google_map_embed')->nullable(); // Google Map Embed Iframe or URL
                // Social Media
                $table->json('social_links')->nullable(); // { facebook: "", instagram: "", youtube: "", twitter: "", telegram: "" }
                // Custom Sections
                $table->json('custom_sections')->nullable(); // Additional cards/FAQ blocks
                $table->boolean('is_published')->default(true);
                $table->timestamps();

                $table->index('slug');
            });
        }

        // 2. Centre Landing Page Sliders / Banners
        if (!Schema::hasTable('centre_banners')) {
            Schema::create('centre_banners', function (Blueprint $table) {
                $table->id();
                $table->foreignId('centre_id')->constrained('centres')->onDelete('cascade');
                $table->string('image_path');
                $table->string('title')->nullable();
                $table->string('subtitle')->nullable();
                $table->string('button_text')->nullable();
                $table->string('button_link')->nullable();
                $table->integer('sort_order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->index(['centre_id', 'sort_order']);
            });
        }

        // 3. Centre Landing Page Photo Gallery
        if (!Schema::hasTable('centre_galleries')) {
            Schema::create('centre_galleries', function (Blueprint $table) {
                $table->id();
                $table->foreignId('centre_id')->constrained('centres')->onDelete('cascade');
                $table->string('image_path');
                $table->string('caption')->nullable();
                $table->string('category', 100)->default('Campus'); // Lab, Campus, Events, Certificates
                $table->integer('sort_order')->default(0);
                $table->timestamps();

                $table->index(['centre_id', 'sort_order']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('centre_galleries');
        Schema::dropIfExists('centre_banners');
        Schema::dropIfExists('centre_landing_pages');
    }
};
