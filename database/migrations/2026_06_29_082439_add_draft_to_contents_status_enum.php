<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add 'draft' to the enum for 'status'
        DB::statement("ALTER TABLE contents MODIFY COLUMN status ENUM('blueprint', 'ai_processing', 'failed_cqi', 'canonicalized', 'published', 'needs_reoptimize', 'draft') DEFAULT 'blueprint'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back
        DB::statement("ALTER TABLE contents MODIFY COLUMN status ENUM('blueprint', 'ai_processing', 'failed_cqi', 'canonicalized', 'published', 'needs_reoptimize') DEFAULT 'blueprint'");
    }
};
