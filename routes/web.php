<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin');
});




Route::get('/mobile-guide', function () {
    return view('mobile-guide');
})->name('mobile-guide');

Route::get('/dev/migrate', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        return response()->json([
            'success' => true,
            'output' => \Illuminate\Support\Facades\Artisan::output()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
});

Route::get('/dev/storage-link', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('storage:link');
        return response()->json([
            'success' => true,
            'output' => \Illuminate\Support\Facades\Artisan::output()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
});

Route::get('/dev/clear', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('optimize:clear');
        return response()->json([
            'success' => true,
            'output' => \Illuminate\Support\Facades\Artisan::output()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
});

Route::get('/waiting-approval', function () {
    $user = auth()->user();
    
    // Redirect if not logged in
    if (!$user) {
        return redirect()->route('filament.admin.auth.login');
    }
    
    // Redirect to admin if already approved or is admin
    if ($user->approval_status === 'approved' || $user->isAdmin()) {
        return redirect('/admin');
    }
    
    return view('waiting-approval');
})->name('waiting-approval');

Route::post('/waiting-approval/logout', function (\Illuminate\Http\Request $request) {
    auth()->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('filament.admin.auth.login');
})->name('waiting-approval.logout');

/**
 * API endpoint: returns the authenticated user's profile photo URL
 * used by face-api.js as the reference face descriptor.
 */
Route::get('/face-reference', function () {
    if (!auth()->check()) {
        return response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401);
    }

    $user = auth()->user();

    // Check if user has face photos registered (3-pose) or legacy single photo
    $hasFacePhotos = $user->face_photo_front && $user->face_photo_right && $user->face_photo_left;
    $hasLegacyPhoto = $user->photo;

    if (!$hasFacePhotos && !$hasLegacyPhoto) {
        return response()->json([
            'success' => false,
            'message' => 'Foto profil belum diatur. Silakan daftarkan wajah terlebih dahulu.',
        ], 404);
    }

    $getUrl = function ($path) {
        if (!$path) return null;
        if (str_starts_with($path, 'http')) return $path;
        
        // Check if file exists in the face-photos disk first
        if (\Illuminate\Support\Facades\Storage::disk('face-photos')->exists($path)) {
            return \Illuminate\Support\Facades\Storage::disk('face-photos')->url($path);
        }
        
        // Fallback: check public disk (for legacy photos with profile-photos/ prefix)
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
            return \Illuminate\Support\Facades\Storage::disk('public')->url($path);
        }
        
        // Last resort: try face-photos disk URL anyway
        return \Illuminate\Support\Facades\Storage::disk('face-photos')->url($path);
    };

    // If 3-pose photos exist, return all of them
    if ($hasFacePhotos) {
        return response()->json([
            'success' => true,
            'multi_pose' => true,
            'photos' => [
                'front' => $getUrl($user->face_photo_front),
                'right' => $getUrl($user->face_photo_right),
                'left'  => $getUrl($user->face_photo_left),
            ],
            'photo_url' => $getUrl($user->face_photo_front), // backward compat
            'user_name' => $user->name,
        ]);
    }

    // Fallback: legacy single photo
    return response()->json([
        'success' => true,
        'multi_pose' => false,
        'photo_url' => $getUrl($user->photo),
        'user_name' => $user->name,
    ]);
})->name('face-reference');
