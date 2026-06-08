<div>
    <x-filament-panels::page>
        <div id="presence-qr-container">
            <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

            @php $companies = $this->getCompanies(); @endphp
            
            <div style="display: flex; flex-direction: column; gap: 4rem;">
                @forelse($companies as $company)
                    <div class="fi-card" style="background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(24px) saturate(180%); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 2.5rem; padding: 3rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5); overflow: hidden; font-family: 'Outfit', sans-serif;">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 3rem;">
                            <div>
                                <h2 style="color: white; font-size: 2rem; font-weight: 900; letter-spacing: -0.02em; margin: 0;">Presensi Barcode</h2>
                                <p style="color: #94a3b8; font-size: 1rem; font-weight: 500; margin-top: 0.25rem;">{{ $company->name }}</p>
                            </div>
                            <div style="display: flex; gap: 1rem;">
                                <a href="{{ route('admin.download-qr', ['company' => $company->id]) }}" target="_blank" style="background: rgba(255, 255, 255, 0.05); color: white; border: 1px solid rgba(255, 255, 255, 0.1); padding: 0.75rem; border-radius: 1rem; display: flex; align-items: center; justify-content: center; transition: all 0.3s;" onmouseover="this.style.background='rgba(255,255,255,0.1)';" onmouseout="this.style.background='rgba(255,255,255,0.05)';">
                                    <x-heroicon-o-printer style="width: 1.5rem; height: 1.5rem;" />
                                </a>
                            </div>
                        </div>

                        <div style="display: flex; justify-content: center;">
                            {{-- QR Card --}}
                            <div id="check-in-card-{{ $company->id }}" class="group/card" style="width: 100%; max-width: 400px; background: rgba(15, 23, 42, 0.95); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 3rem; padding: 3rem; display: flex; flex-direction: column; align-items: center; gap: 2rem; position: relative; transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);">
                                <div style="position: absolute; inset: 0; background: radial-gradient(circle at center, #f59e0b10 0%, transparent 70%); opacity: 0; transition: opacity 0.5s;" class="group-hover/card:opacity-100"></div>
                                <span style="color: #f59e0b; font-size: 11px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.3em; position: relative; z-index: 10;">QR PRESENSI</span>
                                <div style="background: white; padding: 2rem; border-radius: 2.5rem; box-shadow: 0 20px 50px rgba(0,0,0,0.4); position: relative; z-index: 10; transform: transition-transform 0.5s;" class="group-hover/card:scale-105">
                                    <div id="check-in-qr-{{ $company->id }}" class="qr-container" data-code="{{ $company->check_in_code }}" style="width: 250px; height: 250px;"></div>
                                </div>
                                <div style="text-align: center; width: 100%; position: relative; z-index: 10;">
                                    <div style="color: #64748b; font-size: 0.875rem; font-weight: 700; margin-bottom: 1.5rem; font-family: 'JetBrains Mono', monospace; opacity: 0.6;">{{ $company->check_in_code }}</div>
                                    <div style="display: flex; gap: 1rem;">
                                        <button onclick="downloadCard('check-in-card-{{ $company->id }}', '{{ str()->slug($company->name) }}-presensi')" style="flex: 1; background: #f59e0b; color: #020617; border: none; padding: 1rem; border-radius: 1.5rem; font-size: 0.875rem; font-weight: 900; text-transform: uppercase; letter-spacing: 0.1em; display: flex; align-items: center; gap: 0.75rem; transition: all 0.3s; cursor: pointer; justify-content: center; box-shadow: 0 10px 20px -5px rgba(245, 158, 11, 0.4);">
                                            <x-heroicon-m-arrow-down-tray style="width: 1.25rem; height: 1.25rem;" />
                                            Download
                                        </button>
                                        <a href="{{ route('admin.download-qr', ['company' => $company->id, 'type' => 'check-in']) }}" target="_blank" style="background: rgba(255, 255, 255, 0.05); color: white; border: 1px solid rgba(255, 255, 255, 0.1); padding: 1rem; border-radius: 1.5rem; display: flex; align-items: center; justify-content: center; transition: all 0.3s;" onmouseover="this.style.background='rgba(255,255,255,0.1)';" onmouseout="this.style.background='rgba(255,255,255,0.05)';">
                                            <x-heroicon-o-printer style="width: 1.5rem; height: 1.5rem;" />
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div style="padding: 5rem; text-align: center; color: #64748b; font-style: italic; background: rgba(15, 23, 42, 0.7); border-radius: 2.5rem; border: 1px solid rgba(255, 255, 255, 0.1);">
                        Data perusahaan tidak ditemukan. Silakan hubungi administrator.
                    </div>
                @endforelse
            </div>

            <script>
                window.addEventListener('DOMContentLoaded', function() {
                    document.querySelectorAll('.qr-container').forEach(container => {
                        const code = container.getAttribute('data-code');
                        if (code) {
                            new QRCode(container, {
                                text: code,
                                width: 250,
                                height: 250,
                                colorDark: "#000000",
                                colorLight: "#ffffff",
                                correctLevel: QRCode.CorrectLevel.H
                            });
                        } else {
                            container.innerHTML = '<div style="display:flex;align-items:center;justify-content:center;height:100%;color:#94a3b8;font-size:0.8rem;text-align:center;">Kode belum diatur</div>';
                        }
                    });
                });

                function downloadCard(cardId, filename) {
                    const card = document.getElementById(cardId);
                    const btn = card.querySelector('button');
                    const originalDisplay = btn.style.display;
                    btn.style.display = 'none';

                    html2canvas(card, {
                        backgroundColor: '#0f172a',
                        borderRadius: 40,
                        scale: 3,
                        useCORS: true
                    }).then(canvas => {
                        const link = document.createElement('a');
                        link.download = filename + '.png';
                        link.href = canvas.toDataURL('image/png');
                        link.click();
                        btn.style.display = originalDisplay;
                    });
                }
            </script>
        </div>
    </x-filament-panels::page>
</div>
