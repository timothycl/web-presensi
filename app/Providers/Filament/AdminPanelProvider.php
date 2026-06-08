<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;





class AdminPanelProvider extends PanelProvider
{

public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->registration(\App\Filament\Pages\Auth\Register::class)
            ->colors([
                'primary' => Color::Amber,
            ])
            ->brandLogo(fn () => new \Illuminate\Support\HtmlString('
                <div class="fi-logo-wrapper group/logo" 
                     onclick="event.preventDefault(); event.stopPropagation();"
                     style="display: flex; align-items: center; gap: 0.75rem; cursor: default; user-select: none; pointer-events: auto !important;">
                    <div class="fi-logo-icon-container" style="background: transparent; padding: 0; display: flex; align-items: center; justify-content: center; transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);">
                        <img src="' . asset('images/brand/logo.png.png') . '" style="height: 2.25rem; width: 2.25rem; object-fit: contain; filter: drop-shadow(0 4px 8px rgba(0,0,0,0.3));" />
                    </div>
                    <div class="fi-logo-text" style="white-space: nowrap;">
                        <span class="text-[0.8rem] font-extrabold tracking-widest text-white uppercase italic leading-tight flex items-center gap-1">
                            <span class="fi-logo-word-1 transition-all duration-500">Timothy\'s</span>
                            <span class="fi-logo-word-2 text-amber-500 not-italic transition-all duration-500">Company</span>
                        </span>
                    </div>
                </div>
            '))

            ->brandLogoHeight('3rem')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->pages([
                \App\Filament\Pages\Dashboard::class,
            ])
            ->sidebarCollapsibleOnDesktop()


            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->renderHook(
                'panels::head.start',
                fn () => new \Illuminate\Support\HtmlString('
                    <link rel="manifest" href="/manifest.json">
                    <meta name="apple-mobile-web-app-capable" content="yes">
                    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
                    <meta name="apple-mobile-web-app-title" content="Presence">
                    <link rel="apple-touch-icon" href="/images/brand/logo.png.png">
                    <script>
                        if ("serviceWorker" in navigator) {
                            window.addEventListener("load", () => {
                                navigator.serviceWorker.register("/sw.js");
                            });
                        }
                    </script>
                ')
            )
            ->renderHook(
                'panels::auth.login.before',
                fn () => new \Illuminate\Support\HtmlString('<style>.fi-logo, a.fi-logo, a:has(.fi-logo-wrapper) { cursor: default !important; } .fi-logo-wrapper { pointer-events: auto !important; }</style>')
            )
            ->renderHook(
                'panels::auth.login.form.after',
                fn () => new \Illuminate\Support\HtmlString('
                    <style>
                        .mobile-access-banner {
                            position: relative;
                            display: block;
                            padding: 1rem;
                            border-radius: 1.5rem;
                            background: linear-gradient(to right, rgba(245, 158, 11, 0.08), rgba(245, 158, 11, 0.01) 80%, transparent);
                            border: 1px solid rgba(245, 158, 11, 0.15);
                            text-decoration: none;
                            overflow: hidden;
                            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
                        }
                        .mobile-access-banner:hover {
                            border-color: rgba(245, 158, 11, 0.4) !important;
                            background: linear-gradient(to right, rgba(245, 158, 11, 0.12), rgba(245, 158, 11, 0.03) 80%, transparent) !important;
                            box-shadow: 0 10px 25px -10px rgba(245, 158, 11, 0.15) !important;
                        }
                        .mobile-access-banner:hover .banner-title {
                            color: #fbbf24 !important;
                            transform: translateX(6px) !important;
                        }
                        .mobile-access-banner:hover .banner-desc {
                            color: #ffffff !important;
                            transform: translateX(6px) !important;
                        }
                        .mobile-access-banner:hover .banner-icon {
                            background: #f59e0b !important;
                            color: #020617 !important;
                            transform: scale(1.1) !important;
                            box-shadow: 0 0 15px rgba(245, 158, 11, 0.4) !important;
                            border-color: #f59e0b !important;
                        }
                        .mobile-access-banner:hover .banner-arrow {
                            color: #f59e0b !important;
                            transform: translateX(4px) !important;
                        }
                    </style>
                    <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid rgba(255, 255, 255, 0.05); width: 100%; font-family: \'Outfit\', sans-serif;">
                        <a href="' . route('mobile-guide') . '" class="mobile-access-banner">
                            <div style="display: flex; align-items: center; gap: 1rem; position: relative; z-index: 10;">
                                <!-- Icon Container -->
                                <div class="banner-icon" style="flex-shrink: 0; width: 3rem; height: 3rem; border-radius: 1rem; background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.2); display: flex; align-items: center; justify-content: center; color: #f59e0b; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);">
                                    <svg style="width: 1.5rem; height: 1.5rem; display: block;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                                    </svg>
                                </div>
                                
                                <!-- Text Area -->
                                <div style="flex-grow: 1; text-align: left; display: flex; flex-direction: column; gap: 2px;">
                                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                                        <span class="banner-title" style="color: white; font-size: 0.875rem; font-weight: 900; text-transform: uppercase; letter-spacing: -0.01em; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); display: inline-block;">Akses Mobile (PWA)</span>
                                        <span class="animate-pulse" style="display: inline-flex; align-items: center; padding: 2px 8px; border-radius: 9999px; font-size: 8px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; background: rgba(245, 158, 11, 0.2); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.3);">Rekomendasi</span>
                                    </div>
                                    <p class="banner-desc" style="color: #94a3b8; font-size: 0.75rem; font-weight: 500; line-height: 1.25; margin: 0; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1) 0.04s; display: inline-block;">
                                        Panduan instalasi presensi instan di HP Anda tanpa Playstore.
                                    </p>
                                </div>

                                <!-- Arrow Indicator -->
                                <div class="banner-arrow" style="flex-shrink: 0; color: #64748b; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);">
                                    <svg style="width: 1.25rem; height: 1.25rem; display: block;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                    </svg>
                                </div>
                            </div>
                        </a>
                    </div>
                ')
            )
            ->renderHook(
                'panels::body.end',

                fn () => new \Illuminate\Support\HtmlString(<<<'HTML'
                    <div id="cursor-backlight"></div>
                    <style>
                        /* --- Sidebar & Layout Transitions --- */
                        :root {
                            --sidebar-transition: 0.45s cubic-bezier(0.4, 0, 0.2, 1);
                        }
                        /* Topbar Glass */
                        .fi-topbar, .fi-topbar nav, .fi-topbar header {
                            background: rgba(15, 23, 42, 0.45) !important;
                            backdrop-filter: blur(24px) saturate(180%) !important;
                            -webkit-backdrop-filter: blur(24px) saturate(180%) !important;
                            border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
                            z-index: 9999 !important;
                            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.5), inset 0 0 20px rgba(255, 255, 255, 0.02) !important;
                        }

                        .fi-main {
                            position: relative !important;
                            z-index: 1 !important;
                        }

                        .fi-topbar {
                            height: auto !important;
                            min-height: 4rem !important;
                        }

                        /* Hide Theme Switcher */
                        .fi-theme-switcher, 
                        [x-data*="themeSwitcher"] {
                            display: none !important;
                        }

                        /* Hide Header on Dashboard */
                        .fi-page-dashboard .fi-header {
                            display: none !important;
                        }

                        /* Hide Sidebar Label for Home */
                        .fi-sidebar-item[href$="/admin"] .fi-sidebar-item-label {
                            display: none !important;
                        }

                        .fi-sidebar {
                            background: rgba(15, 23, 42, 0.75) !important;
                            backdrop-filter: blur(30px) !important;
                            -webkit-backdrop-filter: blur(30px) !important;
                            border-right: 1px solid rgba(255, 255, 255, 0.1) !important;
                            z-index: 10000 !important;
                            transition: width var(--sidebar-transition), transform var(--sidebar-transition) !important;
                        }

                        .fi-main, .fi-topbar {
                            transition: all var(--sidebar-transition) !important;
                        }

                        /* Disable clicking on Brand Logo */
                        .fi-logo, 
                        a.fi-logo,
                        .fi-sidebar-header a,
                        .fi-topbar-header a {
                            /* pointer-events: none !important; */
                            cursor: default !important;
                        }


                        .fi-sidebar-nav {
                            transition: padding var(--sidebar-transition) !important;
                        }

                        /* Logo Animations */
                        .fi-logo-wrapper {
                            position: relative;
                            display: flex !important;
                            align-items: center !important;
                            justify-content: flex-start !important;
                            height: 64px !important;
                            padding: 0 0.5rem !important;
                            margin-left: -1.25rem !important; /* Pull closer to collapse button */
                            overflow: visible !important;
                            margin-top: 0 !important;
                            align-self: center !important; /* Ensure it stays in the middle of the flex container */
                            cursor: default;
                        }

                        /* Logo Hover Animations - Floating Version (No Box) */
                        .fi-logo-wrapper:hover .fi-logo-icon-container {
                            transform: scale(1.3) rotate(-15deg) translateY(-2px) !important;
                        }
                        
                        .fi-logo-wrapper:hover .fi-logo-icon-container img {
                            filter: drop-shadow(0 0 20px rgba(245, 158, 11, 0.8)) brightness(1.1) !important;
                        }

                        .fi-logo-wrapper:hover .fi-logo-word-1 {
                            color: #fbbf24 !important;
                            transform: translateY(-4px) !important;
                            letter-spacing: 0.3em !important;
                            text-shadow: 0 0 15px rgba(245, 158, 11, 0.4) !important;
                        }

                        .fi-logo-wrapper:hover .fi-logo-word-2 {
                            color: white !important;
                            transform: translateY(4px) !important;
                            letter-spacing: 0.3em !important;
                            text-shadow: 0 0 15px rgba(255, 255, 255, 0.4) !important;
                        }

                        .fi-logo-wrapper:hover .fi-logo-text {
                             filter: brightness(1.1) !important;
                        }

                        .fi-logo-icon-container, .fi-logo-word-1, .fi-logo-word-2 {
                            transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
                        }

                        .fi-logo-collapsed {
                            display: flex !important;
                            align-items: center !important;
                            justify-content: center !important;
                            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1) !important;
                            backface-visibility: hidden;
                        }

                        .fi-logo-collapsed {
                            position: absolute !important;
                            left: 1.5rem;
                            transform: scale(0.6);
                            opacity: 0;
                            display: flex !important;
                            pointer-events: none;
                        }

                        .fi-sidebar-is-collapsed .fi-logo-expanded {
                            opacity: 0;
                            transform: scale(0.8) translateY(-10px);
                            pointer-events: none;
                        }

                        .fi-sidebar-is-collapsed .fi-logo-collapsed {
                            opacity: 1;
                            transform: translateX(-50%) scale(1.1);
                            pointer-events: auto;
                        }

                        body:not(.fi-sidebar-is-collapsed) .fi-logo-collapsed {
                             /* Keep absolute and transparent */
                        }

                        /* Sidebar Items labels */
                        .fi-sidebar-item-label, .fi-sidebar-group-label {
                            transition: opacity 0.3s ease, transform 0.4s var(--sidebar-transition) !important;
                        }

                        .fi-sidebar-is-collapsed .fi-sidebar-item-label {
                            opacity: 0;
                            transform: translateX(-10px);
                            pointer-events: none;
                        }

                        .fi-sidebar-is-collapsed .fi-sidebar-item:hover .fi-sidebar-item-label {
                            opacity: 1 !important;
                            visibility: visible !important;
                            display: block !important;
                            transform: translateX(15px) !important;
                            position: absolute !important;
                            left: 100% !important;
                            background: rgba(15, 23, 42, 0.9) !important;
                            backdrop-filter: blur(16px) !important;
                            -webkit-backdrop-filter: blur(16px) !important;
                            padding: 0.5rem 0.9rem !important;
                            border-radius: 0.6rem !important;
                            border: 1px solid rgba(255, 255, 255, 0.2) !important;
                            white-space: nowrap !important;
                            box-shadow: 0 8px 25px rgba(0,0,0,0.6) !important;
                            z-index: 10001 !important;
                            pointer-events: none !important;
                            color: white !important;
                            font-size: 0.8rem !important;
                            font-weight: 600 !important;
                        }



                        /* --- Cursor Backlight (Clipped Version) --- */
                        #cursor-backlight {
                            position: fixed;
                            top: 0;
                            left: 0;
                            right: 0;
                            bottom: 0;
                            width: 100vw;
                            height: 100vh;
                            background: radial-gradient(circle at var(--x, 0) var(--y, 0), rgba(255, 255, 255, var(--backlight-opacity, 0.3)) 0%, rgba(255, 255, 255, 0) var(--backlight-radius, 150px));
                            pointer-events: none;
                            z-index: 0; /* Behind glass widgets (z-index: 10) */
                            transition: opacity 0.1s ease; /* Removed clip-path transition to prevent 'flying box' */
                            opacity: 0;
                            clip-path: inset(0 0 100% 100%); /* Start hidden */
                            filter: blur(50px);
                            will-change: clip-path, opacity, --x, --y;
                        }

                        /* --- Global Glassmorphism Overrides --- */
                        
                        /* Background Setup */
                        body.fi-body {
                            background: radial-gradient(circle at top left, #1e293b, #0f172a) !important;
                            background-attachment: fixed !important;
                            position: relative;
                        }

                        /* Glass Cards (Widgets, Sections, Stats Overview) */
                        .fi-wi-widget,
                        .fi-wi-stats-overview,
                        .fi-ta-ctn,
                        .fi-card,
                        .fi-ca-card,
                        .fi-wi-account-widget > div,
                        .fi-section.fi-card {
                            background: rgba(15, 23, 42, 0.45) !important;
                            backdrop-filter: blur(24px) saturate(180%) !important;
                            -webkit-backdrop-filter: blur(24px) saturate(180%) !important;
                            border: 1px solid rgba(255, 255, 255, 0.1) !important;
                            border-radius: 2rem !important;
                            box-shadow: 0 12px 40px -10px rgba(0, 0, 0, 0.5) !important;
                            position: relative !important;
                            z-index: 5; /* Stand above the backlight, but stay below popups */
                        }

                        /* Prevent Nested Layers in Widgets */
                        .fi-wi-widget > div,
                        .fi-wi-widget .fi-section,
                        .fi-wi-widget .fi-card {
                            background: transparent !important;
                            backdrop-filter: none !important;
                            -webkit-backdrop-filter: none !important;
                            border: none !important;
                            box-shadow: none !important;
                        }

                        /* Raise z-index of the card being interacted with (fixes date picker overlap) */
                        .fi-wi-widget:focus-within,
                        .fi-ta-ctn:focus-within,
                        .fi-card:focus-within,
                        .fi-section:focus-within {
                            z-index: 100 !important;
                        }

                        /* Stats Overview Unification */
                        .fi-wi-stats-overview {
                            gap: 0 !important;
                            overflow: hidden !important;
                        }

                        .fi-wi-stats-overview-stat {
                            background: transparent !important;
                            border: none !important;
                            border-radius: 0 !important;
                            box-shadow: none !important;
                        }

                        .fi-wi-stats-overview-stat:not(:last-child) {
                            border-inline-end: 1px solid rgba(255, 255, 255, 0.1) !important;
                        }

                        /* Hover Effects for Icons (Merged with previous fixes) */
                        .fi-sidebar-item-icon {
                            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
                        }

                        .fi-sidebar-item a:hover .fi-sidebar-item-icon {
                            transform: scale(1.4) rotate(10deg) !important;
                            color: #f59e0b !important;
                            filter: drop-shadow(0 0 8px rgba(245, 158, 11, 0.6)) !important;
                        }

                        /* Table Customization */
                        .fi-ta-header-ctn, .fi-ta-content, .fi-ta-footer, .fi-ta-pagination {
                            background: transparent !important;
                            border: none !important;
                        }
                        
                        .fi-ta-ctn > div {
                            border: none !important;
                        }

                        /* Input Glass */
                        .fi-input-wrp {
                            background: rgba(255, 255, 255, 0.05) !important;
                            border: 1px solid rgba(255, 255, 255, 0.1) !important;
                            backdrop-filter: blur(4px) !important;
                        }

                        .fi-sidebar-item-btn {
                            overflow: visible !important;
                        }

                        /* Ensure overflow is visible for fly-out labels when collapsed */
                        .fi-sidebar-is-collapsed .fi-sidebar,
                        .fi-sidebar-is-collapsed .fi-sidebar-nav,
                        .fi-sidebar-is-collapsed .fi-sidebar-nav > ul,
                        .fi-sidebar-is-collapsed .fi-sidebar-group,
                        .fi-sidebar-is-collapsed .fi-sidebar-group-items,
                        .fi-sidebar-is-collapsed .fi-sidebar-item {
                            overflow: visible !important;
                        }


                        /* Glassy Dropdowns/Popovers */
                        .fi-dropdown-panel,
                        .fi-fo-date-time-picker-picker,
                        .fi-modal-window,
                        .flatpickr-calendar,
                        .fi-popover {
                            z-index: 1000000 !important;
                            background: rgba(15, 23, 42, 0.8) !important;
                            backdrop-filter: blur(16px) saturate(180%) !important;
                            -webkit-backdrop-filter: blur(16px) saturate(180%) !important;
                            border: 1px solid rgba(255, 255, 255, 0.1) !important;
                            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5) !important;
                            border-radius: 1.5rem !important;
                        }

                        .fi-dropdown-list-item:hover, .fi-dropdown-list-item:focus {
                            background: rgba(255, 255, 255, 0.05) !important;
                        }

                        .fi-ta-filters-form, .fi-select-input {
                            background: transparent !important;
                        }

                        /* Glassy Buttons */
                        .fi-ac-btn-action {
                            background: rgba(255, 255, 255, 0.08) !important;
                            backdrop-filter: blur(12px) !important;
                            -webkit-backdrop-filter: blur(12px) !important;
                            border: 1px solid rgba(255, 255, 255, 0.2) !important;
                            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2) !important;
                            color: white !important;
                            border-radius: 2rem !important; /* Unified roundness */
                            transition: all 0.3s ease !important;
                        }

                        .fi-ac-btn-action:hover {
                            background: rgba(255, 255, 255, 0.15) !important;
                            border-color: rgba(255, 255, 255, 0.4) !important;
                            box-shadow: 0 0 20px rgba(255, 255, 255, 0.2) !important;
                            transform: translateY(-1px) !important;
                        }

                        /* Enhanced Stats & Account Widgets */
                        .fi-wi-stats-overview, 
                        .fi-wi-account-widget > div {
                            background: rgba(30, 41, 59, 0.5) !important;
                            backdrop-filter: blur(20px) saturate(200%) !important;
                            -webkit-backdrop-filter: blur(20px) saturate(200%) !important;
                            border: 1px solid rgba(255, 255, 255, 0.15) !important;
                            border-radius: 2rem !important;
                            box-shadow: 0 15px 35px -5px rgba(0, 0, 0, 0.6), inset 0 0 20px rgba(255, 255, 255, 0.05) !important;
                        }

                        .fi-wi-stats-overview-stat-label, 
                        .fi-wi-stats-overview-stat-value {
                            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
                        }

                        /* Toggleable Selection Column */
                        .fi-ta-selection-cell,
                        .fi-ta-group-selection-cell {
                            display: none !important;
                        }

                        .fi-ta-ctn.selection-mode-active .fi-ta-selection-cell,
                        .fi-ta-ctn.selection-mode-active .fi-ta-group-selection-cell {
                            display: table-cell !important;
                        }

                        /* Glassy Badges */
                        .fi-badge {
                            background: rgba(255, 255, 255, 0.05) !important;
                            backdrop-filter: blur(8px) !important;
                            -webkit-backdrop-filter: blur(8px) !important;
                            border: 1px solid rgba(255, 255, 255, 0.1) !important;
                            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1) !important;
                        }

                        .fi-color-success.fi-badge {
                            background: rgba(34, 197, 94, 0.15) !important;
                            border-color: rgba(34, 197, 94, 0.3) !important;
                            color: rgb(74, 222, 128) !important;
                        }

                        .fi-color-danger.fi-badge {
                            background: rgba(239, 68, 68, 0.15) !important;
                            border-color: rgba(239, 68, 68, 0.3) !important;
                            color: rgb(248, 113, 113) !important;
                        }
                    </style>
                    <script>
                        // Sidebar State Watcher
                        document.addEventListener('alpine:init', () => {
                            Alpine.effect(() => {
                                const isOpen = Alpine.store('sidebar').isOpenDesktop;
                                document.body.classList.toggle('fi-sidebar-is-collapsed', !isOpen);
                            });
                        });

                        document.addEventListener('mousemove', (e) => {
                            const backlight = document.getElementById('cursor-backlight');
                            if (!backlight) return;
                            
                            // Always update light position
                            backlight.style.setProperty('--x', e.clientX + 'px');
                            backlight.style.setProperty('--y', e.clientY + 'px');

                            // Refined target discovery: Only match actual glass cards/widgets
                            const glassSelector = '.fi-wi-widget, .fi-wi-stats-overview, .fi-wi-account-widget, .fi-ta-ctn, .fi-card, .fi-sidebar, .fi-topbar, .fi-ca-card, .fi-section';
                            let glassWidget = e.target.closest(glassSelector);
                            
                            // Specific check to EXCLUDE layout containers that might share these classes
                            if (glassWidget) {
                                const rect = glassWidget.getBoundingClientRect();
                                const isLayout = glassWidget.classList.contains('fi-main-ctn') || 
                                               glassWidget.classList.contains('fi-main') || 
                                               glassWidget.classList.contains('fi-layout') ||
                                               (rect.width > window.innerWidth * 0.9 && rect.height > window.innerHeight * 0.7);
                                
                                if (isLayout) {
                                    glassWidget = null;
                                }
                            }

                            if (glassWidget) {
                                const rect = glassWidget.getBoundingClientRect();
                                const style = window.getComputedStyle(glassWidget);
                                const radius = style.borderRadius || '1rem';
                                const isStats = glassWidget.classList.contains('fi-wi-stats-overview');

                                // Set dynamic opacity and radius
                                backlight.style.setProperty('--backlight-opacity', isStats ? '0.8' : '0.3');
                                backlight.style.setProperty('--backlight-radius', isStats ? '400px' : '150px');
                                
                                const top = rect.top;
                                const left = rect.left;
                                const bottom = window.innerHeight - rect.bottom;
                                const right = window.innerWidth - rect.right;
                                
                                // Apply clip-path and make visible
                                backlight.style.clipPath = `inset(${top}px ${right}px ${bottom}px ${left}px round ${radius})`;
                                backlight.style.opacity = '1';
                                backlight.style.visibility = 'visible';
                            } else {
                                // Hide instantly when not over a target
                                backlight.style.opacity = '0';
                                backlight.style.visibility = 'hidden';
                                backlight.style.clipPath = 'inset(0 0 100% 100%)';
                            }
                        });

                        document.addEventListener('mouseleave', () => {
                            const backlight = document.getElementById('cursor-backlight');
                            if (backlight) {
                                backlight.style.opacity = '0';
                                backlight.style.visibility = 'hidden';
                            }
                        });

                        // Force Dark Mode
                        if (localStorage.getItem('theme') !== 'dark') {
                            localStorage.setItem('theme', 'dark');
                        }
                    </script>
HTML
)
            );

    }

}
