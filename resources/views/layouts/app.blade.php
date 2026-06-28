<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PT WAGS - Sistem Pakar')</title>
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
    @yield('styles')
</head>
<body>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="app-container">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="brand">
                <div class="brand-logo">
                    <i data-lucide="gem"></i>
                </div>
                <h1 class="brand-name">PT WAGS</h1>
            </div>

            <nav class="nav-list">
                <li>
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i data-lucide="layout-dashboard"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('samples.create') }}" class="nav-link {{ request()->routeIs('samples.create*') ? 'active' : '' }}">
                        <i data-lucide="clipboard-edit"></i>
                        <span>Input Data Uji</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('samples.index') }}" class="nav-link {{ request()->routeIs('samples.index') ? 'active' : '' }}">
                        <i data-lucide="file-text"></i>
                        <span>Laporan Uji</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('settings.index') }}" class="nav-link {{ request()->routeIs('settings.index') ? 'active' : '' }}">
                        <i data-lucide="settings"></i>
                        <span>Pengaturan</span>
                    </a>
                </li>
            </nav>

            <div class="mt-auto pt-4">
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
                <a href="#" class="nav-link nav-link-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i data-lucide="log-out"></i>
                    <span>Keluar</span>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="main-inner">
                <header class="app-header d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        <button class="header-mobile-toggle" id="sidebarToggle">
                            <i data-lucide="menu"></i>
                        </button>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <div class="text-end d-none d-lg-block">
                            <p class="user-title">Admin Internal</p>
                            <p class="user-subtitle">PT Wina Alam Gunung Semesta</p>
                        </div>
                        <div class="user-avatar">
                            <i data-lucide="user" class="user-avatar-icon"></i>
                        </div>
                    </div>
                </header>

                @if(session('success'))
                    <div class="card animate-fade-in alert-card alert-card-success">
                        <div class="alert-card-body">
                            <i data-lucide="check-circle" class="alert-card-icon"></i>
                            <span class="alert-card-text">{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="card animate-fade-in alert-card alert-card-danger">
                        <div class="alert-card-body">
                            <i data-lucide="alert-circle" class="alert-card-icon"></i>
                            <span class="alert-card-text">{{ session('error') }}</span>
                        </div>
                    </div>
                @endif

                <div class="animate-fade-in">
                    @yield('content')
                </div>
            </div>
        </main>
    </div>

    <script>
        lucide.createIcons();

        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        function toggleSidebar() {
            sidebar.classList.toggle('active');
            sidebarOverlay.classList.toggle('active');
        }

        sidebarToggle.addEventListener('click', toggleSidebar);
        sidebarOverlay.addEventListener('click', toggleSidebar);
    </script>
    @yield('scripts')
</body>
</html>
