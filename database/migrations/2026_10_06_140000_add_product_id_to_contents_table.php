<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contents', function (Blueprint $table): void {
            $table->foreignId('product_id')
                ->nullable()
                ->after('tenant_id')
                ->constrained('products')
                ->nullOnDelete();
            $table->index(['product_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::table('contents', function (Blueprint $table): void {
            $table->dropForeign(['product_id']);
            $table->dropIndex(['product_id', 'status']);
            $table->dropColumn('product_id');
        });
    }
};
