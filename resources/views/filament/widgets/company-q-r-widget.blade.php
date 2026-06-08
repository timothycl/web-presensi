<x-filament-widgets::widget>
    @php $company = $this->getCompany(); @endphp
    
    <style>
        .qr-card-glow {
            position: relative;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 2rem;
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
            overflow: hidden;
            transition: transform 0.3s, border-color 0.3s, box-shadow 0.3s;
        }
        .qr-card-glow::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(
                250px circle at var(--mouse-x, 0px) var(--mouse-y, 0px),
                var(--glow-color, rgba(255, 255, 255, 0.15)),
                transparent 80%
            );
            z-index: 1;
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.3s;
        }
        .qr-card-glow:hover::before {
            opacity: 1;
        }
        .qr-card-glow:hover {
            transform: translateY(-5px);
        }
        .qr-card-glow.check-in:hover {
            border-color: rgba(245, 158, 11, 0.3);
            box-shadow: 0 10px 25px -5px rgba(245, 158, 11, 0.15);
        }
        .qr-card-glow.check-out:hover {
            border-color: rgba(59, 130, 246, 0.3);
            box-shadow: 0 10px 25px -5px rgba(59, 130, 246, 0.15);
        }
        .qr-card-glow > * {
            position: relative;
            z-index: 2;
        }
    </style>

    <div style="background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(24px) saturate(180%); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 2.5rem; padding: 2rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5); overflow: hidden; font-family: 'Outfit', sans-serif;">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2rem;">
            <div>
                <h2 style="color: white; font-size: 1.5rem; font-weight: 900; letter-spacing: -0.02em; margin: 0;">Presensi Barcode</h2>
                <p style="color: #64748b; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; margin-top: 0.25rem;">{{ $company?->name ?? 'Konfigurasi Perusahaan Belum Ada' }}</p>
            </div>
            @if($company)
                <div style="display: flex; gap: 0.75rem;">
                    <a href="{{ route('admin.download-qr', $company) }}" target="_blank" title="Cetak / PDF" style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 1rem; padding: 0.75rem; color: #94a3b8; transition: all 0.3s; display: flex; align-items: center; justify-content: center;" onmouseover="this.style.background='rgba(245, 158, 11, 0.1)'; this.style.borderColor='rgba(245, 158, 11, 0.2)'; this.style.color='#f59e0b';" onmouseout="this.style.background='rgba(255, 255, 255, 0.05)'; this.style.borderColor='rgba(255, 255, 255, 0.1)'; this.style.color='#94a3b8';">
                        <x-heroicon-m-printer style="width: 1.25rem; height: 1.25rem;" />
                    </a>
                </div>
            @endif
        </div>

        @if($company)
            <div style="display: flex; justify-content: center; margin-top: 1.5rem;">
                {{-- QR Card --}}
                <div class="qr-card-glow check-in" style="width: 100%; max-width: 300px;"
                     x-data="{ x: 0, y: 0 }"
                     @mousemove="x = $event.clientX - $el.getBoundingClientRect().left; y = $event.clientY - $el.getBoundingClientRect().top"
                     :style="{ '--mouse-x': x + 'px', '--mouse-y': y + 'px', '--glow-color': 'rgba(245, 158, 11, 0.15)' }">
                    <span style="color: #f59e0b; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.2em;">QR PRESENSI</span>
                    <div style="background: white; padding: 1rem; border-radius: 1.5rem; box-shadow: 0 10px 30px rgba(0,0,0,0.3); position: relative; overflow: hidden; group;">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode($company->check_in_code) }}" alt="QR Presensi" style="width: 150px; height: 150px; display: block;">
                    </div>
                    <a href="{{ route('admin.download-qr-image', ['code' => $company->check_in_code, 'type' => 'check-in', 'company' => $company->name]) }}" style="margin-top: 0.5rem; background: rgba(245, 158, 11, 0.1); color: #f59e0b; border: 1px solid rgba(245, 158, 11, 0.2); padding: 0.5rem 1rem; border-radius: 0.75rem; font-size: 0.75rem; font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 0.5rem; transition: all 0.3s;" onmouseover="this.style.background='#f59e0b'; this.style.color='white';" onmouseout="this.style.background='rgba(245, 158, 11, 0.1)'; this.style.color='#f59e0b';">
                        <x-heroicon-m-arrow-down-tray style="width: 1rem; height: 1rem;" />
                        Download IMG
                    </a>
                </div>
            </div>
        @else
            <div style="padding: 3rem; text-align: center; color: #64748b; font-style: italic;">
                Mohon atur kode check-in/out di menu Perusahaan.
            </div>
        @endif
    </div>
</x-filament-widgets::widget>
