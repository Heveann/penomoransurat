<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KodeKearsipan;

class KlasifikasiController extends Controller
{
    public function search(Request $request)
    {
        $search = $request->get('q', '');
        $klasifikasis = KodeKearsipan::where('kode', 'like', "%{$search}%")
            ->orWhere('nama', 'like', "%{$search}%")
            ->orderBy('kode', 'asc')
            ->limit(20)
            ->get();
        $results = [];
        foreach ($klasifikasis as $klasifikasi) {
            $results[] = [
                'id' => $klasifikasi->id,
                'text' => "{$klasifikasi->kode} - {$klasifikasi->nama}"
            ];
        }
        return response()->json($results);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $type = $request->query('type');
            
            if ($type === 'kode') {
                $query = KodeKearsipan::query();
                return \Yajra\DataTables\Facades\DataTables::of($query)
                    ->addIndexColumn()
                    ->addColumn('aksi', function($row) {
                        return '<div class="d-flex justify-content-center gap-2">
                                    <button class="btn-action btn-print btn-edit-kode" data-id="'.$row->id.'" data-kode="'.htmlspecialchars($row->kode).'" data-nama="'.htmlspecialchars($row->nama).'" data-bs-toggle="modal" data-bs-target="#modalEditKode" title="Edit"><i class="bi bi-pencil"></i></button>
                                    <button class="btn-action btn-reject btn-hapus" data-id="'.$row->id.'" data-nama="'.htmlspecialchars($row->kode.' - '.$row->nama).'" data-type="kode" title="Hapus"><i class="bi bi-trash"></i></button>
                                    <form action="'.route('klasifikasi.destroy', $row->id).'" method="POST" class="d-none form-delete-kode-'.$row->id.'">'.csrf_field().method_field('DELETE').'</form>
                                </div>';
                    })
                    ->rawColumns(['aksi'])
                    ->make(true);
            } elseif ($type === 'jenis') {
                $query = \App\Models\JenisNaskah::query();
                return \Yajra\DataTables\Facades\DataTables::of($query)
                    ->addIndexColumn()
                    ->addColumn('aksi', function($row) {
                        return '<div class="d-flex justify-content-center gap-2">
                                    <button class="btn-action btn-print btn-edit-jenis" data-id="'.$row->id.'" data-nama="'.htmlspecialchars($row->nama).'" data-bs-toggle="modal" data-bs-target="#modalEditJenis" title="Edit"><i class="bi bi-pencil"></i></button>
                                    <button class="btn-action btn-reject btn-hapus" data-id="'.$row->id.'" data-nama="'.htmlspecialchars($row->nama).'" data-type="jenis" title="Hapus"><i class="bi bi-trash"></i></button>
                                    <form action="'.route('jenis-naskah.destroy', $row->id).'" method="POST" class="d-none form-delete-jenis-'.$row->id.'">'.csrf_field().method_field('DELETE').'</form>
                                </div>';
                    })
                    ->rawColumns(['aksi'])
                    ->make(true);
            } elseif ($type === 'sifat') {
                $query = \App\Models\SifatNaskah::query();
                return \Yajra\DataTables\Facades\DataTables::of($query)
                    ->addIndexColumn()
                    ->addColumn('aksi', function($row) {
                        return '<div class="d-flex justify-content-center gap-2">
                                    <button class="btn-action btn-print btn-edit-sifat" data-id="'.$row->id.'" data-nama="'.htmlspecialchars($row->nama).'" data-bs-toggle="modal" data-bs-target="#modalEditSifat" title="Edit"><i class="bi bi-pencil"></i></button>
                                    <button class="btn-action btn-reject btn-hapus" data-id="'.$row->id.'" data-nama="'.htmlspecialchars($row->nama).'" data-type="sifat" title="Hapus"><i class="bi bi-trash"></i></button>
                                    <form action="'.route('sifat-naskah.destroy', $row->id).'" method="POST" class="d-none form-delete-sifat-'.$row->id.'">'.csrf_field().method_field('DELETE').'</form>
                                </div>';
                    })
                    ->rawColumns(['aksi'])
                    ->make(true);
            }
        }

        $klasifikasis = KodeKearsipan::orderBy('kode', 'asc')->get();
        $jenisNaskahs = \App\Models\JenisNaskah::orderBy('nama', 'asc')->get();
        $sifatNaskahs = \App\Models\SifatNaskah::orderBy('nama', 'asc')->get();
        return view('klasifikasi.index', compact('klasifikasis', 'jenisNaskahs', 'sifatNaskahs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|unique:kode_kearsipan,kode|max:255',
            'nama' => 'required|max:255',
        ]);
        KodeKearsipan::create($request->all());
        return redirect()->route('klasifikasi.index')->with('success', 'Klasifikasi berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $klasifikasi = KodeKearsipan::findOrFail($id);
        $request->validate([
            'kode' => 'required|max:255|unique:kode_kearsipan,kode,'.$id,
            'nama' => 'required|max:255',
        ]);
        $klasifikasi->update($request->all());
        return redirect()->route('klasifikasi.index')->with('success', 'Klasifikasi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $klasifikasi = KodeKearsipan::findOrFail($id);
        $klasifikasi->delete();
        return redirect()->route('klasifikasi.index')->with('success', 'Klasifikasi berhasil dihapus.');
    }

    // JENIS NASKAH
    public function storeJenis(Request $request)
    {
        $request->validate(['nama' => 'required|unique:jenis_naskahs,nama|max:255']);
        \App\Models\JenisNaskah::create($request->all());
        return redirect()->route('klasifikasi.index')->with('success', 'Jenis Naskah berhasil ditambahkan.');
    }
    public function updateJenis(Request $request, $id)
    {
        $jenis = \App\Models\JenisNaskah::findOrFail($id);
        $request->validate(['nama' => 'required|max:255|unique:jenis_naskahs,nama,'.$id]);
        $jenis->update($request->all());
        return redirect()->route('klasifikasi.index')->with('success', 'Jenis Naskah berhasil diperbarui.');
    }
    public function destroyJenis($id)
    {
        \App\Models\JenisNaskah::findOrFail($id)->delete();
        return redirect()->route('klasifikasi.index')->with('success', 'Jenis Naskah berhasil dihapus.');
    }

    // SIFAT NASKAH
    public function storeSifat(Request $request)
    {
        $request->validate(['nama' => 'required|unique:sifat_naskahs,nama|max:255']);
        \App\Models\SifatNaskah::create($request->all());
        return redirect()->route('klasifikasi.index')->with('success', 'Sifat Naskah berhasil ditambahkan.');
    }
    public function updateSifat(Request $request, $id)
    {
        $sifat = \App\Models\SifatNaskah::findOrFail($id);
        $request->validate(['nama' => 'required|max:255|unique:sifat_naskahs,nama,'.$id]);
        $sifat->update($request->all());
        return redirect()->route('klasifikasi.index')->with('success', 'Sifat Naskah berhasil diperbarui.');
    }
    public function destroySifat($id)
    {
        \App\Models\SifatNaskah::findOrFail($id)->delete();
        return redirect()->route('klasifikasi.index')->with('success', 'Sifat Naskah berhasil dihapus.');
    }
}
