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
            ->orderBy('id')
            ->get(['id', 'tenant_id', 'slug']);

        foreach ($records as $record) {
            $decoded = json_decode($record->slug, true);
            if (!is_array($decoded)) {
                continue;
            }

            $plainSlug = $decoded['id'] ?? reset($decoded);
            if (!is_string($plainSlug) || empty($plainSlug)) {
                continue;
            }

            // Ensure unique slug within tenant
            $finalSlug = $plainSlug;
            $counter = 1;
            while (
                DB::table('contents')
                    ->where('tenant_id', $record->tenant_id)
                    ->where('slug', $finalSlug)
                    ->where('id', '!=', $record->id)
                    ->exists()
            ) {
                $finalSlug = $plainSlug . '-' . $counter++;
            }

            DB::table('contents')
                ->where('id', $record->id)
                ->update(['slug' => $finalSlug]);
        }
    }

    public function down(): void
    {
        // noop
    }
};
