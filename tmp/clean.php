<?php

$content = file_get_contents('d:/laragon/www/web-presensi-2026/resources/views/filament/pages/attendance-scanner.blade.php');

// Replace open-register-btn with link
$content = str_replace(
    '<button id="open-register-btn"',
    '<a href="{{ \App\Filament\Pages\FaceRegistration::getUrl() }}"',
    $content
);
$content = str_replace(
    "Daftarkan Wajah Saya\n                        </button>",
    "Daftarkan Wajah Saya\n                        </a>",
    $content
);

// Replace open-register-from-no-photo-btn with link
$content = str_replace(
    '<button id="open-register-from-no-photo-btn"',
    '<a href="{{ \App\Filament\Pages\FaceRegistration::getUrl() }}"',
    $content
);
$content = str_replace(
    "Daftarkan Wajah Sekarang\n                        </button>",
    "Daftarkan Wajah Sekarang\n                        </a>",
    $content
);

// Remove registered-state
$content = preg_replace('/<div id="registered-state" style="display:none;[^>]+>.*?<\/div>\s*<\/div>\s*<!-- Status badge -->/s', '<!-- Status badge -->', $content);

// In JS, remove register variables
$content = preg_replace('/\/\/ Register Panel.*?const progressLine2\s*=\s*document\.getElementById\(\'progress-line-2\'\);/s', '', $content);

file_put_contents('d:/laragon/www/web-presensi-2026/scratch_check.php', $content);
