<x-filament-widgets::widget>
    @php
        $primaryColor = '#f59e0b'; /* Amber 500 */
    @endphp
    <style>
        .upcoming-event-card:hover .event-icon {
            filter: drop-shadow(0 0 10px var(--accent-color-glow));
            transform: scale(1.1);
        }
        .upcoming-event-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .custom-scrollbar {
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.1) transparent;
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 10px;
            border: 2px solid transparent;
            background-clip: content-box;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
            border: 2px solid transparent;
            background-clip: content-box;
        }
    </style>
    <div style="background: rgba(15, 23, 42, 0.82); backdrop-filter: blur(24px) saturate(180%); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 1.5rem; width: 100%; box-shadow: 0 20px 50px -12px rgba(0, 0, 0, 0.5); padding: 1.75rem; position: relative; overflow: hidden;">
        {{-- Header --}}
        <div style="display: flex; flex-direction: column; align-items: flex-start; gap: 0.75rem; margin-bottom: 2rem;">
            <h2 style="font-size: 16px; font-weight: 900; color: white; text-transform: uppercase; letter-spacing: 0.2em; margin: 0; font-family: 'Outfit', sans-serif;">Upcoming Events</h2>
            <div style="height: 2px; width: 40px; background: {{ $primaryColor }}; border-radius: 2px; opacity: 0.6;"></div>
        </div>

        {{-- Vertical List of Horizontal Items --}}
        <div class="custom-scrollbar" style="display: block; height: 320px; overflow-y: auto; overflow-x: hidden; padding-right: 12px; scroll-behavior: smooth;">
            <div style="display: flex; flex-direction: column; gap: 1rem;">
            @foreach($events as $event)
                <div class="upcoming-event-card hover:bg-white/[0.07] hover:border-white/10 hover:-translate-y-0.5" style="display: flex !important; flex-direction: row !important; align-items: center; gap: 1.25rem; padding: 1rem 1.5rem; border-radius: 1.25rem; border: 1px solid rgba(255, 255, 255, 0.05); background: rgba(255, 255, 255, 0.02); --accent-color-glow: {{ $event->accent_color }}44;">
                    {{-- Icon Box --}}
                    <div style="flex-shrink: 0; background: rgba(255, 255, 255, 0.04); border: 1px solid rgba(255, 255, 255, 0.08); padding: 12px; border-radius: 14px; display: flex; align-items: center; justify-content: center;">
                        @svg($event->icon, "event-icon", ['style' => "width: 22px; height: 22px; color: {$event->accent_color}; transition: all 0.3s ease;"])
                    </div>

                    {{-- Text Content --}}
                    <div style="flex-grow: 1; display: flex; flex-direction: column; gap: 4px;">
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                            <h3 style="font-size: 14px; font-weight: 700; color: white; margin: 0; letter-spacing: 0.01em;">{{ $event->name }}</h3>
                            <span style="padding: 2px 9px; border-radius: 7px; font-size: 9px; font-weight: 900; text-transform: uppercase; background: rgba(245, 158, 11, 0.15); color: {{ $primaryColor }}; border: 1px solid rgba(245, 158, 11, 0.25); letter-spacing: 0.05em;">{{ $event->label }}</span>
                        </div>
                        <p style="font-size: 12px; color: rgba(255, 255, 255, 0.5); margin: 0; font-weight: 500;">{{ $event->event_date->format('d F Y') }}</p>
                    </div>
                </div>
            @endforeach
            </div>
        </div>
    </div>
</x-filament-widgets::widget>
