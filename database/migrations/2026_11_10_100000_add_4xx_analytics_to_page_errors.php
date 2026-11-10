<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('page_errors', function (Blueprint $table): void {
            $table->unsignedSmallInteger('status_code')->default(404)->after('url_hash');
            $table->string('last_ip_address', 45)->nullable()->after('referer');
            $table->string('category', 32)->default('person')->after('last_ip_address');
            $table->string('last_user_agent', 512)->nullable()->after('category');
            $table->index(['status_code', 'category']);
            $table->index('last_ip_address');
        });

        Schema::create('page_error_visits', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('page_error_id')->constrained('page_errors')->cascadeOnDelete();
            $table->string('ip_address', 45);
            $table->string('path', 2048);
            $table->unsignedSmallInteger('status_code');
            $table->string('category', 32);
            $table->string('user_agent', 512)->nullable();
            $table->string('referer', 2048)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->index(['ip_address', 'created_at']);
            $table->index(['category', 'created_at']);
            $table->index(['status_code', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_error_visits');
        Schema::table('page_errors', function (Blueprint $table): void {
            $table->dropIndex(['status_code', 'category']);
            $table->dropIndex(['last_ip_address']);
            $table->dropColumn(['status_code', 'last_ip_address', 'category', 'last_user_agent']);
        });
    }
};
