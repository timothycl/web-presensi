<x-filament-widgets::widget>
    <div class="fi-section" style="background: rgba(15, 23, 42, 0.65); backdrop-filter: blur(24px) saturate(180%); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 2rem; width: 100%; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5); overflow: hidden;">
        <div class="grid grid-cols-1 md:grid-cols-2">
            {{-- Left Side: Status Info --}}
            <div class="p-8 md:p-10 flex flex-col justify-center">
                <div class="flex flex-col gap-2 mb-10">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/5 border border-white/10 w-fit">
                        <div class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></div>
                        <span class="text-amber-500 font-black text-[9px] uppercase tracking-[0.4em]">Status Presensi</span>
                    </div>
                    <h2 class="text-white text-3xl md:text-4xl font-black tracking-tighter leading-[0.9] uppercase italic">
                        {{ now()->translatedFormat('l,') }}<br>
                        <span class="text-amber-500">{{ now()->translatedFormat('d F Y') }}</span>
                    </h2>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-8">
                    {{-- Check In Card --}}
                    <div class="bg-white/[0.03] border border-white/10 rounded-2xl p-4 flex flex-col gap-2">
                        <span class="text-slate-400 text-[10px] font-bold uppercase tracking-wider">Jam Masuk</span>
                        <span class="text-white text-xl font-black italic">
                            {{ $attendance && $attendance->check_in_time ? $attendance->check_in_time->format('H:i') : '--:--' }}
                        </span>
                        @if($attendance && $attendance->status)
                            <span @class([
                                'text-[9px] font-black uppercase px-2 py-0.5 rounded-md self-start border',
                                'bg-emerald-500/10 text-emerald-400 border-emerald-500/20' => $attendance->status === 'on_time',
                                'bg-amber-500/10 text-amber-400 border-amber-500/20' => $attendance->status === 'late',
                            ])>
                                {{ $attendance->status === 'on_time' ? 'Tepat Waktu' : 'Terlambat' }}
                            </span>
                        @endif
                    </div>

                    {{-- Check Out Card --}}
                    <div class="bg-white/[0.03] border border-white/10 rounded-2xl p-4 flex flex-col gap-2">
                        <span class="text-slate-400 text-[10px] font-bold uppercase tracking-wider">Jam Pulang</span>
                        <span class="text-white text-xl font-black italic">
                            {{ $attendance && $attendance->check_out_time ? $attendance->check_out_time->format('H:i') : '--:--' }}
                        </span>
                        @if($attendance && $attendance->check_out_time)
                             <span class="bg-blue-500/10 text-blue-400 border border-blue-500/20 text-[9px] font-black uppercase px-2 py-0.5 rounded-md self-start">
                                Selesai
                             </span>
                        @endif
                    </div>
                </div>

                @if(!$attendance || !$attendance->check_out_time)
                <a href="{{ \App\Filament\Pages\AttendanceScanner::getUrl() }}" 
                   class="group relative inline-flex items-center justify-center gap-3 px-8 py-4 bg-gradient-to-r from-amber-500 to-amber-400 hover:from-amber-400 hover:to-amber-300 text-slate-950 font-black uppercase tracking-widest text-sm rounded-2xl transition-all duration-300 shadow-xl shadow-amber-500/20 hover:shadow-2xl hover:shadow-amber-500/40 hover:-translate-y-1 overflow-hidden">
                    {{-- Shine effect on hover --}}
                    <div class="absolute inset-0 w-full h-full bg-gradient-to-r from-transparent via-white/40 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-700 ease-in-out"></div>
                    
                    <x-heroicon-s-qr-code class="w-6 h-6 relative z-10 group-hover:scale-110 group-hover:rotate-12 transition-transform duration-300" />
                    <span class="relative z-10">Scan Presensi</span>
                    <div class="absolute inset-0 rounded-2xl ring-4 ring-amber-500/20 animate-pulse pointer-events-none"></div>
                </a>
                @else
                <div class="flex items-center gap-3 px-8 py-4 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 font-black uppercase tracking-widest text-sm rounded-2xl">
                    <x-heroicon-s-check-badge class="w-6 h-6" />
                    <span>Sudah Selesai</span>
                </div>
                @endif
            </div>

            {{-- Right Side: Decorative/Visual --}}
            <div class="hidden md:flex bg-gradient-to-br from-amber-500/10 to-transparent p-10 items-center justify-center relative overflow-hidden">
                <div class="absolute inset-0 opacity-10 pointer-events-none" style="background-image: radial-gradient(#fbbf24 1px, transparent 1px); background-size: 20px 20px;"></div>
                
                <div class="relative z-10 flex flex-col items-center gap-4">
                    <div class="w-24 h-24 bg-amber-500 rounded-3xl shadow-2xl shadow-amber-500/20 transform -rotate-6 flex items-center justify-center">
                        <x-heroicon-o-finger-print class="w-12 h-12 text-white" />
                    </div>
                    <div class="text-center">
                        <p class="text-white/40 text-[10px] font-bold uppercase tracking-[0.2em] mb-1">Sistem Presensi</p>
                        <p class="text-white/90 font-extrabold uppercase tracking-widest text-xs">Timothy's Company</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-widgets::widget>
