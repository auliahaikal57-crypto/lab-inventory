<?php

// Force Debug Mode & Environment
$_ENV['APP_ENV'] = 'production';
$_ENV['APP_DEBUG'] = 'true';

// 1. Muat Composer Autoloader
require __DIR__ . '/../vendor/autoload.php';

// 2. Inisialisasi Aplikasi
$app = require_once __DIR__ . '/../bootstrap/app.php';

// 3. Set Storage Path ke /tmp bawaan Vercel
$app->useStoragePath('/tmp/storage');

if (!file_exists('/tmp/storage')) {
    mkdir('/tmp/storage/framework/views', 0755, true);
    mkdir('/tmp/storage/framework/sessions', 0755, true);
    mkdir('/tmp/storage/framework/cache', 0755, true);
    mkdir('/tmp/storage/logs', 0755, true);
}

// 4. Handle Request
$request = Illuminate\Http\Request::capture();
$response = $app->handleRequest($request);
$response->send();