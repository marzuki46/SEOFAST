<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\View\View;

class InfrastructureController extends Controller
{
    public function index(): View
    {
        $redisInstalled = extension_loaded('redis');
        $redisRunning = false;
        if ($redisInstalled) {
            try {
                $redis = new \Redis();
                $redis->connect(
                    config('database.redis.default.host'),
                    config('database.redis.default.port'),
                    2
                );
                $redisRunning = true;
            } catch (\Exception) {
                $redisRunning = false;
            }
        }

        $envPath = base_path('.env');
        $envContents = file_exists($envPath) ? file_get_contents($envPath) : '';

        return view('admin.infrastructure', [
            'queueDriver'  => config('queue.default'),
            'cacheDriver'  => config('cache.default'),
            'sessionDriver' => config('session.driver'),
            'redisInstalled' => $redisInstalled,
            'redisRunning'   => $redisRunning,
            'horizonInstalled' => class_exists(\Laravel\Horizon\Horizon::class),
            'envContents' => $envContents,
        ]);
    }

    public function updateQueue(Request $request): RedirectResponse
    {
        $request->validate(['driver' => 'required|in:database,redis']);

        $this->updateEnv('QUEUE_CONNECTION', $request->driver);

        return redirect()->back()->with('success', "Queue driver diubah ke {$request->driver}. " . ($request->driver === 'redis' ? 'Jangan lupa restart queue!' : ''));
    }

    public function updateCache(Request $request): RedirectResponse
    {
        $request->validate(['driver' => 'required|in:file,redis']);

        $this->updateEnv('CACHE_DRIVER', $request->driver);

        return redirect()->back()->with('success', "Cache driver diubah ke {$request->driver}.");
    }

    public function restartQueue(): RedirectResponse
    {
        if (PHP_OS_FAMILY === 'Windows') {
            return redirect()->back()->with('error', 'Restart queue hanya bisa dijalankan di server Linux (production).');
        }

        try {
            Artisan::call('horizon:terminate');
            return redirect()->back()->with('success', 'Perintah restart queue sudah dikirim. Horizon akan restart dalam beberapa detik.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal restart queue: ' . $e->getMessage());
        }
    }

    public function queueStatus(): RedirectResponse
    {
        try {
            Artisan::call('horizon:status');
            $output = Artisan::output();
            return redirect()->back()->with('info', 'Status: ' . trim($output));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Horizon tidak tersedia: ' . $e->getMessage());
        }
    }

    private function updateEnv(string $key, string $value): void
    {
        $envPath = base_path('.env');
        if (!file_exists($envPath)) {
            return;
        }

        $contents = file_get_contents($envPath);

        $pattern = '/^' . preg_quote($key, '/') . '=.*$/m';
        $replacement = $key . '=' . $value;

        if (preg_match($pattern, $contents)) {
            $contents = preg_replace($pattern, $replacement, $contents);
        } else {
            $contents .= "\n" . $replacement;
        }

        file_put_contents($envPath, $contents);
    }
}
