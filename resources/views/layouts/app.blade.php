<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ sidebarOpen: false, darkMode: localStorage.getItem('darkMode') === 'true' }" 
      x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))"
      :class="{ 'dark': darkMode }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <script>
            if (localStorage.getItem('darkMode') === 'true') {
                document.documentElement.classList.add('dark');
            }
        </script>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
        
        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        
        <style>
            * { font-family: 'Inter', sans-serif; }
            
            /* ===== DESIGN TOKENS - LIGHT MODE ===== */
            :root {
                /* Backgrounds */
                --bg: #f8fafc;
                --surface: #ffffff;
                --surface-2: #f1f5f9;
                
                /* Text */
                --text: #0f172a;
                --text-secondary: #475569;
                --muted: #94a3b8;
                
                /* Borders */
                --border: #e2e8f0;
                --border-light: #f1f5f9;
                
                /* Primary */
                --primary: #3b82f6;
                --primary-hover: #2563eb;
                --primary-contrast: #ffffff;
                --primary-soft: #eff6ff;
                
                /* Status */
                --success: #10b981;
                --success-soft: #ecfdf5;
                --warning: #f59e0b;
                --warning-soft: #fffbeb;
                --danger: #ef4444;
                --danger-soft: #fef2f2;
                
                /* Interactive */
                --focus-ring: rgba(59, 130, 246, 0.4);
                --shadow-sm: 0 1px 2px rgba(0,0,0,0.05);
                --shadow: 0 1px 3px rgba(0,0,0,0.08), 0 1px 2px rgba(0,0,0,0.04);
                --shadow-md: 0 4px 6px -1px rgba(0,0,0,0.08);
                
                /* Table */
                --table-header: #f8fafc;
                --table-row-hover: #f1f5f9;
                --table-row-alt: #fafbfc;
            }
            
            /* ===== DESIGN TOKENS - DARK MODE ===== */
            .dark {
                /* Backgrounds */
                --bg: #0f172a;
                --surface: #1e293b;
                --surface-2: #334155;
                
                /* Text */
                --text: #f1f5f9;
                --text-secondary: #cbd5e1;
                --muted: #94a3b8;
                
                /* Borders */
                --border: #334155;
                --border-light: #1e293b;
                
                /* Primary */
                --primary: #60a5fa;
                --primary-hover: #3b82f6;
                --primary-contrast: #0f172a;
                --primary-soft: rgba(59, 130, 246, 0.15);
                
                /* Status */
                --success: #34d399;
                --success-soft: rgba(16, 185, 129, 0.15);
                --warning: #fbbf24;
                --warning-soft: rgba(245, 158, 11, 0.15);
                --danger: #f87171;
                --danger-soft: rgba(239, 68, 68, 0.15);
                
                /* Interactive */
                --focus-ring: rgba(96, 165, 250, 0.4);
                --shadow-sm: 0 1px 2px rgba(0,0,0,0.2);
                --shadow: 0 1px 3px rgba(0,0,0,0.3);
                --shadow-md: 0 4px 6px rgba(0,0,0,0.3);
                
                /* Table */
                --table-header: #1e293b;
                --table-row-hover: rgba(51, 65, 85, 0.5);
                --table-row-alt: #1a2536;
            }
            
            
            /* ===== SIDEBAR ===== */
            .sidebar {
                width: 260px;
                min-width: 260px;
                height: 100vh;
                background: rgba(255, 255, 255, 0.7) !important;
                backdrop-filter: blur(16px) !important;
                -webkit-backdrop-filter: blur(16px) !important;
                border-right: 1px solid rgba(200, 220, 240, 0.5) !important;
                box-shadow: 4px 0 30px rgba(59, 130, 246, 0.08) !important;
                display: flex;
                flex-direction: column;
                position: fixed;
                left: 0;
                top: 0;
                z-index: 50;
                transition: all 0.3s ease;
            }
            
            /* Dark mode sidebar glassmorphism */
            .dark .sidebar {
                background: rgba(15, 23, 42, 0.8) !important;
                border-right: 1px solid rgba(100, 150, 200, 0.15) !important;
                box-shadow: 4px 0 30px rgba(0, 0, 0, 0.4) !important;
            }
            
            /* ===== BODY GRADIENT BACKGROUND ===== */
            body {
                background: linear-gradient(180deg, 
                    #d4eeff 0%, 
                    #b8e4ff 20%, 
                    #9dd6f7 40%, 
                    #87ceeb 60%,
                    #b8e4ff 80%,
                    #e8f4fc 100%
                ) !important;
                background-attachment: fixed !important;
                min-height: 100vh;
            }
            
            .dark body,
            body.dark,
            .dark {
                background: linear-gradient(180deg, 
                    #0c1929 0%, 
                    #0f2744 20%, 
                    #0d2238 40%, 
                    #0a1a2e 60%,
                    #0c1f35 80%,
                    #0f172a 100%
                ) !important;
                background-attachment: fixed !important;
            }
            
            /* ===== MAIN CONTENT ===== */
            .main-content {
                margin-left: 260px;
                min-height: 100vh;
                background: transparent;
                transition: all 0.3s ease;
            }
            
            /* Dark mode for cards */
            .dark .bg-white {
                background: #1e293b !important;
            }
            
            .dark .text-gray-800, .dark .text-gray-700, .dark .text-gray-600 {
                color: #e2e8f0 !important;
            }
            
            .dark .text-gray-500 {
                color: #94a3b8 !important;
            }
            
            .dark .border-gray-200, .dark .border-gray-300 {
                border-color: #334155 !important;
            }
            
            .dark input:not(.kode-desa-input):not(.location-list-input), 
            .dark textarea, 
            .dark select:not(.location-list-select) {
                background: #334155 !important;
                border-color: #475569 !important;
                color: #e2e8f0 !important;
            }
            
            .dark input:not(.kode-desa-input):not(.location-list-input)::placeholder, .dark textarea::placeholder {
                color: #64748b !important;
            }
            
            /* Location List Page - preserve Tailwind classes */
            .location-list-input,
            .location-list-select {
                /* Allow Tailwind to control these */
            }
            
            /* Kode Desa input maintains its own styling */
            .kode-desa-container {
                background: #f3f4f6 !important;
                border-color: #e5e7eb !important;
            }
            
            .dark .kode-desa-container {
                background: #374151 !important;
                border-color: #4b5563 !important;
            }
            
            /* Label styling for Kode Desa */
            .kode-desa-label {
                color: #1f2937 !important;
                font-weight: 600 !important;
            }
            
            .dark .kode-desa-label {
                color: #f9fafb !important;
            }
            
            .kode-desa-input {
                background: #ffffff !important;
                border-color: #d1d5db !important;
                color: #111827 !important;
            }
            
            .dark .kode-desa-input {
                background: #1f2937 !important;
                border-color: #3b82f6 !important;
                color: #f3f4f6 !important;
            }
            
            .dark .kode-desa-input::placeholder {
                color: #9ca3af !important;
            }
            
            /* ===== NAV ITEMS ===== */
            .nav-item {
                display: flex;
                align-items: center;
                padding: 10px 16px;
                margin: 2px 12px;
                border-radius: 8px;
                color: var(--text-secondary);
                font-size: 14px;
                font-weight: 500;
                transition: all 0.15s ease;
                cursor: pointer;
                text-decoration: none;
                position: relative;
            }
            
            .nav-item:hover {
                background: var(--surface-2);
                color: var(--text);
            }
            
            .nav-item.active {
                background: var(--primary-soft);
                color: var(--primary);
            }
            
            .nav-item.active::before {
                content: '';
                position: absolute;
                left: 0;
                top: 50%;
                transform: translateY(-50%);
                width: 3px;
                height: 20px;
                background: var(--primary);
                border-radius: 0 3px 3px 0;
            }
            
            .nav-icon {
                width: 18px;
                height: 18px;
                margin-right: 12px;
                flex-shrink: 0;
            }
            
            .section-title {
                font-size: 11px;
                font-weight: 600;
                color: var(--muted);
                text-transform: uppercase;
                letter-spacing: 0.05em;
                padding: 16px 16px 8px 28px;
            }
            
            .sidebar-divider {
                height: 1px;
                background: var(--border);
                margin: 8px 16px;
            }
            
            /* ===== USER PROFILE ===== */
            .user-profile {
                display: flex;
                align-items: center;
                padding: 16px;
                border-top: 1px solid #e5e7eb;
                margin-top: auto;
            }
            
            .dark .user-profile {
                border-top-color: #334155;
            }
            
            .user-avatar {
                width: 40px;
                height: 40px;
                border-radius: 50%;
                background: linear-gradient(135deg, #3b82f6, #8b5cf6);
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-weight: 600;
                font-size: 14px;
                margin-right: 12px;
            }
            
            .user-name {
                font-size: 14px;
                font-weight: 600;
                color: #1e293b;
            }
            
            .dark .user-name {
                color: #fff;
            }
            
            .user-email {
                font-size: 12px;
                color: #64748b;
            }
            
            /* ===== MOBILE ===== */
            .mobile-header {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                height: 60px;
                background: #fff;
                border-bottom: 1px solid #e5e7eb;
                z-index: 40;
                padding: 0 16px;
                align-items: center;
            }
            
            .dark .mobile-header {
                background: #1e293b;
                border-bottom-color: #334155;
            }
            
            .sidebar-overlay {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(0,0,0,0.5);
                z-index: 45;
            }
            
            @media (max-width: 1024px) {
                .sidebar {
                    transform: translateX(-100%);
                    /* Fix mobile color consistency: Use opaque gradient to mimic desktop glass-on-blue look */
                    background: linear-gradient(180deg, #f0f9ff 0%, #ffffff 100%) !important;
                }
                
                /* Dark mode mobile override to prevent transparancy issues with overlay */
                .dark .sidebar {
                    background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%) !important;
                }
                
                .sidebar.open {
                    transform: translateX(0);
                }
                
                .sidebar-overlay.open {
                    display: block;
                }
                
                .main-content {
                    margin-left: 0;
                    padding-top: 60px;
                }
                
                .mobile-header {
                    display: flex;
                }
            }
            
            /* ===== STANDARD BUTTON SIZES ===== */
            .btn {
                padding: 10px 20px;
                font-size: 14px;
                font-weight: 600;
                border-radius: 8px;
                border: none;
                cursor: pointer;
                transition: all 0.15s ease;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
            }
            
            .btn-sm {
                padding: 8px 16px;
                font-size: 13px;
            }
            
            .btn-primary {
                background: #3b82f6;
                color: white;
            }
            
            .btn-primary:hover {
                background: #2563eb;
            }
            
            .btn-success {
                background: #22c55e;
                color: white;
            }
            
            .btn-success:hover {
                background: #16a34a;
            }
            
            .btn-danger {
                background: #ef4444;
                color: white;
            }
            
            .btn-danger:hover {
                background: #dc2626;
            }
            
            .btn-secondary {
                background: #e2e8f0;
                color: #475569;
            }
            
            .btn-secondary:hover {
                background: #cbd5e1;
            }
            
            .dark .btn-secondary {
                background: #334155;
                color: #e2e8f0;
            }
            
            .dark .btn-secondary:hover {
                background: #475569;
            }
        </style>
    </head>
    @if (session('toast'))
        <script>
            window.addEventListener('DOMContentLoaded', () => {
                window.showToastFromSession(@json(session('toast')));
            });
        </script>
    @endif

    <body class="antialiased">
        <!-- Mobile Header -->
        <div class="mobile-header">
            <button @click="sidebarOpen = true" style="padding: 8px; border-radius: 8px; border: none; background: transparent; cursor: pointer;">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" :style="darkMode ? 'color: #fff' : 'color: #1e293b'">
                    <path d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <div style="margin-left: 12px; font-weight: 600; font-size: 16px;" :style="darkMode ? 'color: #fff' : 'color: #1e293b'">
                {{ config('app.name', 'Pemetaan Wilayah') }}
            </div>
        </div>
        
        <!-- Sidebar Overlay -->
        <div class="sidebar-overlay" :class="{ 'open': sidebarOpen }" @click="sidebarOpen = false"></div>
        
        <!-- Sidebar -->
        <aside class="sidebar" :class="{ 'open': sidebarOpen }">
            <!-- Logo -->
            <div style="padding: 20px 16px; display: flex; align-items: center; gap: 12px;">
                <div style="width: 36px; height: 36px; border-radius: 10px; background: linear-gradient(135deg, #3b82f6, #1d4ed8); display: flex; align-items: center; justify-content: center;">
                    <svg width="20" height="20" fill="white" viewBox="0 0 24 24">
                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                    </svg>
                </div>
                <span style="font-weight: 700; font-size: 16px;" :style="darkMode ? 'color: #fff' : 'color: #1e293b'">
                    Pemetaan Wilayah
                </span>
            </div>
            
            <!-- Navigation -->
            <nav style="flex: 1; overflow-y: auto; padding: 8px 0;">
                {{-- Public Items --}}
                <a href="{{ route('map') }}" class="nav-item {{ request()->routeIs('map') ? 'active' : '' }}" wire:navigate>
                    <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                    </svg>
                    Peta
                </a>
                
                <a href="{{ route('locations.index') }}" class="nav-item {{ request()->routeIs('locations.index') ? 'active' : '' }}" wire:navigate>
                    <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                    Data Lokasi
                </a>
                
                {{-- Protected Items (Authenticated Only) --}}
                @auth
                    <a href="{{ route('locations.create') }}" class="nav-item {{ request()->routeIs('locations.create') ? 'active' : '' }}" wire:navigate>
                        <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M12 4v16m8-8H4"/>
                        </svg>
                        Tambah Lokasi
                    </a>
                    
                    @if(auth()->user()->can('import_excel') || auth()->user()->hasRole('super-admin'))
                    <a href="{{ route('import.index') }}" class="nav-item {{ request()->routeIs('import.index') ? 'active' : '' }}" wire:navigate>
                        <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                        Import Excel
                    </a>
                    @endif

                    <div class="sidebar-divider"></div>
                    <div class="section-title">Akun</div>

                    <a href="{{ route('profile') }}" class="nav-item {{ request()->routeIs('profile') ? 'active' : '' }}" wire:navigate>
                        <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Profil Saya
                    </a>

                    @if(auth()->user()->can('manage_users') || auth()->user()->hasRole('super-admin'))
                    <a href="{{ route('users.index') }}" class="nav-item {{ request()->routeIs('users.index') ? 'active' : '' }}" wire:navigate>
                        <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        Manajemen Akun (User)
                    </a>
                    @endif

                    @role('super-admin')
                    <a href="{{ route('roles.index') }}" class="nav-item {{ request()->routeIs('roles.index') ? 'active' : '' }}" wire:navigate>
                        <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        Manajemen Role
                    </a>
                    @endrole
                @endauth
            </nav>

            <!-- Dark Mode Toggle (Visible to ALL users) -->
            <div style="padding: 0 0 8px 0; border-top: 1px solid var(--border);">
                <div class="section-title" style="margin-top: 12px;">Pengaturan</div>
                <button @click="darkMode = !darkMode" class="dark-mode-toggle" style="width: calc(100% - 24px); border: none; background: none; text-align: left; display: flex; align-items: center; gap: 12px; padding: 10px 12px; margin: 4px 12px; border-radius: 8px; font-size: 14px; color: var(--muted); cursor: pointer; transition: all 0.15s;"
                        onmouseover="this.style.background='var(--surface-2)'" onmouseout="this.style.background='transparent'">
                    <svg x-show="!darkMode" class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                    <svg x-show="darkMode" class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span x-text="darkMode ? 'Mode Terang' : 'Mode Gelap'"></span>
                </button>
            </div>
            
            <!-- User Profile / Login -->
            <div class="user-profile">
                @auth
                    <div class="user-avatar">
                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                    </div>
                    <div style="flex: 1; min-width: 0;">
                        <div class="user-name">{{ auth()->user()->name ?? 'User' }}</div>
                        <div class="user-email">{{ auth()->user()->email ?? '' }}</div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" style="margin-left: 8px;">
                        @csrf
                        <button type="submit" style="padding: 8px; border-radius: 8px; border: none; background: transparent; cursor: pointer;" 
                                :style="darkMode ? 'color: #94a3b8' : 'color: #64748b'" title="Logout">
                            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" style="width: 100%; display: flex; align-items: center; justify-content: center; gap: 8px; background: var(--primary); color: white; padding: 10px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 14px;">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        Login
                    </a>
                @endauth
            </div>
        </aside>
        
        <!-- Main Content -->
        <div class="main-content">
            @hasSection('content')
                @yield('content')
            @elseif(isset($slot))
                {{ $slot }}
            @endif
        </div>
        
        <script>
            // Close sidebar when clicking nav item on mobile
            document.querySelectorAll('.nav-item').forEach(item => {
                item.addEventListener('click', () => {
                    if (window.innerWidth < 1024) {
                        // Use Alpine.js to properly close sidebar
                        const root = document.documentElement;
                        if (root._x_dataStack && root._x_dataStack[0]) {
                            root._x_dataStack[0].sidebarOpen = false;
                        }
                    }
                });
            });

            // Dark Mode Persistence for Livewire Navigate
            function applyDarkMode() {
                if (localStorage.getItem('darkMode') === 'true') {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            }

            // Apply immediately
            applyDarkMode();

            // ===== AUTH STATE CHANGE DETECTION =====
            // Detect if user changed (login/logout/switch account) and force hard reload
            // This clears stale Livewire/Turbolinks cache that causes UI glitches
            (function() {
                const currentUserId = '{{ auth()->id() ?? "guest" }}';
                const storedUserId = localStorage.getItem('authUserId');
                
                // If user changed, force a hard reload to clear cache
                if (storedUserId !== null && storedUserId !== currentUserId) {
                    console.log('[Auth] User changed from', storedUserId, 'to', currentUserId, '- reloading...');
                    localStorage.setItem('authUserId', currentUserId);
                    // Force hard reload (bypass cache)
                    window.location.reload(true);
                    return;
                }
                
                // Store current user ID for next check
                localStorage.setItem('authUserId', currentUserId);
            })();

            // Apply on navigation
            document.addEventListener('livewire:navigated', () => {
                applyDarkMode();
                // Re-init Alpine state if needed, though local storage binding usually handles it.
                // But just in case Alpine loses sync with the class:
                if (window.Alpine) {
                    // Update the Alpine data to match localStorage if it somehow drifted (optional)
                }
            });

            // Global Toast Notification
            if (!window.Toast) {
                window.Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                });
            }

            // Handle Session Flash Messages (Full Page Load)
            @if(session('success'))
                window.Toast.fire({ icon: 'success', title: "{{ session('success') }}" });
            @endif
            @if(session('error'))
                window.Toast.fire({ icon: 'error', title: "{{ session('error') }}" });
            @endif
            @if(session('warning'))
                window.Toast.fire({ icon: 'warning', title: "{{ session('warning') }}" });
            @endif

            // Handle Livewire Events
            document.addEventListener('livewire:initialized', () => {
                Livewire.on('show-toast', (event) => {
                    const data = Array.isArray(event) ? event[0] : event;
                    window.Toast.fire({
                        icon: data.type || 'success',
                        title: data.message
                    });
                });

                Livewire.on('swal:toast', (event) => {
                    const data = Array.isArray(event) ? event[0] : event;
                    window.Toast.fire({
                        icon: data.type || 'success',
                        title: data.message
                    });
                });

                Livewire.on('swal:confirm', (event) => {
                    const data = Array.isArray(event) ? event[0] : event;
                    confirmSwal(data).then((result) => {
                        if (result.isConfirmed) {
                            // Fix: Ensure params is an object for named-argument binding if it's a scalar
                            let params = data.params;
                            if (typeof params !== 'object' || params === null) {
                                params = { id: params };
                            }
                            Livewire.dispatch(data.method, params);
                        }
                    });
                });
            });

            // Global Confirmation Helper
            window.confirmSwal = (config) => {
                return Swal.fire({
                    title: config.title || 'Konfirmasi',
                    text: config.text || 'Apakah Anda yakin?',
                    html: config.html, // Support HTML content
                    icon: config.icon || 'warning',
                    showCancelButton: true,
                    confirmButtonColor: 'var(--danger)',
                    cancelButtonColor: '#6c757d', // Use hardcoded grey to ensure visibility
                    confirmButtonText: config.confirmButtonText || 'Ya, Lanjutkan!',
                    cancelButtonText: config.cancelButtonText || 'Batal',
                    reverseButtons: true, // Actions on right/left preference
                    background: 'var(--surface)',
                    color: 'var(--text)',
                    input: config.input, // Support input
                    // Custom Validator for matching text
                    inputValidator: (value) => {
                        if (config.inputMatch && value !== config.inputMatch) {
                            return 'Konfirmasi salah! Ketik "' + config.inputMatch + '" untuk melanjutkan.';
                        }
                        return config.inputValidator ? config.inputValidator(value) : null;
                    },
                    preConfirm: config.preConfirm,
                    customClass: {
                        popup: 'swal-popup-custom'
                    },
                    didOpen: () => {
                        const container = Swal.getContainer();
                        if (container) container.classList.add('swal-blur');
                        if (config.didOpen) config.didOpen();
                    },
                    willClose: () => {
                        const container = Swal.getContainer();
                        if (container) container.classList.remove('swal-blur');
                        if (config.willClose) config.willClose();
                    }
                });
            };
        </script>
        
        <style>
            .swal-blur {
                backdrop-filter: blur(5px) !important;
                -webkit-backdrop-filter: blur(5px) !important;
                background: rgba(0,0,0,0.5) !important;
            }
        </style>
        
        
        @livewireScripts
    </body>
</html>
