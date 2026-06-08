<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

$columns = Schema::getColumnListing('users');
$migrations = DB::table('migrations')->get();

$data = [
    'users_columns' => $columns,
    'migrations_run' => $migrations->toArray(),
];

file_put_contents('scratch/db_check.json', json_encode($data, JSON_PRETTY_PRINT));
