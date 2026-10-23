<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

// Pastikan storage menggunakan folder /tmp bawaan Vercel
$app->useStoragePath('/tmp/storage');

if (!file_exists('/tmp/storage')) {
    mkdir('/tmp/storage/framework/views', 0755, true);
    mkdir('/tmp/storage/framework/sessions', 0755, true);
    mkdir('/tmp/storage/framework/cache', 0755, true);
    mkdir('/tmp/storage/logs', 0755, true);
}

$request = Illuminate\Http\Request::capture();
$response = $app->handleRequest($request);
$response->send();