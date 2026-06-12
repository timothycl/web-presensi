<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Artisan;

try {
    if (file_exists(__DIR__ . '/storage')) {
        if (is_link(__DIR__ . '/storage')) {
            unlink(__DIR__ . '/storage');
        } else {
            // Might be a directory or junction, use rmdir
            @rmdir(__DIR__ . '/storage');
        }
    }
    
    $exitCode = Artisan::call('storage:link');
    $output = Artisan::output();

    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'url' => \Illuminate\Support\Facades\Storage::disk('public')->url('attendance/photos/check_in_85_1781233378.jpg'),
        'exit_code' => $exitCode,
        'output' => $output,
    ], JSON_PRETTY_PRINT);
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
