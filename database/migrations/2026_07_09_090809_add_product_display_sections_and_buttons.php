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
        Schema::table('products', function (Blueprint $table) {
            $table->json('display_sections')->nullable()->after('is_active');
            $table->json('gallery_images')->nullable()->after('image_url');
            $table->json('specifications')->nullable()->after('features');
            $table->json('faq')->nullable()->after('specifications');
            $table->boolean('enable_buy_button')->default(true)->after('faq');
            $table->boolean('enable_inquiry_button')->default(false)->after('enable_buy_button');
            $table->string('inquiry_label')->nullable()->after('enable_inquiry_button');
            $table->string('inquiry_url')->nullable()->after('inquiry_label');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'display_sections', 'gallery_images', 'specifications', 'faq',
                'enable_buy_button', 'enable_inquiry_button', 'inquiry_label', 'inquiry_url',
            ]);
        });
    }
};
