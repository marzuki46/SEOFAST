<?php

use App\Http\Controllers\Admin\BillingController;
use App\Http\Controllers\Admin\BrokenLinkController;
use App\Http\Controllers\Admin\ContentCampaignController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DuplicateContentController;
use App\Http\Controllers\Admin\GscAdminController;
use App\Http\Controllers\Admin\GscOAuthController;
use App\Http\Controllers\Admin\ImageSearchController;
use App\Http\Controllers\Admin\InfrastructureController;
use App\Http\Controllers\Admin\InquiryController;
use App\Http\Controllers\Admin\InternalLinkController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\OrderManagementController;
use App\Http\Controllers\Admin\PageErrorController;
use App\Http\Controllers\Admin\ProductCategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ReadabilityController;
use App\Http\Controllers\Admin\RedirectController;
use App\Http\Controllers\Admin\SeoSettingController;
use App\Http\Controllers\Admin\SerpRankController;
use App\Http\Controllers\Admin\SiloBlueprintController;
use App\Http\Controllers\Admin\SystemSettingController;
use App\Http\Controllers\Admin\TicketManagementController;
use App\Http\Controllers\Admin\UrlAuditController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\WordPressController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\Buyer\BuyerAuthController;
use App\Http\Controllers\Buyer\BuyerDashboardController;
use App\Http\Controllers\Buyer\BuyerOrderController;
use App\Http\Controllers\Buyer\BuyerProductController;
use App\Http\Controllers\Buyer\BuyerTicketController;
use App\Http\Controllers\Buyer\BuyerVerificationController;
use App\Http\Controllers\CompetitorAnalysisController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\InstallController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PreOrderController;
use App\Http\Controllers\ProductSalesController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\WhatsAppLeadController;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\Route;

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

// Install Wizard Routes (must be before all other routes)
Route::middleware('web')->group(function () {
    Route::get('/install', [InstallController::class, 'welcome'])->name('install.welcome');
    Route::get('/install/database', [InstallController::class, 'database'])->name('install.database');
    Route::post('/install/database', [InstallController::class, 'saveDb'])->name('install.save-db');
    Route::get('/install/admin', [InstallController::class, 'admin'])->name('install.admin');
    Route::post('/install/admin', [InstallController::class, 'saveAdmin'])->name('install.save-admin');
    Route::post('/install/run', [InstallController::class, 'run'])->name('install.run');
    Route::get('/install/complete', [InstallController::class, 'complete'])->name('install.complete');
});

// Public Website Routes
Route::get('/', [PageController::class, 'home'])->name('home');

// SEO Infrastructure Routes (Fase 3)
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/sitemap-static.xml', [SitemapController::class, 'staticSitemap'])->name('sitemap.static');
Route::get('/sitemap-posts-{page}.xml', [SitemapController::class, 'postsSitemap'])->where('page', '[0-9]+')->name('sitemap.posts');
Route::get('/sitemap-en-static.xml', [SitemapController::class, 'staticSitemapEn'])->name('sitemap.en.static');
Route::get('/sitemap-en-posts-{page}.xml', [SitemapController::class, 'postsSitemapEn'])->where('page', '[0-9]+')->name('sitemap.en.posts');
Route::get('/robots.txt', [SitemapController::class, 'robots'])->name('robots');

// RSS 2.0 & WebSub Syndication Feed
Route::get('/feed', [App\Http\Controllers\RssController::class, 'feed'])->name('feed');
Route::get('/rss.xml', [App\Http\Controllers\RssController::class, 'feed'])->name('rss');

// IndexNow Verification Key Route
Route::get('/{key}.txt', function (string $key) {
    $service = app(\App\Services\IndexNowService::class);
    if ($key === $service->getKey()) {
        return response($key, 200, ['Content-Type' => 'text/plain']);
    }
    abort(404);
})->where('key', '[a-zA-Z0-9_-]+')->name('indexnow.key');

