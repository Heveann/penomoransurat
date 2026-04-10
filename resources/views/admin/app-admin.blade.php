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
        }

        /* ===== SIDEBAR ===== */
        .admin-sidebar {
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
            transition: transform 0.3s ease;
            overflow-y: auto;
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
        }

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
            color: #e2e8f0;
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
        .admin-topbar {
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
        }

        .topbar-toggle {
            display: none;
            border: none;
            background: none;
            color: #475569;
            font-size: 1.25rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 8px;
        }
        .topbar-toggle:hover { background: #f1f5f9; }

        .topbar-right {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        /* ===== MAIN CONTENT ===== */
        .admin-content {
            margin-left: 260px;
            margin-top: 64px;
            padding: 1.5rem;
            min-height: calc(100vh - 64px);
            animation: fadeUp 0.4s ease;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ===== CARDS ===== */
        .stat-card {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            padding: 1.25rem;
            transition: all 0.2s ease;
        }
        .stat-card:hover {
            border-color: #cbd5e1;
            box-shadow: 0 4px 12px rgba(0,0,0,0.04);
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
        
        .search-input {
            border: 1.5px solid #e2e8f0 !important;
            border-radius: 8px !important;
            padding: 0.5rem 0.85rem 0.5rem 2.25rem !important;
            font-size: 0.8rem !important;
            font-family: 'Inter', sans-serif !important;
            color: #334155 !important;
            background: #fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' fill='%2394a3b8' viewBox='0 0 16 16'%3E%3Cpath d='M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0'/%3E%3C/svg%3E") no-repeat 0.75rem center !important;
            outline: none !important;
            width: 240px !important;
            transition: border-color 0.2s !important;
        }
        .search-input:focus { border-color: #6366f1 !important; box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1) !important; }
        .search-input::placeholder { color: #94a3b8 !important; }

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

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table thead th {
            padding: 0.85rem 1.25rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            text-align: left;
        }

        .data-table tbody td {
            padding: 0.85rem 1.25rem;
            font-size: 0.875rem;
            color: #334155;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        .data-table tbody tr:last-child td { border-bottom: none; }

        .data-table tbody tr:hover { background: #f8fafc; }

        /* ===== BADGES (CUSTOM) ===== */
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
        .badge-revisi   { background: #eff6ff; color: #2563eb; }
        .badge-role     { background: #f1f5f9; color: #475569; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px; }

        /* ===== BUTTONS ===== */
        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.4rem 0.85rem;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 500;
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.15s ease;
        }
        .btn-approve { background: #ecfdf5; color: #059669; }
        .btn-approve:hover { background: #d1fae5; color: #047857; }
        .btn-reject  { background: #fef2f2; color: #dc2626; }
        .btn-reject:hover  { background: #fee2e2; color: #b91c1c; }
        .btn-print   { background: #eff6ff; color: #2563eb; }
        .btn-print:hover   { background: #dbeafe; color: #1d4ed8; }

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

        /* ===== ALERT ===== */
        .alert-modern {
            border-radius: 8px;
            padding: 0.75rem 1rem;
            font-size: 0.85rem;
            font-weight: 500;
            border: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }
        .alert-modern.success { background: #ecfdf5; color: #059669; }
        .alert-modern.error   { background: #fef2f2; color: #dc2626; }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 991px) {
            .admin-sidebar { transform: translateX(-260px); }
            .admin-sidebar.open { transform: translateX(0); }
            .admin-topbar { left: 0; }
            .admin-content { margin-left: 0; }
            .topbar-toggle { display: block; }
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

    <!-- ===== SIDEBAR ===== -->
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="sidebar-brand">
            <div class="sidebar-brand-icon">
                <i class="bi bi-envelope-paper-fill text-white"></i>
            </div>
            <span class="sidebar-brand-text">Penomoran Surat</span>
        </div>

        <div class="sidebar-section">Menu</div>
        <ul class="sidebar-nav">
            <li>
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="bi bi-grid-1x2"></i></span>
                    Dashboard
                </a>
            </li>
            @php
                $pendingCount = \App\Models\SuratKeluar::whereNotIn('status', ['disetujui', 'ditolak'])->count();
            @endphp
            <li>
                <a href="{{ route('surat.index') }}" class="{{ request()->routeIs('surat.index') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="bi bi-box-arrow-up-right"></i></span>
                    <span style="flex: 1;"> Pengajuan Nomor Surat</span>
                    @if(Auth::user()->role === 'admin' && $pendingCount > 0)
                        <span class="badge rounded-pill" style="background-color: #ef4444; font-size: 0.65rem; padding: 0.3rem 0.6rem;">{{ $pendingCount }}</span>
                    @endif
                </a>
            </li>
            @if(Auth::user()->role === 'pimpinan' || Auth::user()->role === 'admin')
            <li>
                <a href="{{ route('monitoring.index') }}" class="{{ request()->routeIs('monitoring.*') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="bi bi-activity"></i></span>
                    <span style="flex: 1;"> Monitoring Ajuan</span>
                </a>
            </li>
            @endif

        </ul>

        @if(Auth::user()->role === 'admin')
        <div class="sidebar-section" style="margin-top: 0.5rem;">Master Data</div>
        <ul class="sidebar-nav">
            <li>
                <a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="bi bi-people"></i></span>
                    Manajemen User
                </a>
            </li>
            <li>
                <a href="{{ route('klasifikasi.index') }}" class="{{ request()->routeIs('klasifikasi.*') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="bi bi-tags"></i></span>
                    Data Klasifikasi
                </a>
            </li>
            <li>
                <a href="{{ route('unit-kerja.index') }}" class="{{ request()->routeIs('unit-kerja.*') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="bi bi-building"></i></span>
                    Unit Kerja
                </a>
            </li>
        </ul>
        @endif

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
    <header class="admin-topbar">
        <button class="topbar-toggle" id="sidebarToggle" aria-label="Toggle sidebar">
            <i class="bi bi-list"></i>
        </button>
        <div class="topbar-right">
            @include('layouts.navigation')
        </div>
    </header>

    <!-- ===== MAIN CONTENT ===== -->
    <main class="admin-content">
        @yield('content')
    </main>

    <script>
        const sidebar = document.getElementById('adminSidebar');
        const toggle  = document.getElementById('sidebarToggle');
        toggle.addEventListener('click', () => sidebar.classList.toggle('open'));
        document.addEventListener('click', e => {
            if (window.innerWidth <= 991 && !sidebar.contains(e.target) && !toggle.contains(e.target)) {
                sidebar.classList.remove('open');
            }
        });
    </script>
</body>
</html>
