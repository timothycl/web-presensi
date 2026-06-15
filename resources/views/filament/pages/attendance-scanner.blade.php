<x-filament-panels::page>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;700;800;900&display=swap');

        @keyframes scanLoad {
            0% { left: -50%; }
            100% { left: 100%; }
        }
        .scan-loader-bar {
            position: absolute; top: 0; left: 0; height: 100%; width: 50%;
            background: linear-gradient(90deg, #7c3aed, #a78bfa, #7c3aed);
            border-radius: 9999px;
            animation: scanLoad 1.5s ease-in-out infinite alternate;
        }
        .scan-loader-bar-green {
            position: absolute; top: 0; left: 0; height: 100%; width: 50%;
            background: linear-gradient(90deg, #059669, #34d399, #059669);
            border-radius: 9999px;
            animation: scanLoad 1.5s ease-in-out infinite alternate;
        }
        @keyframes faceRing {
            0%, 100% { transform: scale(1); opacity: 0.6; }
            50% { transform: scale(1.08); opacity: 1; }
        }
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
        @keyframes successPop {
            0% { transform: scale(0) rotate(-10deg); opacity: 0; }
            60% { transform: scale(1.15) rotate(5deg); }
            100% { transform: scale(1) rotate(0deg); opacity: 1; }
        }
        @keyframes slideUp {
            from { transform: translateY(30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        @keyframes shimmer {
            0% { background-position: -200% center; }
            100% { background-position: 200% center; }
        }
        .face-ring-active { animation: faceRing 1.5s ease-in-out infinite; }
        .hidden { display: none !important; }
        .register-btn-glow {
            background: linear-gradient(135deg, #059669, #10b981);
            box-shadow: 0 0 30px rgba(16, 185, 129, 0.4);
            animation: faceRing 2s ease-in-out infinite;
        }
        .register-panel {
            animation: slideUp 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
        }

        @media (max-width: 640px) {
            .responsive-grid { grid-template-columns: 1fr !important; gap: 1rem !important; }
            .scanner-footer { flex-direction: column !important; align-items: stretch !important; gap: 1.5rem !important; padding: 1.5rem !important; }
            .scanner-status { flex-direction: column !important; align-items: center !important; text-align: center; gap: 0.5rem !important; }
            .scanner-actions { flex-direction: column !important; width: 100% !important; gap: 0.75rem !important; }
            .scanner-btn { width: 100% !important; justify-content: center !important; }
        }
    </style>

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- Loading overlays (Livewire targets)                         --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    @teleport('body')
    <div wire:loading.flex wire:target="processAttendance" class="fixed inset-0 flex-col items-center justify-center bg-slate-900/80 backdrop-blur-md" style="display:none; z-index:2147483647 !important;">
        <div class="w-72 h-2.5 bg-slate-800/80 rounded-full overflow-hidden relative border border-white/10">
            <div class="scan-loader-bar"></div>
        </div>
        <div class="mt-6 flex flex-col items-center gap-2">
            <span class="text-violet-400 text-sm font-black uppercase tracking-[0.3em] animate-pulse">Memproses Presensi</span>
            <span class="text-slate-400 text-[10px] font-bold uppercase tracking-widest">Mohon Tunggu Sebentar...</span>
        </div>
    </div>

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

        {{-- ═══════════════════════════════════════ --}}
        {{-- Status Cards (Jam Masuk / Jam Pulang)   --}}
        {{-- ═══════════════════════════════════════ --}}
        <div class="responsive-grid" style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem;">
            <div style="background:rgba(15,23,42,0.6); backdrop-filter:blur(20px); border:1px solid rgba(255,255,255,0.1); border-radius:2rem; padding:1.75rem; box-shadow:0 20px 40px rgba(0,0,0,0.4); position:relative; overflow:hidden;">
                <div style="position:absolute; top:0; left:0; width:4px; height:100%; background:#7c3aed;"></div>
                <span style="color:#64748b; font-size:11px; font-weight:800; text-transform:uppercase; letter-spacing:0.2em; display:block; margin-bottom:0.5rem;">Jam Masuk</span>
                <span style="color:white; font-size:1.875rem; font-weight:900; font-style:italic; letter-spacing:-0.02em; display:block;">
                    {{ $attendance && $attendance->check_in_time ? \Illuminate\Support\Carbon::parse($attendance->check_in_time)->format('H:i') : '--:--' }}
                </span>
                @if($attendance && $attendance->status)
                    <div style="margin-top:1rem; display:inline-flex; align-items:center; gap:0.5rem; padding:0.25rem 0.75rem; border-radius:9999px; font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:0.05em;" @class([
                        'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' => $attendance->status === 'on_time',
                        'bg-amber-500/10 text-amber-400 border border-amber-500/20' => $attendance->status === 'late',
                    ])>
                        <div style="width:6px; height:6px; border-radius:50%;" @class([
                            'bg-emerald-400 animate-pulse' => $attendance->status === 'on_time',
                            'bg-amber-400 animate-pulse' => $attendance->status === 'late',
                        ])></div>
                        {{ $attendance->status === 'on_time' ? 'Tepat Waktu' : 'Terlambat' }}
                    </div>
                @endif
            </div>

            <div style="background:rgba(15,23,42,0.6); backdrop-filter:blur(20px); border:1px solid rgba(255,255,255,0.1); border-radius:2rem; padding:1.75rem; box-shadow:0 20px 40px rgba(0,0,0,0.4); position:relative; overflow:hidden;">
                <div style="position:absolute; top:0; left:0; width:4px; height:100%; background:#3b82f6;"></div>
                <span style="color:#64748b; font-size:11px; font-weight:800; text-transform:uppercase; letter-spacing:0.2em; display:block; margin-bottom:0.5rem;">Jam Pulang</span>
                <span style="color:white; font-size:1.875rem; font-weight:900; font-style:italic; letter-spacing:-0.02em; display:block;">
                    {{ $attendance && $attendance->check_out_time ? \Illuminate\Support\Carbon::parse($attendance->check_out_time)->format('H:i') : '--:--' }}
                </span>
                @if($attendance && $attendance->check_out_time)
                    <div style="margin-top:1rem; display:inline-flex; align-items:center; gap:0.5rem; padding:0.25rem 0.75rem; border-radius:9999px; font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:0.05em; background:rgba(59,130,246,0.1); color:#60a5fa; border:1px solid rgba(59,130,246,0.2);">
                        <div style="width:6px; height:6px; border-radius:50%; background:#60a5fa; box-shadow:0 0 10px #60a5fa;"></div>
                        Selesai
                    </div>
                @endif
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════════════════ --}}
        {{-- Main Card                                                    --}}
        {{-- ═══════════════════════════════════════════════════════════ --}}
        <div style="background:rgba(15,23,42,0.82); backdrop-filter:blur(30px); border:1px solid rgba(255,255,255,0.1); border-radius:2.5rem; overflow:hidden; box-shadow:0 40px 80px -20px rgba(0,0,0,0.8);">

            {{-- ─────────────────────────── Camera Area ─────────────────────────── --}}
            <div id="face-scanner-area" style="position:relative; width:100%; aspect-ratio:1/1; background:#000; border-radius:2.5rem 2.5rem 0 0; overflow:hidden;">

                <video id="face-video" style="width:100%; height:100%; object-fit:cover; transform:scaleX(-1); display:none;" autoplay playsinline muted></video>
                <canvas id="face-canvas" style="position:absolute; inset:0; width:100%; height:100%; transform:scaleX(-1); pointer-events:none;"></canvas>

                {{-- ── Idle ── --}}
                <div id="idle-state" style="position:absolute; inset:0; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:1.5rem; background:rgba(15,23,42,0.95);">
                    <div style="width:120px; height:120px; border-radius:50%; background:rgba(124,58,237,0.1); border:2px dashed rgba(124,58,237,0.4); display:flex; align-items:center; justify-content:center;">
                        <svg style="width:3rem; height:3rem; color:rgba(124,58,237,0.7);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                    </div>
                    <div style="text-align:center;">
                        <p style="color:#a78bfa; font-size:11px; font-weight:900; text-transform:uppercase; letter-spacing:0.3em; margin:0 0 0.5rem;">Verifikasi Wajah</p>
                        <p style="color:#475569; font-size:10px; font-weight:600; margin:0;">Klik tombol di bawah untuk memulai</p>
                    </div>
                </div>

                {{-- ── Loading ── --}}
                <div id="loading-state" style="display:none; position:absolute; inset:0; flex-direction:column; align-items:center; justify-content:center; gap:1.5rem; background:rgba(15,23,42,0.95);">
                    <svg style="width:3rem; height:3rem; color:#7c3aed; animation:spin 1s linear infinite;" fill="none" viewBox="0 0 24 24">
                        <circle style="opacity:0.25;" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path style="opacity:0.75;" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p id="loading-text" style="color:#a78bfa; font-size:11px; font-weight:900; text-transform:uppercase; letter-spacing:0.2em; margin:0;">Memuat Model AI...</p>
                </div>

                {{-- ── Detection Active Overlay ── --}}
                <div id="detection-overlay" style="display:none; position:absolute; inset:0; pointer-events:none; flex-direction:column; align-items:center; justify-content:center; z-index:10;">
                    <div id="face-oval" style="width:55%; aspect-ratio:3/4; border:3px solid rgba(124,58,237,0.5); border-radius:50%; box-shadow:0 0 30px rgba(124,58,237,0.2); position:relative; transition:border-color 0.4s, box-shadow 0.4s;" class="face-ring-active">
                        <div style="position:absolute; top:-2px; left:50%; transform:translateX(-50%); width:40px; height:3px; background:#7c3aed; border-radius:9999px; opacity:0.8;"></div>
                        <div style="position:absolute; bottom:-2px; left:50%; transform:translateX(-50%); width:40px; height:3px; background:#7c3aed; border-radius:9999px; opacity:0.8;"></div>
                    </div>
                    <div style="position:absolute; top:0; left:0; width:100%; height:2px; background:linear-gradient(to right, transparent, #7c3aed, transparent); box-shadow:0 0 15px #7c3aed; animation:scanMove 3s infinite ease-in-out;"></div>
                </div>

                {{-- ── Success State ── --}}
                <div id="success-state" style="display:none; position:absolute; inset:0; flex-direction:column; align-items:center; justify-content:center; gap:1.5rem; background:rgba(15,23,42,0.9); z-index:20;">
                    <div style="width:100px; height:100px; background:rgba(16,185,129,0.15); border-radius:50%; border:3px solid #10b981; display:flex; align-items:center; justify-content:center; animation:successPop 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards; box-shadow:0 0 40px rgba(16,185,129,0.4);">
                        <svg style="width:3rem; height:3rem; color:#10b981;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                    </div>
                    <div style="text-align:center;">
                        <p style="color:#10b981; font-size:14px; font-weight:900; text-transform:uppercase; letter-spacing:0.2em; margin:0 0 0.5rem;">Wajah Terverifikasi!</p>
                        <p style="color:#475569; font-size:10px; font-weight:600; margin:0;">Sedang memproses presensi...</p>
                    </div>
                </div>

                {{-- ── "Wajah Tidak Dikenali" Overlay — muncul setelah N kali gagal ── --}}
                <div id="unrecognized-overlay" style="display:none; position:absolute; inset:0; flex-direction:column; align-items:center; justify-content:center; gap:1.75rem; background:rgba(15,23,42,0.96); z-index:30; padding:2rem;">
                    {{-- Icon --}}
                    <div style="width:90px; height:90px; background:rgba(239,68,68,0.12); border-radius:50%; border:2px solid rgba(239,68,68,0.4); display:flex; align-items:center; justify-content:center;">
                        <svg style="width:2.5rem; height:2.5rem; color:#f87171;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.182 16.318A4.486 4.486 0 0012.016 15a4.486 4.486 0 00-3.198 1.318M21 12a9 9 0 11-18 0 9 9 0 0118 0zM9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75zm-.375 0h.008v.015h-.008V9.75zm5.625 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75zm-.375 0h.008v.015h-.008V9.75z" />
                        </svg>
                    </div>

                    {{-- Title --}}
                    <div style="text-align:center;">
                        <p style="color:#f87171; font-size:13px; font-weight:900; text-transform:uppercase; letter-spacing:0.2em; margin:0 0 0.5rem;">Wajah Tidak Dikenali</p>
                        <p style="color:#64748b; font-size:10px; font-weight:600; line-height:1.6; margin:0; max-width:260px;">Wajah Anda tidak cocok dengan data yang terdaftar.<br>Daftarkan wajah Anda untuk melanjutkan.</p>
                    </div>

                    {{-- Buttons --}}
                    <div style="display:flex; flex-direction:column; gap:0.75rem; width:100%; max-width:280px;">
                        {{-- Primary: Daftarkan Wajah --}}
                        <button id="open-register-btn"
                            style="width:100%; background:linear-gradient(135deg, #059669, #10b981); color:white; font-weight:900; font-size:12px; text-transform:uppercase; letter-spacing:0.15em; padding:1rem 1.5rem; border-radius:1.25rem; border:none; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:0.6rem; box-shadow:0 10px 30px -5px rgba(16,185,129,0.45); transition:all 0.3s;"
                            onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 15px 35px -5px rgba(16,185,129,0.55)';"
                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 30px -5px rgba(16,185,129,0.45)';">
                            <svg style="width:1rem; height:1rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                            </svg>
                            Daftarkan Wajah Saya
                        </button>

                        {{-- Secondary: Coba Lagi --}}
                        <button id="retry-from-unrecognized-btn"
                            style="width:100%; background:rgba(255,255,255,0.06); color:#94a3b8; font-weight:800; font-size:11px; text-transform:uppercase; letter-spacing:0.1em; padding:0.875rem 1.5rem; border-radius:1.25rem; border:1px solid rgba(255,255,255,0.1); cursor:pointer; transition:all 0.3s;"
                            onmouseover="this.style.background='rgba(255,255,255,0.1)'; this.style.color='white';"
                            onmouseout="this.style.background='rgba(255,255,255,0.06)'; this.style.color='#94a3b8';">
                            Coba Verifikasi Lagi
                        </button>
                    </div>
                </div>

                {{-- ── "Belum Ada Foto Profil" Overlay ── --}}
                <div id="no-photo-overlay" style="display:none; position:absolute; inset:0; flex-direction:column; align-items:center; justify-content:center; gap:1.75rem; background:rgba(15,23,42,0.96); z-index:30; padding:2rem;">
                    <div style="width:90px; height:90px; background:rgba(245,158,11,0.12); border-radius:50%; border:2px solid rgba(245,158,11,0.4); display:flex; align-items:center; justify-content:center;">
                        <svg style="width:2.5rem; height:2.5rem; color:#fbbf24;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                    </div>
                    <div style="text-align:center;">
                        <p style="color:#fbbf24; font-size:13px; font-weight:900; text-transform:uppercase; letter-spacing:0.2em; margin:0 0 0.5rem;">Wajah Belum Terdaftar</p>
                        <p style="color:#64748b; font-size:10px; font-weight:600; line-height:1.6; margin:0; max-width:260px;">Anda belum mendaftarkan wajah.<br>Daftarkan sekarang untuk dapat melakukan presensi.</p>
                    </div>
                    <div style="width:100%; max-width:280px;">
                        <button id="open-register-from-no-photo-btn"
                            style="width:100%; background:linear-gradient(135deg, #059669, #10b981); color:white; font-weight:900; font-size:12px; text-transform:uppercase; letter-spacing:0.15em; padding:1rem 1.5rem; border-radius:1.25rem; border:none; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:0.6rem; box-shadow:0 10px 30px -5px rgba(16,185,129,0.45); transition:all 0.3s;"
                            onmouseover="this.style.transform='translateY(-2px)';"
                            onmouseout="this.style.transform='translateY(0)';">
                            <svg style="width:1rem; height:1rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            Daftarkan Wajah Sekarang
                        </button>
                    </div>
                </div>

                {{-- ── Registered Success ── --}}
                <div id="registered-state" style="display:none; position:absolute; inset:0; flex-direction:column; align-items:center; justify-content:center; gap:1.5rem; background:rgba(15,23,42,0.95); z-index:35; padding:2rem;">
                    <div style="width:100px; height:100px; background:rgba(16,185,129,0.15); border-radius:50%; border:3px solid #10b981; display:flex; align-items:center; justify-content:center; animation:successPop 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards; box-shadow:0 0 40px rgba(16,185,129,0.4);">
                        <svg style="width:3rem; height:3rem; color:#10b981;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                    </div>
                    <div style="text-align:center;">
                        <p style="color:#10b981; font-size:14px; font-weight:900; text-transform:uppercase; letter-spacing:0.2em; margin:0 0 0.5rem;">Wajah Berhasil Didaftarkan!</p>
                        <p style="color:#64748b; font-size:10px; font-weight:600; margin:0;">Silakan mulai verifikasi kembali</p>
                    </div>
                    <button id="start-after-register-btn"
                        style="background:linear-gradient(135deg, #7c3aed, #6d28d9); color:white; font-weight:900; font-size:12px; text-transform:uppercase; letter-spacing:0.15em; padding:0.875rem 2rem; border-radius:1.25rem; border:none; cursor:pointer; box-shadow:0 10px 25px -5px rgba(109,40,217,0.5); transition:all 0.3s;">
                        Mulai Verifikasi
                    </button>
                </div>

                {{-- Status badge (shown during detection) --}}
                <div id="face-status-badge" style="display:none; position:absolute; bottom:1.5rem; left:50%; transform:translateX(-50%); padding:0.5rem 1.25rem; border-radius:9999px; font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:0.1em; z-index:15; white-space:nowrap;"></div>
            </div>

            {{-- ─────────────────────────── Footer ─────────────────────────── --}}
            <div class="scanner-footer" style="padding:2rem; background:rgba(15,23,42,0.9); border-top:1px solid rgba(255,255,255,0.05); display:flex; align-items:center; justify-content:space-between;">
                <div id="scanner-status" class="scanner-status" style="display:flex; align-items:center; gap:1rem;">
                    <div id="status-dot" style="width:12px; height:12px; background:#7c3aed; border-radius:50%; box-shadow:0 0 15px #7c3aed; position:relative;">
                        <div style="position:absolute; inset:-4px; border:1px solid #7c3aed; border-radius:50%; opacity:0.5; animation:ping 2s infinite;"></div>
                    </div>
                    <div style="display:flex; flex-direction:column;">
                        <span style="color:#64748b; font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:0.1em;">Status</span>
                        <span style="color:white; font-size:13px; font-weight:700;" id="scanner-status-text">Menunggu...</span>
                    </div>
                </div>

                <div class="scanner-actions" style="display:flex; gap:0.75rem;">
                    <button id="start-button" class="scanner-btn"
                        style="background:linear-gradient(135deg, #7c3aed, #6d28d9); color:white; font-weight:900; font-size:13px; text-transform:uppercase; letter-spacing:0.1em; padding:1rem 2rem; border-radius:1.25rem; border:none; cursor:pointer; box-shadow:0 15px 30px -5px rgba(109,40,217,0.5); transition:all 0.3s; display:flex; align-items:center; gap:0.5rem;"
                        onmouseover="this.style.transform='translateY(-2px) scale(1.02)';"
                        onmouseout="this.style.transform='translateY(0) scale(1)';">
                        <svg style="width:1.1rem; height:1.1rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                        Mulai Verifikasi
                    </button>

                    <button id="stop-button" class="hidden scanner-btn"
                        style="background:rgba(239,68,68,0.1); color:#f87171; font-weight:900; font-size:13px; text-transform:uppercase; letter-spacing:0.1em; padding:1rem 2rem; border-radius:1.25rem; border:1px solid rgba(239,68,68,0.2); cursor:pointer; transition:all 0.3s; display:none; align-items:center; justify-content:center;">
                        Berhenti
                    </button>
                </div>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════════════════ --}}
        {{-- PANEL: Pendaftaran Wajah                                    --}}
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
                    <p style="color:#64748b; font-size:10px; font-weight:600; margin:0;">Foto ini akan tersimpan sebagai foto profil Anda</p>
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

            {{-- Camera area --}}
            <div style="padding:2rem; display:flex; flex-direction:column; align-items:center; gap:1.5rem;">

                {{-- Video Circle --}}
                <div style="position:relative; width:260px; height:260px; border-radius:50%; overflow:hidden; border:4px solid rgba(16,185,129,0.5); box-shadow:0 0 40px rgba(16,185,129,0.2); background:#000; flex-shrink:0;">
                    <video id="register-video" style="width:100%; height:100%; object-fit:cover; transform:scaleX(-1);" autoplay playsinline muted></video>
                    <img id="register-preview" style="display:none; width:100%; height:100%; object-fit:cover; transform:scaleX(-1);" alt="preview" />

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

                {{-- Action Buttons --}}
                <div style="display:flex; flex-direction:column; gap:0.75rem; width:100%; max-width:320px;">
                    {{-- Capture --}}
                    <button id="capture-register-btn" class="hidden"
                        style="width:100%; background:linear-gradient(135deg, #059669, #10b981); color:white; font-weight:900; font-size:12px; text-transform:uppercase; letter-spacing:0.15em; padding:1rem; border-radius:1.25rem; border:none; cursor:pointer; display:none; align-items:center; justify-content:center; gap:0.5rem; box-shadow:0 10px 20px -5px rgba(16,185,129,0.4); transition:all 0.3s;">
                        <svg style="width:1.25rem; height:1.25rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z" />
                        </svg>
                        Ambil Foto
                    </button>

                    {{-- Confirm / Retake row (shown after capture) --}}
                    <div id="register-confirm-actions" style="display:none; gap:0.75rem;">
                        <button id="retake-register-btn"
                            style="flex:1; background:rgba(255,255,255,0.05); color:#94a3b8; border:1px solid rgba(255,255,255,0.1); font-weight:900; font-size:11px; text-transform:uppercase; letter-spacing:0.1em; padding:1rem; border-radius:1.25rem; cursor:pointer; transition:all 0.3s;">
                            Foto Ulang
                        </button>
                        <button id="confirm-register-btn"
                            style="flex:1; background:#10b981; color:white; font-weight:900; font-size:11px; text-transform:uppercase; letter-spacing:0.1em; padding:1rem; border-radius:1.25rem; border:none; cursor:pointer; box-shadow:0 10px 20px -5px rgba(16,185,129,0.4); transition:all 0.3s; display:flex; align-items:center; justify-content:center; gap:0.5rem;">
                            <svg style="width:1.1rem; height:1.1rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4.5 12.75l6 6 9-13.5" />
                            </svg>
                            Simpan Wajah
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════════════════ --}}
        {{-- Info Grid                                                    --}}
        {{-- ═══════════════════════════════════════════════════════════ --}}
        <div class="responsive-grid" style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem;">
            <div style="background:rgba(15,23,42,0.4); backdrop-filter:blur(10px); border:1px solid rgba(255,255,255,0.05); padding:1.5rem; border-radius:2rem; display:flex; gap:1.25rem; align-items:center;">
                <div style="width:3rem; height:3rem; background:rgba(124,58,237,0.1); border-radius:1rem; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <svg style="width:1.5rem; height:1.5rem; color:#7c3aed;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                </div>
                <div>
                    <h4 style="color:white; font-size:12px; font-weight:800; text-transform:uppercase; letter-spacing:0.1em; margin-bottom:0.25rem;">Panduan</h4>
                    <p style="color:#64748b; font-size:10px; line-height:1.4; margin:0;">Posisikan wajah di kamera. Pastikan pencahayaan cukup.</p>
                </div>
            </div>

            <div style="background:rgba(15,23,42,0.4); backdrop-filter:blur(10px); border:1px solid rgba(255,255,255,0.05); padding:1.5rem; border-radius:2rem; display:flex; gap:1.25rem; align-items:center;">
                <div style="width:3rem; height:3rem; background:rgba(59,130,246,0.1); border-radius:1rem; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <x-heroicon-o-map-pin style="width:1.5rem; height:1.5rem; color:#3b82f6;" />
                </div>
                <div style="overflow:hidden;">
                    <h4 style="color:white; font-size:12px; font-weight:800; text-transform:uppercase; letter-spacing:0.1em; margin-bottom:0.25rem;">Lokasi</h4>
                    <p id="location-status" style="color:#64748b; font-size:10px; font-style:italic; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; margin:0;">Mencari koordinat...</p>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/@vladmandic/face-api/dist/face-api.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {

        // ── DOM refs ──────────────────────────────────────────────────────────
        const startBtn              = document.getElementById('start-button');
        const stopBtn               = document.getElementById('stop-button');
        const statusText            = document.getElementById('scanner-status-text');
        const statusDot             = document.getElementById('status-dot');
        const locationText          = document.getElementById('location-status');
        const faceVideo             = document.getElementById('face-video');
        const faceCanvas            = document.getElementById('face-canvas');
        const idleState             = document.getElementById('idle-state');
        const loadingState          = document.getElementById('loading-state');
        const loadingText           = document.getElementById('loading-text');
        const detectionOverlay      = document.getElementById('detection-overlay');
        const successState          = document.getElementById('success-state');
        const faceOval              = document.getElementById('face-oval');
        const faceStatusBadge       = document.getElementById('face-status-badge');
        const unrecognizedOverlay   = document.getElementById('unrecognized-overlay');
        const noPhotoOverlay        = document.getElementById('no-photo-overlay');
        const registeredState       = document.getElementById('registered-state');

        // Register Panel
        const registerPanel         = document.getElementById('register-panel');
        const registerVideo         = document.getElementById('register-video');
        const registerPreview       = document.getElementById('register-preview');
        const registerLoading       = document.getElementById('register-loading');
        const registerGuideOverlay  = document.getElementById('register-guide-overlay');
        const captureRegisterBtn    = document.getElementById('capture-register-btn');
        const registerConfirmActions= document.getElementById('register-confirm-actions');
        const retakeRegisterBtn     = document.getElementById('retake-register-btn');
        const confirmRegisterBtn    = document.getElementById('confirm-register-btn');
        const registerHint          = document.getElementById('register-hint');
        const closeRegisterPanelBtn = document.getElementById('close-register-panel-btn');
        const openRegisterBtn       = document.getElementById('open-register-btn');
        const openRegisterFromNoPhotoBtn = document.getElementById('open-register-from-no-photo-btn');
        const retryFromUnrecognizedBtn   = document.getElementById('retry-from-unrecognized-btn');
        const startAfterRegisterBtn      = document.getElementById('start-after-register-btn');

        // ── State ─────────────────────────────────────────────────────────────
        let userLat             = null;
        let userLon             = null;
        let videoStream         = null;
        let registerStream      = null;
        let detectionInterval   = null;
        let referenceDescriptor = null;
        let modelsLoaded        = false;
        let isProcessing        = false;
        let consecutiveMatches  = 0;
        let consecutiveMisses   = 0;
        let capturedRegisterB64 = null;

        const REQUIRED_MATCHES      = 3;
        const MISS_THRESHOLD        = 20;   // frames before showing "unrecognized" overlay
        const FACE_MATCH_THRESHOLD  = 0.55;
        const MODEL_BASE_URL        = 'https://cdn.jsdelivr.net/npm/@vladmandic/face-api/model';

        // ── Helpers ───────────────────────────────────────────────────────────
        function setStatus(text, color = '#a78bfa') {
            statusText.innerText = text;
            statusDot.style.background = color;
            statusDot.style.boxShadow = `0 0 15px ${color}`;
        }

        function showBadge(text, type = 'info') {
            const colors = {
                info:    { bg: 'rgba(124,58,237,0.25)',  color: '#a78bfa', border: 'rgba(124,58,237,0.4)' },
                success: { bg: 'rgba(16,185,129,0.25)',  color: '#10b981', border: 'rgba(16,185,129,0.4)' },
                warning: { bg: 'rgba(245,158,11,0.25)',  color: '#f59e0b', border: 'rgba(245,158,11,0.4)' },
                error:   { bg: 'rgba(239,68,68,0.25)',   color: '#f87171', border: 'rgba(239,68,68,0.4)' },
            };
            const c = colors[type] || colors.info;
            Object.assign(faceStatusBadge.style, { background: c.bg, color: c.color, border: `1px solid ${c.border}`, display: 'block' });
            faceStatusBadge.innerText = text;
        }
        function hideBadge() { faceStatusBadge.style.display = 'none'; }

        // ── Load face-api models ──────────────────────────────────────────────
        async function loadModels() {
            if (modelsLoaded) return true;
            try {
                loadingText.innerText = 'Memuat Model AI...';
                await faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_BASE_URL);
                loadingText.innerText = 'Memuat Landmark Model...';
                await faceapi.nets.faceLandmark68TinyNet.loadFromUri(MODEL_BASE_URL);
                loadingText.innerText = 'Memuat Recognition Model...';
                await faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_BASE_URL);
                modelsLoaded = true;
                return true;
            } catch (e) {
                console.error('Model load error:', e);
                return false;
            }
        }

        // ── Load reference descriptor ─────────────────────────────────────────
        async function loadReferenceDescriptor() {
            try {
                loadingText.innerText = 'Memuat Foto Referensi...';
                const resp = await fetch('/face-reference', { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                const data = await resp.json();

                if (!data.success) {
                    // No photo registered → show no-photo overlay
                    stopVideoStream();
                    idleState.style.display = 'none';
                    loadingState.style.display = 'none';
                    noPhotoOverlay.style.display = 'flex';
                    setStatus('Wajah belum terdaftar', '#f59e0b');
                    startBtn.classList.remove('hidden');
                    stopBtn.classList.add('hidden');
                    stopBtn.style.display = 'none';
                    return false;
                }

                loadingText.innerText = 'Menganalisis Wajah Referensi...';
                const img = await faceapi.fetchImage(data.photo_url);
                const detection = await faceapi
                    .detectSingleFace(img, new faceapi.TinyFaceDetectorOptions())
                    .withFaceLandmarks(true)
                    .withFaceDescriptor();

                if (!detection) {
                    // Photo exists but no face detected in it
                    stopVideoStream();
                    idleState.style.display = 'none';
                    loadingState.style.display = 'none';
                    noPhotoOverlay.style.display = 'flex';
                    noPhotoOverlay.querySelector('p:nth-child(2)').innerText = 'Foto profil ada, namun wajah tidak terdeteksi. Daftarkan ulang wajah Anda.';
                    setStatus('Foto profil tidak valid', '#f59e0b');
                    startBtn.classList.remove('hidden');
                    stopBtn.classList.add('hidden');
                    stopBtn.style.display = 'none';
                    return false;
                }

                referenceDescriptor = detection.descriptor;
                return true;
            } catch (e) {
                console.error('Reference load error:', e);
                new FilamentNotification().title('Gagal memuat foto referensi').body('Coba lagi.').danger().send();
                return false;
            }
        }

        // ── Camera helpers ────────────────────────────────────────────────────
        async function startCamera() {
            try {
                videoStream = await navigator.mediaDevices.getUserMedia({
                    video: { facingMode: { ideal: 'user' }, width: { ideal: 1280 }, height: { ideal: 720 } },
                    audio: false
                });
            } catch (e) {
                try { videoStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: false }); }
                catch (e2) {
                    let msg = 'Mohon izinkan akses kamera di browser Anda.';
                    if (e2.name === 'NotAllowedError') msg = 'Izin kamera ditolak.';
                    if (!window.isSecureContext) msg = 'Kamera hanya dapat diakses melalui HTTPS atau localhost.';
                    new FilamentNotification().title('Gagal Mengakses Kamera').body(msg).danger().send();
                    return false;
                }
            }
            faceVideo.srcObject = videoStream;
            await new Promise(resolve => {
                faceVideo.onloadedmetadata = () => faceVideo.play().then(resolve).catch(resolve);
                if (faceVideo.readyState >= 2) resolve();
            });
            faceCanvas.width = faceVideo.videoWidth;
            faceCanvas.height = faceVideo.videoHeight;
            return true;
        }

        function stopVideoStream() {
            clearInterval(detectionInterval);
            detectionInterval = null;
            if (videoStream) { videoStream.getTracks().forEach(t => t.stop()); videoStream = null; }
            faceVideo.style.display = 'none';
            const ctx = faceCanvas.getContext('2d');
            ctx.clearRect(0, 0, faceCanvas.width, faceCanvas.height);
        }

        function stopEverything() {
            stopVideoStream();
            idleState.style.display = 'flex';
            loadingState.style.display = 'none';
            detectionOverlay.style.display = 'none';
            successState.style.display = 'none';
            unrecognizedOverlay.style.display = 'none';
            noPhotoOverlay.style.display = 'none';
            registeredState.style.display = 'none';
            hideBadge();
            faceOval.className = 'face-ring-active';
            faceOval.style.borderColor = 'rgba(124,58,237,0.5)';
            faceOval.style.boxShadow = '0 0 30px rgba(124,58,237,0.2)';
            startBtn.classList.remove('hidden');
            stopBtn.classList.add('hidden');
            stopBtn.style.display = 'none';
            setStatus('Menunggu...', '#7c3aed');
            consecutiveMatches = 0;
            consecutiveMisses  = 0;
            isProcessing       = false;
        }

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

        // ── Detection loop ────────────────────────────────────────────────────
        async function runDetectionLoop() {
            if (isProcessing) return;
            const options = new faceapi.TinyFaceDetectorOptions({ inputSize: 320, scoreThreshold: 0.5 });

            let detection;
            try {
                detection = await faceapi
                    .detectSingleFace(faceVideo, options)
                    .withFaceLandmarks(true)
                    .withFaceDescriptor();
            } catch (e) { return; }

            if (isProcessing) return;

            const ctx = faceCanvas.getContext('2d');
            ctx.clearRect(0, 0, faceCanvas.width, faceCanvas.height);

            if (!detection) {
                consecutiveMatches = 0;
                consecutiveMisses++;
                showBadge('Posisikan wajah di depan kamera', 'info');
                setStatus('Mendeteksi wajah...', '#7c3aed');
                faceOval.className = 'face-ring-active';
                faceOval.style.borderColor = 'rgba(124,58,237,0.5)';
                faceOval.style.boxShadow = '0 0 30px rgba(124,58,237,0.2)';
                return;
            }

            // Draw bounding box
            const dims = faceapi.matchDimensions(faceCanvas, faceVideo, true);
            const resized = faceapi.resizeResults(detection, dims);
            faceapi.draw.drawDetections(faceCanvas, resized);

            const distance = faceapi.euclideanDistance(referenceDescriptor, detection.descriptor);
            const isMatch  = distance <= FACE_MATCH_THRESHOLD;

            if (isMatch) {
                consecutiveMisses = 0;
                consecutiveMatches++;
                const pct = Math.round((1 - distance) * 100);
                showBadge(`Kecocokan: ${pct}% — (${consecutiveMatches}/${REQUIRED_MATCHES})`, 'success');
                setStatus('Wajah cocok!', '#10b981');
                faceOval.className = '';
                faceOval.style.borderColor = '#10b981';
                faceOval.style.boxShadow = '0 0 40px rgba(16,185,129,0.5)';

                if (consecutiveMatches >= REQUIRED_MATCHES) {
                    isProcessing = true;
                    clearInterval(detectionInterval);
                    const snapshot = captureSnapshot(faceVideo);

                    detectionOverlay.style.display = 'none';
                    successState.style.display = 'flex';
                    hideBadge();
                    setStatus('Presensi diproses...', '#10b981');

                    if (!userLat || !userLon) {
                        new FilamentNotification().title('GPS belum aktif').body('Mohon aktifkan GPS dan coba lagi.').danger().send();
                        setTimeout(stopEverything, 1500);
                        return;
                    }
                    @this.call('processAttendance', userLat, userLon, snapshot);
                }
            } else {
                consecutiveMatches = 0;
                consecutiveMisses++;
                const pct = Math.round((1 - distance) * 100);
                showBadge(`Wajah tidak cocok (${pct}%)`, 'warning');
                setStatus('Wajah tidak dikenali', '#f59e0b');
                faceOval.className = '';
                faceOval.style.borderColor = 'rgba(245,158,11,0.6)';
                faceOval.style.boxShadow = '0 0 30px rgba(245,158,11,0.3)';

                // After enough misses → show "unrecognized" overlay
                if (consecutiveMisses >= MISS_THRESHOLD) {
                    isProcessing = true;
                    clearInterval(detectionInterval);
                    stopVideoStream();
                    detectionOverlay.style.display = 'none';
                    unrecognizedOverlay.style.display = 'flex';
                    hideBadge();
                    setStatus('Wajah tidak dikenali', '#ef4444');
                    startBtn.classList.remove('hidden');
                    stopBtn.classList.add('hidden');
                    stopBtn.style.display = 'none';
                }
            }
        }

        // ── Main start flow ───────────────────────────────────────────────────
        async function startVerification() {
            startBtn.classList.add('hidden');
            stopBtn.classList.remove('hidden');
            stopBtn.style.display = 'flex';
            idleState.style.display = 'none';
            unrecognizedOverlay.style.display = 'none';
            noPhotoOverlay.style.display = 'none';
            registeredState.style.display = 'none';
            loadingState.style.display = 'flex';
            setStatus('Mempersiapkan...', '#7c3aed');
            consecutiveMatches = 0;
            consecutiveMisses  = 0;
            isProcessing       = false;

            // 1. GPS
            if (!userLat || !userLon) {
                setStatus('Meminta akses lokasi...', '#7c3aed');
                const geoOk = await new Promise(resolve => {
                    if (!navigator.geolocation) { resolve(false); return; }
                    const ok   = pos => { userLat = pos.coords.latitude; userLon = pos.coords.longitude; locationText.innerText = `Koordinat: ${userLat.toFixed(6)}, ${userLon.toFixed(6)}`; locationText.style.cssText = 'color:#60a5fa; font-style:normal; font-size:10px; overflow:hidden; text-overflow:ellipsis;'; resolve(true); };
                    const fail = () => navigator.geolocation.getCurrentPosition(ok, () => resolve(false), { enableHighAccuracy: false, timeout: 15000 });
                    navigator.geolocation.getCurrentPosition(ok, fail, { enableHighAccuracy: true, timeout: 8000 });
                });
                if (!geoOk) {
                    new FilamentNotification().title('Gagal Akses GPS').body('Mohon izinkan akses lokasi di browser Anda.').danger().send();
                    stopEverything(); return;
                }
            }

            // 2. Load AI models
            const modelsOk = await loadModels();
            if (!modelsOk) {
                new FilamentNotification().title('Gagal memuat model AI').body('Pastikan koneksi internet stabil dan coba lagi.').danger().send();
                stopEverything(); return;
            }

            // 3. Load reference
            const refOk = await loadReferenceDescriptor();
            if (!refOk) return; // loadReferenceDescriptor handles UI itself

            // 4. Start camera
            loadingText.innerText = 'Membuka Kamera...';
            const camOk = await startCamera();
            if (!camOk) { stopEverything(); return; }

            // 5. Show detection UI
            faceVideo.style.display = 'block';
            loadingState.style.display = 'none';
            detectionOverlay.style.display = 'flex';
            setStatus('Mendeteksi wajah...', '#7c3aed');

            detectionInterval = setInterval(runDetectionLoop, 500);
        }

        // ── Register Face Panel ───────────────────────────────────────────────
        async function openRegisterPanel() {
            // Hide overlays
            unrecognizedOverlay.style.display = 'none';
            noPhotoOverlay.style.display = 'none';

            // Show panel
            registerPanel.classList.remove('hidden');
            registerPanel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });

            // Reset panel state
            registerPreview.style.display = 'none';
            registerVideo.style.display = 'block';
            captureRegisterBtn.style.display = 'none';
            registerConfirmActions.style.display = 'none';
            registerLoading.style.display = 'flex';
            registerGuideOverlay.style.display = 'none';
            registerHint.innerText = 'Memuat kamera depan...';
            capturedRegisterB64 = null;

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

        function stopRegisterCamera() {
            if (registerStream) { registerStream.getTracks().forEach(t => t.stop()); registerStream = null; }
        }

        function captureRegisterPhoto() {
            capturedRegisterB64 = captureSnapshot(registerVideo);
            registerPreview.src = capturedRegisterB64;
            registerPreview.style.display = 'block';
            registerVideo.style.display   = 'none';
            registerGuideOverlay.style.display = 'none';
            captureRegisterBtn.style.display   = 'none';
            registerConfirmActions.style.display = 'flex';
            registerHint.innerText = 'Apakah foto wajah Anda sudah jelas?';
            stopRegisterCamera();
        }

        function closeRegisterPanel() {
            stopRegisterCamera();
            registerPanel.classList.add('hidden');
            capturedRegisterB64 = null;
        }

        // ── Event Listeners ───────────────────────────────────────────────────
        startBtn.addEventListener('click', startVerification);
        stopBtn.addEventListener('click', stopEverything);

        // Open register panel from overlays
        openRegisterBtn.addEventListener('click', openRegisterPanel);
        openRegisterFromNoPhotoBtn.addEventListener('click', openRegisterPanel);

        // Retry verification from unrecognized overlay
        retryFromUnrecognizedBtn.addEventListener('click', () => {
            unrecognizedOverlay.style.display = 'none';
            startVerification();
        });

        // Register panel controls
        closeRegisterPanelBtn.addEventListener('click', () => {
            closeRegisterPanel();
            // Restore idle or show noPhoto depending on state
            idleState.style.display = 'flex';
        });

        captureRegisterBtn.addEventListener('click', captureRegisterPhoto);

        retakeRegisterBtn.addEventListener('click', async () => {
            registerPreview.style.display = 'none';
            registerVideo.style.display   = 'block';
            captureRegisterBtn.style.display  = 'flex';
            registerConfirmActions.style.display = 'none';
            registerHint.innerText = 'Memuat kamera...';
            capturedRegisterB64 = null;

            try {
                registerStream = await navigator.mediaDevices.getUserMedia({
                    video: { facingMode: { ideal: 'user' } }, audio: false
                });
            } catch(e) {
                registerStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: false });
            }
            registerVideo.srcObject = registerStream;
            registerVideo.onloadedmetadata = () => {
                registerVideo.play().catch(() => {});
                registerGuideOverlay.style.display = 'flex';
                registerHint.innerText = 'Posisikan wajah di dalam lingkaran';
            };
            if (registerVideo.readyState >= 2) registerVideo.onloadedmetadata();
        });

        confirmRegisterBtn.addEventListener('click', () => {
            if (!capturedRegisterB64) return;
            stopRegisterCamera();
            registerPanel.classList.add('hidden');
            // Call Livewire to save face
            @this.call('registerFace', capturedRegisterB64);
        });

        // After verified face → start verification again
        startAfterRegisterBtn.addEventListener('click', () => {
            registeredState.style.display = 'none';
            referenceDescriptor = null; // Force reload reference
            startVerification();
        });

        // Livewire events
        window.addEventListener('attendance-success', () => setTimeout(stopEverything, 1500));
        window.addEventListener('face-registered', () => {
            // Show success state inside camera area
            referenceDescriptor = null; // Reset so it reloads on next verification
            idleState.style.display     = 'none';
            loadingState.style.display  = 'none';
            unrecognizedOverlay.style.display = 'none';
            noPhotoOverlay.style.display      = 'none';
            registeredState.style.display     = 'flex';
            startBtn.classList.remove('hidden');
            stopBtn.classList.add('hidden');
            stopBtn.style.display = 'none';
            setStatus('Wajah terdaftar!', '#10b981');
        });

        // Secure context check
        if (!window.isSecureContext && !['localhost','127.0.0.1'].includes(window.location.hostname)) {
            setStatus('Error: Butuh HTTPS.', '#ef4444');
        }
    });
    </script>
    @endpush

    <style>
        #face-video, #face-canvas, #face-scanner-area { border-radius: 2.5rem 2.5rem 0 0; }
        #face-canvas { position: absolute !important; }
        #register-confirm-actions { display: flex; }
    </style>
</x-filament-panels::page>
