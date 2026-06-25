<?php

namespace App\Traits;

use App\Models\SeoMeta;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasSeoMeta
{
    /**
     * Get the SEO meta associated with the model.
     */
    public function seoMeta(): MorphOne
    {
        return $this->morphOne(SeoMeta::class, 'model');
    }

    /**
     * Update or create the SEO meta for the model.
     */
    public function updateSeoMeta(array $attributes)
    {
        return $this->seoMeta()->updateOrCreate(
            ['model_id' => $this->id, 'model_type' => self::class],
            $attributes
        );
    }
}