// Public Blog Routes
$blogRoutes = function () {
    Route::get('/', [BlogController::class, 'index'])->name('index');
    Route::get('/tag/{slug}', [BlogController::class, 'tag'])->name('tag');
    Route::get('/category/{slug}', [BlogController::class, 'category'])->name('category');
    Route::get('/preview/{slug}', [BlogController::class, 'preview'])
        ->middleware('signed')
        ->name('preview');
    Route::get('/{slug}', [BlogController::class, 'show'])->name('show');
};

$blogPrefix = SystemSetting::tryGet('permalink_blog', 'blog');

// Default Locale (ID)
if ($blogPrefix) {
    Route::prefix($blogPrefix)->name('blog.')->middleware('throttle:120,1')->group($blogRoutes);
} else {
    Route::name('blog.')->middleware('throttle:120,1')->group($blogRoutes);
}

// English Locale (EN)
Route::prefix('en')->group(function () use ($blogPrefix, $blogRoutes) {
    if ($blogPrefix) {
        Route::prefix($blogPrefix)->name('en.blog.')->middleware('throttle:120,1')->group($blogRoutes);
    } else {
        Route::name('en.blog.')->middleware('throttle:120,1')->group($blogRoutes);
    }
});

// Public Product Routes
$productRoutes = function () {
    Route::get('/', [ProductSalesController::class, 'index'])->name('catalog');
    Route::get('/{slug}', [ProductSalesController::class, 'show'])->name('show');
    Route::post('/{product}/order', [ProductSalesController::class, 'order'])->name('order');
    Route::post('/{product}/pre-order', [PreOrderController::class, 'store'])->name('pre-order');
};

$productPrefix = SystemSetting::tryGet('permalink_product', 'produk');

// Default Locale (ID)
if ($productPrefix) {
    Route::prefix($productPrefix)->name('products.')->middleware('throttle:60,1')->group($productRoutes);
}

// English Locale (EN)
Route::prefix('en')->group(function () use ($productPrefix, $productRoutes) {
    if ($productPrefix) {
        Route::prefix($productPrefix)->name('en.products.')->middleware('throttle:60,1')->group($productRoutes);
    }
});

// Public Project Routes
$projectRoutes = function () {
    Route::get('/', [ProjectController::class, 'index'])->name('index');
    Route::get('/{slug}', [ProjectController::class, 'show'])->name('show');
};

$projectPrefix = SystemSetting::tryGet('permalink_project', 'projeku');

// Default Locale (ID)
if ($projectPrefix) {
    Route::prefix($projectPrefix)->name('projects.')->group($projectRoutes);
}

// English Locale (EN)
Route::prefix('en')->group(function () use ($projectPrefix, $projectRoutes) {
    if ($projectPrefix) {
        Route::prefix($projectPrefix)->name('en.projects.')->group($projectRoutes);
    }
});

// Midtrans Payment Routes

Route::post('/payment/notification', [PaymentController::class, 'notification'])->name('payment.notification');
Route::get('/payment/finish', [PaymentController::class, 'finish'])->name('payment.finish');
Route::get('/payment/pending', [PaymentController::class, 'pending'])->name('payment.pending');
Route::get('/payment/error', [PaymentController::class, 'error'])->name('payment.error');

