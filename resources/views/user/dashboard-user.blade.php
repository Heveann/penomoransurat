@extends('user.app-user')
@section('content')

<!-- Page Header -->
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h5 style="font-weight: 700; color: #0f172a; margin: 0;">Dashboard</h5>
        <p style="font-size: 0.85rem; color: #64748b; margin: 0;">Selamat datang, {{ Auth::user()->name ?? 'User' }}</p>
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
                    <i class="bi bi-collection"></i>
                </div>
                <div>
                    <div class="stat-label">Total Request Bulan Ini</div>
                    <div class="stat-value">{{ $totalRequest ?? 0 }}</div>
                </div>
            </div>
            <div class="mt-2" style="font-size: 0.8rem;">
                @php
                    $pr = round($percentRequest ?? 0, 1);
                    $prColor = $pr > 0 ? 'color:#059669' : ($pr < 0 ? 'color:#dc2626' : 'color:#94a3b8');
                    $prArrow = $pr > 0 ? '↑' : ($pr < 0 ? '↓' : '→');
                @endphp
                <span style="{{ $prColor }}; font-weight:600;">{{ $prArrow }}{{ abs($pr) }}%</span>
                <span style="color:#94a3b8;"> dari bulan lalu</span>
            </div>
            <div class="stat-card-bar" style="background: #3b82f6;"></div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background: #fffbeb; color: #f59e0b;">
                    <i class="bi bi-hourglass-split"></i>
                </div>
                <div>
                    <div class="stat-label">Menunggu Persetujuan</div>
                    <div class="stat-value">{{ \App\Models\SuratKeluar::where('user_id', Auth::id())->whereNotIn('status', ['disetujui', 'ditolak'])->count() }}</div>
                </div>
            </div>
            <div class="stat-card-bar" style="background: #f59e0b;"></div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background: #fffbeb; color: #f59e0b;">
                    <i class="bi bi-file-earmark-text"></i>
                </div>
                <div>
                    <div class="stat-label">Surat Keputusan</div>
                    <div class="stat-value">{{ $totalKeputusan ?? 0 }}</div>
                </div>
            </div>
            <div class="mt-2" style="font-size: 0.8rem;">
                @php
                    $pkp = round($percentKeputusan ?? 0, 1);
                    $pkpColor = $pkp > 0 ? 'color:#059669' : ($pkp < 0 ? 'color:#dc2626' : 'color:#94a3b8');
                    $pkpArrow = $pkp > 0 ? '↑' : ($pkp < 0 ? '↓' : '→');
                @endphp
                <span style="{{ $pkpColor }}; font-weight:600;">{{ $pkpArrow }}{{ abs($pkp) }}%</span>
                <span style="color:#94a3b8;"> dari bulan lalu</span>
            </div>
            <div class="stat-card-bar" style="background: #f59e0b;"></div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background: #fce7f3; color: #ec4899;">
                    <i class="bi bi-send"></i>
                </div>
                <div>
                    <div class="stat-label">Surat Keluar</div>
                    <div class="stat-value">{{ $totalKeluar ?? 0 }}</div>
                </div>
            </div>
            <div class="mt-2" style="font-size: 0.8rem;">
                @php
                    $pkel = round($percentKeluar ?? 0, 1);
                    $pkelColor = $pkel > 0 ? 'color:#059669' : ($pkel < 0 ? 'color:#dc2626' : 'color:#94a3b8');
                    $pkelArrow = $pkel > 0 ? '↑' : ($pkel < 0 ? '↓' : '→');
                @endphp
                <span style="{{ $pkelColor }}; font-weight:600;">{{ $pkelArrow }}{{ abs($pkel) }}%</span>
                <span style="color:#94a3b8;"> dari bulan lalu</span>
            </div>
            <div class="stat-card-bar" style="background: #ec4899;"></div>
        </div>
    </div>
</div>

<!-- Charts -->
<div class="row g-3 mb-4">
    <div class="col-lg-8">
        <div class="chart-card">
            <div class="chart-title">Nomor Surat Dibuat per Bulan</div>
            <canvas id="suratChart" height="85"></canvas>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="chart-card">
            <div class="chart-title">Sifat Naskah Dibuat</div>
            <canvas id="revenueChart" height="170"></canvas>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@php
$monthlySuratData = array_fill(0, 12, 0);
foreach ($requests as $req) {
    $month = \Carbon\Carbon::parse($req->created_at)->month;
    $monthlySuratData[$month-1]++;
}
$sifatNaskahCounts = [];
foreach ($requests as $req) {
    $sifat = $req->sifat_naskah ?? 'Lainnya';
    if (!isset($sifatNaskahCounts[$sifat])) $sifatNaskahCounts[$sifat] = 0;
    $sifatNaskahCounts[$sifat]++;
}
@endphp
<script>
// Line Chart
const ctxSurat = document.getElementById('suratChart').getContext('2d');
new Chart(ctxSurat, {
    type: 'line',
    data: {
        labels: ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'],
        datasets: [{
            label: 'Nomor Surat',
            data: @json($monthlySuratData),
            borderColor: '#3b82f6',
            backgroundColor: 'rgba(59,130,246,0.08)',
            fill: true,
            tension: 0.4,
            pointRadius: 3,
            pointBackgroundColor: '#3b82f6',
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
const donutColors = ['#3b82f6','#10b981','#f59e0b','#ec4899','#8b5cf6','#ef4444','#06b6d4'];
const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
new Chart(ctxRevenue, {
    type: 'doughnut',
    data: {
        labels: @json(array_keys($sifatNaskahCounts)),
        datasets: [{
            data: @json(array_values($sifatNaskahCounts)),
            backgroundColor: donutColors.slice(0, {{ count($sifatNaskahCounts) }}),
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
