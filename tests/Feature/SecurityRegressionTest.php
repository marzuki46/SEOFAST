<?php

namespace Tests\Feature;

use App\Http\Middleware\ForceHttps;
use App\Models\Buyer;
use App\Models\BuyerOrder;
use App\Models\Content;
use App\Models\Product;
use App\Models\PageError;
use App\Models\SiloBlueprint;
use App\Models\Tenant;
use App\Models\User;
use Database\Seeders\AdminUserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class SecurityRegressionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ForceHttps::class);
    }

    public function test_draft_preview_requires_a_valid_signature(): void
    {
        [$content] = $this->makeContent('private-draft', 'draft');

        $this->get(route('blog.preview', ['slug' => $content->slug]))->assertForbidden();

        $signedUrl = URL::temporarySignedRoute(
            'blog.preview',
            now()->addMinutes(5),
            ['slug' => $content->slug]
        );

        $response = $this->get($signedUrl);

        $response->assertOk()->assertSee('Private Draft');
        $this->assertStringNotContainsString('public', (string) $response->headers->get('Cache-Control'));
    }

    public function test_payment_result_cannot_display_another_buyers_order(): void
    {
        $tenant = Tenant::create([
            'name' => 'Store', 'slug' => 'store', 'domain' => 'store.test',
            'subscription_plan' => 'pro', 'is_active' => true,
        ]);
        $product = Product::forceCreate([
            'tenant_id' => $tenant->id, 'name' => 'Product', 'slug' => 'product',
            'shortcode' => 'product-test', 'price' => 10000, 'is_active' => true,
        ]);
        $owner = Buyer::create(['name' => 'Owner', 'email' => 'owner@test.local', 'password' => 'secret123']);
        $attacker = Buyer::create(['name' => 'Attacker', 'email' => 'attacker@test.local', 'password' => 'secret123']);
        $order = BuyerOrder::create([
            'buyer_id' => $owner->id, 'product_id' => $product->id,
            'order_number' => 'ORD-PRIVATE', 'unique_code' => '123',
            'amount' => 10000, 'unique_amount' => 0, 'status' => 'pending',
        ]);

        $response = $this->actingAs($attacker, 'buyer')
            ->get(route('payment.finish', ['order_id' => $order->order_number]));

        $response->assertRedirect(route('home'));
        $response->assertSessionHas('error', 'Order reference not found.');
    }

    public function test_tenant_user_only_sees_its_own_content(): void
    {
        [$first, $firstTenant] = $this->makeContent('first-tenant');
        [$second, $secondTenant] = $this->makeContent('second-tenant');
        $user = User::factory()->create(['role' => 'admin', 'tenant_id' => $firstTenant->id]);

        $this->actingAs($user);

        $this->assertTrue(Content::whereKey($first->id)->exists());
        $this->assertFalse(Content::whereKey($second->id)->exists());
        $this->assertSame($firstTenant->id, Content::firstOrFail()->tenant_id);
        $this->assertNotSame($firstTenant->id, $secondTenant->id);
    }

    public function test_admin_seeder_does_not_create_default_credentials(): void
    {
        $this->seed(AdminUserSeeder::class);

        $this->assertDatabaseCount('users', 0);
    }

    public function test_installer_execution_is_post_only_and_emergency_migrate_route_is_removed(): void
    {
        $this->assertSame(['POST'], Route::getRoutes()->getByName('install.run')?->methods());
        $this->assertFalse(collect(Route::getRoutes()->getRoutes())->contains(
            static fn ($route): bool => $route->uri() === 'force-migrate'
        ));
    }

    public function test_scanner_probe_is_not_written_to_404_tracker(): void
    {
        $this->get('/wp-login.php')->assertNotFound();

        $this->assertDatabaseMissing('page_errors', [
            'url' => url('/wp-login.php'),
        ]);
    }

    private function makeContent(string $slug, string $status = 'draft'): array
    {
        $tenant = Tenant::create([
            'name' => $slug, 'slug' => $slug, 'domain' => $slug.'.test',
            'subscription_plan' => 'pro', 'is_active' => true,
        ]);
        $silo = SiloBlueprint::create([
            'tenant_id' => $tenant->id, 'silo_name' => 'Category '.$slug,
            'seed_keyword' => $slug, 'target_language' => 'id', 'target_country' => 'ID',
            'total_contents' => 1, 'published_contents' => 0,
        ]);
        $content = Content::create([
            'tenant_id' => $tenant->id, 'silo_blueprint_id' => $silo->id,
            'target_keyword' => 'Private Draft', 'slug' => $slug,
            'meta_title' => 'Private Draft', 'body_raw' => '<p>Private Draft</p>',
            'hierarchy_level' => 'pillar', 'status' => $status,
            'published_at' => $status === 'published' ? now() : null,
        ]);

        return [$content, $tenant];
    }
}
