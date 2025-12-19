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
        Schema::create('site_visits', function (Blueprint $table) {
            $table->id();
            $table->string('visitor_identifier', 64)->index(); // Cookie-based unique identifier
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null'); // For authenticated users
            $table->string('page_url', 500)->nullable();
            $table->string('referer', 500)->nullable(); // Where the visitor came from
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 500)->nullable(); // Browser info
            $table->timestamp('visited_at')->useCurrent();
            $table->timestamps();

            // Indexes for fast queries
            $table->index(['visitor_identifier', 'visited_at']);
            $table->index(['visited_at']); // For date range queries
            $table->index(['page_url']); // For page-specific analytics
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_visits');
    }
};
