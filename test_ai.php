<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$srv = new App\Services\AIService(null, 'default');
$sys = "You are an Expert SEO Strategist.";
$usr = "Topic: Pengaruh backlink dari situs pemerintah (.go.id) pada SEO";
$res = $srv->generate($sys, $usr);
var_dump($res);

$diag = $srv->getLastDiagnostics();
print_r($diag);