// Authentication Routes (Admin)
Route::middleware('guest')->group(function () {
    Route::get('/master/adminis-trator', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/master/adminis-trator', [LoginController::class, 'authenticate'])
        ->middleware('throttle:10,1')
        ->name('login.authenticate');
});

// Buyer Public Authentication Routes (Moved to root level for easier access)
Route::middleware('guest:buyer')->name('buyer.')->group(function () {
    Route::get('/login', [BuyerAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [BuyerAuthController::class, 'login'])
        ->middleware('throttle:10,1')
        ->name('login.post');
    Route::get('/register', [BuyerAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [BuyerAuthController::class, 'register'])
        ->middleware('throttle:5,10')
        ->name('register.post');
    Route::get('/auth/google/buyer', [BuyerAuthController::class, 'googleRedirect'])->name('auth.google');
    Route::get('/auth/google/buyer/callback', [BuyerAuthController::class, 'googleCallback'])->name('auth.google.callback');
});

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
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/create', [UserController::class, 'create'])->name('create');
            Route::post('/', [UserController::class, 'store'])->name('store');
            Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
            Route::put('/{user}', [UserController::class, 'update'])->name('update');
            Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
        });

        // Silo / Topical Map
        Route::prefix('silo')->name('admin.silo.')->group(function () {
            Route::get('/', [SiloBlueprintController::class, 'index'])->name('index');
            Route::post('/', [SiloBlueprintController::class, 'store'])->name('store');
            Route::get('/{siloBlueprint}', [SiloBlueprintController::class, 'show'])->name('show');
            Route::delete('/{silo}', [SiloBlueprintController::class, 'destroy'])->name('destroy');

            // Interactive generation
            Route::post('/{silo}/generate-pillar', [SiloBlueprintController::class, 'generatePillar'])->name('generate_pillar');
            Route::post('/{silo}/content/{content}/generate-clusters', [SiloBlueprintController::class, 'generateClusters'])->name('generate_clusters');
            Route::post('/{silo}/content/{content}/generate-subclusters', [SiloBlueprintController::class, 'generateSubClusters'])->name('generate_subclusters');
            Route::post('/{silo}/map-internal-links', [SiloBlueprintController::class, 'mapInternalLinks'])->name('map_internal_links');

            // Content management within Silo
            Route::post('/{silo}/content/{content}/approve', [SiloBlueprintController::class, 'approveContent'])->name('approve_content');
            Route::delete('/{silo}/content/{content}', [SiloBlueprintController::class, 'deleteContent'])->name('delete_content');
            Route::post('/{silo}/content/{content}/regenerate', [SiloBlueprintController::class, 'regenerateContent'])->name('regenerate_content');
            Route::post('/{silo}/content/{content}/process', [SiloBlueprintController::class, 'processCluster'])->name('process_cluster');
        });

        // Internal Link Mapping
        Route::prefix('links')->name('admin.links.')->group(function () {
            Route::get('/', [InternalLinkController::class, 'index'])->name('index');
            Route::post('/', [InternalLinkController::class, 'store'])->name('store');
            Route::post('/generate-ai', [InternalLinkController::class, 'generateAi'])->name('generate_ai');
            Route::post('/reset', [InternalLinkController::class, 'resetLinks'])->name('reset');
            Route::get('/process-ai', [InternalLinkController::class, 'processAiView'])->name('process_ai_view');
            Route::post('/process-ai-chunk', [InternalLinkController::class, 'processAiChunk'])->name('process_ai_chunk');
            Route::put('/{link}', [InternalLinkController::class, 'update'])->name('update');
        });

        // Midtrans Products & Shortcodes
        Route::prefix('products')->name('admin.products.')->group(function () {
            Route::get('/', [ProductController::class, 'index'])->name('index');
            Route::post('/', [ProductController::class, 'store'])->name('store');
            Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('edit');
            Route::put('/{product}', [ProductController::class, 'update'])->name('update');
            Route::delete('/{product}', [ProductController::class, 'destroy'])->name('destroy');
        });

        // Pre-Orders
        Route::get('/pre-orders', [App\Http\Controllers\Admin\PreOrderController::class, 'global'])->name('admin.pre-orders.global');
        Route::prefix('products/{product}/pre-orders')->name('admin.pre-orders.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\PreOrderController::class, 'index'])->name('index');
            Route::post('/launch', [App\Http\Controllers\Admin\PreOrderController::class, 'launch'])->name('launch');
        });

        // Product Categories
        Route::prefix('product-categories')->name('admin.product-categories.')->group(function () {
            Route::get('/', [ProductCategoryController::class, 'index'])->name('index');
            Route::post('/', [ProductCategoryController::class, 'store'])->name('store');
            Route::put('/{productCategory}', [ProductCategoryController::class, 'update'])->name('update');
            Route::delete('/{productCategory}', [ProductCategoryController::class, 'destroy'])->name('destroy');
        });

        // Media Library
        Route::prefix('media')->name('admin.media.')->group(function () {
            Route::get('/', [MediaController::class, 'index'])->name('index');
            Route::post('/', [MediaController::class, 'store'])->name('store');
            Route::post('/{id}', [MediaController::class, 'update'])->name('update');
            Route::delete('/', [MediaController::class, 'destroy'])->name('destroy');
        });

        // Content / AI Generator
        Route::prefix('content-campaigns')->name('admin.content-campaigns.')->group(function () {
            Route::get('/', [ContentCampaignController::class, 'index'])->name('index');
            Route::post('/', [ContentCampaignController::class, 'store'])->name('store');
            Route::post('/global-settings', [ContentCampaignController::class, 'updateGlobalSettings'])->name('global_settings');
            Route::get('/{campaign}/edit', [ContentCampaignController::class, 'edit'])->name('edit');
            Route::get('/{campaign}/topics', [ContentCampaignController::class, 'topics'])->name('topics');
            Route::get('/{campaign}/settings', [ContentCampaignController::class, 'settings'])->name('settings');
            Route::put('/{campaign}/settings', [ContentCampaignController::class, 'updateSettings'])->name('settings.update');
            Route::put('/{campaign}', [ContentCampaignController::class, 'update'])->name('update');
            Route::delete('/{campaign}', [ContentCampaignController::class, 'destroy'])->name('destroy');
            Route::post('/{campaign}/research', [ContentCampaignController::class, 'research'])->name('research');
            Route::post('/{campaign}/trigger', [ContentCampaignController::class, 'triggerPost'])->name('trigger');
            Route::post('/{campaign}/toggle', [ContentCampaignController::class, 'toggle'])->name('toggle');
        });

        Route::prefix('content')->name('admin.content.')->group(function () {
            Route::get('/', [ContentController::class, 'index'])->name('index');
            Route::get('/pipeline', [ContentController::class, 'pipeline'])->name('pipeline');
            Route::get('/prapost', [ContentController::class, 'prapost'])->name('prapost');
            Route::get('/calendar', [ContentController::class, 'calendar'])->name('calendar');
            Route::get('/drafts', [ContentController::class, 'drafts'])->name('drafts');
            Route::post('/work-queue', [ContentController::class, 'workQueue'])->name('work_queue');
            Route::match(['get', 'post'], '/start-ai-batch', [ContentController::class, 'bulkGenerateAi'])->name('bulk_generate');
            Route::post('/generate-single', [ContentController::class, 'generateSingle'])->name('generate_single');
            Route::match(['get', 'post'], '/check-ai-connection', [ContentController::class, 'checkConnection'])->name('check_connection');
            Route::get('/create', [ContentController::class, 'create'])->name('create');
            Route::post('/', [ContentController::class, 'store'])->name('store');

            // Phase 4: Image Search & AI Illustration
            Route::get('/{content}/images', [ImageSearchController::class, 'index'])->name('images');
            Route::post('/{content}/images/search', [ImageSearchController::class, 'search'])->name('images.search');
            Route::post('/{content}/images/select', [ImageSearchController::class, 'select'])->name('images.select');
            Route::post('/{content}/images/ai-generate', [ImageSearchController::class, 'generateAiIllustration'])->name('images.ai-generate');

            Route::get('/trash', [ContentController::class, 'trash'])->name('trash');
            Route::post('/{content}/restore', [ContentController::class, 'restore'])->name('restore')->withTrashed();
            Route::delete('/{content}/force-delete', [ContentController::class, 'forceDelete'])->name('force_delete')->withTrashed();
            Route::get('/{content}', [ContentController::class, 'show'])->name('show');
            Route::get('/{content}/edit', [ContentController::class, 'edit'])->name('edit');
            Route::put('/{content}', [ContentController::class, 'update'])->name('update');
            Route::post('/{content}/generate', [ContentController::class, 'generateAi'])->name('generate');
            Route::post('/bulk-publish', [ContentController::class, 'bulkPublish'])->name('bulk_publish');
            Route::post('/indexnow-ping', [ContentController::class, 'indexNowPing'])->name('indexnow_ping');
            Route::delete('/{content}', [ContentController::class, 'destroy'])->name('destroy');
        });

        // WordPress Import / Export
        Route::prefix('wordpress')->name('admin.wordpress.')->group(function () {
            Route::get('/', [WordPressController::class, 'index'])->name('index');
            Route::post('/import', [WordPressController::class, 'import'])->name('import');
            Route::get('/export', [WordPressController::class, 'export'])->name('export');
        });

        // Billing & Invoices (Legacy / Tenant)
        Route::prefix('billing')->name('admin.billing.')->group(function () {
            Route::get('/', [BillingController::class, 'index'])->name('index');
            Route::post('/', [BillingController::class, 'store'])->name('store');
            Route::post('/invoice/{invoice}/verify', [BillingController::class, 'verify'])->name('verify');
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
            Route::get('/', [OrderManagementController::class, 'index'])->name('index');
            Route::get('/{order}', [OrderManagementController::class, 'show'])->name('show');
            Route::post('/{order}/verify', [OrderManagementController::class, 'verify'])->name('verify');
            Route::post('/{order}/reject', [OrderManagementController::class, 'reject'])->name('reject');
        });

        // Support Tickets (Super Admin)
        Route::prefix('tickets')->name('admin.tickets.')->group(function () {
            Route::get('/', [TicketManagementController::class, 'index'])->name('index');
            Route::get('/{ticket}', [TicketManagementController::class, 'show'])->name('show');
            Route::post('/{ticket}/reply', [TicketManagementController::class, 'reply'])->name('reply');
            Route::post('/{ticket}/status', [TicketManagementController::class, 'updateStatus'])->name('status');
        });

        // Contact Inquiries (Super Admin)
        Route::prefix('inquiries')->name('admin.inquiries.')->group(function () {
            Route::get('/', [InquiryController::class, 'index'])->name('index');
            Route::post('/bulk', [InquiryController::class, 'bulk'])->name('bulk');
            Route::get('/{inquiry}', [InquiryController::class, 'show'])->name('show');
            Route::post('/{inquiry}/replied', [InquiryController::class, 'markReplied'])->name('replied');
            Route::post('/{inquiry}/whatsapp', [InquiryController::class, 'sendWhatsapp'])->name('whatsapp');
            Route::post('/{inquiry}/toggle-spam', [InquiryController::class, 'toggleSpam'])->name('toggle_spam');
            Route::delete('/{inquiry}', [InquiryController::class, 'destroy'])->name('destroy');
        });

        // Static Pages & Page Builder
        Route::prefix('pages')->name('admin.pages.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\PageController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\Admin\PageController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\Admin\PageController::class, 'store'])->name('store');
            Route::get('/{page}/builder', [App\Http\Controllers\Admin\PageController::class, 'builder'])->name('builder');
            Route::get('/{page}/edit', [App\Http\Controllers\Admin\PageController::class, 'edit'])->name('edit');
            Route::put('/{page}', [App\Http\Controllers\Admin\PageController::class, 'update'])->name('update');
            Route::post('/{page}/builder/save', [App\Http\Controllers\Admin\PageController::class, 'saveBuilder'])->name('builder.save');
            Route::post('/{page}/set-home', [App\Http\Controllers\Admin\PageController::class, 'setHomepage'])->name('set_home');
        });

        // Menus Management
        Route::prefix('menus')->name('admin.menus.')->group(function () {
            Route::get('/', [MenuController::class, 'index'])->name('index');
            Route::post('/{menu}/items', [MenuController::class, 'storeItems'])->name('items.store');
        });

        // Infrastructure Settings (Queue, Cache, Redis)
        Route::prefix('infrastructure')->name('admin.infrastructure.')->group(function () {
            Route::get('/', [InfrastructureController::class, 'index'])->name('index');
            Route::post('/update-queue', [InfrastructureController::class, 'updateQueue'])->name('update-queue');
            Route::post('/update-cache', [InfrastructureController::class, 'updateCache'])->name('update-cache');
            Route::post('/restart-queue', [InfrastructureController::class, 'restartQueue'])->name('restart-queue');
            Route::post('/queue-status', [InfrastructureController::class, 'queueStatus'])->name('queue-status');
            Route::post('/worker/start', [InfrastructureController::class, 'startQueueWorker'])->name('worker.start');
            Route::post('/worker/stop', [InfrastructureController::class, 'stopQueueWorker'])->name('worker.stop');
            Route::post('/worker/restart', [InfrastructureController::class, 'restartQueueWorker'])->name('worker.restart');
        });

        // System Settings & Cache
        Route::prefix('settings')->name('admin.settings.')->group(function () {
            // Super Admin Global Settings (Tabbed)
            Route::get('/', [SystemSettingController::class, 'index'])->name('index');
            Route::post('/', [SystemSettingController::class, 'update'])->name('update');
            Route::post('/clear-cache', [SystemSettingController::class, 'clearCache'])->name('clear_cache');
            Route::post('/ai/sync-models', [SystemSettingController::class, 'syncModels'])->name('ai.sync_models');
        });

        // Enterprise SEO Settings
        Route::prefix('seo/settings')->name('admin.seo.settings.')->group(function () {
            Route::get('/', [SeoSettingController::class, 'index'])->name('index');
            Route::post('/clean-garbage', [SeoSettingController::class, 'cleanGarbage'])->name('clean_garbage');
            // We use SystemSettingController@update for the form submission
            Route::post('/', [SystemSettingController::class, 'update'])->name('update');
        });

        // Redirect Manager
        Route::prefix('redirects')->name('admin.redirects.')->group(function () {
            Route::get('/', [RedirectController::class, 'index'])->name('index');
            Route::get('/create', [RedirectController::class, 'create'])->name('create');
            Route::post('/', [RedirectController::class, 'store'])->name('store');
            Route::get('/{redirect}/edit', [RedirectController::class, 'edit'])->name('edit');
            Route::put('/{redirect}', [RedirectController::class, 'update'])->name('update');
            Route::delete('/{redirect}', [RedirectController::class, 'destroy'])->name('destroy');
        });

        // 404 Error Tracker
        Route::prefix('errors')->name('admin.errors.')->group(function () {
            Route::get('/', [PageErrorController::class, 'index'])->name('index');
            Route::get('/export', [PageErrorController::class, 'export'])->name('export');
            Route::get('/ip/{ip}', [PageErrorController::class, 'ipDetails'])->where('ip', '[0-9a-fA-F:.]+')->name('ip');
            Route::post('/clear-all', [PageErrorController::class, 'clearAll'])->name('clear_all');
            Route::post('/{pageError}/redirect', [PageErrorController::class, 'createRedirect'])->name('create_redirect');
            Route::delete('/{pageError}', [PageErrorController::class, 'destroy'])->name('destroy');
        });

        // Broken Link Checker
        Route::prefix('broken-links')->name('admin.broken-links.')->group(function () {
            Route::get('/', [BrokenLinkController::class, 'index'])->name('index');
            Route::post('/scan', [BrokenLinkController::class, 'scan'])->name('scan');
            Route::delete('/clear-all', [BrokenLinkController::class, 'clearAll'])->name('clear_all');
            Route::delete('/{brokenLink}', [BrokenLinkController::class, 'destroy'])->name('destroy');
        });

        // Content Duplication Detector
        Route::prefix('duplicates')->name('admin.duplicates.')->group(function () {
            Route::get('/', [DuplicateContentController::class, 'index'])->name('index');
            Route::post('/detect', [DuplicateContentController::class, 'detect'])->name('detect');
            Route::post('/{mapping}/resolve', [DuplicateContentController::class, 'resolve'])->name('resolve');
            Route::post('/{mapping}/unresolve', [DuplicateContentController::class, 'unresolve'])->name('unresolve');
        });

        // Readability Dashboard
        Route::prefix('readability')->name('admin.readability.')->group(function () {
            Route::get('/', [ReadabilityController::class, 'index'])->name('index');
            Route::post('/compute', [ReadabilityController::class, 'compute'])->name('compute');
        });

        Route::prefix('url-audit')->name('admin.url-audit.')->group(function () {
            Route::get('/', [UrlAuditController::class, 'index'])->name('index');
            Route::post('/run', [UrlAuditController::class, 'run'])->name('run');
        });

        Route::prefix('serp-rank')->name('admin.serp-rank.')->group(function () {
            Route::get('/', [SerpRankController::class, 'index'])->name('index');
            Route::post('/sample', [SerpRankController::class, 'generateSample'])->name('sample');
        });

        // Competitor Analysis
        Route::prefix('competitor-analysis')->name('admin.competitor-analysis.')->controller(CompetitorAnalysisController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::get('/{analysis}', 'show')->name('show');
            Route::delete('/{analysis}', 'destroy')->name('destroy');
        });
    });
});

