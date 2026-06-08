<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin');
});

Route::get('/admin/download-qr/{company}', function (\App\Models\Company $company) {
    if (!auth()->check() || !auth()->user()->isAdmin()) {
        abort(403);
    }
    return view('admin.company-qr', [
        'company' => $company,
        'type' => request()->query('type')
    ]);
})->name('admin.download-qr');

Route::get('/admin/download-qr-image', function (\Illuminate\Http\Request $request) {
    if (!auth()->check() || !auth()->user()->isAdmin()) {
        abort(403);
    }
    
    $code = $request->query('code');
    $type = $request->query('type', 'check-in');
    $companyName = $request->query('company', 'Company');
    
    $url = "https://api.qrserver.com/v1/create-qr-code/?size=1000x1000&data=" . urlencode($code);
    $filename = str()->slug($companyName) . "-" . $type . ".png";
    
    try {
        $response = \Illuminate\Support\Facades\Http::get($url);
        
        if ($response->successful()) {
            return response($response->body())
                ->header('Content-Type', 'image/png')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
        }
        
        return back()->with('error', 'Gagal mengunduh QR Code dari server.');
    } catch (\Exception $e) {
        return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
})->name('admin.download-qr-image');

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
