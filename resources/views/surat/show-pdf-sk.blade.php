<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Keputusan - {{ $surat->nomor_surat }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 13px;
            margin: 0;
            padding: 0;
        }
        .page {
            width: 794px; 
            min-height: 100vh;  /* biar fleksibel */
            margin: 0 auto;
            display: table;
            text-align: center;
        }
        .page-content {
            display: table-cell;
            vertical-align: middle;
            padding: 32px 48px;
            transform: translate(-40px, 20px); /* geser kiri -20px, geser bawah 20px */
        }
        .kop-logo-center img { width: 90px; margin-top: -30px; margin-bottom: 10px; }
        .title { font-size: 18px; font-weight: bold; letter-spacing: 1px; }
        .subtitle { font-size: 16px; font-weight: bold; letter-spacing: 1px; }
        .desc { font-size: 11px; margin-top: 4px; }
        .sk-title-main { font-size: 15px; font-weight: bold; margin-top: 24px; }
        .sk-title-main span { text-decoration: underline; }
        .sk-nomor { font-size: 14px; margin-top: 8px; }
        .sk-tentang { font-size: 14px; margin-top: 18px; font-weight: bold; }
        .sk-tentang-content { font-size: 13px; margin-top: 4px; }
        .sk-content { margin-top: 18px; text-align: justify; }
        .footer-pdf { margin-top: 40px; font-size: 11px; color: #555; text-align: right; }

        .sk-title-main {
            font-size: 15px; 
            margin-top: 24px;
            font-weight: normal;   /* tidak bold */
        }
        .sk-title-main span {
            text-decoration: none; /* hilangkan garis bawah */
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="page-content">
            <!-- KOP SURAT -->
            <div class="kop-logo-center">
                <img src="{{ public_path('foto/logo-jateng.png') }}" alt="Logo">
            </div>
            <div class="title">PEMERINTAH PROVINSI JAWA TENGAH</div>
            <div class="subtitle">DINAS PERTANIAN DAN PERKEBUNAN</div>

            <!-- JUDUL SK -->
            <div class="sk-title-main">
                <span>KEPUTUSAN KEPALA DINAS PERTANIAN DAN PERKEBUNAN<br>PROVINSI JAWA TENGAH</span>
            </div>
            <div class="sk-nomor">NOMOR : {{ $surat->nomor_surat }}</div>
            <div class="sk-tentang">TENTANG</div>
            <div class="sk-tentang-content">{!! nl2br(e($surat->hal ?? '-')) !!}</div>

            <!-- ISI SURAT -->
            <div class="sk-content">
                {!! nl2br(e($surat->isi ?? '')) !!}
            </div>
        </div>
    </div>
</body>
</html>
