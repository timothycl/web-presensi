<x-filament-panels::page>
    <div style="font-family: 'Outfit', sans-serif; letter-spacing: -0.01em; max-width: 1000px; margin: 40px auto; padding: 0 20px;">
        {{-- Header Section --}}
        <div style="margin-bottom: 60px; text-align: center; position: relative;">
            <div style="position: absolute; inset: 0; top: -100px; background: rgba(245, 158, 11, 0.08); filter: blur(100px); border-radius: 9999px; pointer-events: none;"></div>
            
            <div style="display: inline-flex; align-items: center; gap: 8px; px: 12px; py: 4px; border-radius: 9999px; background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); margin-bottom: 24px; padding: 6px 16px;">
                <div style="width: 6px; height: 6px; border-radius: 50%; background: #f59e0b;"></div>
                <span style="color: rgba(245, 158, 11, 0.8); font-weight: 900; font-size: 9px; text-transform: uppercase; letter-spacing: 0.3em;">Tutorial Instalasi</span>
            </div>

            <h1 style="color: white; font-size: clamp(2rem, 8vw, 3.5rem); font-weight: 900; tracking: -0.04em; line-height: 0.9; text-transform: uppercase; font-style: italic; margin-bottom: 24px; margin-top: 0;">
                Panduan Akses <span style="background: linear-gradient(to bottom right, #fbbf24, #fde68a, white); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Mobile</span>
            </h1>
            
            <p style="color: rgba(255, 255, 255, 0.5); font-size: 16px; max-width: 600px; margin: 0 auto; font-weight: 500; line-height: 1.6;">
                Gunakan aplikasi presensi dengan lebih <span style="color: white; font-weight: 700;">cepat & stabil</span> melalui fitur Progressive Web App (PWA) di handphone Anda.
            </p>
        </div>

        {{-- Langkah 1: QR Code Section --}}
        <div class="p-6 sm:p-10 flex-col lg:flex-row items-center justify-between" style="position: relative; background: rgba(15, 23, 42, 0.75); backdrop-filter: blur(40px); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 40px; margin-bottom: 40px; box-shadow: 0 30px 60px -12px rgba(0, 0, 0, 0.6); overflow: hidden; display: flex; flex-wrap: wrap; gap: 40px;">
            {{-- Decorative Spotlight --}}
            <div style="position: absolute; width: 300px; height: 300px; background: radial-gradient(circle, rgba(245, 158, 11, 0.1) 0%, transparent 70%); top: -150px; right: -150px; border-radius: 50%; pointer-events: none;"></div>

            {{-- Content Area --}}
            <div style="flex: 1 1 400px; display: flex; flex-direction: column; gap: 20px;">
                <div style="display: inline-flex; align-items: center; gap: 8px; align-self: flex-start; padding: 6px 16px; border-radius: 9999px; background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.25);">
                    <div style="width: 6px; height: 6px; border-radius: 50%; background: #f59e0b; box-shadow: 0 0 8px #f59e0b;"></div>
                    <span style="color: #f59e0b; font-weight: 800; font-size: 9px; text-transform: uppercase; letter-spacing: 0.2em;">Langkah 1</span>
                </div>

                <h2 style="color: white; font-size: 28px; font-weight: 900; text-transform: uppercase; font-style: italic; letter-spacing: -0.02em; margin: 0; line-height: 1.1;">
                    Pindai QR Code untuk Akses Mobile
                </h2>

                <p style="color: rgba(255, 255, 255, 0.5); font-size: 14px; line-height: 1.6; font-weight: 500; margin: 0;">
                    Buka kamera handphone Anda dan arahkan ke QR Code di samping untuk membuka aplikasi presensi secara instan. 
                    <span style="color: white; font-weight: 700;">Tidak perlu mengetik URL secara manual!</span>
                </p>

                {{-- Interactive URL box --}}
                <div style="display: flex; flex-direction: column; gap: 8px; margin-top: 10px;">
                    <span style="color: rgba(255, 255, 255, 0.3); font-weight: 800; font-size: 9px; text-transform: uppercase; letter-spacing: 0.15em;">Link Akses Presensi:</span>
                    <div style="display: flex; align-items: center; gap: 12px; padding: 12px 20px; background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.06); border-radius: 16px;">
                        <x-heroicon-o-link style="width: 16px; height: 16px; color: #f59e0b;" />
                        <span style="color: rgba(255, 255, 255, 0.75); font-family: monospace; font-size: 13px; font-weight: 600; letter-spacing: -0.02em; word-break: break-all;">
                            {{ url('/admin/login') }}
                        </span>
                    </div>
                </div>

                {{-- Direct Access Button (for mobile users) --}}
                <div style="margin-top: 10px; display: flex; flex-wrap: wrap; gap: 16px; align-items: center;">
                    <a href="{{ url('/admin/login') }}" target="_blank" style="display: inline-flex; align-items: center; gap: 10px; px: 24px; py: 14px; border-radius: 16px; background: #f59e0b; color: #020617; font-weight: 900; font-size: 12px; text-transform: uppercase; letter-spacing: 0.1em; transition: all 0.3s; text-decoration: none; padding: 14px 28px; box-shadow: 0 10px 20px -5px rgba(245, 158, 11, 0.3);" class="hover:bg-amber-400 hover:-translate-y-0.5">
                        <x-heroicon-s-arrow-top-right-on-square style="width: 16px; height: 16px;" />
                        <span>Buka Langsung di HP</span>
                    </a>
                    <span style="color: rgba(255, 255, 255, 0.3); font-size: 11px; font-weight: 500; font-style: italic;">
                        *Ketuk jika Anda sedang membuka halaman ini melalui HP.
                    </span>
                </div>
            </div>

            {{-- QR Code Container --}}
            <div style="flex: 0 0 auto; margin: 0 auto; display: flex; flex-direction: column; align-items: center; gap: 16px; relative; z-index: 10;">
                <div style="position: relative; padding: 20px; background: white; border-radius: 32px; box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4); display: flex; justify-content: center; align-items: center;" class="group-hover:scale-105 transition-transform duration-500">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data={{ url('/admin/login') }}" alt="QR Code Login" style="width: 160px; height: 160px; display: block;">
                </div>
                <div style="display: flex; flex-direction: column; align-items: center; gap: 4px;">
                    <span style="color: rgba(255, 255, 255, 0.4); font-weight: 800; font-size: 9px; text-transform: uppercase; letter-spacing: 0.25em;">Pindai untuk Login</span>
                    <span style="color: #f59e0b; font-weight: 700; font-size: 11px; font-family: monospace;">{{ parse_url(url('/'), PHP_URL_HOST) }}</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            {{-- iOS / iPhone Card --}}
            <div class="p-6 sm:p-10" style="position: relative; background: rgba(15, 23, 42, 0.75); backdrop-filter: blur(40px); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 40px; display: flex; flex-direction: column; gap: 40px; box-shadow: 0 30px 60px -12px rgba(0, 0, 0, 0.6);">
                <div style="display: flex; align-items: center; gap: 20px;">
                    <div style="width: 56px; height: 56px; background: rgba(255, 255, 255, 0.05); border-radius: 16px; display: flex; align-items: center; justify-content: center; border: 1px solid rgba(255, 255, 255, 0.1);">
                        <x-heroicon-o-device-phone-mobile style="width: 28px; height: 28px; color: white;" />
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 4px;">
                        <h2 style="color: white; font-size: 20px; font-weight: 900; text-transform: uppercase; font-style: italic; letter-spacing: -0.02em; margin: 0;">iOS / iPhone</h2>
                        <p style="color: #f59e0b; font-weight: 800; font-size: 9px; text-transform: uppercase; letter-spacing: 0.2em; margin: 0;">Safari Browser Required</p>
                    </div>
                </div>

                <div style="display: flex; flex-direction: column; gap: 32px;">
                    @php
                        $iosSteps = [
                            ['title' => 'Buka Browser Safari', 'desc' => 'Masuk ke URL aplikasi melalui browser Safari bawaan iPhone Anda.'],
                            ['title' => 'Klik Ikon Share', 'desc' => 'Ketuk ikon kotak dengan panah ke atas di bagian bawah layar.'],
                            ['title' => 'Add to Home Screen', 'desc' => 'Cari dan pilih menu "Add to Home Screen" atau "Tambah ke Layar Utama".'],
                            ['title' => 'Konfirmasi "Add"', 'desc' => 'Klik tombol "Add" di pojok kanan atas untuk menyelesaikan instalasi.'],
                        ];
                    @endphp

                    @foreach($iosSteps as $index => $step)
                        <div style="display: flex; gap: 24px; align-items: flex-start;">
                            <div style="flex: none; width: 36px; height: 36px; border-radius: 12px; background: linear-gradient(to bottom right, #fbbf24, #d97706); display: flex; align-items: center; justify-content: center; font-weight: 900; color: #020617; font-size: 14px; shadow: 0 10px 20px rgba(245, 158, 11, 0.2);">
                                {{ $index + 1 }}
                            </div>
                            <div style="padding-top: 2px;">
                                <h3 style="color: white; font-weight: 700; font-size: 16px; margin: 0 0 4px 0; line-height: 1.2;">{{ $step['title'] }}</h3>
                                <p style="color: rgba(255, 255, 255, 0.4); font-size: 13px; line-height: 1.5; font-weight: 500; margin: 0;">{{ $step['desc'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Android Card --}}
            <div class="p-6 sm:p-10" style="position: relative; background: rgba(15, 23, 42, 0.75); backdrop-filter: blur(40px); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 40px; display: flex; flex-direction: column; gap: 40px; box-shadow: 0 30px 60px -12px rgba(0, 0, 0, 0.6);">
                <div style="display: flex; align-items: center; gap: 20px;">
                    <div style="width: 56px; height: 56px; background: rgba(255, 255, 255, 0.05); border-radius: 16px; display: flex; align-items: center; justify-content: center; border: 1px solid rgba(255, 255, 255, 0.1);">
                        <x-heroicon-o-command-line style="width: 28px; height: 28px; color: white;" />
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 4px;">
                        <h2 style="color: white; font-size: 20px; font-weight: 900; text-transform: uppercase; font-style: italic; letter-spacing: -0.02em; margin: 0;">Android OS</h2>
                        <p style="color: #10b981; font-weight: 800; font-size: 9px; text-transform: uppercase; letter-spacing: 0.2em; margin: 0;">Chrome Browser Required</p>
                    </div>
                </div>

                <div style="display: flex; flex-direction: column; gap: 32px;">
                    @php
                        $androidSteps = [
                            ['title' => 'Buka Google Chrome', 'desc' => 'Luncurkan Chrome dan masuk ke URL aplikasi presensi Anda.'],
                            ['title' => 'Klik Titik Tiga', 'desc' => 'Ketuk ikon titik tiga (⋮) di pojok kanan atas layar browser.'],
                            ['title' => 'Pilih "Install App"', 'desc' => 'Klik menu "Install App" atau "Tambahkan ke Layar Utama".'],
                            ['title' => 'Mulai Gunakan', 'desc' => 'Konfirmasi instalasi dan buka aplikasi melalui ikon di menu HP.'],
                        ];
                    @endphp

                    @foreach($androidSteps as $index => $step)
                        <div style="display: flex; gap: 24px; align-items: flex-start;">
                            <div style="flex: none; width: 36px; height: 36px; border-radius: 12px; background: linear-gradient(to bottom right, #34d399, #059669); display: flex; align-items: center; justify-content: center; font-weight: 900; color: #020617; font-size: 14px; shadow: 0 10px 20px rgba(16, 185, 129, 0.2);">
                                {{ $index + 1 }}
                            </div>
                            <div style="padding-top: 2px;">
                                <h3 style="color: white; font-weight: 700; font-size: 16px; margin: 0 0 4px 0; line-height: 1.2;">{{ $step['title'] }}</h3>
                                <p style="color: rgba(255, 255, 255, 0.4); font-size: 13px; line-height: 1.5; font-weight: 500; margin: 0;">{{ $step['desc'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Explainer Card --}}
        <div class="p-6 sm:p-10" style="margin-top: 60px; position: relative; overflow: hidden; border-radius: 40px; border: 1px solid rgba(255, 255, 255, 0.08); background: linear-gradient(135deg, rgba(255,255,255,0.03) 0%, rgba(255,255,255,0) 100%);">
            <h3 style="color: white; font-size: 20px; font-weight: 900; text-transform: uppercase; font-style: italic; margin-bottom: 16px; margin-top: 0;">Apa itu Progressive Web App?</h3>
            <p style="color: rgba(255, 255, 255, 0.5); font-size: 14px; line-height: 1.6; font-weight: 500; font-style: italic; margin: 0;">
                PWA memungkinkan situs web ini berfungsi seperti <span style="color: #f59e0b; font-weight: 700;">Aplikasi Native</span>. 
                Anda akan mendapatkan tampilan <span style="color: white; font-weight: 700;">Full-Screen</span> tanpa bar navigasi browser, 
                performa yang lebih responsif, dan akses instan langsung dari layar utama HP Anda.
            </p>
        </div>
    </div>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@100;300;400;500;700;900&display=swap');
        .fi-header, .fi-header-heading, .fi-page-header { display: none !important; visibility: hidden !important; height: 0 !important; margin: 0 !important; padding: 0 !important; }
        html { scroll-behavior: smooth; }
    </style>
</x-filament-panels::page>
