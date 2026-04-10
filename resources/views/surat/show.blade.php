@extends('user.app-user')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="card shadow-sm rounded-4 border-0 w-100">
                <div class="card-body p-4">
                    <h4 class="fw-semibold mb-4">Detail Surat Keluar</h4>
                    @include('surat._cetak_pdf_button', ['surat' => $surat])
                    <table class="table table-bordered mt-3">
                        <tr><th>Nomor Surat</th><td>{{ $surat->nomor_surat }}</td></tr>
                        <tr><th>Kode Kearsipan</th><td>{{ $surat->kodeKearsipan->kode ?? '-' }}</td></tr>
                        <tr><th>Nama Kearsipan</th><td>{{ $surat->kodeKearsipan->nama ?? '-' }}</td></tr>
                        <tr><th>Pengolah</th><td>{{ $surat->pengolah }}</td></tr>
                        <tr><th>Jenis Naskah</th><td>{{ $surat->jenis_naskah }}</td></tr>
                        <tr><th>Sifat Naskah</th><td>{{ $surat->sifat_naskah }}</td></tr>
                        <tr><th>Hal</th><td>{{ $surat->hal }}</td></tr>
                        <tr><th>Tanggal Ditetapkan</th><td>{{ $surat->tanggal_ditetapkan }}</td></tr>
                        <tr><th>Tanggal Berlaku</th><td>{{ $surat->tanggal_berlaku }}</td></tr>
                        <tr><th>Status</th><td>{{ $surat->status }}</td></tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
