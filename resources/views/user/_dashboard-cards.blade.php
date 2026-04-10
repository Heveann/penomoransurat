<!-- Bootstrap Icons CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<style>
.stat-card {
  min-height: 120px;
  overflow: hidden;
  position: relative;
  background: #fff;
  border-radius: 0.5rem;
  box-shadow: 0 2px 8px rgba(44,62,80,0.04);
  display: flex;
  align-items: center;
  padding: 1.5rem 1.25rem;
}
/* Specific card height for Total Klasifikasi */
.stat-card--klasifikasi {
  min-height: 150px; /* adjust this value as needed */
  align-items: flex-start; /* align content to top */
  padding-top: 1.5rem; /* match default padding */
}

.stat-card--klasifikasi .ms-auto {
  position: absolute;
  top: 3rem;    /* atur jarak dari atas */
  right: 1.5rem;  /* atur jarak dari kanan */
}
.stat-card-bar {
  position: absolute;
  left: 0;
  top: 16px;
  bottom: 16px;
  width: 4px;
  border-radius: 6px;
}
.icon-circle {
  width: 48px;
  height: 48px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.7rem;
}
.bg-purple { background: #7c3aed !important; }
.bg-blue { background: #03a9f4 !important; }
.bg-green { background: #4caf50 !important; }
.bg-orange { background: #ff9800 !important; }
.bg-pink { background: #e91e63 !important; }
</style>
<div class="row g-3 mb-4">
  <div class="col-md-3">
    <div class="stat-card position-relative">
      <div>
        <div class="fs-2 fw-bold">{{ $totalRequest ?? 0 }}</div>
        <div class="text-muted mb-2">Total Request Nomor</div>
        <div class="d-flex align-items-center" style="font-size:0.95rem;">
          @php
            $pr = round($percentRequest, 1);
            $prColor = $pr > 0 ? 'text-success' : ($pr < 0 ? 'text-danger' : 'text-secondary');
            $prArrow = $pr > 0 ? '↑' : ($pr < 0 ? '↓' : '→');
          @endphp
          <span class="fw-semibold me-1 {{ $prColor }}">{{ $prArrow }}{{ abs($pr) }}%</span>
          <span class="text-muted">dari bulan lalu</span>
        </div>
      </div>
      <div class="ms-auto">
        <div class="icon-circle bg-blue">
          <i class="bi bi-collection text-white"></i>
        </div>
      </div>
      <div class="stat-card-bar bg-blue"></div>
    </div>
  </div>
<div class="col-md-3">
  <div class="stat-card stat-card--klasifikasi position-relative">
    <div>
      <div class="fs-2 fw-bold">{{ \App\Models\KodeKearsipan::count() }}</div>
      <div class="text-muted mb-2">Total Klasifikasi</div>
      <!-- Tidak ada persen atau keterangan -->
    </div>
    <div class="ms-auto">
      <div class="icon-circle bg-green">
        <i class="bi bi-tags text-white"></i>
      </div>
    </div>
    <div class="stat-card-bar bg-green"></div>
  </div>
</div>

  <div class="col-md-3">
    <div class="stat-card position-relative">
      <div>
        <div class="fs-2 fw-bold">{{ $totalKeputusan ?? 0 }}</div>
        <div class="text-muted mb-2">Surat Keputusan</div>
        <div class="d-flex align-items-center" style="font-size:0.95rem;">
          @php
            $pkp = round($percentKeputusan, 1);
            $pkpColor = $pkp > 0 ? 'text-success' : ($pkp < 0 ? 'text-danger' : 'text-secondary');
            $pkpArrow = $pkp > 0 ? '↑' : ($pkp < 0 ? '↓' : '→');
          @endphp
          <span class="fw-semibold me-1 {{ $pkpColor }}">{{ $pkpArrow }}{{ abs($pkp) }}%</span>
          <span class="text-muted">dari bulan lalu</span>
        </div>
      </div>
      <div class="ms-auto">
        <div class="icon-circle bg-orange">
          <i class="bi bi-file-earmark-text text-white"></i>
        </div>
      </div>
      <div class="stat-card-bar bg-orange"></div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="stat-card position-relative">
      <div>
        <div class="fs-2 fw-bold">{{ $totalKeluar ?? 0 }}</div>
        <div class="text-muted mb-2">Surat Keluar</div>
        <div class="d-flex align-items-center" style="font-size:0.95rem;">
          @php
            $pkel = round($percentKeluar, 1);
            $pkelColor = $pkel > 0 ? 'text-success' : ($pkel < 0 ? 'text-danger' : 'text-secondary');
            $pkelArrow = $pkel > 0 ? '↑' : ($pkel < 0 ? '↓' : '→');
          @endphp
          <span class="fw-semibold me-1 {{ $pkelColor }}">{{ $pkelArrow }}{{ abs($pkel) }}%</span>
          <span class="text-muted">dari bulan lalu</span>
        </div>
      </div>
      <div class="ms-auto">
        <div class="icon-circle bg-pink">
          <i class="bi bi-send text-white"></i>
        </div>
      </div>
      <div class="stat-card-bar bg-pink"></div>
    </div>
  </div>
</div>
