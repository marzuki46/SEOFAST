<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('content_campaigns', function (Blueprint $table): void {
            $table->text('keyword_prompt')->nullable()->after('entity_seed');
            $table->text('entity_prompt')->nullable()->after('keyword_prompt');
            $table->text('landing_prompt')->nullable()->after('entity_prompt');
            $table->text('content_prompt')->nullable()->after('landing_prompt');
            $table->string('brand_voice', 255)->nullable()->after('audience');
            $table->string('niche', 255)->nullable()->after('brand_voice');
            $table->unsignedSmallInteger('word_count_min')->default(900)->after('content_prompt');
            $table->unsignedSmallInteger('word_count_max')->default(1400)->after('word_count_min');
        });
    }

    public function down(): void
    {
        Schema::table('content_campaigns', function (Blueprint $table): void {
            $table->dropColumn([
                'keyword_prompt', 'entity_prompt', 'landing_prompt', 'content_prompt',
                'brand_voice', 'niche', 'word_count_min', 'word_count_max',
            ]);
        });
    }
};
