<?php

namespace App\Services\Seo;

use App\Models\CanonicalMapping;
use App\Models\Content;

class CanonicalMappingService
{
    /**
     * Set a canonical mapping for duplicate or paginated content.
     */
    public function setCanonical(Content $duplicateContent, Content $originalContent, string $reason, float $similarityScore = null): CanonicalMapping
    {
        return CanonicalMapping::updateOrCreate(
            [
                'tenant_id' => $duplicateContent->tenant_id,
                'content_id' => $duplicateContent->id,
            ],
            [
                'canonical_target_id' => $originalContent->id,
                'reason' => $reason,
                'similarity_score' => $similarityScore,
                'is_resolved' => false,
            ]
        );
    }

    /**
     * Resolve a canonical mapping.
     */
    public function resolveMapping(int $mappingId): bool
    {
        $mapping = CanonicalMapping::find($mappingId);
        if (!$mapping) return false;

        $mapping->update([
            'is_resolved' => true,
            'resolved_at' => now(),
        ]);

        return true;
    }
}
