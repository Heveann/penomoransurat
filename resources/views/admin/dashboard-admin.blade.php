@extends('admin.app-admin')
@section('content')

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({ icon: 'success', title: 'Berhasil!', text: '{{ session("success") }}', confirmButtonColor: '#6366f1', timer: 2500, timerProgressBar: true });
            });
        </script>
    @endif
    @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({ icon: 'error', title: 'Gagal!', text: '{{ session("error") }}', confirmButtonColor: '#6366f1' });
            });
        </script>
    @endif

    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h5 style="font-weight: 700; color: #0f172a; margin: 0;">Dashboard</h5>
            <p style="font-size: 0.85rem; color: #64748b; margin: 0;">Selamat datang, {{ Auth::user()->name }}</p>
        </div>
        <div style="font-size: 0.8rem; color: #94a3b8;">
            <i class="bi bi-calendar3 me-1"></i> {{ now()->translatedFormat('l, d F Y') }}
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: #eff6ff; color: #3b82f6;">
                        <i class="bi bi-envelope"></i>
                    </div>
                    <div>
                        <div class="stat-label">Pengajuan Nomor Surat Bulan Ini</div>
                        <div class="stat-value">
                            {{ \App\Models\SuratKeluar::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: #ecfdf5; color: #10b981;">
                        <i class="bi bi-check2-circle"></i>
                    </div>
                    <div>
                        <div class="stat-label">Disetujui Bulan Ini</div>
                        <div class="stat-value">
                            {{ \App\Models\SuratKeluar::where('status', 'disetujui')->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: #fffbeb; color: #f59e0b;">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <div>
                        <div class="stat-label">Menunggu</div>
                        <div class="stat-value">
                            {{ \App\Models\SuratKeluar::whereNotIn('status', ['disetujui', 'ditolak'])->count() }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: #f5f3ff; color: #8b5cf6;">
                        <i class="bi bi-people"></i>
                    </div>
                    <div>
                        <div class="stat-label">Total User</div>
                        <div class="stat-value">{{ \App\Models\User::count() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="row g-3 mb-4">
        <div class="col-lg-8">
            <div class="chart-card">
                <div class="chart-title">Nomor Surat Dibuat per Bulan</div>
                <canvas id="chartSuratKeluar" height="85"></canvas>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="chart-card">
                <div class="chart-title">Distribusi Status</div>
                <canvas id="chartKlasifikasi" height="170"></canvas>
            </div>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @php
        $monthlySuratData = array_fill(0, 12, 0);
        $suratTahunIni = \App\Models\SuratKeluar::where('status', 'disetujui')
            ->whereYear('created_at', now()->year)
            ->get();
        foreach ($suratTahunIni as $req) {
            $month = \Carbon\Carbon::parse($req->created_at)->month;
            $monthlySuratData[$month - 1]++;
        }
    @endphp
    <script>
        // Line Chart
        const ctxLine = document.getElementById('chartSuratKeluar').getContext('2d');
        new Chart(ctxLine, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Nomor Surat',
                    data: @json($monthlySuratData),
                    borderColor: '#6366f1',
                    backgroundColor: 'rgba(99,102,241,0.08)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 3,
                    pointBackgroundColor: '#6366f1',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false }, ticks: { color: '#94a3b8', font: { size: 11 } } },
                    y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { color: '#94a3b8', font: { size: 11 }, precision: 0 } }
                }
            }
        });

        // Doughnut Chart
        const ctxDonut = document.getElementById('chartKlasifikasi').getContext('2d');
        new Chart(ctxDonut, {
            type: 'doughnut',
            data: {
                labels: ['Disetujui', 'Pending', 'Ditolak'],
                datasets: [{
                    data: [
                    {{ \App\Models\SuratKeluar::where('status', 'disetujui')->count() }},
                    {{ \App\Models\SuratKeluar::whereNotIn('status', ['disetujui', 'ditolak'])->count() }},
                        {{ \App\Models\SuratKeluar::where('status', 'ditolak')->count() }}
                    ],
                    backgroundColor: ['#10b981', '#f59e0b', '#ef4444'],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                cutout: '65%',
                plugins: {
                    legend: { position: 'bottom', labels: { padding: 16, usePointStyle: true, pointStyle: 'circle', font: { size: 11 } } }
                }
            }
        });
    </script>

@endsection