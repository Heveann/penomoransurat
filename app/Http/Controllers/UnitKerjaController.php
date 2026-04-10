<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UnitKerja;

class UnitKerjaController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        
        if ($request->ajax()) {
            $query = UnitKerja::query();
            return \Yajra\DataTables\Facades\DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('aksi', function($row) {
                    return '<div class="d-flex justify-content-center gap-2">
                                <button class="btn-action btn-print btn-edit-unit" data-id="'.$row->id.'" data-nama="'.htmlspecialchars($row->nama).'" data-bs-toggle="modal" data-bs-target="#modalEditUnit" title="Edit"><i class="bi bi-pencil"></i></button>
                                <button class="btn-action btn-reject btn-hapus" data-id="'.$row->id.'" data-nama="'.htmlspecialchars($row->nama).'" title="Hapus"><i class="bi bi-trash"></i></button>
                                <form action="'.route('unit-kerja.destroy', $row->id).'" method="POST" class="d-none form-delete-'.$row->id.'">'.csrf_field().method_field('DELETE').'</form>
                            </div>';
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }

        $unitKerjas = UnitKerja::orderBy('nama', 'asc')->get();
        return view('unit_kerja.index', compact('unitKerjas'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        $request->validate(['nama' => 'required|string|max:255']);
        UnitKerja::create(['nama' => $request->nama]);
        return redirect()->back()->with('success', 'Unit Kerja berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        $request->validate(['nama' => 'required|string|max:255']);
        $unitKerja = UnitKerja::findOrFail($id);
        $unitKerja->update(['nama' => $request->nama]);
        return redirect()->back()->with('success', 'Unit Kerja berhasil diperbarui');
    }

    public function destroy($id)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        $unitKerja = UnitKerja::findOrFail($id);
        $unitKerja->delete();
        return redirect()->back()->with('success', 'Unit Kerja berhasil dihapus');
    }
}
