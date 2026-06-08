<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panduan Akses Mobile - Timothy's Company</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    },
                },
            },
        }
    </script>
    
    <style>
        body {
            background: radial-gradient(circle at top left, #1e293b, #0f172a) !important;
            background-attachment: fixed !important;
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }
        ::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.4);
        }
    </style>
</head>
<body class="text-white min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-[1000px] mx-auto relative">
        
        {{-- Back Button to Login --}}
        <div class="mb-8">
            <a href="{{ url('/admin/login') }}" class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-slate-400 hover:text-white transition-colors duration-300">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
                <span>Kembali ke Halaman Login</span>
            </a>
        </div>

        {{-- Header Section --}}
        <div class="mb-16 text-center relative">
            <div class="absolute inset-0 top-[-100px] bg-amber-500/10 blur-[100px] rounded-full pointer-events-none"></div>
            
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/5 border border-white/10 mb-6">
                <div class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></div>
                <span class="text-amber-500/80 font-black text-[9px] uppercase tracking-[0.3em]">Tutorial Instalasi</span>
            </div>

            <h1 class="text-white text-4xl sm:text-5xl lg:text-6xl font-black uppercase italic tracking-tight leading-[0.9] mb-6">
                Panduan Akses <span class="bg-gradient-to-br from-amber-400 via-amber-200 to-white bg-clip-text text-transparent">Mobile</span>
            </h1>
            
            <p class="text-slate-400 text-base sm:text-lg max-w-[600px] mx-auto font-medium leading-relaxed">
                Gunakan aplikasi presensi dengan lebih <span class="text-white font-bold">cepat & stabil</span> melalui fitur Progressive Web App (PWA) di handphone Anda.
            </p>
        </div>

        {{-- Langkah 1: QR Code Section --}}
        <div class="relative bg-slate-900/75 backdrop-blur-3xl border border-white/10 rounded-[2.5rem] p-8 sm:p-10 mb-10 shadow-2xl flex flex-col lg:flex-row gap-10 items-center justify-between overflow-hidden">
            <div class="absolute w-[300px] height-[300px] bg-radial bg-amber-500/10 blur-3xl top-[-150px] right-[-150px] rounded-full pointer-events-none"></div>

            {{-- Content Area --}}
            <div class="flex-1 flex flex-col gap-5 w-full">
                <div class="inline-flex items-center gap-2 self-start px-4 py-1.5 rounded-full bg-amber-500/10 border border-amber-500/25">
                    <div class="w-1.5 h-1.5 rounded-full bg-amber-500 shadow-[0_0_8px_#f59e0b]"></div>
                    <span class="text-amber-500 font-extrabold text-[9px] uppercase tracking-widest">Langkah 1</span>
                </div>

                <h2 class="text-white text-2xl sm:text-3xl font-black uppercase italic tracking-tight leading-none">
                    Pindai QR Code untuk Akses Mobile
                </h2>

                <p class="text-slate-400 text-sm sm:text-base leading-relaxed">
                    Buka kamera handphone Anda dan arahkan ke QR Code di samping untuk membuka aplikasi presensi secara instan. 
                    <span class="text-white font-bold">Tidak perlu mengetik URL secara manual!</span>
                </p>

                {{-- URL Box --}}
                <div class="flex flex-col gap-2 mt-2">
                    <span class="text-slate-500 font-extrabold text-[9px] uppercase tracking-widest">Link Akses Presensi:</span>
                    <div class="flex items-center gap-3 p-4 bg-white/[0.03] border border-white/5 rounded-2xl">
                        <svg style="width: 16px; height: 16px; color: #f59e0b;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="flex-shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m13.35-.622 1.757-1.757a4.5 4.5 0 0 0-6.364-6.364l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244" />
                        </svg>
                        <span class="text-slate-300 font-mono text-xs sm:text-sm font-semibold break-all leading-tight">
                            {{ url('/admin/login') }}
                        </span>
                    </div>
                </div>

                {{-- Direct Access Button (for mobile users) --}}
                <div class="mt-2 flex flex-wrap gap-4 items-center">
                    <a href="{{ url('/admin/login') }}" target="_blank" class="inline-flex items-center gap-2.5 px-6 py-3.5 rounded-2xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-black text-xs uppercase tracking-wide transition-all duration-300 shadow-xl shadow-amber-500/10 hover:shadow-amber-500/20 hover:-translate-y-0.5 text-center">
                        <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                        </svg>
                        <span>Buka Langsung di HP</span>
                    </a>
                    <span class="text-slate-500 text-xs font-semibold italic">
                        *Ketuk jika Anda sedang membuka halaman ini melalui HP.
                    </span>
                </div>
            </div>

            {{-- QR Code Container --}}
            <div class="flex-shrink-0 mx-auto flex flex-col items-center gap-4 relative z-10">
                <div class="p-5 bg-white rounded-[2.5rem] shadow-2xl hover:scale-105 transition-transform duration-500">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data={{ url('/admin/login') }}" alt="QR Code Login" class="w-[160px] h-[160px] block">
                </div>
                <div class="flex flex-col items-center gap-1">
                    <span class="text-slate-500 font-extrabold text-[9px] uppercase tracking-[0.25em]">Pindai untuk Login</span>
                    <span class="text-amber-500 font-bold text-xs font-mono">{{ parse_url(url('/'), PHP_URL_HOST) }}</span>
                </div>
            </div>
        </div>

        {{-- Langkah 2: Grid of Steps --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            {{-- iOS / iPhone Card --}}
            <div class="bg-slate-900/75 backdrop-blur-3xl border border-white/10 rounded-[2.5rem] p-8 sm:p-10 flex flex-col gap-10 shadow-2xl">
                <div class="flex items-center gap-5">
                    <div class="w-[56px] h-[56px] bg-white/5 border border-white/10 rounded-2xl flex items-center justify-center">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3"></path>
                        </svg>
                    </div>
                    <div class="flex flex-col gap-1">
                        <h2 class="text-white text-xl font-black uppercase italic tracking-tight">iOS / iPhone</h2>
                        <span class="text-amber-500 font-extrabold text-[9px] uppercase tracking-wider">Safari Browser Required</span>
                    </div>
                </div>

                <div class="flex flex-col gap-8">
                    @php
                        $iosSteps = [
                            ['title' => 'Buka Browser Safari', 'desc' => 'Masuk ke URL aplikasi melalui browser Safari bawaan iPhone Anda.'],
                            ['title' => 'Klik Ikon Share', 'desc' => 'Ketuk ikon kotak dengan panah ke atas di bagian bawah layar.'],
                            ['title' => 'Add to Home Screen', 'desc' => 'Cari dan pilih menu "Add to Home Screen" atau "Tambah ke Layar Utama".'],
                            ['title' => 'Konfirmasi "Add"', 'desc' => 'Klik tombol "Add" di pojok kanan atas untuk menyelesaikan instalasi.'],
                        ];
                    @endphp

                    @foreach($iosSteps as $index => $step)
                        <div class="flex gap-6 items-start">
                            <div class="flex-shrink-0 w-9 h-9 rounded-xl bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center font-black text-slate-950 text-sm shadow-lg shadow-amber-500/20">
                                {{ $index + 1 }}
                            </div>
                            <div class="pt-0.5">
                                <h3 class="text-white font-bold text-base mb-1">{{ $step['title'] }}</h3>
                                <p class="text-slate-400/80 text-sm leading-relaxed font-medium">{{ $step['desc'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Android Card --}}
            <div class="bg-slate-900/75 backdrop-blur-3xl border border-white/10 rounded-[2.5rem] p-8 sm:p-10 flex flex-col gap-10 shadow-2xl">
                <div class="flex items-center gap-5">
                    <div class="w-[56px] h-[56px] bg-white/5 border border-white/10 rounded-2xl flex items-center justify-center">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25"></path>
                        </svg>
                    </div>
                    <div class="flex flex-col gap-1">
                        <h2 class="text-white text-xl font-black uppercase italic tracking-tight">Android OS</h2>
                        <span class="text-emerald-500 font-extrabold text-[9px] uppercase tracking-wider">Chrome Browser Required</span>
                    </div>
                </div>

                <div class="flex flex-col gap-8">
                    @php
                        $androidSteps = [
                            ['title' => 'Buka Google Chrome', 'desc' => 'Luncurkan Chrome dan masuk ke URL aplikasi presensi Anda.'],
                            ['title' => 'Klik Titik Tiga', 'desc' => 'Ketuk ikon titik tiga (⋮) di pojok kanan atas layar browser.'],
                            ['title' => 'Pilih "Install App"', 'desc' => 'Klik menu "Install App" atau "Tambahkan ke Layar Utama".'],
                            ['title' => 'Mulai Gunakan', 'desc' => 'Konfirmasi instalasi dan buka aplikasi melalui ikon di menu HP.'],
                        ];
                    @endphp

                    @foreach($androidSteps as $index => $step)
                        <div class="flex gap-6 items-start">
                            <div class="flex-shrink-0 w-9 h-9 rounded-xl bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center font-black text-slate-950 text-sm shadow-lg shadow-emerald-500/20">
                                {{ $index + 1 }}
                            </div>
                            <div class="pt-0.5">
                                <h3 class="text-white font-bold text-base mb-1">{{ $step['title'] }}</h3>
                                <p class="text-slate-400/80 text-sm leading-relaxed font-medium">{{ $step['desc'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Explainer Card --}}
        <div class="mt-16 relative overflow-hidden p-8 sm:p-10 border border-white/10 rounded-[2.5rem] bg-gradient-to-br from-white/[0.02] to-transparent shadow-2xl">
            <h3 class="text-white text-lg sm:text-xl font-black uppercase italic tracking-wider mb-4">Apa itu Progressive Web App?</h3>
            <p class="text-slate-400 text-sm leading-relaxed font-medium italic">
                PWA memungkinkan situs web ini berfungsi seperti <span class="text-amber-500 font-bold">Aplikasi Native</span>. 
                Anda akan mendapatkan tampilan <span class="text-white font-bold">Full-Screen</span> tanpa bar navigasi browser, 
                performa yang lebih responsif, dan akses instan langsung dari layar utama HP Anda.
            </p>
        </div>
    </div>
</body>
</html>
