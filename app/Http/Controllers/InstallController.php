<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class InstallController extends Controller
{
    public function welcome()
    {
        if (file_exists(storage_path('installed'))) {
            return redirect()->route('home');
        }

        $requirements = $this->checkRequirements();
        $hasErrors = collect($requirements)->contains(fn($r) => !$r['ok']);

        return view('install.wizard', compact('requirements', 'hasErrors'));
    }

    public function admin()
    {
        if (file_exists(storage_path('installed'))) {
            return redirect()->route('home');
        }
        return view('install.admin');
    }

    public function saveAdmin(Request $request)
    {
        $data = $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'nullable|string|max:500',
            'admin_email' => 'required|email|max:255',
            'admin_password' => 'required|string|min:8|confirmed',
            'app_url' => 'required|url|max:255',
        ]);

        $request->session()->put('install.admin', $data);

        return redirect()->route('install.database');
    }

    public function database()
    {
        if (file_exists(storage_path('installed'))) {
            return redirect()->route('home');
        }
        if (!session()->has('install.admin')) {
            return redirect()->route('install.welcome');
        }
        return view('install.database');
    }

    public function saveDb(Request $request)
    {
        $data = $request->validate([
            'db_host' => 'required|string|max:255',
            'db_port' => 'required|string|max:10',
            'db_database' => 'required|string|max:255',
            'db_username' => 'required|string|max:255',
            'db_password' => 'nullable|string|max:255',
        ]);

        $request->session()->put('install.db', $data);

        $test = $this->testDbConnection(
            $data['db_host'], $data['db_port'],
            $data['db_database'], $data['db_username'], $data['db_password']
        );

        if (!$test['ok']) {
            return back()->withErrors(['db_connection' => $test['error']])->withInput();
        }

        return redirect()->route('install.run');
    }

    public function run()
    {
        if (file_exists(storage_path('installed'))) {
            return redirect()->route('home');
        }

        $db = session('install.db');
        $admin = session('install.admin');

        if (!$db || !$admin) {
            return redirect()->route('install.welcome');
        }

        set_time_limit(120);
        ini_set('max_execution_time', 120);
        $output = [];

        try {
            $this->updateEnv('APP_URL', $admin['app_url']);
            $output[] = 'APP_URL updated';

            $this->updateEnv('APP_ENV', 'production');
            $output[] = 'APP_ENV set to production';

            $this->updateEnv('APP_DEBUG', 'false');
            $output[] = 'APP_DEBUG disabled';

            $this->updateEnv('DB_HOST', $db['db_host']);
            $this->updateEnv('DB_PORT', $db['db_port']);
            $this->updateEnv('DB_DATABASE', $db['db_database']);
            $this->updateEnv('DB_USERNAME', $db['db_username']);
            $this->updateEnv('DB_PASSWORD', $db['db_password'] ?? '');
            $output[] = 'Database credentials saved';

            Artisan::call('key:generate', ['--force' => true]);
            $output[] = 'App key generated: ' . trim(Artisan::output());

            Artisan::call('migrate', ['--force' => true]);
            $output[] = 'Migrations ran: ' . trim(Artisan::output());

            $user = \App\Models\User::create([
                'name' => 'Admin',
                'email' => $admin['admin_email'],
                'password' => Hash::make($admin['admin_password']),
            ]);
            $user->assignRole('super-admin');
            $output[] = 'Admin user created: ' . $admin['admin_email'];

            Artisan::call('db:seed', ['--force' => true]);
            $output[] = 'Database seeded: ' . trim(Artisan::output());

            Artisan::call('storage:link');
            $output[] = 'Storage linked';

            \App\Models\SystemSetting::set('site_name', $admin['site_name']);
            if ($admin['site_description']) {
                \App\Models\SystemSetting::set('seo_global_meta_description', $admin['site_description']);
            }
            $output[] = 'Site settings saved';

            file_put_contents(storage_path('installed'), date('Y-m-d H:i:s'));
            $output[] = 'Installation lock created';

            $creds = [
                'site_name' => $admin['site_name'],
                'app_url' => $admin['app_url'],
                'admin_email' => $admin['admin_email'],
                'admin_password' => $admin['admin_password'],
                'db_host' => $db['db_host'],
                'db_port' => $db['db_port'],
                'db_database' => $db['db_database'],
                'db_username' => $db['db_username'],
                'db_password' => $db['db_password'] ?? '',
            ];

            session()->forget(['install.db', 'install.admin']);

            return redirect()->route('install.complete')
                ->with('output', $output)
                ->with('credentials', $creds);
        } catch (\Exception $e) {
            $output[] = 'ERROR: ' . $e->getMessage();
            return view('install.result', ['output' => $output, 'error' => true]);
        }
    }

    public function complete()
    {
        if (!file_exists(storage_path('installed'))) {
            return redirect()->route('install.welcome');
        }
        return view('install.complete');
    }

    protected function updateEnv(string $key, string $value): void
    {
        $path = base_path('.env');
        if (!file_exists($path)) {
            file_put_contents($path, '');
        }
        $content = file_get_contents($path);

        if (str_contains($content, "{$key}=")) {
            $pattern = "/^{$key}=.*/m";
            $escaped = str_replace(['\\', '$'], ['\\\\', '\\$'], $value);
            $content = preg_replace($pattern, "{$key}={$escaped}", $content);
        } else {
            $content .= "\n{$key}={$value}\n";
        }

        file_put_contents($path, $content);
    }

    protected function testDbConnection(string $host, string $port, string $db, string $user, string $pass): array
    {
        $key = 'install_test_' . md5($host . $port . $db . $user);
        $config = config('database.connections.mysql');
        $config['host'] = $host;
        $config['port'] = $port;
        $config['database'] = $db;
        $config['username'] = $user;
        $config['password'] = $pass;

        try {
            $connection = \Illuminate\Support\Facades\DB::connectUsing($key, $config);
            $connection->getPdo();
            \Illuminate\Support\Facades\DB::purge($key);
            return ['ok' => true, 'error' => null];
        } catch (\Exception $e) {
            return ['ok' => false, 'error' => $e->getMessage()];
        }
    }

    protected function checkRequirements(): array
    {
        return [
            'php_version' => [
                'label' => 'PHP ' . phpversion(),
                'ok' => version_compare(phpversion(), '8.3.0', '>='),
                'note' => 'Minimal PHP 8.3',
            ],
            'pdo' => [
                'label' => 'PDO Extension',
                'ok' => extension_loaded('pdo'),
                'note' => 'Required for database',
            ],
            'pdo_mysql' => [
                'label' => 'MySQL PDO Extension',
                'ok' => extension_loaded('pdo_mysql'),
                'note' => 'Required for MySQL',
            ],
            'mbstring' => [
                'label' => 'MBString Extension',
                'ok' => extension_loaded('mbstring'),
                'note' => 'Required for string handling',
            ],
            'xml' => [
                'label' => 'XML Extension',
                'ok' => extension_loaded('xml'),
                'note' => 'Required for various features',
            ],
            'curl' => [
                'label' => 'cURL Extension',
                'ok' => extension_loaded('curl'),
                'note' => 'Required for HTTP requests',
            ],
            'gd' => [
                'label' => 'GD / Imagick Extension',
                'ok' => extension_loaded('gd') || extension_loaded('imagick'),
                'note' => 'Required for image processing',
            ],
            'bcmath' => [
                'label' => 'BCMath Extension',
                'ok' => extension_loaded('bcmath'),
                'note' => 'Required by various packages',
            ],
            'openssl' => [
                'label' => 'OpenSSL Extension',
                'ok' => extension_loaded('openssl'),
                'note' => 'Required for encryption',
            ],
            'fileinfo' => [
                'label' => 'FileInfo Extension',
                'ok' => extension_loaded('fileinfo'),
                'note' => 'Required for file uploads',
            ],
            'env_writable' => [
                'label' => '.env File Writable',
                'ok' => is_writable(base_path('.env')) || is_writable(base_path()),
                'note' => 'Required to save configuration',
            ],
            'storage_writable' => [
                'label' => 'Storage Directory Writable',
                'ok' => is_writable(storage_path()),
                'note' => 'Required for caching & logs',
            ],
        ];
    }
}
