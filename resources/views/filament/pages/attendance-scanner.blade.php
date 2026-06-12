<x-filament-panels::page>
    <div style="max-width: 600px; margin: 0 auto; width: 100%; display: flex; flex-direction: column; gap: 2rem; font-family: 'Outfit', sans-serif;">
        {{-- Today's Attendance Status --}}
        <style>
            @media (max-width: 640px) {
                .responsive-grid { grid-template-columns: 1fr !important; gap: 1rem !important; }
                .scanner-footer { flex-direction: column !important; align-items: stretch !important; gap: 1.5rem !important; padding: 1.5rem !important; }
                .scanner-status { flex-direction: column !important; align-items: center !important; text-align: center; gap: 0.5rem !important; }
                .scanner-actions { flex-direction: column !important; width: 100% !important; gap: 0.75rem !important; }
                .scanner-btn { width: 100% !important; justify-content: center !important; }
            }
        </style>
        <div class="responsive-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <div style="background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 2rem; padding: 1.75rem; box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4); position: relative; overflow: hidden;" class="group">
                <div style="position: absolute; top: 0; left: 0; width: 4px; height: 100%; background: #f59e0b;"></div>
                <span style="color: #64748b; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.2em; display: block; margin-bottom: 0.5rem;">Jam Masuk</span>
                <span style="color: white; font-size: 1.875rem; font-weight: 900; font-style: italic; letter-spacing: -0.02em; display: block;">
                    {{ $attendance && $attendance->check_in_time ? \Illuminate\Support\Carbon::parse($attendance->check_in_time)->format('H:i') : '--:--' }}
                </span>
                @if($attendance && $attendance->status)
                    <div style="margin-top: 1rem; display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em;" @class([
                        'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' => $attendance->status === 'on_time',
                        'bg-amber-500/10 text-amber-400 border border-amber-500/20' => $attendance->status === 'late',
                    ])>
                        <div style="width: 6px; height: 6px; border-radius: 50%;" @class([
                            'bg-emerald-400 animate-pulse' => $attendance->status === 'on_time',
                            'bg-amber-400 animate-pulse' => $attendance->status === 'late',
                        ])></div>
                        {{ $attendance->status === 'on_time' ? 'Tepat Waktu' : 'Terlambat' }}
                    </div>
                @endif
            </div>

            <div style="background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 2rem; padding: 1.75rem; box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4); position: relative; overflow: hidden;" class="group">
                <div style="position: absolute; top: 0; left: 0; width: 4px; height: 100%; background: #3b82f6;"></div>
                <span style="color: #64748b; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.2em; display: block; margin-bottom: 0.5rem;">Jam Pulang</span>
                <span style="color: white; font-size: 1.875rem; font-weight: 900; font-style: italic; letter-spacing: -0.02em; display: block;">
                    {{ $attendance && $attendance->check_out_time ? \Illuminate\Support\Carbon::parse($attendance->check_out_time)->format('H:i') : '--:--' }}
                </span>
                @if($attendance && $attendance->check_out_time)
                    <div style="margin-top: 1rem; display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; background: rgba(59, 130, 246, 0.1); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.2);">
                        <div style="width: 6px; height: 6px; border-radius: 50%; background: #60a5fa; box-shadow: 0 0 10px #60a5fa;"></div>
                        Selesai
                    </div>
                @endif
            </div>
        </div>

        {{-- Main Scanner Section --}}
        <div style="background: rgba(15, 23, 42, 0.82); backdrop-filter: blur(30px); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 2.5rem; overflow: hidden; box-shadow: 0 40px 80px -20px rgba(0, 0, 0, 0.8);">
            
            {{-- STEP 1: Selfie Capture Wrapper --}}
            <div id="selfie-wrapper" class="hidden">
                <div style="width: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 3rem 1.5rem; gap: 2rem;">
                    <div style="display: flex; flex-direction: column; align-items: center; gap: 0.5rem; text-align: center;">
                        <span style="color: #f59e0b; font-size: 10px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.25em;">Tahap 1 dari 2</span>
                        <h3 style="color: white; font-size: 20px; font-weight: 900; text-transform: uppercase; font-style: italic; margin: 0;">Verifikasi Wajah (Selfie)</h3>
                    </div>

                    <div style="position: relative; width: 280px; height: 280px; border-radius: 50%; overflow: hidden; border: 4px solid #f59e0b; box-shadow: 0 0 30px rgba(245, 158, 11, 0.3); background: #000;">
                        <video id="selfie-video" style="width: 100%; height: 100%; object-fit: cover; transform: scaleX(-1);" autoplay playsinline muted></video>
                        <img id="selfie-preview" style="display: none; width: 100%; height: 100%; object-fit: cover; transform: scaleX(-1);" />
                        
                        <div id="selfie-overlay" style="position: absolute; inset: 0; pointer-events: none; border: 15px solid rgba(15,23,42,0.6); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <div style="width: 90%; height: 90%; border: 2px dashed rgba(245, 158, 11, 0.4); border-radius: 50%;"></div>
                        </div>

                        {{-- Loading Indicator --}}
                        <div id="selfie-loading" style="position: absolute; inset: 0; background: rgba(15,23,42,0.8); display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 0.75rem; border-radius: 50%;">
                            <svg style="width: 2.5rem; height: 2.5rem; color: #f59e0b; animation: spin 1s linear infinite;" fill="none" viewBox="0 0 24 24">
                                <circle style="opacity: 0.25;" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path style="opacity: 0.75;" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span style="color: #f59e0b; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.15em;">Memuat Kamera...</span>
                        </div>
                    </div>

                    {{-- Switch Camera Button (for selfie mode) --}}
                    <div id="selfie-camera-controls" style="display: none; justify-content: center;">
                        <button id="switch-selfie-btn" style="background: rgba(255,255,255,0.08); color: #94a3b8; border: 1px solid rgba(255,255,255,0.12); font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em; padding: 0.5rem 1.25rem; border-radius: 9999px; cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem; transition: all 0.3s;" title="Ganti Kamera">
                            <x-heroicon-m-arrow-path style="width: 0.875rem; height: 0.875rem;" />
                            <span id="switch-selfie-label">Ganti Kamera</span>
                        </button>
                    </div>

                    <p style="color: #64748b; font-size: 10px; text-transform: uppercase; font-weight: 800; letter-spacing: 0.15em; text-align: center; margin: 0;" id="selfie-hint">Posisikan wajah Anda di dalam lingkaran</p>
                    
                    <div style="display: flex; gap: 1rem; width: 100%; max-width: 320px; justify-content: center;">
                        <button id="capture-selfie-btn" style="width: 100%; background: linear-gradient(135deg, #f59e0b, #d97706); color: white; font-weight: 900; font-size: 12px; text-transform: uppercase; letter-spacing: 0.15em; padding: 1rem; border-radius: 1.25rem; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.5rem; box-shadow: 0 10px 20px -5px rgba(217, 119, 6, 0.4); transition: all 0.3s;">
                            <x-heroicon-m-camera style="width: 1.25rem; height: 1.25rem;" />
                            <span>Ambil Foto</span>
                        </button>
                        
                        <div id="confirm-selfie-actions" style="display: none; gap: 1rem; width: 100%;">
                            <button id="retake-selfie-btn" style="flex: 1; background: rgba(255, 255, 255, 0.05); color: #94a3b8; border: 1px solid rgba(255, 255, 255, 0.1); font-weight: 900; font-size: 11px; text-transform: uppercase; letter-spacing: 0.1em; padding: 1rem; border-radius: 1.25rem; cursor: pointer; transition: all 0.3s;">
                                Foto Ulang
                            </button>
                            <button id="confirm-selfie-btn" style="flex: 1; background: #10b981; color: white; font-weight: 900; font-size: 11px; text-transform: uppercase; letter-spacing: 0.15em; padding: 1rem; border-radius: 1.25rem; border: none; cursor: pointer; box-shadow: 0 10px 20px -5px rgba(16, 185, 129, 0.4); transition: all 0.3s; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                                <x-heroicon-m-check style="width: 1.25rem; height: 1.25rem;" />
                                <span>Gunakan</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- STEP 2: QR Scanner Wrapper --}}
            <div id="qr-wrapper">
                <div style="position: relative;">
                    <!-- Scanner Interface -->
                    <div id="reader" style="width: 100%; min-height: 300px; aspect-ratio: 1/1; background: #000; overflow: hidden; border-radius: 2.5rem;"></div>
                    
                    <!-- High-Tech Overlay -->
                    <div style="position: absolute; inset: 0; pointer-events: none; display: flex; flex-direction: column; align-items: center; justify-content: center; z-index: 20;">
                        <div style="width: 70%; aspect-ratio: 1/1; max-width: 280px; position: relative;">
                            {{-- Corner Brackets --}}
                            <div style="position: absolute; top: -10px; left: -10px; width: 40px; height: 40px; border-top: 4px solid #f59e0b; border-left: 4px solid #f59e0b; border-top-left-radius: 1.5rem;"></div>
                            <div style="position: absolute; top: -10px; right: -10px; width: 40px; height: 40px; border-top: 4px solid #f59e0b; border-right: 4px solid #f59e0b; border-top-right-radius: 1.5rem;"></div>
                            <div style="position: absolute; bottom: -10px; left: -10px; width: 40px; height: 40px; border-bottom: 4px solid #f59e0b; border-left: 4px solid #f59e0b; border-bottom-left-radius: 1.5rem;"></div>
                            <div style="position: absolute; bottom: -10px; right: -10px; width: 40px; height: 40px; border-bottom: 4px solid #f59e0b; border-right: 4px solid #f59e0b; border-bottom-right-radius: 1.5rem;"></div>
                            
                            {{-- Scanning Line Animation --}}
                            <div style="position: absolute; top: 0; left: 0; width: 100%; height: 2px; background: linear-gradient(to right, transparent, #f59e0b, transparent); box-shadow: 0 0 15px #f59e0b; animation: scanMove 3s infinite ease-in-out;"></div>
                        </div>
                        <div style="display: flex; flex-direction: column; align-items: center; gap: 0.5rem; margin-top: 2rem;">
                            <span style="color: #f59e0b; font-size: 10px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.25em;">Tahap 2 dari 2</span>
                            <p style="color: #f59e0b; font-size: 10px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.3em; opacity: 0.8; text-shadow: 0 0 10px rgba(245, 158, 11, 0.5); margin: 0;">Arahkan Kamera ke Barcode</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Scanner Footer -->
            <div class="scanner-footer" style="padding: 2rem; background: rgba(15, 23, 42, 0.9); border-top: 1px solid rgba(255, 255, 255, 0.05); display: flex; align-items: center; justify-content: space-between;">
                <div id="scanner-status" class="scanner-status" style="display: flex; align-items: center; gap: 1rem;">
                    <div style="width: 12px; height: 12px; background: #f59e0b; border-radius: 50%; box-shadow: 0 0 15px #f59e0b; position: relative;">
                        <div style="position: absolute; inset: -4px; border: 1px solid #f59e0b; border-radius: 50%; opacity: 0.5; animation: ping 2s infinite;"></div>
                    </div>
                    <div style="display: flex; flex-direction: column;">
                        <span style="color: #64748b; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em;">Scanner Status</span>
                        <span style="color: white; font-size: 13px; font-weight: 700;" id="scanner-status-text">Menunggu...</span>
                    </div>
                </div>

                <div class="scanner-actions" style="display: flex; gap: 1rem;">
                    <button 
                        id="start-button"
                        class="scanner-btn"
                        style="background: linear-gradient(135deg, #f59e0b, #d97706); color: white; font-weight: 900; font-size: 13px; text-transform: uppercase; letter-spacing: 0.1em; padding: 1rem 2rem; border-radius: 1.25rem; border: none; cursor: pointer; box-shadow: 0 15px 30px -5px rgba(217, 119, 6, 0.4); transition: all 0.3s;"
                        onmouseover="this.style.transform='translateY(-2px) scale(1.02)'; this.style.boxShadow='0 20px 35px -5px rgba(217, 119, 6, 0.5)';"
                        onmouseout="this.style.transform='translateY(0) scale(1)'; this.style.boxShadow='0 15px 30px -5px rgba(217, 119, 6, 0.4)';"
                    >
                        Mulai Presensi
                    </button>
                    
                    <div class="scanner-actions" style="display: flex; gap: 0.5rem;">
                        <button 
                            id="switch-button"
                            class="hidden scanner-btn"
                            style="background: rgba(255, 255, 255, 0.1); color: white; font-weight: 900; font-size: 11px; text-transform: uppercase; letter-spacing: 0.1em; padding: 1rem; border-radius: 1.25rem; border: 1px solid rgba(255, 255, 255, 0.1); cursor: pointer; transition: all 0.3s; display: none; align-items: center; justify-content: center;"
                            title="Ganti Kamera"
                        >
                            <x-heroicon-m-arrow-path style="width: 1.25rem; height: 1.25rem;" />
                        </button>

                        <button 
                            id="stop-button"
                            class="hidden scanner-btn"
                            style="background: rgba(239, 68, 68, 0.1); color: #f87171; font-weight: 900; font-size: 13px; text-transform: uppercase; letter-spacing: 0.1em; padding: 1rem 2rem; border-radius: 1.25rem; border: 1px solid rgba(239, 68, 68, 0.2); cursor: pointer; transition: all 0.3s; display: none; align-items: center; justify-content: center;"
                        >
                            Berhenti
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Grid -->
        <div class="responsive-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <div style="background: rgba(15, 23, 42, 0.4); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.05); padding: 1.5rem; border-radius: 2rem; display: flex; gap: 1.25rem; align-items: center;">
                <div style="width: 3rem; height: 3rem; background: rgba(245, 158, 11, 0.1); border-radius: 1rem; display: flex; align-items: center; justify-content: center; shrink: 0;">
                    <x-heroicon-o-information-circle style="width: 1.5rem; height: 1.5rem; color: #f59e0b;" />
                </div>
                <div>
                    <h4 style="color: white; font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 0.25rem;">Panduan</h4>
                    <p style="color: #64748b; font-size: 10px; line-height: 1.4;">Scan QR code di resepsionis.</p>
                </div>
            </div>

            <div style="background: rgba(15, 23, 42, 0.4); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.05); padding: 1.5rem; border-radius: 2rem; display: flex; gap: 1.25rem; align-items: center;">
                <div style="width: 3rem; height: 3rem; background: rgba(59, 130, 246, 0.1); border-radius: 1rem; display: flex; align-items: center; justify-content: center; shrink: 0;">
                    <x-heroicon-o-map-pin style="width: 1.5rem; height: 1.5rem; color: #3b82f6;" />
                </div>
                <div style="overflow: hidden;">
                    <h4 style="color: white; font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 0.25rem;">Lokasi</h4>
                    <p id="location-status" style="color: #64748b; font-size: 10px; font-style: italic; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Mencari koordinat...</p>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let html5QrCode;
            const startBtn = document.getElementById('start-button');
            const stopBtn = document.getElementById('stop-button');
            const switchBtn = document.getElementById('switch-button');
            const statusText = document.getElementById('scanner-status').querySelector('span:last-child');
            const locationText = document.getElementById('location-status');
            
            const selfieWrapper = document.getElementById('selfie-wrapper');
            const qrWrapper = document.getElementById('qr-wrapper');
            const selfieVideo = document.getElementById('selfie-video');
            const selfiePreview = document.getElementById('selfie-preview');
            const captureSelfieBtn = document.getElementById('capture-selfie-btn');
            const confirmSelfieActions = document.getElementById('confirm-selfie-actions');
            const retakeSelfieBtn = document.getElementById('retake-selfie-btn');
            const confirmSelfieBtn = document.getElementById('confirm-selfie-btn');
            const selfieHint = document.getElementById('selfie-hint');

            let userLat = null;
            let userLon = null;
            let currentFacingMode = "environment";
            let selfieStream = null;
            let capturedSelfieBase64 = null;
            let selfieCameras = []; // List of available cameras
            let selfieCurrentCamIdx = 0; // Index of current selfie camera
            let isSelfieLoading = false;

            // 1. Check for Secure Context (HTTPS/Localhost)
            if (!window.isSecureContext && window.location.hostname !== 'localhost' && window.location.hostname !== '127.0.0.1') {
                statusText.innerText = "Error: Kamera butuh koneksi HTTPS.";
                statusText.classList.add('text-danger-600', 'font-bold');
            }

            // 2. Selfie Camera Operations
            const switchSelfieBtn = document.getElementById('switch-selfie-btn');
            const switchSelfieLabel = document.getElementById('switch-selfie-label');
            const selfieCameraControls = document.getElementById('selfie-camera-controls');
            const selfieLoading = document.getElementById('selfie-loading');

            async function getAvailableCameras() {
                try {
                    // Request permissions first, then immediately stop the temp stream
                    const tempStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: false });
                    tempStream.getTracks().forEach(t => t.stop()); // release immediately
                    const devices = await navigator.mediaDevices.enumerateDevices();
                    selfieCameras = devices.filter(d => d.kind === 'videoinput');
                    return selfieCameras;
                } catch (e) {
                    console.warn('Cannot enumerate cameras:', e);
                    return [];
                }
            }

            async function startSelfieCamera(camDeviceId = null) {
                if (isSelfieLoading) return;
                isSelfieLoading = true;

                selfieWrapper.classList.remove('hidden');
                qrWrapper.classList.add('hidden');
                selfiePreview.style.display = 'none';
                selfieVideo.style.display = 'block';
                captureSelfieBtn.style.display = 'none'; // hide until camera loads
                confirmSelfieActions.style.display = 'none';
                selfieLoading.style.display = 'flex';
                selfieHint.innerText = "Memuat kamera depan...";
                statusText.innerText = "Mengambil Foto Selfie...";
                
                startBtn.classList.add('hidden');
                stopBtn.classList.remove('hidden');

                // Stop previous stream if any
                stopSelfieCamera();

                // Build constraints - try ideal facingMode first, then fallback
                let constraints;
                if (camDeviceId) {
                    constraints = { video: { deviceId: { exact: camDeviceId } }, audio: false };
                } else {
                    constraints = { video: { facingMode: { ideal: "user" }, width: { ideal: 1280 }, height: { ideal: 720 } }, audio: false };
                }

                async function tryGetUserMedia(c) {
                    try {
                        return await navigator.mediaDevices.getUserMedia(c);
                    } catch (e) {
                        return null;
                    }
                }

                // Try with ideal facingMode first
                let stream = await tryGetUserMedia(constraints);

                // Fallback 1: try without facingMode constraint (just get any camera)
                if (!stream && !camDeviceId) {
                    console.warn('facingMode:user failed, trying any video device...');
                    stream = await tryGetUserMedia({ video: true, audio: false });
                }

                if (!stream) {
                    isSelfieLoading = false;
                    selfieLoading.style.display = 'none';

                    let errTitle = 'Gagal Mengakses Kamera';
                    let errBody = 'Mohon izinkan akses kamera di browser Anda.';

                    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                        errTitle = 'Browser Tidak Mendukung';
                        errBody = 'Browser Anda tidak mendukung akses kamera. Coba gunakan Chrome atau Firefox versi terbaru.';
                    } else if (!window.isSecureContext) {
                        errTitle = 'Koneksi Tidak Aman (HTTP)';
                        errBody = 'Kamera hanya dapat diakses melalui HTTPS atau localhost. Hubungi administrator.';
                    }

                    new FilamentNotification()
                        .title(errTitle)
                        .body(errBody)
                        .danger()
                        .send();
                    resetScannerUI();
                    return;
                }

                selfieStream = stream;
                selfieVideo.srcObject = stream;

                // Wait for video to be ready
                selfieVideo.onloadedmetadata = async () => {
                    try {
                        await selfieVideo.play();
                    } catch(e) { console.warn('Video play error:', e); }

                    selfieLoading.style.display = 'none';
                    captureSelfieBtn.style.display = 'flex';
                    selfieHint.innerText = "Posisikan wajah Anda di dalam lingkaran";
                    isSelfieLoading = false;

                    // Show camera switch button if multiple cameras available
                    if (selfieCameras.length > 1) {
                        selfieCameraControls.style.display = 'flex';
                        switchSelfieLabel.innerText = `Kamera ${selfieCurrentCamIdx + 1}/${selfieCameras.length} — Ganti`;
                    }
                };

                // Fallback if onloadedmetadata never fires (already loaded)
                if (selfieVideo.readyState >= 2) {
                    selfieVideo.onloadedmetadata();
                }
            }

            function captureSelfie() {
                const canvas = document.createElement('canvas');
                canvas.width = selfieVideo.videoWidth || 640;
                canvas.height = selfieVideo.videoHeight || 480;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(selfieVideo, 0, 0, canvas.width, canvas.height);
                
                capturedSelfieBase64 = canvas.toDataURL('image/jpeg', 0.85);
                
                selfieVideo.style.display = 'none';
                selfiePreview.src = capturedSelfieBase64;
                selfiePreview.style.display = 'block';
                
                captureSelfieBtn.style.display = 'none';
                confirmSelfieActions.style.display = 'flex';
                selfieHint.innerText = "Apakah foto Anda sudah terlihat jelas?";
                
                stopSelfieCamera();
            }

            function stopSelfieCamera() {
                if (selfieStream) {
                    selfieStream.getTracks().forEach(track => track.stop());
                    selfieStream = null;
                }
            }

            // 3. QR Code Scanner Operations
            async function startScanner(facingMode = null) {
                statusText.innerText = "Menginisialisasi Kamera Scanner...";
                
                // Detection logic for laptops vs mobile
                const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
                
                if (!facingMode) {
                    facingMode = isMobile ? "environment" : "user";
                }
                
                currentFacingMode = facingMode;
                
                try {
                    const devices = await Html5Qrcode.getCameras();
                    if (!devices || devices.length === 0) {
                        statusText.innerText = "Tidak ada kamera ditemukan.";
                        return;
                    }
                    if (devices.length > 1) {
                        switchBtn.classList.remove('hidden');
                    }
                } catch (err) {
                    console.warn("Camera detection warning:", err);
                }

                if (html5QrCode && html5QrCode.isScanning) {
                    await html5QrCode.stop();
                }

                html5QrCode = new Html5Qrcode("reader");
                const config = { 
                    fps: 10, 
                    qrbox: (viewfinderWidth, viewfinderHeight) => {
                        const minEdgeSize = Math.min(viewfinderWidth, viewfinderHeight);
                        const qrboxSize = Math.floor(minEdgeSize * 0.7);
                        return { width: qrboxSize, height: qrboxSize };
                    },
                    aspectRatio: 1.0
                };

                html5QrCode.start(
                    { facingMode: facingMode }, 
                    config, 
                    onScanSuccess
                ).then(() => {
                    statusText.innerText = `Kamera Aktif (${facingMode === 'user' ? 'Depan' : 'Belakang'})`;
                    statusText.classList.remove('text-danger-600');
                    startBtn.classList.add('hidden');
                    stopBtn.classList.remove('hidden');
                }).catch(err => {
                    console.error("Camera start error:", err);
                    
                    // Fallback if environment/user fails
                    if (facingMode === "environment") {
                        console.log("Retrying with 'user' mode...");
                        startScanner("user");
                        return;
                    }

                    let errMsg = "Gagal memuat kamera.";
                    if (err.toString().includes("NotAllowedError") || err.toString().includes("Permission denied")) {
                        errMsg = "Izin kamera ditolak. Mohon izinkan di browser.";
                    } else if (err.toString().includes("NotFoundError")) {
                        errMsg = "Perangkat kamera tidak ditemukan.";
                    }
                    
                    statusText.innerText = errMsg;
                    statusText.classList.add('text-danger-600');
                });
            }

            function onScanSuccess(decodedText, decodedResult) {
                // Stop scanning to prevent multiple hits
                html5QrCode.stop().then(() => {
                    statusText.innerText = "Memproses Kode...";
                    
                    if (!userLat || !userLon) {
                        new FilamentNotification()
                            .title('GPS belum aktif.')
                            .body('Mohon aktifkan GPS dan refresh halaman.')
                            .danger()
                            .send();
                        startScanner(currentFacingMode);
                        return;
                    }

                    // Call backend Livewire method with captured selfie!
                    @this.call('processScan', decodedText, userLat, userLon, capturedSelfieBase64);
                });
            }

            function resetScannerUI() {
                stopSelfieCamera();
                capturedSelfieBase64 = null;
                
                if (html5QrCode && html5QrCode.isScanning) {
                    html5QrCode.stop().then(() => {
                        startBtn.classList.remove('hidden');
                        stopBtn.classList.add('hidden');
                        switchBtn.classList.add('hidden');
                        statusText.innerText = "Kamera Berhenti.";
                    }).catch(err => console.error("Error stopping scanner:", err));
                } else {
                    startBtn.classList.remove('hidden');
                    stopBtn.classList.add('hidden');
                    switchBtn.classList.add('hidden');
                    statusText.innerText = "Kamera Berhenti.";
                }
                
                selfieWrapper.classList.add('hidden');
                qrWrapper.classList.remove('hidden');
                selfieCameraControls.style.display = 'none';
                isSelfieLoading = false;
            }

            window.addEventListener('scan-success', () => {
                setTimeout(() => resetScannerUI(), 1000);
            });

            // Discover cameras on page load (to enable switch button early)
            getAvailableCameras().then(cams => {
                selfieCameras = cams;
                console.log(`Found ${cams.length} camera(s).`);
            });

            // Event Listeners for Selfie Steps
            startBtn.addEventListener('click', () => {
                if (userLat && userLon) {
                    selfieCurrentCamIdx = 0;
                    startSelfieCamera();
                } else {
                    statusText.innerText = "Meminta akses lokasi...";
                    if (navigator.geolocation) {
                        const geoSuccess = (position) => {
                            userLat = position.coords.latitude;
                            userLon = position.coords.longitude;
                            locationText.innerText = `Koordinat: ${userLat.toFixed(6)}, ${userLon.toFixed(6)}`;
                            locationText.classList.remove('italic');
                            locationText.classList.add('text-blue-400', 'font-medium');
                            
                            selfieCurrentCamIdx = 0;
                            startSelfieCamera();
                        };

                        const geoErrorFallback = (error) => {
                            console.error("Geolocation error (fallback):", error);
                            let errorMsg = 'Mohon izinkan akses lokasi (GPS) di pengaturan browser/HP Anda.';
                            if (error.code === 1) errorMsg = 'Akses lokasi ditolak. Buka Pengaturan > Safari/Chrome > Lokasi, lalu pilih Izinkan.';
                            else if (error.code === 2) errorMsg = 'Sinyal GPS tidak tersedia. Coba pindah ke area terbuka.';
                            else if (error.code === 3) errorMsg = 'Pencarian lokasi timeout (terlalu lama).';
                            
                            statusText.innerText = "Akses GPS gagal.";
                            new FilamentNotification()
                                .title('Gagal Akses GPS')
                                .body(errorMsg)
                                .danger()
                                .send();
                        };

                        const geoError = (error) => {
                            console.warn("High accuracy failed, trying low accuracy...", error);
                            // Retry without high accuracy
                            navigator.geolocation.getCurrentPosition(geoSuccess, geoErrorFallback, { enableHighAccuracy: false, timeout: 15000 });
                        };

                        navigator.geolocation.getCurrentPosition(geoSuccess, geoError, { enableHighAccuracy: true, timeout: 8000 });
                    } else {
                        new FilamentNotification()
                            .title('GPS Tidak Didukung')
                            .body('Perangkat atau browser Anda tidak mendukung fitur lokasi.')
                            .danger()
                            .send();
                    }
                }
            });

            // Switch selfie camera
            switchSelfieBtn.addEventListener('click', () => {
                if (selfieCameras.length > 1) {
                    selfieCurrentCamIdx = (selfieCurrentCamIdx + 1) % selfieCameras.length;
                    const nextCam = selfieCameras[selfieCurrentCamIdx];
                    startSelfieCamera(nextCam.deviceId);
                }
            });

            captureSelfieBtn.addEventListener('click', captureSelfie);
            
            retakeSelfieBtn.addEventListener('click', () => {
                selfieCameraControls.style.display = 'none';
                startSelfieCamera(selfieCameras.length > 0 ? selfieCameras[selfieCurrentCamIdx]?.deviceId : null);
            });
            
            confirmSelfieBtn.addEventListener('click', () => {
                selfieWrapper.classList.add('hidden');
                qrWrapper.classList.remove('hidden');
                startScanner();
            });
            
            switchBtn.addEventListener('click', () => {
                const newMode = currentFacingMode === "environment" ? "user" : "environment";
                startScanner(newMode);
            });

            stopBtn.addEventListener('click', resetScannerUI);

            // Auto start if parameter is present
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('autoStart') === '1') {
                setTimeout(() => {
                    if (userLat && userLon) {
                        startSelfieCamera();
                    }
                }, 1500);
            }
        });
    </script>
    @endpush

    <style>
        #reader { border: none !important; position: relative; overflow: hidden; border-radius: 2.5rem; }
        #reader video { border-radius: 2.5rem; object-fit: cover !important; width: 100% !important; height: 100% !important; }
        #reader__dashboard { display: none !important; }
        .hidden { display: none !important; }
        
        @keyframes scanMove {
            0%, 100% { transform: translateY(0); opacity: 0; }
            10%, 90% { opacity: 1; }
            50% { transform: translateY(240px); }
        }
        
        @keyframes ping {
            75%, 100% { transform: scale(2); opacity: 0; }
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        #selfie-video {
            /* Mirror effect for selfie - feels more natural */
            transform: scaleX(-1);
        }

        #switch-selfie-btn:hover {
            background: rgba(255,255,255,0.15) !important;
            color: white !important;
            border-color: rgba(255,255,255,0.2) !important;
        }
    </style>
</x-filament-panels::page>
