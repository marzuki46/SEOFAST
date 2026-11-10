<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('content_campaigns', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->foreignId('silo_blueprint_id')->nullable()->constrained('silo_blueprints')->nullOnDelete();
            $table->string('name');
            $table->string('status', 20)->default('draft');
            $table->string('language', 10)->default('id');
            $table->string('funnel_mode', 20)->default('balanced');
            $table->string('audience', 500)->nullable();
            $table->text('business_data')->nullable();
            $table->string('entity_seed', 500)->nullable();
            $table->string('cta_label', 120)->nullable();
            $table->string('cta_url', 2048)->nullable();
            $table->boolean('auto_research')->default(true);
            $table->unsignedTinyInteger('research_batch_size')->default(5);
            $table->unsignedTinyInteger('posts_per_day')->default(1);
            $table->string('schedule_type', 20)->default('random');
            $table->string('fixed_hours', 255)->nullable();
            $table->string('publish_status', 20)->default('draft');
            $table->string('timezone', 64)->default('Asia/Jakarta');
            $table->timestamp('last_run_at')->nullable();
            $table->timestamp('next_run_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['tenant_id', 'status', 'next_run_at']);
            $table->index(['product_id', 'status']);
        });

        Schema::create('content_topics', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('campaign_id')->constrained('content_campaigns')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->foreignId('silo_blueprint_id')->nullable()->constrained('silo_blueprints')->nullOnDelete();
            $table->foreignId('content_id')->nullable()->constrained('contents')->nullOnDelete();
            $table->string('funnel_stage', 10)->default('tofu');
            $table->string('title');
            $table->string('primary_keyword');
            $table->string('normalized_keyword', 191);
            $table->string('intent', 50)->default('informational');
            $table->text('angle')->nullable();
            $table->json('entity_keywords')->nullable();
            $table->string('status', 20)->default('idea');
            $table->string('source', 40)->default('ai_research');
            $table->decimal('similarity_score', 5, 4)->nullable();
            $table->string('rejection_reason', 255)->nullable();
            $table->timestamps();
            $table->index(['campaign_id', 'status', 'funnel_stage']);
            $table->index(['tenant_id', 'normalized_keyword']);
            $table->unique(['tenant_id', 'normalized_keyword']);
        });

        Schema::create('content_campaign_schedules', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('campaign_id')->constrained('content_campaigns')->cascadeOnDelete();
            $table->foreignId('content_topic_id')->nullable()->constrained('content_topics')->nullOnDelete();
            $table->foreignId('content_id')->nullable()->constrained('contents')->nullOnDelete();
            $table->timestamp('scheduled_at');
            $table->string('status', 20)->default('pending');
            $table->timestamp('locked_at')->nullable();
            $table->unsignedTinyInteger('attempt_count')->default(0);
            $table->text('error_message')->nullable();
            $table->timestamps();
            $table->unique(['campaign_id', 'scheduled_at']);
            $table->index(['status', 'scheduled_at']);
        });

        Schema::table('contents', function (Blueprint $table): void {
            $table->foreignId('campaign_id')->nullable()->after('product_id')->constrained('content_campaigns')->nullOnDelete();
            $table->foreignId('content_topic_id')->nullable()->after('campaign_id')->constrained('content_topics')->nullOnDelete();
            $table->index(['campaign_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::table('contents', function (Blueprint $table): void {
            $table->dropForeign(['content_topic_id']);
            $table->dropForeign(['campaign_id']);
            $table->dropIndex(['campaign_id', 'status']);
            $table->dropColumn(['campaign_id', 'content_topic_id']);
        });
        Schema::dropIfExists('content_campaign_schedules');
        Schema::dropIfExists('content_topics');
        Schema::dropIfExists('content_campaigns');
    }
};