// Admin Auth Google OAuth
Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');

// Buyer Portal Routes
Route::prefix('buyer')->name('buyer.')->group(function () {
    // Public — belum login (Already moved to root /login & /register)

    // Protected — sudah login sebagai buyer
    Route::middleware(['auth:buyer', 'verified'])->group(function () {
        Route::post('/logout', [BuyerAuthController::class, 'logout'])->name('logout')->withoutMiddleware('verified');

        // Email Verification Routes
        Route::get('/email/verify', [BuyerVerificationController::class, 'show'])
            ->withoutMiddleware('verified')
            ->name('verification.notice');

        Route::get('/email/verify/{id}/{hash}', [BuyerVerificationController::class, 'verify'])
            ->middleware(['signed'])
            ->withoutMiddleware('verified')
            ->name('verification.verify');

        Route::post('/email/verification-notification', [BuyerVerificationController::class, 'send'])
            ->middleware(['throttle:6,1'])
            ->withoutMiddleware('verified')
            ->name('verification.send');

        Route::get('/dashboard', [BuyerDashboardController::class, 'index'])->name('dashboard');
        Route::get('/orders', [BuyerOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [BuyerOrderController::class, 'show'])->name('orders.show');
        Route::post('/orders/{order}/upload-proof', [BuyerOrderController::class, 'uploadProof'])->name('orders.upload_proof');
        Route::get('/products', [BuyerProductController::class, 'index'])->name('products.index');
        Route::get('/products/{access}', [BuyerProductController::class, 'access'])->name('products.access');

        // Support Tickets
        Route::get('/tickets', [BuyerTicketController::class, 'index'])->name('tickets.index');
        Route::get('/tickets/create', [BuyerTicketController::class, 'create'])->name('tickets.create');
        Route::post('/tickets', [BuyerTicketController::class, 'store'])->middleware('throttle:5,3')->name('tickets.store');
        Route::get('/tickets/{ticket}', [BuyerTicketController::class, 'show'])->name('tickets.show');
        Route::post('/tickets/{ticket}/reply', [BuyerTicketController::class, 'reply'])->middleware('throttle:10,3')->name('tickets.reply');
    });
});
// Contact / Inquiry
Route::get('/contact', [ContactController::class, 'show'])->name('contact.show');
Route::get('/contact-us', function () {
    return redirect()->route('contact.show');
});
Route::post('/contact', [ContactController::class, 'submit'])->middleware('throttle:3,5')->name('contact.submit');

// WhatsApp Lead Intake (Home: contact form, live chat, enterprise package)
Route::post('/lead', [WhatsAppLeadController::class, 'store'])->middleware('throttle:5,10')->name('lead.store');

// Global Search
Route::get('/search', [SearchController::class, 'index'])->name('search');

// English Locale (EN) — Home & Pages
Route::get('/en', [PageController::class, 'home'])->name('en.home');
Route::get('/en/{slug}', [PageController::class, 'show'])->where('slug', '.*')->name('en.page.show');

// Catch-all — ID pages (must be last)
Route::get('/{slug}', [PageController::class, 'show'])->where('slug', '.*')->name('page.show');
