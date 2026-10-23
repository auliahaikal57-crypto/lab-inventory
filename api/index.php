<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

// 1. Buat folder storage sementara di /tmp bawaan Vercel
$app->useStoragePath('/tmp/storage');

if (!file_exists('/tmp/storage')) {
    mkdir('/tmp/storage/framework/views', 0755, true);
    mkdir('/tmp/storage/framework/sessions', 0755, true);
    mkdir('/tmp/storage/framework/cache', 0755, true);
    mkdir('/tmp/storage/logs', 0755, true);
}

// 2. Buat file database SQLite sementara agar Laravel tidak crash
$dbPath = '/tmp/database.sqlite';
if (!file_exists($dbPath)) {
    touch($dbPath);
}

// 3. Force variabel database & cache ke SQLite / Array
$_ENV['DB_CONNECTION'] = 'sqlite';
$_ENV['DB_DATABASE'] = $dbPath;
$_ENV['SESSION_DRIVER'] = 'array';
$_ENV['CACHE_STORE'] = 'array';

// 4. Jalankan aplikasi
$request = Illuminate\Http\Request::capture();
$response = $app->handleRequest($request);
$response->send();