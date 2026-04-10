<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Google Font: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>
        /* ===== BASE ===== */
        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #f1f5f9;
            color: #1e293b;
            margin: 0;
            overflow-x: hidden;
        }

        /* ===== SIDEBAR ===== */
        .user-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: 260px;
            background: #0f172a;
            color: #e2e8f0;
            z-index: 1100;
            display: flex;
            flex-direction: column;
            transition: transform 0.35s cubic-bezier(.4,.6,.2,1);
            overflow-y: auto;
        }

        .user-sidebar.closed {
            transform: translateX(-260px);
        }

        .sidebar-brand {
            padding: 1.5rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }

        .sidebar-brand-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: linear-gradient(135deg, #6366f1, #818cf8);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .sidebar-brand-text {
            font-weight: 700;
            font-size: 1rem;
            color: #fff;
            letter-spacing: -0.3px;
        }

        .sidebar-section {
            padding: 1.25rem 1.25rem 0.5rem;
            font-size: 0.68rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #64748b;
        }

        .sidebar-nav {
            list-style: none;
            padding: 0 0.75rem;
            margin: 0;
        }

        .sidebar-nav li { margin-bottom: 2px; }

        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.7rem 0.85rem;
            border-radius: 8px;
            color: #94a3b8;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .sidebar-nav a:hover {
            background: rgba(255,255,255,0.06);
            color: #e2e8f0;
        }

        .sidebar-nav a.active {
            background: #4f46e5;
            color: #fff;
            box-shadow: 0 2px 8px rgba(79,70,229,0.35);
        }

        .sidebar-nav a .nav-icon {
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 1.05rem;
        }

        /* Sidebar Footer */
        .sidebar-footer {
            margin-top: auto;
            padding: 1rem 1.25rem;
            border-top: 1px solid rgba(255,255,255,0.06);
        }

        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .sidebar-user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid rgba(255,255,255,0.1);
        }

        .sidebar-user-info { flex: 1; min-width: 0; }
        .sidebar-user-name {
            font-size: 0.8rem;
            font-weight: 600;
            color: #fff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .sidebar-user-role {
            font-size: 0.7rem;
            color: #64748b;
            text-transform: capitalize;
        }

        /* ===== TOP NAVBAR ===== */
        .user-topbar {
            position: fixed;
            top: 0;
            left: 260px;
            right: 0;
            height: 64px;
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            z-index: 1000;
            transition: left 0.35s cubic-bezier(.4,.6,.2,1);
        }

        .user-topbar.full { left: 0; }

        .topbar-toggle {
            border: none;
            background: none;
            color: #475569;
            font-size: 1.25rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 8px;
            transition: background 0.2s;
        }
        .topbar-toggle:hover { background: #f1f5f9; }

        .topbar-right {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        /* ===== MAIN CONTENT ===== */
        .user-content {
            margin-left: 260px;
            margin-top: 64px;
            min-height: calc(100vh - 64px);
            background: #f1f5f9;
            transition: margin-left 0.35s cubic-bezier(.4,.6,.2,1);
            animation: fadeUp 0.4s ease;
            display: flex;
            flex-direction: column;
        }

        .user-content.full { margin-left: 0; }

        .user-content > main {
            flex: 1;
            padding: 1.5rem;
        }

        /* ===== FOOTER ===== */
        .user-footer {
            padding: 1rem 1.5rem;
            text-align: center;
            font-size: 0.78rem;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            background: #fff;
            margin-top: auto;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ===== STAT CARDS (shared with dashboard) ===== */
        .stat-card {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            padding: 1.25rem;
            transition: all 0.2s ease;
            overflow: hidden;
            position: relative;
        }
        .stat-card:hover {
            border-color: #cbd5e1;
            box-shadow: 0 4px 12px rgba(0,0,0,0.04);
        }
        .stat-card-bar {
            position: absolute;
            left: 0; top: 16px; bottom: 16px;
            width: 4px;
            border-radius: 6px;
        }

        .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        .stat-label {
            font-size: 0.8rem;
            color: #64748b;
            font-weight: 500;
            margin-bottom: 0.25rem;
        }

        .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: #0f172a;
            line-height: 1;
        }

        /* ===== DATA TABLE ===== */
        .data-card {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }

        .data-card-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .data-card-title {
            font-weight: 600;
            font-size: 0.95rem;
            color: #0f172a;
        }

        /* ===== CHART CARD ===== */
        .chart-card {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            padding: 1.25rem;
        }
        .chart-title {
            font-size: 0.85rem;
            font-weight: 600;
            color: #334155;
            margin-bottom: 1rem;
        }

        /* ===== BADGES ===== */
        .badge-status {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.3rem 0.7rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .badge-approved { background: #ecfdf5; color: #059669; }
        .badge-rejected { background: #fef2f2; color: #dc2626; }
        .badge-pending  { background: #fffbeb; color: #d97706; }

        /* ===== FOOTER ===== */
        .user-footer {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 1rem;
            margin-top: 2rem;
            text-align: center;
            font-size: 0.8rem;
            color: #94a3b8;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 991px) {
            .user-sidebar { transform: translateX(-260px); }
            .user-sidebar:not(.closed) { transform: translateX(0); }
            .user-sidebar.closed { transform: translateX(-260px); }
            .user-topbar { left: 0 !important; }
            .user-content { margin-left: 0 !important; }
        }

        @media (min-width: 992px) {
            .user-sidebar { transform: translateX(0); }
            .user-sidebar.closed { transform: translateX(-260px); }
            .user-sidebar.closed ~ .user-topbar { left: 0; }
            .user-sidebar.closed ~ .user-content { margin-left: 0; }
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

    <!-- ===== SIDEBAR ===== -->
    <aside class="user-sidebar" id="userSidebar">
        <div class="sidebar-brand">
            <div class="sidebar-brand-icon">
                <i class="bi bi-envelope-paper-fill text-white"></i>
            </div>
            <span class="sidebar-brand-text">Penomoran Surat</span>
        </div>

        <div class="sidebar-section">Menu Utama</div>
        <ul class="sidebar-nav">
            <li>
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') || request()->routeIs('user.dashboard-user') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="bi bi-grid-1x2"></i></span>
                    Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('user.create-user') }}" class="{{ request()->routeIs('user.create-user') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="bi bi-plus-circle"></i></span>
                    Permintaan Nomor
                </a>
            </li>
            <li>
                <a href="{{ route('user.daftar-nomor') }}" class="{{ request()->routeIs('user.daftar-nomor') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="bi bi-file-earmark-text"></i></span>
                    Daftar Nomor
                </a>
            </li>

        </ul>

        <!-- Sidebar Footer: User Info -->
        <div class="sidebar-footer">
            <div class="sidebar-user">
                <img src="{{ (!empty(Auth::user()->avatar_url) && file_exists(public_path(Auth::user()->avatar_url))) ? asset(Auth::user()->avatar_url) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name ?? 'U') . '&size=40&background=4f46e5&color=fff' }}"
                     alt="Avatar" class="sidebar-user-avatar">
                <div class="sidebar-user-info">
                    <div class="sidebar-user-name">{{ Auth::user()->name }}</div>
                    <div class="sidebar-user-role">{{ Auth::user()->role }}</div>
                </div>
            </div>
        </div>
    </aside>

    <!-- ===== TOP NAVBAR ===== -->
    <header class="user-topbar" id="userTopbar">
        <button class="topbar-toggle" id="sidebarToggle" aria-label="Toggle sidebar">
            <i class="bi bi-list"></i>
        </button>
        <div class="topbar-right">
            @include('layouts.navigation')
        </div>
    </header>

    <!-- ===== MAIN CONTENT ===== -->
    <div class="user-content" id="userContent">
        <main>
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="user-footer">
            &copy; {{ date('Y') }} Penomoran Surat Otomatis &mdash; All rights reserved.
        </footer>
    </div>

    <script>
        const sidebar = document.getElementById('userSidebar');
        const topbar  = document.getElementById('userTopbar');
        const content = document.getElementById('userContent');
        const toggle  = document.getElementById('sidebarToggle');

        toggle.addEventListener('click', () => {
            sidebar.classList.toggle('closed');
            topbar.classList.toggle('full');
            content.classList.toggle('full');
        });

        // Close sidebar on outside click (mobile)
        document.addEventListener('click', (e) => {
            if (window.innerWidth <= 991) {
                if (!sidebar.contains(e.target) && !toggle.contains(e.target)) {
                    sidebar.classList.add('closed');
                    topbar.classList.remove('full');
                    content.classList.remove('full');
                }
            }
        });
    </script>
</body>
</html>