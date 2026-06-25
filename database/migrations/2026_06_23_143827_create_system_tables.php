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
        Schema::create('ai_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('provider'); // openai, gemini, claude, deepseek, etc.
            $table->string('model');
            $table->string('endpoint')->nullable();
            $table->integer('prompt_tokens')->default(0);
            $table->integer('completion_tokens')->default(0);
            $table->integer('total_tokens')->default(0);
            $table->integer('cost_micros')->default(0); // Cost in micro dollars
            $table->string('status')->default('success'); // success, failed, retry
            $table->text('error_message')->nullable();
            $table->nullableMorphs('loggable');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'provider', 'created_at']);
            $table->index(['tenant_id', 'status']);
            $table->index('created_at');
        });

        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('invoice_number')->unique();
            $table->enum('status', [
                'draft', 'pending', 'paid',
                'overdue', 'cancelled', 'refunded',
            ]);
            $table->date('invoice_date');
            $table->date('due_date');
            $table->date('paid_at')->nullable();
            $table->string('payment_method')->nullable();
            $table->text('payment_proof')->nullable();
            $table->text('notes')->nullable();
            $table->decimal('subtotal', 10, 2);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->decimal('ai_usage_cost', 10, 2)->default(0);
            $table->timestamps();

            $table->index(['tenant_id', 'status']);
            $table->index(['tenant_id', 'invoice_date']);
        });

        Schema::create('notification_channels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->enum('channel_type', [
                'email', 'telegram', 'whatsapp', 'slack',
            ]);
            $table->string('label');
            $table->text('config_json');
            $table->boolean('is_active')->default(true);
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            $table->unique(['tenant_id', 'channel_type', 'label']);
            $table->index(['tenant_id', 'is_active']);
        });

        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->nullableMorphs('causer');
            $table->string('action'); // created, updated, deleted, published, etc.
            $table->nullableMorphs('subject');
            $table->text('description')->nullable();
            $table->json('changes')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'created_at']);
            $table->index(['tenant_id', 'action']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
        Schema::dropIfExists('notification_channels');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('ai_logs');
    }
};