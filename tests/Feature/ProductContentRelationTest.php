<?php

namespace Tests\Feature;

use App\Models\Content;
use App\Models\Product;
use App\Models\SiloBlueprint;
use App\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductContentRelationTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_product_can_have_many_contents(): void
    {
        [$tenant, $product, $silo] = $this->productContext();

        Content::forceCreate($this->contentData($tenant->id, $product->id, $silo->id, 'panduan produk'));
        Content::forceCreate($this->contentData($tenant->id, $product->id, $silo->id, 'fitur produk'));

        $this->assertCount(2, $product->fresh()->contents);
        $this->assertSame($product->id, $silo->fresh()->product->id);
    }

    public function test_content_can_remain_unassigned_to_a_product(): void
    {
        [$tenant, , $silo] = $this->productContext();

        $content = Content::forceCreate($this->contentData($tenant->id, null, $silo->id, 'artikel umum'));

        $this->assertNull($content->fresh()->product_id);
        $this->assertNull($content->fresh()->product);
    }

    private function productContext(): array
    {
        $tenant = Tenant::create([
            'name' => 'Product Content Tenant',
            'slug' => 'product-content-tenant',
            'domain' => 'product-content.test',
            'subscription_plan' => 'starter',
            'is_active' => true,
        ]);

        $product = Product::forceCreate([
            'tenant_id' => $tenant->id,
            'name' => 'Produk Konten',
            'slug' => 'produk-konten',
            'description' => 'Produk untuk pengujian konten.',
            'price' => 100000,
            'shortcode' => '[produk-konten]',
            'is_active' => true,
        ]);

        $silo = SiloBlueprint::forceCreate([
            'tenant_id' => $tenant->id,
            'product_id' => $product->id,
            'silo_name' => 'Silo Produk Konten',
            'seed_keyword' => 'produk konten',
            'target_language' => 'id',
            'target_country' => 'ID',
        ]);

        return [$tenant, $product, $silo];
    }

    private function contentData(int $tenantId, ?int $productId, int $siloId, string $keyword): array
    {
        return [
            'tenant_id' => $tenantId,
            'product_id' => $productId,
            'silo_blueprint_id' => $siloId,
            'target_keyword' => $keyword,
            'slug' => $keyword,
            'hierarchy_level' => 'cluster',
            'status' => 'blueprint',
        ];
    }
}
