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
        Schema::create('silo_blueprints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('silo_name');
            $table->string('seed_keyword');
            $table->string('target_language', 10)->default('id');
            $table->string('target_country', 10)->default('ID');
            $table->json('visual_graph_data')->nullable();
            $table->boolean('is_locked')->default(false);
            $table->integer('total_contents')->default(0);
            $table->integer('published_contents')->default(0);
            $table->timestamps();

            $table->index(['tenant_id', 'is_locked']);
        });

        Schema::create('contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('silo_blueprint_id')->constrained()->cascadeOnDelete();

            // SEO Identity
            $table->string('target_keyword');
            $table->string('slug');
            $table->string('meta_title', 70)->nullable();
            $table->string('meta_description', 160)->nullable();
            $table->enum('hierarchy_level', ['pillar', 'cluster', 'sub_cluster']);

            // KGR & Quality Metrics
            $table->integer('search_volume')->default(0);
            $table->decimal('kgr_score', 5, 2)->nullable();
            $table->decimal('cqi_score', 5, 2)->nullable();
            $table->decimal('semantic_depth_score', 5, 2)->nullable();
            $table->decimal('entity_coverage_score', 5, 2)->nullable();
            $table->decimal('readability_score', 5, 2)->nullable();

            // Vector Embedding
            $table->string('vector_embedding_id', 100)->nullable();
            $table->string('embedding_model_version', 50)->nullable();
            $table->timestamp('embedding_generated_at')->nullable();

            // Content Body
            $table->longText('body_raw')->nullable();
            $table->string('rendered_html_path')->nullable();
            $table->string('content_hash', 64)->nullable();
            $table->boolean('last_render_intact')->default(true);

            // Lifecycle
            $table->enum('status', [
                'blueprint',
                'ai_processing',
                'failed_cqi',
                'canonicalized',
                'published',
                'needs_reoptimize',
            ]);
            $table->timestamp('published_at')->nullable();
            $table->timestamp('last_partial_update_at')->nullable();

            // Indexation & Ranking (denormalized)
            $table->string('gsc_coverage_state', 50)->nullable();
            $table->integer('current_serp_position')->nullable();
            $table->timestamp('ranking_last_checked_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tenant_id', 'slug']);
            $table->index(['tenant_id', 'status']);
            $table->index(['tenant_id', 'silo_blueprint_id', 'hierarchy_level']);
            $table->index(['tenant_id', 'published_at']);
            $table->index(['tenant_id', 'current_serp_position']);
            $table->index('gsc_coverage_state');
        });

        Schema::create('deterministic_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('source_content_id')->constrained('contents')->cascadeOnDelete();
            $table->foreignId('target_content_id')->constrained('contents')->cascadeOnDelete();
            $table->string('mandatory_anchor_text');
            $table->boolean('is_injected_successfully')->default(false);
            $table->timestamp('injected_at')->nullable();
            $table->timestamps();

            $table->unique(
                ['source_content_id', 'target_content_id', 'mandatory_anchor_text'],
                'unique_link_mapping'
            );
            $table->index('source_content_id');
            $table->index(['target_content_id', 'is_injected_successfully'], 'dt_links_target_injected_idx');
        });

        Schema::create('schema_markups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('content_id')->constrained()->cascadeOnDelete();
            $table->enum('schema_type', [
                'Article', 'FAQPage', 'HowTo', 'BreadcrumbList',
                'Product', 'LocalBusiness', 'WebPage', 'ItemList',
            ]);
            $table->json('schema_payload');
            $table->boolean('is_validated')->default(false);
            $table->timestamp('validated_at')->nullable();
            $table->string('validation_issues')->nullable();
            $table->timestamps();

            $table->index(['content_id', 'schema_type']);
        });

        Schema::create('canonical_mappings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('content_id')->constrained()->cascadeOnDelete();
            $table->foreignId('canonical_target_id')->constrained('contents')->cascadeOnDelete();
            $table->enum('reason', [
                'duplicate_intent',
                'merge_pending',
                'manual_override',
                'pagination',
            ]);
            $table->decimal('similarity_score', 5, 4)->nullable();
            $table->boolean('is_resolved')->default(false);
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'is_resolved']);
            $table->index('content_id');
            $table->index('canonical_target_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('canonical_mappings');
        Schema::dropIfExists('schema_markups');
        Schema::dropIfExists('deterministic_links');
        Schema::dropIfExists('contents');
        Schema::dropIfExists('silo_blueprints');
    }
};