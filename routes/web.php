<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group.
|
*/

use App\Http\Controllers\HomeController;
use App\Http\Controllers\BlogController;

// Install Wizard Routes (must be before all other routes)
Route::middleware('web')->group(function () {
    Route::get('/install', [\App\Http\Controllers\InstallController::class, 'welcome'])->name('install.welcome');
    Route::get('/install/database', [\App\Http\Controllers\InstallController::class, 'database'])->name('install.database');
    Route::post('/install/database', [\App\Http\Controllers\InstallController::class, 'saveDb'])->name('install.save-db');
    Route::get('/install/admin', [\App\Http\Controllers\InstallController::class, 'admin'])->name('install.admin');
    Route::post('/install/admin', [\App\Http\Controllers\InstallController::class, 'saveAdmin'])->name('install.save-admin');
    Route::get('/install/run', [\App\Http\Controllers\InstallController::class, 'run'])->name('install.run');
    Route::get('/install/complete', [\App\Http\Controllers\InstallController::class, 'complete'])->name('install.complete');
});

// Public Website Routes
Route::get('/', [\App\Http\Controllers\PageController::class, 'home'])->name('home');

// SEO Infrastructure Routes (Fase 3)
Route::get('/sitemap.xml', [\App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');
Route::get('/sitemap-static.xml', [\App\Http\Controllers\SitemapController::class, 'staticSitemap'])->name('sitemap.static');
Route::get('/sitemap-posts-{page}.xml', [\App\Http\Controllers\SitemapController::class, 'postsSitemap'])->where('page', '[0-9]+')->name('sitemap.posts');
Route::get('/sitemap-en-static.xml', [\App\Http\Controllers\SitemapController::class, 'staticSitemapEn'])->name('sitemap.en.static');
Route::get('/sitemap-en-posts-{page}.xml', [\App\Http\Controllers\SitemapController::class, 'postsSitemapEn'])->where('page', '[0-9]+')->name('sitemap.en.posts');
Route::get('/robots.txt', [\App\Http\Controllers\SitemapController::class, 'robots'])->name('robots');
// Ghost Publish — Blueprint URLs with noindex placeholder
Route::get('/g/{slug}', [\App\Http\Controllers\SitemapController::class, 'ghost'])->name('ghost.show');

use App\Models\SystemSetting;

// Public Blog Routes
$blogRoutes = function() {
    Route::get('/', [BlogController::class, 'index'])->name('index');
    Route::get('/category/{slug}', [BlogController::class, 'category'])->name('category');
    Route::get('/preview/{slug}', [BlogController::class, 'preview'])->name('preview');
    Route::get('/{slug}', [BlogController::class, 'show'])->name('show');
};

$blogPrefix = SystemSetting::get('permalink_blog', 'blog');

// Default Locale (ID)
if ($blogPrefix) {
    Route::prefix($blogPrefix)->name('blog.')->middleware('throttle:120,1')->group($blogRoutes);
} else {
    Route::name('blog.')->middleware('throttle:120,1')->group($blogRoutes);
}

// English Locale (EN)
Route::prefix('en')->group(function() use ($blogPrefix, $blogRoutes) {
    if ($blogPrefix) {
        Route::prefix($blogPrefix)->name('en.blog.')->middleware('throttle:120,1')->group($blogRoutes);
    } else {
        Route::name('en.blog.')->middleware('throttle:120,1')->group($blogRoutes);
    }
});

// Public Product Routes
$productRoutes = function() {
    Route::get('/', [\App\Http\Controllers\ProductSalesController::class, 'index'])->name('catalog');
    Route::get('/{slug}', [\App\Http\Controllers\ProductSalesController::class, 'show'])->name('show');
    Route::post('/{product}/order', [\App\Http\Controllers\ProductSalesController::class, 'order'])->name('order');
};

$productPrefix = SystemSetting::get('permalink_product', 'produk');

// Default Locale (ID)
if ($productPrefix) {
    Route::prefix($productPrefix)->name('products.')->middleware('throttle:60,1')->group($productRoutes);
}

// English Locale (EN)
Route::prefix('en')->group(function() use ($productPrefix, $productRoutes) {
    if ($productPrefix) {
        Route::prefix($productPrefix)->name('en.products.')->middleware('throttle:60,1')->group($productRoutes);
    }
});

// Public Project Routes
$projectRoutes = function() {
    Route::get('/', [\App\Http\Controllers\ProjectController::class, 'index'])->name('index');
    Route::get('/{slug}', [\App\Http\Controllers\ProjectController::class, 'show'])->name('show');
};

$projectPrefix = SystemSetting::get('permalink_project', 'projeku');

// Default Locale (ID)
if ($projectPrefix) {
    Route::prefix($projectPrefix)->name('projects.')->group($projectRoutes);
}

// English Locale (EN)
Route::prefix('en')->group(function() use ($projectPrefix, $projectRoutes) {
    if ($projectPrefix) {
        Route::prefix($projectPrefix)->name('en.projects.')->group($projectRoutes);
    }
});

// Authentication Routes (Admin)
Route::middleware('guest')->group(function () {
    Route::get('/master/adminis-trator', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/master/adminis-trator', [LoginController::class, 'authenticate'])->name('login.authenticate');
});

// Buyer Public Authentication Routes (Moved to root level for easier access)
Route::middleware('guest:buyer')->name('buyer.')->group(function () {
    Route::get('/login', [\App\Http\Controllers\Buyer\BuyerAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [\App\Http\Controllers\Buyer\BuyerAuthController::class, 'login'])->name('login.post');
    Route::get('/register', [\App\Http\Controllers\Buyer\BuyerAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [\App\Http\Controllers\Buyer\BuyerAuthController::class, 'register'])->name('register.post');
    Route::get('/auth/google/buyer', [\App\Http\Controllers\Buyer\BuyerAuthController::class, 'googleRedirect'])->name('auth.google');
    Route::get('/auth/google/buyer/callback', [\App\Http\Controllers\Buyer\BuyerAuthController::class, 'googleCallback'])->name('auth.google.callback');
});

use App\Http\Controllers\Admin\GscAdminController;
use App\Http\Controllers\Admin\GscOAuthController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\BillingController;
use App\Http\Controllers\Admin\SettingController;

// Authenticated Admin Routes
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    
    // Admin Dashboard
    Route::prefix('admin')->middleware(['auth.admin'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('/dashboard/cache-html', [DashboardController::class, 'cacheHtml'])->name('admin.dashboard.cache-html');
        Route::post('/dashboard/queue-restart', [DashboardController::class, 'restartQueue'])->name('admin.dashboard.queue-restart');

        // Users Management
        Route::prefix('users')->name('admin.users.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Admin\UserController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Admin\UserController::class, 'store'])->name('store');
            Route::get('/{user}/edit', [\App\Http\Controllers\Admin\UserController::class, 'edit'])->name('edit');
            Route::put('/{user}', [\App\Http\Controllers\Admin\UserController::class, 'update'])->name('update');
            Route::delete('/{user}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('destroy');
        });

        // Silo / Topical Map
        Route::prefix('silo')->name('admin.silo.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\SiloBlueprintController::class, 'index'])->name('index');
            Route::post('/', [\App\Http\Controllers\Admin\SiloBlueprintController::class, 'store'])->name('store');
            Route::get('/{siloBlueprint}', [\App\Http\Controllers\Admin\SiloBlueprintController::class, 'show'])->name('show');
            Route::delete('/{silo}', [\App\Http\Controllers\Admin\SiloBlueprintController::class, 'destroy'])->name('destroy');
            
            // Interactive generation
            Route::post('/{silo}/generate-pillar', [\App\Http\Controllers\Admin\SiloBlueprintController::class, 'generatePillar'])->name('generate_pillar');
            Route::post('/{silo}/content/{content}/generate-clusters', [\App\Http\Controllers\Admin\SiloBlueprintController::class, 'generateClusters'])->name('generate_clusters');
            Route::post('/{silo}/content/{content}/generate-subclusters', [\App\Http\Controllers\Admin\SiloBlueprintController::class, 'generateSubClusters'])->name('generate_subclusters');
            Route::post('/{silo}/map-internal-links', [\App\Http\Controllers\Admin\SiloBlueprintController::class, 'mapInternalLinks'])->name('map_internal_links');
            
            // Content management within Silo
            Route::post('/{silo}/content/{content}/approve', [\App\Http\Controllers\Admin\SiloBlueprintController::class, 'approveContent'])->name('approve_content');
            Route::delete('/{silo}/content/{content}', [\App\Http\Controllers\Admin\SiloBlueprintController::class, 'deleteContent'])->name('delete_content');
            Route::post('/{silo}/content/{content}/regenerate', [\App\Http\Controllers\Admin\SiloBlueprintController::class, 'regenerateContent'])->name('regenerate_content');
            Route::post('/{silo}/content/{content}/process', [\App\Http\Controllers\Admin\SiloBlueprintController::class, 'processCluster'])->name('process_cluster');
        });

        // Internal Link Mapping
        Route::prefix('links')->name('admin.links.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\InternalLinkController::class, 'index'])->name('index');
            Route::post('/', [\App\Http\Controllers\Admin\InternalLinkController::class, 'store'])->name('store');
            Route::post('/generate-ai', [\App\Http\Controllers\Admin\InternalLinkController::class, 'generateAi'])->name('generate_ai');
            Route::post('/reset', [\App\Http\Controllers\Admin\InternalLinkController::class, 'resetLinks'])->name('reset');
            Route::get('/process-ai', [\App\Http\Controllers\Admin\InternalLinkController::class, 'processAiView'])->name('process_ai_view');
            Route::post('/process-ai-chunk', [\App\Http\Controllers\Admin\InternalLinkController::class, 'processAiChunk'])->name('process_ai_chunk');
            Route::put('/{link}', [\App\Http\Controllers\Admin\InternalLinkController::class, 'update'])->name('update');
        });

        // Midtrans Products & Shortcodes
        Route::prefix('products')->name('admin.products.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\ProductController::class, 'index'])->name('index');
            Route::post('/', [\App\Http\Controllers\Admin\ProductController::class, 'store'])->name('store');
            Route::get('/{product}/edit', [\App\Http\Controllers\Admin\ProductController::class, 'edit'])->name('edit');
            Route::put('/{product}', [\App\Http\Controllers\Admin\ProductController::class, 'update'])->name('update');
            Route::delete('/{product}', [\App\Http\Controllers\Admin\ProductController::class, 'destroy'])->name('destroy');
        });

        // Media Library
        Route::prefix('media')->name('admin.media.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\MediaController::class, 'index'])->name('index');
            Route::post('/', [\App\Http\Controllers\Admin\MediaController::class, 'store'])->name('store');
            Route::post('/{id}', [\App\Http\Controllers\Admin\MediaController::class, 'update'])->name('update');
            Route::delete('/', [\App\Http\Controllers\Admin\MediaController::class, 'destroy'])->name('destroy');
        });

        // Content / AI Generator
        Route::prefix('content')->name('admin.content.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\ContentController::class, 'index'])->name('index');
            Route::get('/prapost', [\App\Http\Controllers\Admin\ContentController::class, 'prapost'])->name('prapost');
            Route::get('/calendar', [\App\Http\Controllers\Admin\ContentController::class, 'calendar'])->name('calendar');
            Route::get('/drafts', [\App\Http\Controllers\Admin\ContentController::class, 'drafts'])->name('drafts');
            Route::post('/work-queue', [\App\Http\Controllers\Admin\ContentController::class, 'workQueue'])->name('work_queue');
            Route::match(['get', 'post'], '/start-ai-batch', [\App\Http\Controllers\Admin\ContentController::class, 'bulkGenerateAi'])->name('bulk_generate');
            Route::post('/generate-single', [\App\Http\Controllers\Admin\ContentController::class, 'generateSingle'])->name('generate_single');
            Route::match(['get', 'post'], '/check-ai-connection', [\App\Http\Controllers\Admin\ContentController::class, 'checkConnection'])->name('check_connection');
            Route::get('/create', [\App\Http\Controllers\Admin\ContentController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Admin\ContentController::class, 'store'])->name('store');
            
            // Phase 4: Image Search & AI Illustration
            Route::get('/{content}/images', [\App\Http\Controllers\Admin\ImageSearchController::class, 'index'])->name('images');
            Route::post('/{content}/images/search', [\App\Http\Controllers\Admin\ImageSearchController::class, 'search'])->name('images.search');
            Route::post('/{content}/images/select', [\App\Http\Controllers\Admin\ImageSearchController::class, 'select'])->name('images.select');
            Route::post('/{content}/images/ai-generate', [\App\Http\Controllers\Admin\ImageSearchController::class, 'generateAiIllustration'])->name('images.ai-generate');
            
            Route::get('/{content}', [\App\Http\Controllers\Admin\ContentController::class, 'show'])->name('show');
            Route::get('/{content}/edit', [\App\Http\Controllers\Admin\ContentController::class, 'edit'])->name('edit');
            Route::put('/{content}', [\App\Http\Controllers\Admin\ContentController::class, 'update'])->name('update');
            Route::post('/{content}/generate', [\App\Http\Controllers\Admin\ContentController::class, 'generateAi'])->name('generate');
            Route::post('/bulk-publish', [\App\Http\Controllers\Admin\ContentController::class, 'bulkPublish'])->name('bulk_publish');
            Route::delete('/{content}', [\App\Http\Controllers\Admin\ContentController::class, 'destroy'])->name('destroy');
        });

        // Billing & Invoices (Legacy / Tenant)
        Route::prefix('billing')->name('admin.billing.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\BillingController::class, 'index'])->name('index');
            Route::post('/', [\App\Http\Controllers\Admin\BillingController::class, 'store'])->name('store');
            Route::post('/invoice/{invoice}/verify', [\App\Http\Controllers\Admin\BillingController::class, 'verify'])->name('verify');
        });

        // Google Search Console (GSC) Integration
        Route::prefix('gsc')->name('admin.gsc.')->group(function () {
            Route::get('/', [GscAdminController::class, 'index'])->name('index');
            Route::post('/credentials', [GscAdminController::class, 'saveCredentials'])->name('save_credentials');
            Route::post('/sync-inspections', [GscAdminController::class, 'syncInspections'])->name('sync_inspections');
            Route::post('/sync-analytics', [GscAdminController::class, 'syncAnalytics'])->name('sync_analytics');
            Route::post('/submit-indexing', [GscAdminController::class, 'submitIndexing'])->name('submit_indexing');
            
            // OAuth flow routes
            Route::get('/auth', [GscOAuthController::class, 'redirectToGoogle'])->name('auth');
            Route::get('/callback', [GscOAuthController::class, 'handleCallback'])->name('callback');
        });

        // Order Management (Super Admin)
        Route::prefix('orders')->name('admin.orders.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\OrderManagementController::class, 'index'])->name('index');
            Route::get('/{order}', [\App\Http\Controllers\Admin\OrderManagementController::class, 'show'])->name('show');
            Route::post('/{order}/verify', [\App\Http\Controllers\Admin\OrderManagementController::class, 'verify'])->name('verify');
            Route::post('/{order}/reject', [\App\Http\Controllers\Admin\OrderManagementController::class, 'reject'])->name('reject');
        });

        // Static Pages & Page Builder
        Route::prefix('pages')->name('admin.pages.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\PageController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Admin\PageController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Admin\PageController::class, 'store'])->name('store');
            Route::get('/{page}/builder', [\App\Http\Controllers\Admin\PageController::class, 'builder'])->name('builder');
            Route::get('/{page}/edit', [\App\Http\Controllers\Admin\PageController::class, 'edit'])->name('edit');
            Route::put('/{page}', [\App\Http\Controllers\Admin\PageController::class, 'update'])->name('update');
            Route::post('/{page}/builder/save', [\App\Http\Controllers\Admin\PageController::class, 'saveBuilder'])->name('builder.save');
            Route::post('/{page}/set-home', [\App\Http\Controllers\Admin\PageController::class, 'setHomepage'])->name('set_home');
        });

        // Menus Management
        Route::prefix('menus')->name('admin.menus.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\MenuController::class, 'index'])->name('index');
            Route::post('/{menu}/items', [\App\Http\Controllers\Admin\MenuController::class, 'storeItems'])->name('items.store');
        });

        // Infrastructure Settings (Queue, Cache, Redis)
        Route::prefix('infrastructure')->name('admin.infrastructure.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\InfrastructureController::class, 'index'])->name('index');
            Route::post('/update-queue', [\App\Http\Controllers\Admin\InfrastructureController::class, 'updateQueue'])->name('update-queue');
            Route::post('/update-cache', [\App\Http\Controllers\Admin\InfrastructureController::class, 'updateCache'])->name('update-cache');
            Route::post('/restart-queue', [\App\Http\Controllers\Admin\InfrastructureController::class, 'restartQueue'])->name('restart-queue');
            Route::post('/queue-status', [\App\Http\Controllers\Admin\InfrastructureController::class, 'queueStatus'])->name('queue-status');
        });

        // System Settings & Cache
        Route::prefix('settings')->name('admin.settings.')->group(function () {
            // Super Admin Global Settings (Tabbed)
            Route::get('/', [\App\Http\Controllers\Admin\SystemSettingController::class, 'index'])->name('index');
            Route::post('/', [\App\Http\Controllers\Admin\SystemSettingController::class, 'update'])->name('update');
            Route::post('/clear-cache', [\App\Http\Controllers\Admin\SystemSettingController::class, 'clearCache'])->name('clear_cache');
            Route::post('/ai/sync-models', [\App\Http\Controllers\Admin\SystemSettingController::class, 'syncModels'])->name('ai.sync_models');
        });

        // Enterprise SEO Settings
        Route::prefix('seo/settings')->name('admin.seo.settings.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\SeoSettingController::class, 'index'])->name('index');
            Route::post('/clean-garbage', [\App\Http\Controllers\Admin\SeoSettingController::class, 'cleanGarbage'])->name('clean_garbage');
            // We use SystemSettingController@update for the form submission
            Route::post('/', [\App\Http\Controllers\Admin\SystemSettingController::class, 'update'])->name('update');
        });

        // Redirect Manager
        Route::prefix('redirects')->name('admin.redirects.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\RedirectController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Admin\RedirectController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Admin\RedirectController::class, 'store'])->name('store');
            Route::get('/{redirect}/edit', [\App\Http\Controllers\Admin\RedirectController::class, 'edit'])->name('edit');
            Route::put('/{redirect}', [\App\Http\Controllers\Admin\RedirectController::class, 'update'])->name('update');
            Route::delete('/{redirect}', [\App\Http\Controllers\Admin\RedirectController::class, 'destroy'])->name('destroy');
        });

        // 404 Error Tracker
        Route::prefix('errors')->name('admin.errors.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\PageErrorController::class, 'index'])->name('index');
            Route::post('/clear-all', [\App\Http\Controllers\Admin\PageErrorController::class, 'clearAll'])->name('clear_all');
            Route::post('/{pageError}/redirect', [\App\Http\Controllers\Admin\PageErrorController::class, 'createRedirect'])->name('create_redirect');
            Route::delete('/{pageError}', [\App\Http\Controllers\Admin\PageErrorController::class, 'destroy'])->name('destroy');
        });

        // Broken Link Checker
        Route::prefix('broken-links')->name('admin.broken-links.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\BrokenLinkController::class, 'index'])->name('index');
            Route::post('/scan', [\App\Http\Controllers\Admin\BrokenLinkController::class, 'scan'])->name('scan');
            Route::delete('/clear-all', [\App\Http\Controllers\Admin\BrokenLinkController::class, 'clearAll'])->name('clear_all');
            Route::delete('/{brokenLink}', [\App\Http\Controllers\Admin\BrokenLinkController::class, 'destroy'])->name('destroy');
        });

        // Content Duplication Detector
        Route::prefix('duplicates')->name('admin.duplicates.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\DuplicateContentController::class, 'index'])->name('index');
            Route::post('/detect', [\App\Http\Controllers\Admin\DuplicateContentController::class, 'detect'])->name('detect');
            Route::post('/{mapping}/resolve', [\App\Http\Controllers\Admin\DuplicateContentController::class, 'resolve'])->name('resolve');
            Route::post('/{mapping}/unresolve', [\App\Http\Controllers\Admin\DuplicateContentController::class, 'unresolve'])->name('unresolve');
        });

        // Readability Dashboard
        Route::prefix('readability')->name('admin.readability.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\ReadabilityController::class, 'index'])->name('index');
            Route::post('/compute', [\App\Http\Controllers\Admin\ReadabilityController::class, 'compute'])->name('compute');
        });

        Route::prefix('url-audit')->name('admin.url-audit.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\UrlAuditController::class, 'index'])->name('index');
            Route::post('/run', [\App\Http\Controllers\Admin\UrlAuditController::class, 'run'])->name('run');
        });

        Route::prefix('serp-rank')->name('admin.serp-rank.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\SerpRankController::class, 'index'])->name('index');
            Route::post('/sample', [\App\Http\Controllers\Admin\SerpRankController::class, 'generateSample'])->name('sample');
        });
    });
});

