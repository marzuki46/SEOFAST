<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

try {
    \Illuminate\Support\Facades\Artisan::call('route:clear');
    \Illuminate\Support\Facades\Artisan::call('view:clear');
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    echo "<h1>CACHE BERHASIL DIBERSIHKAN!</h1>";
    echo "<p>Silakan tutup tab ini dan ulangi proses Generate Konten.</p>";
} catch (\Exception $e) {
    echo "<h1>GAGAL:</h1>";
    echo "<p>" . $e->getMessage() . "</p>";
}
