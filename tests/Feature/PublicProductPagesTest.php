<?php

namespace Tests\Feature;

use App\Models\PageError;
use App\Models\Product;
use App\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicProductPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_catalog_and_active_product_detail_render_successfully(): void
    {
        $product = $this->createProduct();

        $this->get(route('products.catalog'))
            ->assertOk()
            ->assertSee($product->name);

        $this->get(route('products.show', $product->slug))
            ->assertOk()
            ->assertSee($product->name)
            ->assertSee('assets/og-default.jpg', false);
    }

    public function test_inactive_product_detail_is_not_publicly_accessible(): void
    {
        $product = $this->createProduct(false);

        $this->get(route('products.show', $product->slug))->assertNotFound();

        $this->assertSame(1, PageError::query()->where('url', route('products.show', $product->slug))->value('count'));
    }

    private function createProduct(bool $active = true): Product
    {
        $tenant = Tenant::create([
            'name' => 'Test Tenant',
            'slug' => 'test-tenant',
            'domain' => 'test-tenant.test',
            'subscription_plan' => 'starter',
            'is_active' => true,
        ]);

        $product = new Product;
        $product->forceFill([
            'tenant_id' => $tenant->id,
            'name' => 'Produk Uji',
            'slug' => 'produk-uji',
            'description' => 'Deskripsi produk uji.',
            'price' => 100000,
            'shortcode' => '[produk-uji]',
            'is_active' => $active,
            'enable_buy_button' => true,
        ])->save();

        return $product;
    }
}
