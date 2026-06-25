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
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('domain')->unique();
            $table->string('subscription_plan')->default('free'); // free | starter | pro | enterprise
            $table->integer('ai_credit_balance')->default(0);
            $table->integer('monthly_url_quota')->default(100);
            $table->integer('monthly_url_used')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('tenant_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('key');
            $table->longText('value')->nullable();
            $table->unique(['tenant_id', 'key']);
            $table->timestamps();
        });

        Schema::create('tenant_api_credentials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->enum('service', [
                'google_search_console',
                'google_indexing_api',
                'serp_tracker',
                'llm_provider',
                'cloudflare',
            ]);
            $table->text('access_token')->nullable();
            $table->text('refresh_token')->nullable();
            $table->text('service_account_json')->nullable();
            $table->string('property_url')->nullable();
            $table->timestamp('token_expires_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['tenant_id', 'service']);
            $table->index(['tenant_id', 'service', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenant_api_credentials');
        Schema::dropIfExists('tenant_settings');
        Schema::dropIfExists('tenants');
    }
};