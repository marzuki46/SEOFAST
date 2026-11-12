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
            'queueWorker' => $this->queueWorkerStatus(),
        ]);
    }

    public function startQueueWorker(): RedirectResponse
    {
        if (PHP_OS_FAMILY === 'Windows') {
            return redirect()->back()->with('error', 'Worker permanen dari dashboard hanya didukung di server Linux.');
        }

        if (! function_exists('exec')) {
            return redirect()->back()->with('error', 'PHP exec() dinonaktifkan di server. Aktifkan exec atau gunakan Supervisor/Horizon.');
        }

        if ($this->queueWorkerStatus()['running']) {
            return redirect()->back()->with('info', 'Queue worker sudah berjalan.');
        }

        $logPath = storage_path('logs/queue-worker.log');
        $pidPath = $this->queueWorkerPidPath();
        $command = implode(' ', array_map('escapeshellarg', [
            PHP_BINARY,
            base_path('artisan'),
            'queue:work',
            (string) config('queue.default'),
            '--queue=ai,default',
            '--sleep=1',
            '--tries=2',
            '--timeout=300',
        ]));
        $redirected = 'nohup '.$command.' >> '.escapeshellarg($logPath).' 2>&1 < /dev/null & echo $!';
        $output = [];
        exec($redirected, $output, $exitCode);
        $pid = (int) ($output[0] ?? 0);
        if ($exitCode !== 0 || $pid < 1) {
            return redirect()->back()->with('error', 'Queue worker gagal dijalankan. Periksa permission storage dan log aplikasi.');
        }

        file_put_contents($pidPath, (string) $pid, LOCK_EX);

        return redirect()->back()->with('success', 'Queue worker permanen berhasil dijalankan.');
    }

    public function stopQueueWorker(): RedirectResponse
    {
        $worker = $this->queueWorkerStatus();
        if (! $worker['running'] || ! $worker['pid']) {
            $this->clearQueueWorkerPid();

            return redirect()->back()->with('info', 'Queue worker tidak sedang berjalan.');
        }

        $this->terminateQueueWorker((int) $worker['pid']);
        $this->clearQueueWorkerPid();

        return redirect()->back()->with('success', 'Perintah penghentian queue worker telah dikirim.');
    }

    public function restartQueueWorker(): RedirectResponse
    {
        $worker = $this->queueWorkerStatus();
        if ($worker['running'] && $worker['pid']) {
            $this->terminateQueueWorker((int) $worker['pid']);
            $this->clearQueueWorkerPid();
            usleep(250000);
        }

        return $this->startQueueWorker();
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

    /** @return array{running: bool, pid: int|null} */
    private function queueWorkerStatus(): array
    {
        $pidPath = $this->queueWorkerPidPath();
        $pid = is_file($pidPath) ? (int) trim((string) file_get_contents($pidPath)) : 0;
        if ($pid < 1) {
            return ['running' => false, 'pid' => null];
        }

        if (! $this->isQueueWorkerProcess($pid)) {
            $this->clearQueueWorkerPid();

            return ['running' => false, 'pid' => null];
        }

        $running = false;
        if (PHP_OS_FAMILY !== 'Windows' && function_exists('posix_kill')) {
            $running = @posix_kill($pid, 0);
        } elseif (PHP_OS_FAMILY !== 'Windows' && function_exists('exec')) {
            exec('kill -0 '.((int) $pid).' 2>/dev/null', $output, $exitCode);
            $running = $exitCode === 0;
        }

        if (! $running) {
            $this->clearQueueWorkerPid();
        }

        return ['running' => $running, 'pid' => $running ? $pid : null];
    }

    private function queueWorkerPidPath(): string
    {
        return storage_path('app/queue-worker.pid');
    }

    private function clearQueueWorkerPid(): void
    {
        $path = $this->queueWorkerPidPath();
        if (is_file($path)) {
            @unlink($path);
        }
    }

    private function terminateQueueWorker(int $pid): void
    {
        if (function_exists('posix_kill')) {
            @posix_kill($pid, defined('SIGTERM') ? SIGTERM : 15);

            return;
        }

        if (function_exists('exec')) {
            exec('kill '.((int) $pid).' 2>/dev/null');
        }
    }

    private function isQueueWorkerProcess(int $pid): bool
    {
        if (PHP_OS_FAMILY === 'Windows' || ! function_exists('exec')) {
            return false;
        }

        $output = [];
        exec('ps -p '.((int) $pid).' -o args= 2>/dev/null', $output, $exitCode);

        return $exitCode === 0 && str_contains(implode(' ', $output), 'artisan queue:work');
    }
}
