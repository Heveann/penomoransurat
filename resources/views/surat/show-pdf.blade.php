<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Nota Dinas - {{ $surat->nomor_surat }}</title>
    <style>
        @page {
            margin: 30px 40px;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 13px;
            margin: 0;
            padding: 0;
        }
        .container-pdf {
            width: 100%;
            margin: 0 auto;
            padding: 0;
        }
        /* KOP SURAT - pakai table agar kompatibel DomPDF */
        .kop-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 3px solid #000;
            padding-bottom: 8px;
            margin-bottom: 18px;
        }
        .kop-table td {
            vertical-align: middle;
        }
        .kop-logo {
            width: 80px;
            text-align: center;
        }
        .kop-logo img {
            width: 65px;
        }
        .kop-text {
            text-align: center;
            line-height: 1.3;
        }
        .kop-text .title {
            font-size: 16px;
            font-weight: bold;
        }
        .kop-text .subtitle {
            font-size: 14px;
            font-weight: bold;
        }
        .kop-text .desc {
            font-size: 10px;
            margin-top: 2px;
        }
        .kop-line {
            border-bottom: 3px solid #000;
            margin-bottom: 18px;
        }
        /* TABEL NOTA DINAS */
        .nota-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }
        .nota-table td {
            padding: 4px 6px;
            font-size: 13px;
            vertical-align: top;
        }
        .nota-title {
            text-align: center;
            font-weight: bold;
            font-size: 16px;
            text-decoration: underline;
            letter-spacing: 2px;
            margin: 10px 0;
        }
        .label-col {
            width: 120px;
            font-weight: bold;
        }
        .colon-col {
            width: 10px;
            text-align: center;
        }
        .footer-pdf {
            margin-top: 60px;
            font-size: 11px;
            text-align: right;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="container-pdf">
        <!-- KOP SURAT -->
        <table class="kop-table">
            <tr>
                <td class="kop-logo">
                    <img src="{{ public_path('foto/logo-jateng.png') }}" alt="Logo">
                </td>
                <td class="kop-text">
                    <div class="title">PEMERINTAH PROVINSI JAWA TENGAH</div>
                    <div class="subtitle">DINAS PERTANIAN DAN PERKEBUNAN</div>
                    <div class="desc">
                        Jalan Gatot Subroto Komplek Tarubudaya, Ungaran<br>
                        Telepon (024) 6924155-6921348, Faksimile 024-6921060<br>
                        Email: dishanbun@jatengprov.go.id
                    </div>
                </td>
            </tr>
        </table>

        <!-- JUDUL NOTA DINAS -->
        <div class="nota-title">NOTA DINAS</div>

        <!-- TABEL NOTA DINAS -->
        <table class="nota-table">
            <tr>
                <td class="label-col">Kepada Yth.</td>
                <td class="colon-col">:</td>
                <td>Kepala Dinas Pertanian dan Perkebunan Provinsi Jawa Tengah</td>
            </tr>
            <tr>
                <td class="label-col">Dari</td>
                <td class="colon-col">:</td>
                <td>Sekretaris</td>
            </tr>
            <tr>
                <td class="label-col">Tembusan Yth.</td>
                <td class="colon-col">:</td>
                <td>-</td>
            </tr>
            <tr>
                <td class="label-col">Tanggal</td>
                <td class="colon-col">:</td>
                <td>{{ \Carbon\Carbon::parse($surat->tanggal_ditetapkan)->translatedFormat('F Y') }}</td>
            </tr>
            <tr>
                <td class="label-col">Nomor</td>
                <td class="colon-col">:</td>
                <td>{{ $surat->nomor_surat }}</td>
            </tr>
            <tr>
                <td class="label-col">Sifat</td>
                <td class="colon-col">:</td>
                <td>{{ $surat->sifat_naskah }}</td>
            </tr>
            <tr>
                <td class="label-col">Lampiran</td>
                <td class="colon-col">:</td>
                <td>1 (Satu) Exemplar</td>
            </tr>
            <tr>
                <td class="label-col">Perihal</td>
                <td class="colon-col">:</td>
                <td>{{ $surat->hal }}</td>
            </tr>
        </table>

        <!-- FOOTER -->
        <div class="footer-pdf">
            Dicetak oleh: <b>{{ auth()->user()->name ?? auth()->user()->nama }}</b><br>
            pada {{ now()->format('d-m-Y H:i') }}
        </div>
    </div>
</body>
</html>
