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

    if (!$user->photo) {
        return response()->json([
            'success' => false,
            'message' => 'Foto profil belum diatur. Silakan upload foto profil terlebih dahulu.',
        ], 404);
    }

    $photoUrl = str_starts_with($user->photo, 'http')
        ? $user->photo
        : \Illuminate\Support\Facades\Storage::disk('public')->url($user->photo);

    return response()->json([
        'success' => true,
        'photo_url' => $photoUrl,
        'user_name' => $user->name,
    ]);
})->name('face-reference');
