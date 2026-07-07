<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        $records = DB::table('contents')
            ->where('slug', 'like', '{%')
            ->get(['id', 'slug']);

        foreach ($records as $record) {
            $decoded = json_decode($record->slug, true);
            if (is_array($decoded)) {
                $plainSlug = $decoded['id'] ?? reset($decoded);
                if (is_string($plainSlug) && !empty($plainSlug)) {
                    DB::table('contents')
                        ->where('id', $record->id)
                        ->update(['slug' => $plainSlug]);
                }
            }
        }
    }

    public function down(): void
    {
        // noop
    }
};
