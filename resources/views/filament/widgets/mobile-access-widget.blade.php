<x-filament-widgets::widget>
    <div id="mobile-access-widget" class="fi-card p-10 h-full flex flex-col items-center justify-between text-center gap-8 group relative overflow-hidden transition-all duration-500" style="background: rgba(15, 23, 42, 0.65); backdrop-filter: blur(24px) saturate(180%); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 3rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5); font-family: 'Outfit', sans-serif;">
        {{-- Mouse Backlight --}}
        <div id="widget-spotlight" style="position: absolute; width: 400px; height: 400px; background: radial-gradient(circle at center, rgba(245, 158, 11, 0.15) 0%, transparent 70%); border-radius: 50%; pointer-events: none; opacity: 0; transition: opacity 0.5s; z-index: 1; transform: translate(-50%, -50%);"></div>
        
        <div class="absolute inset-0 bg-gradient-to-br from-amber-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-1000"></div>
        
        <div class="flex flex-col items-center gap-2 relative z-10">
            <span class="text-amber-500 font-black text-[10px] uppercase tracking-[0.4em] opacity-80 mb-1">Akses Cepat</span>
            <h3 class="text-white text-xl font-black uppercase italic tracking-tight leading-none">Buka di Handphone</h3>
        </div>
        
        <div class="relative z-10 flex flex-col items-center gap-6">
            <div class="relative group/qr">
                <div class="bg-white p-4 rounded-[2.5rem] shadow-2xl transform group-hover:scale-105 transition-transform duration-700 relative z-10">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ url('/admin/login') }}" alt="QR Code Login" class="w-36 h-36">
                </div>
                <div class="absolute -inset-6 bg-amber-500/20 blur-3xl rounded-full -z-10 opacity-0 group-hover:opacity-100 transition-opacity duration-1000"></div>
            </div>

            <div class="flex flex-col items-center gap-1">
                <p class="text-white/30 text-[9px] font-bold uppercase tracking-[0.2em]">Pindai untuk Login</p>
                <div class="px-4 py-1 bg-white/5 border border-white/5 rounded-full">
                    <p class="text-white/40 text-[9px] font-mono tracking-tight">{{ parse_url(url('/'), PHP_URL_HOST) }}</p>
                </div>
            </div>
        </div>
        
        <div class="w-100 relative z-10 pt-2">
            <a href="{{ \App\Filament\Pages\MobileGuide::getUrl() }}" class="inline-flex items-center gap-3 px-8 py-4 bg-amber-500 hover:bg-amber-400 text-slate-950 text-[11px] font-black uppercase tracking-[0.2em] rounded-2xl transition-all duration-300 shadow-xl shadow-amber-500/20 hover:shadow-amber-500/40 hover:-translate-y-1">
                <x-heroicon-s-book-open class="w-4 h-4" />
                <span>Lihat Panduan</span>
            </a>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const widget = document.getElementById('mobile-access-widget');
            const spotlight = document.getElementById('widget-spotlight');

            widget.addEventListener('mousemove', (e) => {
                const rect = widget.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;

                spotlight.style.left = `${x}px`;
                spotlight.style.top = `${y}px`;
                spotlight.style.opacity = '1';
            });

            widget.addEventListener('mouseleave', () => {
                spotlight.style.opacity = '0';
            });
        });
    </script>
</x-filament-widgets::widget>
