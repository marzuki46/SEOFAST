<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contents', function (Blueprint $table) {
            // Phase 4 - Image metadata
            $table->string('featured_image_url')->nullable()->after('rendered_html_path');
            $table->string('featured_image_alt')->nullable()->after('featured_image_url');
            $table->string('featured_image_caption')->nullable()->after('featured_image_alt');
            
            // Phase 3 - Ghost publish + crawl priority
            $table->decimal('crawl_priority_score', 5, 2)->default(0)->after('kgr_score');
            $table->boolean('is_ghost_published')->default(false)->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('contents', function (Blueprint $table) {
            $table->dropColumn([
                'featured_image_url',
                'featured_image_alt', 
                'featured_image_caption',
                'crawl_priority_score',
                'is_ghost_published',
            ]);
        });
    }
};
