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
        DB::statement('ALTER TABLE contents MODIFY status ENUM("draft", "pending", "ai_processing", "failed_cqi", "failed", "published", "idea", "blueprint") DEFAULT "idea"');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE contents MODIFY status ENUM("draft", "pending", "ai_processing", "failed_cqi", "failed", "published") DEFAULT "pending"');
    }
};
