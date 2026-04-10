<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DaftarNomorExport;
use App\Models\SuratKeluar;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        
        if ($request->ajax()) {
            $query = User::query();
            
            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('name', function($row) {
                    $encodedName = urlencode($row->name);
                    return '<div class="d-flex align-items-center gap-3">
                                <img src="https://ui-avatars.com/api/?name='.$encodedName.'&size=36&background=e2e8f0&color=475569&font-size=0.4"
                                    alt="" style="width: 36px; height: 36px; border-radius: 50%; object-fit: cover;">
                                <div>
                                    <div style="font-weight: 600; color: #1e293b; line-height: 1.2;">'.($row->name).'</div>
                                    <div style="font-size: 0.75rem; color: #64748b; margin-top: 0.2rem;">ID: #'.($row->id).'</div>
                                </div>
                            </div>';
                })
                ->addColumn('email_telepon', function($row) {
                    $html = '<div style="color: #334155; font-size: 0.85rem;"><i class="bi bi-envelope me-1" style="color: #94a3b8;"></i> '.($row->email).'</div>';
                    if($row->telepon) {
                        $html .= '<div style="color: #64748b; font-size: 0.8rem; margin-top: 0.2rem;"><i class="bi bi-telephone me-1" style="color: #94a3b8;"></i> '.($row->telepon).'</div>';
                    }
                    return $html;
                })
                ->editColumn('role', function($row) {
                    if($row->role === 'admin') {
                        return '<span class="badge" style="background-color: #fef08a; color: #854d0e; padding: 0.4rem 0.6rem; border-radius: 6px; font-weight: 600;"><i class="bi bi-shield-lock me-1"></i> Admin</span>';
                    } elseif($row->role === 'pimpinan') {
                        return '<span class="badge" style="background-color: #e0e7ff; color: #4f46e5; padding: 0.4rem 0.6rem; border-radius: 6px; font-weight: 600;"><i class="bi bi-person-badge me-1"></i> Pimpinan</span>';
                    } else {
                        return '<span class="badge" style="background-color: #f1f5f9; color: #475569; padding: 0.4rem 0.6rem; border-radius: 6px; font-weight: 600;"><i class="bi bi-person me-1"></i> User</span>';
                    }
                })
                ->addColumn('aksi', function($row) {
                    $html = '<div class="d-flex gap-1 flex-nowrap">';
                    $html .= '<a href="'.route('users.edit', $row->id).'" class="btn-action" style="background: #e0e7ff; color: #4f46e5;" title="Edit">
                                <i class="bi bi-pencil"></i>
                              </a>';
                    if($row->id !== auth()->id()) {
                        $html .= '<form action="'.route('users.destroy', $row->id).'" method="POST" class="d-inline form-delete-'.$row->id.'">
                                    '.csrf_field().method_field('DELETE').'
                                    <button type="button" class="btn-action btn-hapus" style="background: #fef2f2; color: #dc2626;" data-id="'.$row->id.'" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                  </form>';
                    }
                    $html .= '</div>';
                    return $html;
                })
                ->rawColumns(['name', 'email_telepon', 'role', 'aksi'])
                ->make(true);
        }

        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        if (auth()->user()->role !== 'admin') abort(403);
        return view('users.create');
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'admin') abort(403);

        $request->validate([
            'name' => 'required|string|max:255',
            'nama_lengkap' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,pimpinan,user',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:500',
        ]);

        User::create([
            'name' => $request->name,
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'telepon' => $request->telepon,
            'alamat' => $request->alamat,
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function show(User $user)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        if (auth()->user()->role !== 'admin') abort(403);

        $request->validate([
            'name' => 'required|string|max:255',
            'nama_lengkap' => 'nullable|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,pimpinan,user',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:500',
        ]);

        $data = [
            'name' => $request->name,
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'role' => $request->role,
            'telepon' => $request->telepon,
            'alamat' => $request->alamat,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if (auth()->user()->role !== 'admin') abort(403);

        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }

    public function daftarNomor(Request $request)
    {
        $user = auth()->user();

        if ($request->ajax()) {
            $query = SuratKeluar::with('kodeKearsipan')
                ->where('user_id', $user->id)
                ->whereYear('created_at', now()->year)
                ->latest();

            if ($request->has('status') && $request->status !== 'all') {
                $query->where('status', $request->status);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('kode_kearsipan_id', function($row) {
                    $kode = $row->kodeKearsipan->kode ?? '-';
                    $nama = $row->kodeKearsipan->nama ?? '-';
                    return '<span style="font-weight: 500; color: #334155;">'.$kode.'</span> <span style="color: #94a3b8;"> — </span> <span style="color: #64748b;">'.$nama.'</span>';
                })
                ->addColumn('nomor_surat_format', function($row) {
                    if($row->status === 'disetujui') {
                        return '<span style="font-weight: 500; color: #111827;">' . ($row->nomor_surat ?? '-') . '</span>';
                    } elseif($row->status === 'ditolak') {
                        return '<span class="fst-italic" style="color: #dc2626; font-size: 0.9em;">Ditolak</span>';
                    } elseif($row->status === 'revisi') {
                        return '<span class="fst-italic" style="color: #854d0e; font-size: 0.9em;">Perlu Revisi</span>';
                    } else {
                        return '<span class="fst-italic text-muted" style="font-size: 0.9em;">Menunggu Disetujui</span>';
                    }
                })
                ->addColumn('file', function($row) {
                    if($row->status === 'disetujui') {
                        return '<a href="'.route('surat.cetak', $row->id).'" class="btn-action btn-print" target="_blank" title="Cetak Surat">
                                    <i class="bi bi-printer"></i>
                                </a>';
                    }
                    return '<span style="color: #cbd5e1;">—</span>';
                })
                ->editColumn('status', function($row) {
                    if($row->status === 'disetujui') {
                        return '<span class="badge-status badge-approved"><i class="bi bi-check-circle-fill" style="font-size: 0.65rem;"></i> Disetujui</span>';
                    } elseif($row->status === 'ditolak') {
                        return '<span class="badge-status badge-rejected"><i class="bi bi-x-circle-fill" style="font-size: 0.65rem;"></i> Ditolak</span>';
                    } elseif($row->status === 'revisi') {
                        return '<span class="badge-status badge-revisi"><i class="bi bi-pencil-fill" style="font-size: 0.65rem;"></i> Revisi</span>';
                    } else {
                        return '<span class="badge-status badge-pending"><i class="bi bi-clock-fill" style="font-size: 0.65rem;"></i> Pending</span>';
                    }
                })
                ->addColumn('aksi', function($row) {
                    $html = '<div class="d-flex gap-1 flex-nowrap">';
                    if(in_array($row->status, ['pending', 'revisi'])) {
                        $html .= '<a href="'.route('user.daftar-nomor.edit', $row->id).'" class="btn-action btn-edit" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                  </a>';
                        $html .= '<form action="'.route('user.daftar-nomor.destroy', $row->id).'" method="POST" class="d-inline form-delete-'.$row->id.'">
                                    '.csrf_field().method_field('DELETE').'
                                    <button type="button" class="btn-action btn-delete btn-hapus" data-id="'.$row->id.'" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                  </form>';
                    } else {
                        $html .= '<span style="color: #cbd5e1;">—</span>';
                    }
                    
                    if($row->status === 'ditolak' || $row->status === 'revisi') {
                        $type = $row->status === 'ditolak' ? 'Penolakan' : 'Revisi';
                        $catatan = htmlspecialchars($row->catatan ?? 'Tidak ada catatan.');
                        $html .= '<button type="button" class="btn-action btn-print btn-catatan" data-catatan="'.$catatan.'" data-type="'.$type.'" title="Lihat Catatan" style="background: #f1f5f9; color: #475569; border-color: #cbd5e1;">
                                    <i class="bi bi-info-circle"></i>
                                  </button>';
                    }
                    
                    $html .= '</div>';
                    return $html;
                })
                ->rawColumns(['kode_kearsipan_id', 'nomor_surat_format', 'file', 'status', 'aksi'])
                ->make(true);
        }

        $surat = SuratKeluar::with('kodeKearsipan')
            ->where('user_id', $user->id)
            ->whereYear('created_at', now()->year)
            ->latest()
            ->get();
        
        $countAll = $surat->count();
        $countApproved = $surat->where('status', 'disetujui')->count();
        $countRejected = $surat->where('status', 'ditolak')->count();
        $countRevisi = $surat->where('status', 'revisi')->count();
        $countPending = $surat->filter(fn($s) => $s->status === 'pending')->count();
        
        return view('user.daftar-nomor', compact('surat', 'countAll', 'countApproved', 'countRejected', 'countPending', 'countRevisi'));
    }

    // Ekspor Excel daftar nomor surat user
    public function exportExcelDaftarNomor()
    {
        $user = auth()->user();
        return Excel::download(new DaftarNomorExport($user->id), 'daftar-nomor-surat.xlsx');
    }

    // Cetak PDF daftar nomor surat user
    public function cetakPdfDaftarNomor()
    {
        $user = auth()->user();
        $requests = \App\Models\SuratKeluar::with('kodeKearsipan')->where('user_id', $user->id)->whereIn('status', ['disetujui', 'ditolak'])->latest()->get();
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('user.daftar-nomor-pdf', compact('requests', 'user'));
        return $pdf->stream('daftar-nomor-surat.pdf');
    }

    public function editDaftarNomor($id)
    {
        $request = SuratKeluar::findOrFail($id);
        $kodeKearsipan = \App\Models\KodeKearsipan::all();
        $jenisNaskah = \App\Models\JenisNaskah::orderBy('nama')->get();
        $sifatNaskah = \App\Models\SifatNaskah::orderBy('nama')->get();
        $unitKerjas = \App\Models\UnitKerja::orderBy('nama')->get();
        return view('user.edit-daftar-nomor', compact('request', 'kodeKearsipan', 'jenisNaskah', 'sifatNaskah', 'unitKerjas'));
    }

    public function updateDaftarNomor(Request $req, $id)
    {
        $surat = SuratKeluar::findOrFail($id);

        if ($surat->status !== 'revisi' && $surat->status !== 'pending') {
            return redirect()->back()->with('error', 'Hanya surat pending atau revisi yang bisa diubah.');
        }

        $rules = [
            'kode_kearsipan_id' => 'required|exists:kode_kearsipan,id',
            'pengolah' => 'required|string',
            'jenis_naskah' => 'required|string',
            'sifat_naskah' => 'required|string',
            'hal' => 'nullable|string',
            'tanggal_berlaku' => 'nullable|date',
        ];

        if ($req->jenis_naskah === 'Surat Keputusan') {
            $rules['tanggal_ditetapkan'] = 'required|date';
        } else {
            $rules['tanggal_ditetapkan'] = 'nullable|date';
        }

        $data = $req->validate($rules);
        $data['status'] = 'pending'; // Reset status to pending after revision

        $surat->update($data);
        return redirect()->route('user.daftar-nomor')->with('success', 'Data revisi berhasil disimpan, menunggu persetujuan.');
    }

    public function destroyDaftarNomor($id)
    {
        $request = SuratKeluar::findOrFail($id);
        \App\Models\Nomor::where('id', $request->nomor_urut)->where('tahun', date('Y'))->delete();
        $request->delete();
        return redirect()->route('user.daftar-nomor')->with('success', 'Data berhasil dihapus.');
    }
}
