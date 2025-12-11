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
        Schema::create('seo_settings', function (Blueprint $table) {
            $table->id();
            $table->string('page_type')->index(); // home, products, category, product_detail, about, etc.
            $table->string('page_identifier')->nullable()->index(); // null for general, slug for specific pages
            $table->string('meta_title', 70)->nullable(); // SEO title (Google displays ~60 chars)
            $table->text('meta_description')->nullable(); // SEO description (Google displays ~160 chars)
            $table->text('meta_keywords')->nullable(); // Keywords (comma separated)
            $table->string('og_title', 70)->nullable(); // Open Graph title
            $table->text('og_description')->nullable(); // Open Graph description
            $table->string('og_image')->nullable(); // Open Graph image path
            $table->string('canonical_url')->nullable(); // Canonical URL
            $table->string('robots_meta', 50)->default('index,follow'); // robots meta directive
            $table->json('schema_markup')->nullable(); // JSON-LD structured data
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Unique constraint for page_type + page_identifier combination
            $table->unique(['page_type', 'page_identifier']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seo_settings');
    }
};
