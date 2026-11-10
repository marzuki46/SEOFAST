<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('content_campaign_targets', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('campaign_id')->constrained('content_campaigns')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->foreignId('page_id')->nullable()->constrained('pages')->nullOnDelete();
            $table->string('keyword');
            $table->string('normalized_keyword', 191);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->string('status', 20)->default('pending');
            $table->string('landing_url', 2048)->nullable();
            $table->json('anchor_variations')->nullable();
            $table->json('landing_data')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();
            $table->unique(['campaign_id', 'normalized_keyword']);
            $table->index(['campaign_id', 'status', 'sort_order']);
        });

        Schema::table('content_topics', function (Blueprint $table): void {
            $table->foreignId('campaign_target_id')->nullable()->after('campaign_id')->constrained('content_campaign_targets')->nullOnDelete();
            $table->index(['campaign_target_id', 'status']);
        });

        Schema::table('contents', function (Blueprint $table): void {
            $table->foreignId('campaign_target_id')->nullable()->after('campaign_id')->constrained('content_campaign_targets')->nullOnDelete();
            $table->index(['campaign_target_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::table('contents', function (Blueprint $table): void {
            $table->dropForeign(['campaign_target_id']);
            $table->dropIndex(['campaign_target_id', 'status']);
            $table->dropColumn('campaign_target_id');
        });
        Schema::table('content_topics', function (Blueprint $table): void {
            $table->dropForeign(['campaign_target_id']);
            $table->dropIndex(['campaign_target_id', 'status']);
            $table->dropColumn('campaign_target_id');
        });
        Schema::dropIfExists('content_campaign_targets');
    }
};
