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
        Schema::create('product_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('visitor_identifier', 64)->index(); // Cookie-based unique identifier
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null'); // For authenticated users
            $table->string('ip_address', 45)->nullable(); // IPv4 or IPv6
            $table->timestamp('viewed_at')->useCurrent();
            $table->timestamps();

            // Composite index for fast lookups
            $table->index(['product_id', 'visitor_identifier', 'viewed_at']);
            $table->index(['viewed_at']); // For date range queries
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_views');
    }
};
