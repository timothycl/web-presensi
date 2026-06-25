<x-filament-panels::page>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;700;800;900&display=swap');

        @keyframes scanLoad {
            0% { left: -50%; }
            100% { left: 100%; }
        }
        .scan-loader-bar-green {
            position: absolute; top: 0; left: 0; height: 100%; width: 50%;
            background: linear-gradient(90deg, #059669, #34d399, #059669);
            border-radius: 9999px;
            animation: scanLoad 1.5s ease-in-out infinite alternate;
        }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        @keyframes successPop {
            0% { transform: scale(0) rotate(-10deg); opacity: 0; }
            60% { transform: scale(1.15) rotate(5deg); }
            100% { transform: scale(1) rotate(0deg); opacity: 1; }
        }
        @keyframes slideUp {
            from { transform: translateY(30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        @keyframes faceRing {
            0%, 100% { transform: scale(1); opacity: 0.6; }
            50% { transform: scale(1.08); opacity: 1; }
        }
        .hidden { display: none !important; }
        .register-panel {
            animation: slideUp 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
        }

        @media (max-width: 640px) {
            .responsive-grid { grid-template-columns: 1fr !important; gap: 1rem !important; }
        }
    </style>

    {{-- Loading overlay (Livewire target) --}}
    @teleport('body')
    <div wire:loading.flex wire:target="registerFace" class="fixed inset-0 flex-col items-center justify-center bg-slate-900/80 backdrop-blur-md" style="display:none; z-index:2147483647 !important;">
        <div class="w-72 h-2.5 bg-slate-800/80 rounded-full overflow-hidden relative border border-white/10">
            <div class="scan-loader-bar-green"></div>
        </div>
        <div class="mt-6 flex flex-col items-center gap-2">
            <span class="text-emerald-400 text-sm font-black uppercase tracking-[0.3em] animate-pulse">Mendaftarkan Wajah</span>
            <span class="text-slate-400 text-[10px] font-bold uppercase tracking-widest">Mohon Tunggu Sebentar...</span>
        </div>
    </div>
    @endteleport

    <div style="max-width:600px; margin:0 auto; width:100%; display:flex; flex-direction:column; gap:2rem; font-family:'Outfit',sans-serif;">

        {{-- ═══════════════════════════════════════════════════════════ --}}
        {{-- Status Card: Face Registration Status                       --}}
        {{-- ═══════════════════════════════════════════════════════════ --}}
        @if($this->hasFaceRegistered())
            {{-- Face Already Registered --}}
            <div style="background:rgba(15,23,42,0.82); backdrop-filter:blur(30px); border:1px solid rgba(16,185,129,0.2); border-radius:2.5rem; overflow:hidden; box-shadow:0 40px 80px -20px rgba(0,0,0,0.8);">
                <div style="padding:2rem; background:linear-gradient(135deg, rgba(5,150,105,0.08), rgba(16,185,129,0.04)); border-bottom:1px solid rgba(16,185,129,0.1);">
                    <div style="display:flex; align-items:center; gap:1rem; margin-bottom:1.5rem;">
                        <div style="width:3rem; height:3rem; background:rgba(16,185,129,0.15); border-radius:1rem; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                            <svg style="width:1.5rem; height:1.5rem; color:#10b981;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 style="color:#10b981; font-size:14px; font-weight:900; text-transform:uppercase; letter-spacing:0.15em; margin:0 0 0.2rem;">Wajah Terdaftar</h3>
                            <p style="color:#64748b; font-size:10px; font-weight:600; margin:0;">Wajah Anda sudah terdaftar. Anda dapat melakukan presensi wajah.</p>
                        </div>
                    </div>

                    {{-- Current Face Photos Preview --}}
                    @php $photos = $this->getFacePhotos(); @endphp
                    <div id="current-faces-grid" style="display:grid; grid-template-columns:repeat(3, 1fr); gap:1rem; margin-bottom:1.5rem;">
                        @foreach(['front' => 'Depan', 'right' => 'Kanan', 'left' => 'Kiri'] as $key => $label)
                            <div style="text-align:center;">
                                <div style="width:100%; aspect-ratio:1; border-radius:1.5rem; overflow:hidden; border:2px solid rgba(16,185,129,0.3); background:rgba(0,0,0,0.3); margin-bottom:0.5rem;">
                                    @if($photos[$key])
                                        <img src="{{ $photos[$key] }}" style="width:100%; height:100%; object-fit:cover;" alt="{{ $label }}" />
                                    @else
                                        <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center;">
                                            <svg style="width:2rem; height:2rem; color:#475569;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <span style="color:#94a3b8; font-size:9px; font-weight:800; text-transform:uppercase; letter-spacing:0.1em;">{{ $label }}</span>
                            </div>
                        @endforeach
                    </div>

                    {{-- Actions --}}
                    <div style="display:flex; gap:0.75rem;">
                        <a href="{{ \App\Filament\Pages\AttendanceScanner::getUrl() }}"
                            style="flex:1; background:linear-gradient(135deg, #7c3aed, #6d28d9); color:white; font-weight:900; font-size:12px; text-transform:uppercase; letter-spacing:0.1em; padding:1rem; border-radius:1.25rem; border:none; cursor:pointer; box-shadow:0 10px 25px -5px rgba(109,40,217,0.5); transition:all 0.3s; text-align:center; text-decoration:none; display:flex; align-items:center; justify-content:center; gap:0.5rem;"
                            onmouseover="this.style.transform='translateY(-2px)';"
                            onmouseout="this.style.transform='translateY(0)';">
                            <svg style="width:1rem; height:1rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                            </svg>
                            Mulai Presensi
                        </a>
                        <button id="re-register-btn"
                            style="flex:1; background:rgba(255,255,255,0.06); color:#94a3b8; font-weight:900; font-size:12px; text-transform:uppercase; letter-spacing:0.1em; padding:1rem; border-radius:1.25rem; border:1px solid rgba(255,255,255,0.1); cursor:pointer; transition:all 0.3s; display:flex; align-items:center; justify-content:center; gap:0.5rem;"
                            onmouseover="this.style.background='rgba(255,255,255,0.1)'; this.style.color='white';"
                            onmouseout="this.style.background='rgba(255,255,255,0.06)'; this.style.color='#94a3b8';">
                            <svg style="width:1rem; height:1rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182" />
                            </svg>
                            Daftar Ulang
                        </button>
                    </div>
                </div>
            </div>
        @else
            {{-- Face Not Registered --}}
            <div style="background:rgba(15,23,42,0.82); backdrop-filter:blur(30px); border:1px solid rgba(245,158,11,0.2); border-radius:2.5rem; overflow:hidden; box-shadow:0 40px 80px -20px rgba(0,0,0,0.8);">
                <div style="padding:2rem; display:flex; flex-direction:column; align-items:center; gap:1.5rem; text-align:center;">
                    <div style="width:90px; height:90px; background:rgba(245,158,11,0.12); border-radius:50%; border:2px solid rgba(245,158,11,0.4); display:flex; align-items:center; justify-content:center;">
                        <svg style="width:2.5rem; height:2.5rem; color:#fbbf24;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                    </div>
                    <div>
                        <p style="color:#fbbf24; font-size:14px; font-weight:900; text-transform:uppercase; letter-spacing:0.2em; margin:0 0 0.5rem;">Wajah Belum Terdaftar</p>
                        <p style="color:#64748b; font-size:11px; font-weight:600; line-height:1.6; margin:0; max-width:300px;">Anda perlu mendaftarkan wajah Anda terlebih dahulu agar dapat melakukan presensi menggunakan verifikasi wajah.</p>
                    </div>
                    <button id="start-register-btn"
                        style="background:linear-gradient(135deg, #059669, #10b981); color:white; font-weight:900; font-size:13px; text-transform:uppercase; letter-spacing:0.15em; padding:1rem 2.5rem; border-radius:1.25rem; border:none; cursor:pointer; display:flex; align-items:center; gap:0.6rem; box-shadow:0 10px 30px -5px rgba(16,185,129,0.45); transition:all 0.3s;"
                        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 15px 35px -5px rgba(16,185,129,0.55)';"
                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 30px -5px rgba(16,185,129,0.45)';">
                        <svg style="width:1.1rem; height:1.1rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Daftarkan Wajah Sekarang
                    </button>
                </div>
            </div>
        @endif

        {{-- ═══════════════════════════════════════════════════════════ --}}
        {{-- PANEL: Pendaftaran Wajah (3-Step Wizard)                     --}}
        {{-- ═══════════════════════════════════════════════════════════ --}}
        <div id="register-panel" class="hidden register-panel" style="background:rgba(15,23,42,0.82); backdrop-filter:blur(30px); border:1px solid rgba(16,185,129,0.25); border-radius:2.5rem; overflow:hidden; box-shadow:0 40px 80px -20px rgba(0,0,0,0.8);">

            {{-- Header --}}
            <div style="padding:2rem 2rem 1.25rem; background:linear-gradient(135deg, rgba(5,150,105,0.12), rgba(16,185,129,0.06)); border-bottom:1px solid rgba(16,185,129,0.15); display:flex; align-items:center; gap:1rem;">
                <div style="width:2.5rem; height:2.5rem; background:rgba(16,185,129,0.15); border-radius:0.875rem; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <svg style="width:1.25rem; height:1.25rem; color:#10b981;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                </div>
                <div>
                    <h3 style="color:white; font-size:14px; font-weight:900; text-transform:uppercase; letter-spacing:0.15em; margin:0 0 0.2rem;">Daftarkan Wajah</h3>
                    <p style="color:#64748b; font-size:10px; font-weight:600; margin:0;">Ambil 3 foto: Depan, Kanan, dan Kiri</p>
                </div>
                <button id="close-register-panel-btn"
                    style="margin-left:auto; width:2rem; height:2rem; background:rgba(255,255,255,0.06); border:1px solid rgba(255,255,255,0.1); border-radius:50%; color:#64748b; cursor:pointer; display:flex; align-items:center; justify-content:center; transition:all 0.2s; flex-shrink:0;"
                    onmouseover="this.style.background='rgba(239,68,68,0.15)'; this.style.color='#f87171';"
                    onmouseout="this.style.background='rgba(255,255,255,0.06)'; this.style.color='#64748b';">
                    <svg style="width:0.875rem; height:0.875rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Progress Steps --}}
            <div id="register-progress" style="padding:1.5rem 2rem 0; display:flex; align-items:center; justify-content:center; gap:0.5rem;">
                <div class="reg-step-indicator" data-step="0" style="display:flex; align-items:center; gap:0.5rem;">
                    <div class="reg-step-dot" style="width:2rem; height:2rem; border-radius:50%; background:linear-gradient(135deg, #059669, #10b981); color:white; font-size:11px; font-weight:900; display:flex; align-items:center; justify-content:center; transition:all 0.3s; box-shadow:0 0 15px rgba(16,185,129,0.4);">1</div>
                    <span class="reg-step-label" style="font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:0.1em; color:#10b981; transition:all 0.3s;">Depan</span>
                </div>
                <div style="width:2rem; height:2px; background:rgba(255,255,255,0.1); border-radius:9999px; position:relative; overflow:hidden;">
                    <div id="progress-line-1" style="position:absolute; inset:0; background:linear-gradient(90deg, #10b981, #059669); transform:scaleX(0); transform-origin:left; transition:transform 0.4s ease;"></div>
                </div>
                <div class="reg-step-indicator" data-step="1" style="display:flex; align-items:center; gap:0.5rem;">
                    <div class="reg-step-dot" style="width:2rem; height:2rem; border-radius:50%; background:rgba(255,255,255,0.08); border:2px solid rgba(255,255,255,0.15); color:#64748b; font-size:11px; font-weight:900; display:flex; align-items:center; justify-content:center; transition:all 0.3s;">2</div>
                    <span class="reg-step-label" style="font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:0.1em; color:#64748b; transition:all 0.3s;">Kanan</span>
                </div>
                <div style="width:2rem; height:2px; background:rgba(255,255,255,0.1); border-radius:9999px; position:relative; overflow:hidden;">
                    <div id="progress-line-2" style="position:absolute; inset:0; background:linear-gradient(90deg, #10b981, #059669); transform:scaleX(0); transform-origin:left; transition:transform 0.4s ease;"></div>
                </div>
                <div class="reg-step-indicator" data-step="2" style="display:flex; align-items:center; gap:0.5rem;">
                    <div class="reg-step-dot" style="width:2rem; height:2rem; border-radius:50%; background:rgba(255,255,255,0.08); border:2px solid rgba(255,255,255,0.15); color:#64748b; font-size:11px; font-weight:900; display:flex; align-items:center; justify-content:center; transition:all 0.3s;">3</div>
                    <span class="reg-step-label" style="font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:0.1em; color:#64748b; transition:all 0.3s;">Kiri</span>
                </div>
            </div>

            {{-- Camera Area --}}
            <div style="padding:1.5rem 2rem 2rem; display:flex; flex-direction:column; align-items:center; gap:1.25rem;">

                {{-- Pose Direction Icon --}}
                <div id="register-pose-icon" style="display:flex; align-items:center; justify-content:center; gap:1rem; padding:0.75rem 1.5rem; background:rgba(16,185,129,0.08); border:1px solid rgba(16,185,129,0.2); border-radius:1rem;">
                    <div id="pose-arrow-icon" style="color:#10b981; display:flex; align-items:center;">
                        {{-- Front face icon --}}
                        <svg id="pose-icon-front" style="width:2rem; height:2rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <circle cx="12" cy="8" r="4" stroke-width="1.5"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 20v-1a7 7 0 0114 0v1"/>
                            <path stroke-linecap="round" stroke-width="2" d="M12 2v2" style="opacity:0.4;"/>
                        </svg>
                        {{-- Right face icon --}}
                        <svg id="pose-icon-right" style="width:2rem; height:2rem; display:none;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <circle cx="12" cy="8" r="4" stroke-width="1.5"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 20v-1a7 7 0 0114 0v1"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M18 8l3 0M19.5 6.5L21 8l-1.5 1.5" style="color:#10b981;"/>
                        </svg>
                        {{-- Left face icon --}}
                        <svg id="pose-icon-left" style="width:2rem; height:2rem; display:none;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <circle cx="12" cy="8" r="4" stroke-width="1.5"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 20v-1a7 7 0 0114 0v1"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 8l-3 0M4.5 6.5L3 8l1.5 1.5" style="color:#10b981;"/>
                        </svg>
                    </div>
                    <div>
                        <p id="register-pose-title" style="color:#10b981; font-size:13px; font-weight:900; text-transform:uppercase; letter-spacing:0.15em; margin:0;">Wajah Depan</p>
                        <p id="register-pose-desc" style="color:#64748b; font-size:9px; font-weight:600; margin:0.2rem 0 0;">Hadapkan wajah lurus ke kamera</p>
                    </div>
                </div>

                {{-- Video Circle --}}
                <div style="position:relative; width:240px; height:240px; border-radius:50%; overflow:hidden; border:4px solid rgba(16,185,129,0.5); box-shadow:0 0 40px rgba(16,185,129,0.2); background:#000; flex-shrink:0;">
                    <video id="register-video" style="width:100%; height:100%; object-fit:cover; transform:scaleX(-1); cursor:pointer;" autoplay playsinline muted></video>
                    <img id="register-preview" style="display:none; width:100%; height:100%; object-fit:cover;" alt="preview" />

                    {{-- Loading overlay --}}
                    <div id="register-loading" style="position:absolute; inset:0; background:rgba(15,23,42,0.85); display:flex; flex-direction:column; align-items:center; justify-content:center; gap:0.75rem; border-radius:50%;">
                        <svg style="width:2.5rem; height:2.5rem; color:#10b981; animation:spin 1s linear infinite;" fill="none" viewBox="0 0 24 24">
                            <circle style="opacity:0.25;" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path style="opacity:0.75;" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span style="color:#10b981; font-size:9px; font-weight:800; text-transform:uppercase; letter-spacing:0.15em;">Memuat Kamera...</span>
                    </div>

                    {{-- Guide overlay --}}
                    <div id="register-guide-overlay" style="display:none; position:absolute; inset:0; pointer-events:none; border:15px solid rgba(15,23,42,0.5); border-radius:50%; display:flex; align-items:center; justify-content:center;">
                        <div style="width:90%; height:90%; border:2px dashed rgba(16,185,129,0.4); border-radius:50%;"></div>
                    </div>
                </div>

                <p id="register-hint" style="color:#64748b; font-size:10px; text-transform:uppercase; font-weight:800; letter-spacing:0.15em; text-align:center; margin:0;">Posisikan wajah di dalam lingkaran</p>

                {{-- Captured Thumbnails Row --}}
                <div id="register-thumbnails" style="display:flex; gap:0.75rem; justify-content:center;">
                    <div class="reg-thumb" id="thumb-front" style="width:60px; height:60px; border-radius:1rem; border:2px dashed rgba(255,255,255,0.15); background:rgba(255,255,255,0.03); display:flex; align-items:center; justify-content:center; overflow:hidden; transition:all 0.3s; position:relative;">
                        <span style="color:#475569; font-size:8px; font-weight:800; text-transform:uppercase;">Depan</span>
                        <img id="thumb-img-front" style="display:none; width:100%; height:100%; object-fit:cover; position:absolute; inset:0;" alt="front" />
                        <div id="thumb-check-front" style="display:none; position:absolute; inset:0; background:rgba(16,185,129,0.3); display:none; align-items:center; justify-content:center;">
                            <svg style="width:1.25rem; height:1.25rem; color:#10b981;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M4.5 12.75l6 6 9-13.5"/></svg>
                        </div>
                    </div>
                    <div class="reg-thumb" id="thumb-right" style="width:60px; height:60px; border-radius:1rem; border:2px dashed rgba(255,255,255,0.15); background:rgba(255,255,255,0.03); display:flex; align-items:center; justify-content:center; overflow:hidden; transition:all 0.3s; position:relative;">
                        <span style="color:#475569; font-size:8px; font-weight:800; text-transform:uppercase;">Kanan</span>
                        <img id="thumb-img-right" style="display:none; width:100%; height:100%; object-fit:cover; position:absolute; inset:0;" alt="right" />
                        <div id="thumb-check-right" style="display:none; position:absolute; inset:0; background:rgba(16,185,129,0.3); display:none; align-items:center; justify-content:center;">
                            <svg style="width:1.25rem; height:1.25rem; color:#10b981;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M4.5 12.75l6 6 9-13.5"/></svg>
                        </div>
                    </div>
                    <div class="reg-thumb" id="thumb-left" style="width:60px; height:60px; border-radius:1rem; border:2px dashed rgba(255,255,255,0.15); background:rgba(255,255,255,0.03); display:flex; align-items:center; justify-content:center; overflow:hidden; transition:all 0.3s; position:relative;">
                        <span style="color:#475569; font-size:8px; font-weight:800; text-transform:uppercase;">Kiri</span>
                        <img id="thumb-img-left" style="display:none; width:100%; height:100%; object-fit:cover; position:absolute; inset:0;" alt="left" />
                        <div id="thumb-check-left" style="display:none; position:absolute; inset:0; background:rgba(16,185,129,0.3); display:none; align-items:center; justify-content:center;">
                            <svg style="width:1.25rem; height:1.25rem; color:#10b981;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M4.5 12.75l6 6 9-13.5"/></svg>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div style="display:flex; flex-direction:column; gap:0.75rem; width:100%; max-width:320px;">
                    {{-- Capture --}}
                    <button id="capture-register-btn"
                        style="width:100%; background:linear-gradient(135deg, #059669, #10b981); color:white; font-weight:900; font-size:12px; text-transform:uppercase; letter-spacing:0.15em; padding:1rem; border-radius:1.25rem; border:none; cursor:pointer; display:none; align-items:center; justify-content:center; gap:0.5rem; box-shadow:0 10px 20px -5px rgba(16,185,129,0.4); transition:all 0.3s;">
                        <svg style="width:1.25rem; height:1.25rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z" />
                        </svg>
                        <span id="capture-btn-text">Ambil Foto Depan</span>
                    </button>

                    {{-- Retake current step --}}
                    <button id="retake-register-btn" style="display:none; width:100%; background:rgba(255,255,255,0.05); color:#94a3b8; border:1px solid rgba(255,255,255,0.1); font-weight:900; font-size:11px; text-transform:uppercase; letter-spacing:0.1em; padding:0.875rem; border-radius:1.25rem; cursor:pointer; transition:all 0.3s; align-items:center; justify-content:center; gap:0.5rem;">
                        <svg style="width:1rem; height:1rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182" />
                        </svg>
                        Foto Ulang
                    </button>

                    {{-- Review Panel (shown after all 3 photos captured) --}}
                    <div id="register-review-panel" style="display:none; flex-direction:column; gap:1rem;">
                        <div style="text-align:center; padding:0.75rem; background:rgba(16,185,129,0.06); border:1px solid rgba(16,185,129,0.15); border-radius:1rem;">
                            <p style="color:#10b981; font-size:11px; font-weight:900; text-transform:uppercase; letter-spacing:0.15em; margin:0 0 0.25rem;">✓ 3 Foto Lengkap!</p>
                            <p style="color:#64748b; font-size:9px; font-weight:600; margin:0;">Pastikan semua foto jelas dan wajah terlihat</p>
                        </div>
                        <div style="display:flex; gap:0.75rem;">
                            <button id="reset-all-register-btn"
                                style="flex:1; background:rgba(255,255,255,0.05); color:#94a3b8; border:1px solid rgba(255,255,255,0.1); font-weight:900; font-size:11px; text-transform:uppercase; letter-spacing:0.1em; padding:1rem; border-radius:1.25rem; cursor:pointer; transition:all 0.3s;">
                                Ulangi Semua
                            </button>
                            <button id="confirm-register-btn"
                                style="flex:1; background:linear-gradient(135deg, #059669, #10b981); color:white; font-weight:900; font-size:11px; text-transform:uppercase; letter-spacing:0.1em; padding:1rem; border-radius:1.25rem; border:none; cursor:pointer; box-shadow:0 10px 20px -5px rgba(16,185,129,0.4); transition:all 0.3s; display:flex; align-items:center; justify-content:center; gap:0.5rem;">
                                <svg style="width:1.1rem; height:1.1rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                                Simpan Semua
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════════════════ --}}
        {{-- Success State (shown after registration)                     --}}
        {{-- ═══════════════════════════════════════════════════════════ --}}
        <div id="registered-state" class="hidden" style="background:rgba(15,23,42,0.82); backdrop-filter:blur(30px); border:1px solid rgba(16,185,129,0.25); border-radius:2.5rem; overflow:hidden; box-shadow:0 40px 80px -20px rgba(0,0,0,0.8);">
            <div style="padding:2.5rem; display:flex; flex-direction:column; align-items:center; gap:1.5rem; text-align:center;">
                <div style="width:100px; height:100px; background:rgba(16,185,129,0.15); border-radius:50%; border:3px solid #10b981; display:flex; align-items:center; justify-content:center; animation:successPop 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards; box-shadow:0 0 40px rgba(16,185,129,0.4);">
                    <svg style="width:3rem; height:3rem; color:#10b981;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>
                </div>
                <div>
                    <p style="color:#10b981; font-size:14px; font-weight:900; text-transform:uppercase; letter-spacing:0.2em; margin:0 0 0.5rem;">Wajah Berhasil Didaftarkan!</p>
                    <p style="color:#64748b; font-size:11px; font-weight:600; margin:0;">Anda sekarang dapat melakukan presensi menggunakan verifikasi wajah.</p>
                </div>
                <div style="display:flex; gap:0.75rem; width:100%; max-width:320px;">
                    <a href="{{ \App\Filament\Pages\AttendanceScanner::getUrl() }}"
                        style="flex:1; background:linear-gradient(135deg, #7c3aed, #6d28d9); color:white; font-weight:900; font-size:12px; text-transform:uppercase; letter-spacing:0.1em; padding:1rem; border-radius:1.25rem; border:none; cursor:pointer; box-shadow:0 10px 25px -5px rgba(109,40,217,0.5); transition:all 0.3s; text-align:center; text-decoration:none; display:flex; align-items:center; justify-content:center; gap:0.5rem;">
                        <svg style="width:1rem; height:1rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                        Mulai Presensi
                    </a>
                    <button onclick="window.location.reload();"
                        style="flex:1; background:rgba(255,255,255,0.06); color:#94a3b8; font-weight:900; font-size:12px; text-transform:uppercase; letter-spacing:0.1em; padding:1rem; border-radius:1.25rem; border:1px solid rgba(255,255,255,0.1); cursor:pointer; transition:all 0.3s;">
                        Lihat Status
                    </button>
                </div>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════════════════ --}}
        {{-- Info Grid                                                    --}}
        {{-- ═══════════════════════════════════════════════════════════ --}}
        <div class="responsive-grid" style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem;">
            <div style="background:rgba(15,23,42,0.4); backdrop-filter:blur(10px); border:1px solid rgba(255,255,255,0.05); padding:1.5rem; border-radius:2rem; display:flex; gap:1.25rem; align-items:center;">
                <div style="width:3rem; height:3rem; background:rgba(16,185,129,0.1); border-radius:1rem; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <svg style="width:1.5rem; height:1.5rem; color:#10b981;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                    </svg>
                </div>
                <div>
                    <h4 style="color:white; font-size:12px; font-weight:800; text-transform:uppercase; letter-spacing:0.1em; margin-bottom:0.25rem;">Keamanan</h4>
                    <p style="color:#64748b; font-size:10px; line-height:1.4; margin:0;">Foto wajah Anda disimpan secara aman dan digunakan untuk verifikasi identitas.</p>
                </div>
            </div>

            <div style="background:rgba(15,23,42,0.4); backdrop-filter:blur(10px); border:1px solid rgba(255,255,255,0.05); padding:1.5rem; border-radius:2rem; display:flex; gap:1.25rem; align-items:center;">
                <div style="width:3rem; height:3rem; background:rgba(124,58,237,0.1); border-radius:1rem; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <svg style="width:1.5rem; height:1.5rem; color:#7c3aed;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                    </svg>
                </div>
                <div>
                    <h4 style="color:white; font-size:12px; font-weight:800; text-transform:uppercase; letter-spacing:0.1em; margin-bottom:0.25rem;">Panduan</h4>
                    <p style="color:#64748b; font-size:10px; line-height:1.4; margin:0;">Ambil foto dengan pencahayaan cukup dari 3 sudut: depan, kanan, dan kiri.</p>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {

        // ── DOM refs ──────────────────────────────────────────────────────────
        const registerPanel         = document.getElementById('register-panel');
        const registerVideo         = document.getElementById('register-video');
        const registerPreview       = document.getElementById('register-preview');
        const registerLoading       = document.getElementById('register-loading');
        const registerGuideOverlay  = document.getElementById('register-guide-overlay');
        const captureRegisterBtn    = document.getElementById('capture-register-btn');
        const captureBtnText        = document.getElementById('capture-btn-text');
        const retakeRegisterBtn     = document.getElementById('retake-register-btn');
        const confirmRegisterBtn    = document.getElementById('confirm-register-btn');
        const registerHint          = document.getElementById('register-hint');
        const closeRegisterPanelBtn = document.getElementById('close-register-panel-btn');
        const registerReviewPanel   = document.getElementById('register-review-panel');
        const resetAllRegisterBtn   = document.getElementById('reset-all-register-btn');
        const registerPoseTitle     = document.getElementById('register-pose-title');
        const registerPoseDesc      = document.getElementById('register-pose-desc');
        const poseIconFront         = document.getElementById('pose-icon-front');
        const poseIconRight         = document.getElementById('pose-icon-right');
        const poseIconLeft          = document.getElementById('pose-icon-left');
        const progressLine1         = document.getElementById('progress-line-1');
        const progressLine2         = document.getElementById('progress-line-2');
        const registeredState       = document.getElementById('registered-state');
        const startRegisterBtn      = document.getElementById('start-register-btn');
        const reRegisterBtn         = document.getElementById('re-register-btn');

        // ── State ─────────────────────────────────────────────────────────────
        let registerStream      = null;
        let currentRegisterStep = 0; // 0=front, 1=right, 2=left
        let photoTakenForCurrentStep = false;
        const capturedPhotos    = { front: null, right: null, left: null };
        const POSE_KEYS         = ['front', 'right', 'left'];
        const POSE_LABELS       = ['Depan', 'Kanan', 'Kiri'];
        const POSE_DESCS        = ['Hadapkan wajah lurus ke kamera', 'Tolehkan wajah ke kanan Anda', 'Tolehkan wajah ke kiri Anda'];

        // ── Helpers ───────────────────────────────────────────────────────────
        function captureSnapshot(videoEl) {
            const canvas = document.createElement('canvas');
            canvas.width  = videoEl.videoWidth  || 640;
            canvas.height = videoEl.videoHeight || 480;
            const ctx = canvas.getContext('2d');
            ctx.translate(canvas.width, 0);
            ctx.scale(-1, 1);
            ctx.drawImage(videoEl, 0, 0, canvas.width, canvas.height);
            return canvas.toDataURL('image/jpeg', 0.85);
        }

        function updateProgressUI() {
            const indicators = document.querySelectorAll('.reg-step-indicator');
            indicators.forEach(ind => {
                const step = parseInt(ind.getAttribute('data-step'));
                const dot = ind.querySelector('.reg-step-dot');
                const label = ind.querySelector('.reg-step-label');
                
                if (step < currentRegisterStep) {
                    dot.style.background = 'linear-gradient(135deg, #059669, #10b981)';
                    dot.style.color = 'white';
                    dot.style.border = 'none';
                    dot.style.boxShadow = '0 0 15px rgba(16,185,129,0.4)';
                    label.style.color = '#10b981';
                } else if (step === currentRegisterStep) {
                    dot.style.background = 'linear-gradient(135deg, #7c3aed, #a78bfa)';
                    dot.style.color = 'white';
                    dot.style.border = 'none';
                    dot.style.boxShadow = '0 0 15px rgba(124,58,237,0.4)';
                    label.style.color = '#a78bfa';
                } else {
                    dot.style.background = 'rgba(255,255,255,0.08)';
                    dot.style.color = '#64748b';
                    dot.style.border = '2px solid rgba(255,255,255,0.15)';
                    dot.style.boxShadow = 'none';
                    label.style.color = '#64748b';
                }
            });

            if (progressLine1 && progressLine2) {
                progressLine1.style.transform = currentRegisterStep > 0 ? 'scaleX(1)' : 'scaleX(0)';
                progressLine2.style.transform = currentRegisterStep > 1 ? 'scaleX(1)' : 'scaleX(0)';
            }
        }

        function updatePoseUI() {
            registerPoseTitle.innerText = POSE_LABELS[currentRegisterStep];
            registerPoseDesc.innerText = POSE_DESCS[currentRegisterStep];
            captureBtnText.innerText = 'Ambil Foto ' + POSE_LABELS[currentRegisterStep];

            poseIconFront.style.display = currentRegisterStep === 0 ? 'block' : 'none';
            poseIconRight.style.display = currentRegisterStep === 1 ? 'block' : 'none';
            poseIconLeft.style.display  = currentRegisterStep === 2 ? 'block' : 'none';
        }

        function stopRegisterCamera() {
            if (registerStream) {
                registerStream.getTracks().forEach(t => t.stop());
                registerStream = null;
            }
        }

        // ── Register Face Panel ───────────────────────────────────────────────
        async function openRegisterPanel() {
            // Show panel
            registerPanel.classList.remove('hidden');
            registerPanel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });

            // Reset panel and registration state
            currentRegisterStep = 0;
            photoTakenForCurrentStep = false;
            capturedPhotos.front = null;
            capturedPhotos.right = null;
            capturedPhotos.left = null;

            registerPreview.style.display = 'none';
            registerVideo.style.display = 'block';
            captureRegisterBtn.style.display = 'none';
            retakeRegisterBtn.style.display = 'none';
            registerReviewPanel.style.display = 'none';
            registerLoading.style.display = 'flex';
            registerGuideOverlay.style.display = 'none';
            registerHint.innerText = 'Memuat kamera depan...';

            // Reset thumbnail visual states
            POSE_KEYS.forEach(key => {
                const thumbImg = document.getElementById('thumb-img-' + key);
                const thumbCheck = document.getElementById('thumb-check-' + key);
                const thumbContainer = document.getElementById('thumb-' + key);
                if (thumbImg) {
                    thumbImg.style.display = 'none';
                    thumbImg.src = '';
                }
                if (thumbCheck) thumbCheck.style.display = 'none';
                if (thumbContainer) {
                    thumbContainer.style.border = '2px dashed rgba(255,255,255,0.15)';
                    thumbContainer.style.background = 'rgba(255,255,255,0.03)';
                }
            });

            updatePoseUI();
            updateProgressUI();

            // Start camera for registration
            try {
                registerStream = await navigator.mediaDevices.getUserMedia({
                    video: { facingMode: { ideal: 'user' }, width: { ideal: 640 }, height: { ideal: 640 } },
                    audio: false
                });
            } catch (e) {
                try { registerStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: false }); }
                catch (e2) {
                    new FilamentNotification().title('Gagal Mengakses Kamera').body('Mohon izinkan akses kamera.').danger().send();
                    registerPanel.classList.add('hidden');
                    return;
                }
            }

            registerVideo.srcObject = registerStream;
            registerVideo.onloadedmetadata = () => {
                registerVideo.play().catch(() => {});
                registerLoading.style.display = 'none';
                registerGuideOverlay.style.display = 'flex';
                captureRegisterBtn.style.display = 'flex';
                registerHint.innerText = 'Posisikan wajah di dalam lingkaran';
            };
            if (registerVideo.readyState >= 2) registerVideo.onloadedmetadata();
        }

        function captureRegisterPhoto() {
            if (!photoTakenForCurrentStep) {
                const b64 = captureSnapshot(registerVideo);
                const poseKey = POSE_KEYS[currentRegisterStep];
                capturedPhotos[poseKey] = b64;

                registerPreview.src = b64;
                registerPreview.style.display = 'block';
                registerVideo.style.display   = 'none';
                registerGuideOverlay.style.display = 'none';

                retakeRegisterBtn.style.display = 'flex';

                const thumbImg = document.getElementById('thumb-img-' + poseKey);
                const thumbCheck = document.getElementById('thumb-check-' + poseKey);
                const thumbContainer = document.getElementById('thumb-' + poseKey);
                if (thumbImg) {
                    thumbImg.src = b64;
                    thumbImg.style.display = 'block';
                }
                if (thumbCheck) thumbCheck.style.display = 'flex';
                if (thumbContainer) {
                    thumbContainer.style.border = '2px solid #10b981';
                    thumbContainer.style.background = 'rgba(16,185,129,0.05)';
                }

                photoTakenForCurrentStep = true;
                registerHint.innerText = 'Apakah foto wajah Anda sudah jelas?';

                if (currentRegisterStep === 2) {
                    captureBtnText.innerText = 'Selesai & Tinjau';
                    stopRegisterCamera();
                } else {
                    captureBtnText.innerText = 'Lanjutkan';
                }
            } else {
                if (currentRegisterStep < 2) {
                    currentRegisterStep++;
                    photoTakenForCurrentStep = false;

                    registerPreview.style.display = 'none';
                    registerPreview.src = '';
                    registerVideo.style.display = 'block';
                    registerGuideOverlay.style.display = 'flex';
                    retakeRegisterBtn.style.display = 'none';

                    updatePoseUI();
                    updateProgressUI();
                    registerHint.innerText = 'Posisikan wajah di dalam lingkaran';
                } else {
                    captureRegisterBtn.style.display = 'none';
                    retakeRegisterBtn.style.display = 'none';
                    registerPreview.style.display = 'none';
                    registerVideo.style.display = 'none';
                    registerGuideOverlay.style.display = 'none';
                    registerReviewPanel.style.display = 'flex';
                    registerHint.innerText = 'Tinjau foto Anda dan simpan';
                }
            }
        }

        function retakeRegisterPhoto() {
            const poseKey = POSE_KEYS[currentRegisterStep];
            capturedPhotos[poseKey] = null;

            photoTakenForCurrentStep = false;
            registerPreview.style.display = 'none';
            registerPreview.src = '';
            registerVideo.style.display   = 'block';
            registerGuideOverlay.style.display = 'flex';
            retakeRegisterBtn.style.display = 'none';
            captureBtnText.innerText = 'Ambil Foto ' + POSE_LABELS[currentRegisterStep];
            registerHint.innerText = 'Posisikan wajah di dalam lingkaran';

            const thumbImg = document.getElementById('thumb-img-' + poseKey);
            const thumbCheck = document.getElementById('thumb-check-' + poseKey);
            const thumbContainer = document.getElementById('thumb-' + poseKey);
            if (thumbImg) {
                thumbImg.style.display = 'none';
                thumbImg.src = '';
            }
            if (thumbCheck) thumbCheck.style.display = 'none';
            if (thumbContainer) {
                thumbContainer.style.border = '2px dashed rgba(255,255,255,0.15)';
                thumbContainer.style.background = 'rgba(255,255,255,0.03)';
            }

            // Restart camera if it was stopped
            if (!registerStream) {
                registerLoading.style.display = 'flex';
                registerGuideOverlay.style.display = 'none';
                captureRegisterBtn.style.display = 'none';
                registerHint.innerText = 'Memuat kamera...';

                navigator.mediaDevices.getUserMedia({
                    video: { facingMode: { ideal: 'user' }, width: { ideal: 640 }, height: { ideal: 640 } },
                    audio: false
                }).then(stream => {
                    registerStream = stream;
                    registerVideo.srcObject = registerStream;
                    registerVideo.onloadedmetadata = () => {
                        registerVideo.play().catch(() => {});
                        registerLoading.style.display = 'none';
                        registerGuideOverlay.style.display = 'flex';
                        captureRegisterBtn.style.display = 'flex';
                        registerHint.innerText = 'Posisikan wajah di dalam lingkaran';
                    };
                    if (registerVideo.readyState >= 2) registerVideo.onloadedmetadata();
                }).catch(() => {
                    navigator.mediaDevices.getUserMedia({ video: true, audio: false }).then(stream => {
                        registerStream = stream;
                        registerVideo.srcObject = registerStream;
                        registerVideo.onloadedmetadata = () => {
                            registerVideo.play().catch(() => {});
                            registerLoading.style.display = 'none';
                            registerGuideOverlay.style.display = 'flex';
                            captureRegisterBtn.style.display = 'flex';
                            registerHint.innerText = 'Posisikan wajah di dalam lingkaran';
                        };
                        if (registerVideo.readyState >= 2) registerVideo.onloadedmetadata();
                    }).catch(() => {
                        new FilamentNotification().title('Gagal Mengakses Kamera').body('Mohon izinkan akses kamera.').danger().send();
                        registerPanel.classList.add('hidden');
                    });
                });
            }
        }

        function closeRegisterPanel() {
            stopRegisterCamera();
            registerPanel.classList.add('hidden');
            currentRegisterStep = 0;
            photoTakenForCurrentStep = false;
            capturedPhotos.front = null;
            capturedPhotos.right = null;
            capturedPhotos.left = null;
        }

        // ── Event Listeners ───────────────────────────────────────────────────

        // Open register panel buttons
        if (startRegisterBtn) startRegisterBtn.addEventListener('click', openRegisterPanel);
        if (reRegisterBtn) reRegisterBtn.addEventListener('click', openRegisterPanel);

        // Register panel controls
        if (closeRegisterPanelBtn) {
            closeRegisterPanelBtn.addEventListener('click', closeRegisterPanel);
        }

        if (captureRegisterBtn) {
            captureRegisterBtn.addEventListener('click', captureRegisterPhoto);
        }

        if (registerVideo) {
            registerVideo.addEventListener('click', () => {
                if (registerVideo.style.display !== 'none' && !photoTakenForCurrentStep && registerStream) {
                    captureRegisterPhoto();
                }
            });
        }

        if (retakeRegisterBtn) {
            retakeRegisterBtn.addEventListener('click', retakeRegisterPhoto);
        }

        if (resetAllRegisterBtn) {
            resetAllRegisterBtn.addEventListener('click', openRegisterPanel);
        }

        if (confirmRegisterBtn) {
            confirmRegisterBtn.addEventListener('click', () => {
                if (!capturedPhotos.front || !capturedPhotos.right || !capturedPhotos.left) return;
                stopRegisterCamera();
                registerPanel.classList.add('hidden');
                @this.call('registerFace', capturedPhotos.front, capturedPhotos.right, capturedPhotos.left);
            });
        }

        // Livewire events
        window.addEventListener('face-registered', () => {
            // Show success state
            registerPanel.classList.add('hidden');
            registeredState.classList.remove('hidden');

            // Hide the status card (if exists)
            const currentFacesGrid = document.getElementById('current-faces-grid');
            if (currentFacesGrid) {
                currentFacesGrid.closest('[style*="border-radius:2.5rem"]').style.display = 'none';
            }
        });
    });
    </script>
    @endpush

</x-filament-panels::page>
