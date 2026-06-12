<x-filament-widgets::widget>
    <div style="background: rgba(15, 23, 42, 0.65); backdrop-filter: blur(24px) saturate(180%); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 2rem; width: 100%; min-height: 480px; height: auto; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5); padding: 1.75rem; font-family: sans-serif; display: flex; flex-direction: column; justify-content: space-between;">
        <div style="display: flex; flex-direction: column; gap: 1.5rem; height: 100%;">
            {{-- User Info Section --}}
            <div class="max-sm:flex-col max-sm:items-start max-sm:gap-4" style="display: flex; align-items: center; justify-content: space-between;">
                <div class="max-sm:w-full" style="display: flex; align-items: center; gap: 1rem;">
                    <div style="width: 3.5rem; height: 3.5rem; border-radius: 9999px; background: #f59e0b; display: flex; align-items: center; justify-content: center; color: white; font-weight: 900; font-size: 1.5rem; box-shadow: 0 10px 20px -5px rgba(245, 158, 11, 0.3); border: 2px solid rgba(255, 255, 255, 0.1); flex-shrink: 0;">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div class="max-sm:w-[calc(100%-4.5rem)]" style="display: flex; flex-direction: column;">
                        <span style="color: #94a3b8; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.2em; margin-bottom: 0.25rem;">Welcome</span>
                        <h2 class="max-sm:truncate" style="color: white; font-size: 1.5rem; font-weight: 900; letter-spacing: -0.025em; line-height: 1; text-transform: uppercase; font-style: italic; margin: 0;">
                            {{ auth()->user()->name }}
                        </h2>
                    </div>
                </div>

                <form action="{{ filament()->getLogoutUrl() }}" method="post" style="margin: 0;" class="max-sm:w-full">
                    @csrf
                    <button type="submit" class="max-sm:w-full max-sm:justify-center" style="display: flex; align-items: center; gap: 0.5rem; padding: 0.625rem 1.25rem; background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 0.85rem; cursor: pointer; transition: all 0.3s; color: #94a3b8;">
                        <x-heroicon-m-arrow-left-on-rectangle style="width: 1.25rem; height: 1.25rem;" />
                        <span style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;">Sign out</span>
                    </button>
                </form>
            </div>

            {{-- Brand Logo Section (Flex Grow to fill space) --}}
            <div style="flex-grow: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 2rem 1.5rem; background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.05); border-radius: 1.5rem; position: relative; overflow: hidden; margin-top: 1rem;">
                <div style="position: relative; z-index: 10; display: flex; flex-direction: column; align-items: center; gap: 1.5rem; width: 100%;">
                    {{-- The Logo Container --}}
                    <div style="width: 10rem; height: 10rem; position: relative; background: white; border-radius: 2rem; padding: 1.25rem; box-shadow: 0 25px 30px -5px rgba(0, 0, 0, 0.4), 0 0 25px rgba(245, 158, 11, 0.15); display: flex; align-items: center; justify-content: center;">
                        {{-- Using the logo from public directory --}}
                        <img src="{{ asset('images/brand/logo.png.png') }}?v={{ time() }}" 
                             alt="Timothy's Company Logo" 
                             style="width: 100%; height: 100%; object-fit: contain; display: block;"
                             onerror="this.style.display='none'; document.getElementById('logo-placeholder').style.display='flex';"
                        >
                        
                        {{-- Fallback Monogram --}}
                        <div id="logo-placeholder" style="display: none; width: 100%; height: 100%; align-items: center; justify-content: center; position: relative;">
                            <span style="font-size: 3rem; font-weight: 900; color: #b45309; opacity: 0.4; font-style: italic; position: absolute;">TC</span>
                            <div style="width: 100%; height: 1px; background: linear-gradient(to right, transparent, rgba(180, 83, 9, 0.3), transparent);"></div>
                            <div style="height: 100%; width: 1px; background: linear-gradient(to bottom, transparent, rgba(180, 83, 9, 0.3), transparent); position: absolute;"></div>
                        </div>
                    </div>

                    <div style="display: flex; flex-direction: column; align-items: center; width: 100%;">
                        <span style="color: rgba(255, 255, 255, 0.9); font-weight: 900; font-size: 1.25rem; letter-spacing: 0.25em; text-transform: uppercase; font-style: italic;">Timothy's</span>
                        <div style="display: flex; align-items: center; gap: 1.25rem; width: 100%; margin-top: 0.5rem;">
                            <div style="height: 1px; flex-grow: 1; background: linear-gradient(to right, transparent, rgba(245, 158, 11, 0.4));"></div>
                            <span style="color: #f59e0b; font-weight: 700; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5em;">Company</span>
                            <div style="height: 1px; flex-grow: 1; background: linear-gradient(to left, transparent, rgba(245, 158, 11, 0.4));"></div>
                        </div>
                    </div>

                    {{-- Scan Button for Employees removed from home --}}
                </div>
            </div>
        </div>
    </div>
</x-filament-widgets::widget>