// Admin Auth Google OAuth
Route::get('/auth/google', [\App\Http\Controllers\Auth\GoogleAuthController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [\App\Http\Controllers\Auth\GoogleAuthController::class, 'callback'])->name('auth.google.callback');

Route::get('/force-migrate', function () {
    \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
    return 'Database migrated successfully! Artisan output: ' . \Illuminate\Support\Facades\Artisan::output();
});

// Buyer Portal Routes
Route::prefix('buyer')->name('buyer.')->group(function () {
    // Public — belum login (Already moved to root /login & /register)

    // Protected — sudah login sebagai buyer
    Route::middleware(['auth:buyer', 'verified'])->group(function () {
        Route::post('/logout', [\App\Http\Controllers\Buyer\BuyerAuthController::class, 'logout'])->name('logout')->withoutMiddleware('verified');
        
        // Email Verification Routes
        Route::get('/email/verify', [\App\Http\Controllers\Buyer\BuyerVerificationController::class, 'show'])
            ->withoutMiddleware('verified')
            ->name('verification.notice');
            
        Route::get('/email/verify/{id}/{hash}', [\App\Http\Controllers\Buyer\BuyerVerificationController::class, 'verify'])
            ->middleware(['signed'])
            ->withoutMiddleware('verified')
            ->name('verification.verify');
            
        Route::post('/email/verification-notification', [\App\Http\Controllers\Buyer\BuyerVerificationController::class, 'send'])
            ->middleware(['throttle:6,1'])
            ->withoutMiddleware('verified')
            ->name('verification.send');

        Route::get('/dashboard', [\App\Http\Controllers\Buyer\BuyerDashboardController::class, 'index'])->name('dashboard');
        Route::get('/orders', [\App\Http\Controllers\Buyer\BuyerOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [\App\Http\Controllers\Buyer\BuyerOrderController::class, 'show'])->name('orders.show');
        Route::post('/orders/{order}/upload-proof', [\App\Http\Controllers\Buyer\BuyerOrderController::class, 'uploadProof'])->name('orders.upload_proof');
        Route::get('/products', [\App\Http\Controllers\Buyer\BuyerProductController::class, 'index'])->name('products.index');
        Route::get('/products/{access}', [\App\Http\Controllers\Buyer\BuyerProductController::class, 'access'])->name('products.access');
        // Route::get('/profile', [\App\Http\Controllers\Buyer\BuyerProfileController::class, 'index'])->name('profile');
        // Route::put('/profile', [\App\Http\Controllers\Buyer\BuyerProfileController::class, 'update'])->name('profile.update');
    });
});
// English Locale (EN) — Home & Pages
Route::get('/en', [\App\Http\Controllers\PageController::class, 'home'])->name('en.home');
Route::get('/en/{slug}', [\App\Http\Controllers\PageController::class, 'show'])->where('slug', '.*')->name('en.page.show');

// Catch-all — ID pages (must be last)
Route::get('/{slug}', [\App\Http\Controllers\PageController::class, 'show'])->where('slug', '.*')->name('page.show');