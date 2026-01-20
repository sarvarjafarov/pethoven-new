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
        Schema::create('compares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('session_id')->nullable();
            $table->unsignedBigInteger('product_id');
            $table->timestamps();

            // Indexes for performance
            $table->index(['user_id', 'product_id']);
            $table->index(['session_id', 'product_id']);

            // Unique constraint - one product per user/session
            $table->unique(['user_id', 'product_id'], 'compare_user_product_unique');
            $table->unique(['session_id', 'product_id'], 'compare_session_product_unique');

            // Foreign key to Lunar products
            $table->foreign('product_id')->references('id')->on('lunar_products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compares');
    }
};
