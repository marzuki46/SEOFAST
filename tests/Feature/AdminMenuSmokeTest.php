<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Middleware\HandleCors;
use PHPUnit\Framework\Attributes\DataProvider;
use App\Http\Middleware\ForceHttps;
use Tests\TestCase;

class AdminMenuSmokeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware([ForceHttps::class, HandleCors::class]);

        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);
    }

    public static function menuRoutes(): array
    {
        $routes = [
            'dashboard',
            'admin.silo.index',
            'admin.links.index',
            'admin.content.prapost',
            'admin.content.create',
            'admin.content.drafts',
            'admin.content.calendar',
            'admin.content.index',
            'admin.content.trash',
            'admin.wordpress.index',
            'admin.pages.index',
            'admin.menus.index',
            'admin.media.index',
            'admin.products.index',
            'admin.product-categories.index',
            'admin.orders.index',
            'admin.tickets.index',
            'admin.inquiries.index',
            'admin.pre-orders.global',
            'admin.billing.index',
            'admin.gsc.index',
            'admin.users.index',
            'admin.seo.settings.index',
            'admin.settings.index',
            'admin.errors.index',
            'admin.broken-links.index',
            'admin.duplicates.index',
            'admin.readability.index',
            'admin.url-audit.index',
            'admin.serp-rank.index',
            'admin.competitor-analysis.index',
            'admin.redirects.index',
            'admin.infrastructure.index',
        ];

        return array_map(
            fn (string $name) => [$name, $name],
            array_combine($routes, $routes)
        );
    }

    #[DataProvider('menuRoutes')]
    public function test_menu_page_loads(string $label, string $routeName): void
    {
        $response = $this->get(route($routeName));

        $this->assertTrue(
            $response->getStatusCode() < 500,
            "Menu '$label' ({$routeName}) returned status {$response->getStatusCode()} (server error)."
        );
    }
}
