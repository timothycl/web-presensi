<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Artisan;

try {
    $exitCode = Artisan::call('migrate', ['--force' => true]);
    $output = Artisan::output();

    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'exit_code' => $exitCode,
        'output' => $output,
    ], JSON_PRETTY_PRINT);
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
