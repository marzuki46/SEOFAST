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
        Schema::table('contents', function (Blueprint $table) {
            $table->text('slug')->change();
            $table->text('meta_title')->nullable()->change();
            $table->text('meta_description')->nullable()->change();
            $table->longText('body_raw')->nullable()->change();
            $table->text('featured_image_alt')->nullable()->change();
            $table->text('featured_image_caption')->nullable()->change();
        });

        Schema::table('seo_metas', function (Blueprint $table) {
            $table->text('title')->nullable()->change();
            $table->text('description')->nullable()->change();
            $table->text('og_title')->nullable()->change();
            $table->text('og_description')->nullable()->change();
        });

        // For existing databases, we need to manually update columns to JSON format
        // and wrap existing values in {"id": "value"}
        
        $tables = [
            'contents' => [
                'slug', 'meta_title', 'meta_description', 
                'body_raw', 'featured_image_alt', 'featured_image_caption'
            ],
            'seo_metas' => [
                'title', 'description', 'og_title', 'og_description'
            ]
        ];

        foreach ($tables as $tableName => $columns) {
            foreach ($columns as $column) {
                // Read existing records
                $records = \Illuminate\Support\Facades\DB::table($tableName)->get(['id', $column]);
                foreach ($records as $record) {
                    if (!empty($record->$column) && !is_array(json_decode($record->$column, true))) {
                        // Wrap in JSON
                        $jsonValue = json_encode(['id' => $record->$column], JSON_UNESCAPED_UNICODE);
                        \Illuminate\Support\Facades\DB::table($tableName)
                            ->where('id', $record->id)
                            ->update([$column => $jsonValue]);
                    }
                }
            }
        }

        // Change to JSON now that the data is valid
        Schema::table('contents', function (Blueprint $table) {
            $table->json('slug')->change();
            $table->json('meta_title')->nullable()->change();
            $table->json('meta_description')->nullable()->change();
            $table->json('body_raw')->nullable()->change();
            $table->json('featured_image_alt')->nullable()->change();
            $table->json('featured_image_caption')->nullable()->change();
        });

        Schema::table('seo_metas', function (Blueprint $table) {
            $table->json('title')->nullable()->change();
            $table->json('description')->nullable()->change();
            $table->json('og_title')->nullable()->change();
            $table->json('og_description')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reversing would extract the 'id' key from JSON back to plain text
        $tables = [
            'contents' => [
                'slug', 'meta_title', 'meta_description', 
                'body_raw', 'featured_image_alt', 'featured_image_caption'
            ],
            'seo_metas' => [
                'title', 'description', 'og_title', 'og_description'
            ]
        ];

        foreach ($tables as $tableName => $columns) {
            foreach ($columns as $column) {
                $records = \Illuminate\Support\Facades\DB::table($tableName)->get(['id', $column]);
                foreach ($records as $record) {
                    $decoded = json_decode($record->$column, true);
                    if (is_array($decoded) && isset($decoded['id'])) {
                        \Illuminate\Support\Facades\DB::table($tableName)
                            ->where('id', $record->id)
                            ->update([$column => $decoded['id']]);
                    }
                }
            }
        }
    }
};
