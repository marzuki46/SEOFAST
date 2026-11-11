<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contents', function (Blueprint $table) {
            if (! Schema::hasColumn('contents', 'revised_at')) {
                $table->timestamp('revised_at')->nullable()->after('published_at');
            }
            if (! Schema::hasColumn('contents', 'editorial_review_count')) {
                $table->unsignedSmallInteger('editorial_review_count')->default(0)->after('revised_at');
            }
            if (! Schema::hasColumn('contents', 'last_editorial_notes')) {
                $table->text('last_editorial_notes')->nullable()->after('editorial_review_count');
            }
        });
    }

    public function down(): void
    {
        Schema::table('contents', function (Blueprint $table) {
            $table->dropColumn(['revised_at', 'editorial_review_count', 'last_editorial_notes']);
        });
    }
};
