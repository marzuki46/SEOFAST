<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Buyers table (terpisah dari users)
        Schema::create('buyers', function (Blueprint $table) {
            $table->id();
            $table->string('google_id')->nullable()->unique();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('avatar')->nullable();
            $table->string('password')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            $table->index('email');
            $table->index('google_id');
        });

        // Buyer orders
        Schema::create('buyer_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('buyer_id')->constrained('buyers')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('order_number', 20)->unique();
            $table->string('unique_code', 6);
            $table->decimal('amount', 12, 2);
            $table->decimal('unique_amount', 12, 2);
            $table->enum('status', ['pending', 'paid', 'verified', 'rejected', 'refunded'])->default('pending');
            $table->string('payment_proof')->nullable();
            $table->string('payment_method')->nullable();
            $table->text('admin_note')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['buyer_id', 'status']);
            $table->index(['product_id', 'status']);
        });

        // Buyer product accesses
        Schema::create('buyer_product_accesses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('buyer_id')->constrained('buyers')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('order_id')->constrained('buyer_orders')->cascadeOnDelete();
            $table->timestamp('granted_at');
            $table->timestamp('expires_at')->nullable();
            $table->integer('access_count')->default(0);
            $table->timestamp('last_accessed_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['buyer_id', 'product_id']);
            $table->index(['buyer_id', 'is_active']);
        });

        // System settings (global, super admin only)
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->longText('value')->nullable();
            $table->string('group')->default('general'); // general|auth|ai|payment|seo|email|storage|queue|integrations
            $table->string('type')->default('string');   // string|boolean|integer|json|encrypted
            $table->string('label')->nullable();
            $table->timestamps();

            $table->index('group');
        });

        // Add google_id to users table for admin OAuth
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'google_id')) {
                $table->string('google_id')->nullable()->unique()->after('email');
            }
            if (!Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable()->after('google_id');
            }
        });

        // Add client management fields to tenants
        Schema::table('tenants', function (Blueprint $table) {
            if (!Schema::hasColumn('tenants', 'contact_email')) {
                $table->string('contact_email')->nullable()->after('domain');
                $table->string('contact_phone')->nullable()->after('contact_email');
                $table->string('company_name')->nullable()->after('contact_phone');
                $table->text('notes')->nullable()->after('company_name');
                $table->timestamp('suspended_at')->nullable()->after('is_active');
                $table->string('suspended_reason')->nullable()->after('suspended_at');
                $table->timestamp('contract_start_at')->nullable();
                $table->timestamp('contract_end_at')->nullable();
                $table->decimal('monthly_rate', 12, 2)->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('buyer_product_accesses');
        Schema::dropIfExists('buyer_orders');
        Schema::dropIfExists('buyers');
        Schema::dropIfExists('system_settings');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['google_id', 'avatar']);
        });
    }
};
