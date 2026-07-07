<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->string('template', 100)->default('default')->after('slug');
            $table->string('hero_headline')->nullable()->after('template');
            $table->string('hero_subheadline')->nullable()->after('hero_headline');
            $table->string('hero_cta_text')->nullable()->after('hero_subheadline');
            $table->string('hero_cta_url')->nullable()->after('hero_cta_text');
            $table->string('hero_cta_text_2')->nullable()->after('hero_cta_url');
            $table->string('hero_cta_url_2')->nullable()->after('hero_cta_text_2');
            $table->string('hero_image')->nullable()->after('hero_cta_url_2');
            $table->string('hero_video_url')->nullable()->after('hero_image');
            $table->text('hero_features')->nullable()->after('hero_video_url');
            $table->string('hero_bg_color')->nullable()->after('hero_features');
        });
    }

    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn([
                'template', 'hero_headline', 'hero_subheadline',
                'hero_cta_text', 'hero_cta_url', 'hero_cta_text_2', 'hero_cta_url_2',
                'hero_image', 'hero_video_url', 'hero_features', 'hero_bg_color',
            ]);
        });
    }
};
