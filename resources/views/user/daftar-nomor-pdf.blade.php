
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Daftar Nomor Surat</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 13px; }
        h4 { margin-bottom: 16px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        th, td { border: 1px solid #333; padding: 6px 8px; font-size: 13px; }
        th { background: #eee; }
        .footer { margin-top: 20px; font-size: 12px; }
    </style>
</head>
<body>
    <h4>Daftar Nomor Surat Direquest</h4>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode & Nama</th>
                <th>Nomor Surat</th>
                <th>Tanggal Ditetapkan</th>
                <th>Jam</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($requests as $i => $request)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $request->kodeKearsipan->kode ?? '-' }} - {{ $request->kodeKearsipan->nama ?? '-' }}</td>
                <td>{{ $request->nomor_surat }}</td>
                <td>{{ $request->tanggal_ditetapkan }}</td>
                <td>{{ $request->created_at ? \Carbon\Carbon::parse($request->created_at)->format('H:i') : '-' }}</td>
                <td>{{ $request->nomor_surat ? 'Disetujui' : 'Ditolak' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="footer">
        Dicetak oleh: <b>{{ $user->name ?? $user->nama }}</b> pada {{ now()->format('d-m-Y H:i') }}
    </div>
</body>
</html>
