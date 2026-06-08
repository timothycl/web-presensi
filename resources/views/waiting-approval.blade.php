<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Waiting for Approval</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:400,500,600,700,800,900&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        amber: {
                            500: '#f59e0b',
                            600: '#d97706',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background: radial-gradient(circle at top left, #1e293b, #0f172a);
            background-attachment: fixed;
            min-height: 100vh;
        }
        .glass-card {
            background: rgba(15, 23, 42, 0.45);
            backdrop-filter: blur(24px) saturate(180%);
            -webkit-backdrop-filter: blur(24px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 12px 40px -10px rgba(0, 0, 0, 0.5);
        }
    </style>
</head>
<body class="antialiased text-white flex items-center justify-center p-4">

    <div class="glass-card max-w-md w-full rounded-3xl p-8 text-center relative overflow-hidden">
        @if(auth()->user()->approval_status === 'rejected')
            <!-- Rejected Decoration -->
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-red-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20"></div>
            <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-orange-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20"></div>

            <div class="relative z-10">
                <!-- Rejected Icon -->
                <div class="mx-auto w-20 h-20 bg-red-500/10 border border-red-500/20 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>

                <!-- Content -->
                <h1 class="text-2xl font-bold mb-2 tracking-tight text-red-400">Pendaftaran Ditolak</h1>
                <p class="text-slate-400 text-sm mb-8 leading-relaxed">
                    Maaf, pengajuan akun Anda <strong class="text-red-500 font-semibold">TIDAK DISETUJUI</strong> oleh Administrator.
                    Anda tidak memiliki akses ke dalam sistem.
                </p>

                <!-- Actions -->
                <div class="space-y-3">
                    <form method="POST" action="{{ route('waiting-approval.logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-slate-700 rounded-xl shadow-sm text-sm font-medium text-slate-300 bg-slate-800/50 hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-slate-900 focus:ring-slate-500 transition-colors duration-200">
                            Keluar (Logout)
                        </button>
                    </form>
                </div>
            </div>
        @else
            <!-- Pending Decoration -->
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-amber-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20"></div>
            <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20"></div>

            <div class="relative z-10">
                <!-- Pending Icon -->
                <div class="mx-auto w-20 h-20 bg-amber-500/10 border border-amber-500/20 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-10 h-10 text-amber-500 animate-pulse" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"></path>
                    </svg>
                </div>

                <!-- Content -->
                <h1 class="text-2xl font-bold mb-2 tracking-tight">Menunggu Persetujuan</h1>
                <p class="text-slate-400 text-sm mb-8 leading-relaxed">
                    Akun Anda saat ini sedang dalam status <strong class="text-amber-500 font-semibold">Pending</strong>. 
                    Silakan tunggu hingga Administrator memverifikasi dan menyetujui akun Anda sebelum dapat masuk ke dashboard.
                </p>

                <!-- Actions -->
                <div class="space-y-3">
                    <button onclick="window.location.reload()" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-xl shadow-sm text-sm font-semibold text-slate-900 bg-amber-500 hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-slate-900 focus:ring-amber-500 transition-colors duration-200">
                        Refresh Status
                    </button>

                    <form method="POST" action="{{ route('waiting-approval.logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-slate-700 rounded-xl shadow-sm text-sm font-medium text-slate-300 bg-slate-800/50 hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-slate-900 focus:ring-slate-500 transition-colors duration-200">
                            Keluar (Logout)
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>

</body>
</html>
