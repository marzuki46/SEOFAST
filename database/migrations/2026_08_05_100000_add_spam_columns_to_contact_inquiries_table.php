<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contact_inquiries', function (Blueprint $table) {
            if (!Schema::hasColumn('contact_inquiries', 'source')) {
                $table->string('source', 50)->nullable()->after('subject');
            }
            if (!Schema::hasColumn('contact_inquiries', 'ip_address')) {
                $table->string('ip_address', 64)->nullable()->after('source');
            }
            if (!Schema::hasColumn('contact_inquiries', 'url')) {
                $table->string('url', 2048)->nullable()->after('ip_address');
            }
            if (!Schema::hasColumn('contact_inquiries', 'is_spam')) {
                $table->boolean('is_spam')->default(false)->after('status');
            }
            if (!Schema::hasIndex('contact_inquiries', 'contact_inquiries_is_spam_created_at_index')) {
                $table->index(['is_spam', 'created_at']);
            }
        });
    }

    public function down(): void
    {
        Schema::table('contact_inquiries', function (Blueprint $table) {
            $table->dropIndex(['is_spam', 'created_at']);
            $table->dropColumn(['source', 'ip_address', 'url', 'is_spam']);
        });
    }
};
