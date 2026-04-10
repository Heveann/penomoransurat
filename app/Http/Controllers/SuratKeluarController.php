<?php

namespace App\Http\Controllers;

use App\Models\SuratKeluar;
use Illuminate\Support\Facades\Auth;
use App\Models\Klasifikasi;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\KodeKearsipan;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class SuratKeluarController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        if ($request->ajax()) {
            $query = SuratKeluar::with(['user', 'kodeKearsipan']);
            
            if ($user->role === 'pimpinan') {
                $query->whereIn('status', ['disetujui', 'ditolak']);
            } elseif ($user->role === 'user') {
                $query->where('user_id', $user->id);
            }
            
            if ($request->has('status') && $request->status !== 'all') {
                $query->where('status', $request->status);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('pengaju', function($row) {
                    $name = htmlspecialchars($row->user->name ?? '-');
                    $encodedName = urlencode($name);
                    return '<div class="d-flex align-items-center gap-2">
                                <img src="https://ui-avatars.com/api/?name='.$encodedName.'&size=32&background=e2e8f0&color=475569&font-size=0.4" alt="" style="width: 28px; height: 28px; border-radius: 50%;">
                                <span style="font-weight: 500;">'.$name.'</span>
                            </div>';
                })
                ->addColumn('kode_nama', function($row) {
                    $kode = $row->kodeKearsipan->kode ?? '-';
                    $nama = $row->kodeKearsipan->nama ?? '-';
                    return '<span style="font-weight: 500; color: #334155;">'.$kode.'</span> <span style="color: #94a3b8;"> — </span> <span style="color: #64748b;">'.$nama.'</span>';
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
                ->addColumn('aksi', function($row) use ($user) {
                    $html = '<div class="d-flex gap-1 flex-nowrap">';
                    if($user->role === 'admin' && $row->status !== 'disetujui' && $row->status !== 'ditolak') {
                        $html .= '<button class="btn-action btn-print" type="button" data-bs-toggle="modal" data-bs-target="#modalDetail-'.$row->id.'" title="Detail Data" style="background: #f1f5f9; color: #475569; border-color: #cbd5e1;"><i class="bi bi-info-circle"></i> Detail</button>';
                    } elseif($row->status === 'disetujui') {
                        $html .= '<a href="'.route('surat.cetak', $row->id).'" class="btn-action btn-print" target="_blank"><i class="bi bi-printer"></i> Lihat</a>';
                    } else {
                        $html .= '<span style="color: #cbd5e1;">—</span>';
                    }
                    $html .= '</div>';
                    return $html;
                })
                ->rawColumns(['pengaju', 'kode_nama', 'status', 'aksi'])
                ->make(true);
        }

        // We still need surat data for generating the Modals in the view, 
        // since we haven't converted modal to dynamic ajax completely.
        $query = SuratKeluar::with(['user', 'kodeKearsipan'])->latest();
        if ($user->role === 'pimpinan') {
            $query->whereIn('status', ['disetujui', 'ditolak']);
        } elseif ($user->role === 'user') {
            $query->where('user_id', $user->id);
        }
        $surat = $query->get();

        return view('surat.index', compact('surat'));
    }

    public function monitoring(Request $request)
    {
        if (Auth::user()->role !== 'admin' && Auth::user()->role !== 'pimpinan') abort(403);
        
        $monitoringData = SuratKeluar::selectRaw('pengolah, 
            SUM(CASE WHEN jenis_naskah LIKE "%Keputusan%" THEN 1 ELSE 0 END) as surat_keputusan,
            SUM(CASE WHEN jenis_naskah LIKE "%Keluar%" THEN 1 ELSE 0 END) as surat_keluar,
            COUNT(*) as total')
            ->where('status', 'disetujui')
            ->where(function($q) {
                $q->where('jenis_naskah', 'LIKE', '%Keputusan%')
                  ->orWhere('jenis_naskah', 'LIKE', '%Keluar%');
            })
            ->groupBy('pengolah')
            ->orderByDesc('total')
            ->get();
            
        if ($request->ajax()) {
            return DataTables::of($monitoringData)
                ->addIndexColumn()
                ->editColumn('pengolah', function($row) {
                    return '<span style="font-weight: 500; color: #475569;">'.($row->pengolah ?? 'Tidak Diketahui').'</span>';
                })
                ->addColumn('surat_keputusan', function($row) {
                    return '<span class="badge" style="background-color: #e0e7ff; color: #4f46e5; border-radius: 6px; padding: 0.4rem 0.6rem;">'.$row->surat_keputusan.'</span>';
                })
                ->addColumn('surat_keluar', function($row) {
                    return '<span class="badge" style="background-color: #ecfdf5; color: #10b981; border-radius: 6px; padding: 0.4rem 0.6rem;">'.$row->surat_keluar.'</span>';
                })
                ->addColumn('total', function($row) {
                    $total = $row->surat_keputusan + $row->surat_keluar;
                    return '<span style="font-weight: 700; color: #0f172a;">'.$total.'</span>';
                })
                ->rawColumns(['pengolah', 'surat_keputusan', 'surat_keluar', 'total'])
                ->make(true);
        }

        return view('admin.monitoring', compact('monitoringData'));
    }

    public function create()
    {
        $kodeKearsipan = KodeKearsipan::orderBy('kode')->get();

        // Kirim ke view 'surat.create'
        return view('surat.create', compact('kodeKearsipan'));
    }

    public function store(Request $request)
    {
        $rules = [
            'kode_kearsipan_id' => 'required|exists:kode_kearsipan,id',
            'pengolah' => 'required|string',
            'jenis_naskah' => 'required|string',
            'sifat_naskah' => 'required|string',
            'hal' => 'nullable|string',
            'tanggal_berlaku' => 'nullable|date',
        ];
        // Validasi tanggal_ditetapkan sesuai jenis_naskah
        if ($request->jenis_naskah === 'Surat Keputusan') {
            $rules['tanggal_ditetapkan'] = 'required|date';
        } else {
            $rules['tanggal_ditetapkan'] = 'nullable|date';
        }
        $data = $request->validate($rules);

        $year = now()->year;
        $user = Auth::user();
        $isAdmin = $user->role === 'admin';
        
        try {
            if ($isAdmin) {
                // Admin langsung mendapat nomor
                $surat = \Illuminate\Support\Facades\DB::transaction(function () use ($data, $year, $user) {
                    $maxNomorFromSurat = SuratKeluar::whereYear('created_at', $year)
                        ->lockForUpdate()
                        ->max('nomor_urut');
                    $nextUrut = $maxNomorFromSurat ? ($maxNomorFromSurat + 1) : 25000;

                    $kodeKearsipanId = $data['kode_kearsipan_id'];
                    $kode = \App\Models\KodeKearsipan::find($kodeKearsipanId)->kode;
                    $nomor_surat = sprintf('%s/%d/%d', $kode, $nextUrut, $year);

                    $data['nomor_urut'] = $nextUrut;
                    $data['nomor_surat'] = $nomor_surat;
                    $data['status'] = 'disetujui';
                    $data['user_id'] = $user->id;

                    $surat = SuratKeluar::create($data);

                    try {
                        \App\Models\Nomor::create([
                            'id' => $data['nomor_urut'],
                            'tahun' => $year,
                        ]);
                    } catch (\Exception $nomorsError) {
                        Log::warning('Nomor entry already exists in nomors table', [
                            'nomor_urut' => $data['nomor_urut'],
                            'tahun' => $year,
                        ]);
                    }

                    return $surat;
                });
            } else {
                // User biasa - status pending
                $data['status'] = 'pending';
                $data['user_id'] = $user->id;
                $surat = SuratKeluar::create($data);
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'nomor_surat' => $surat->nomor_surat ?? 'Menunggu Persetujuan',
                ]);
            } else {
                $msg = $isAdmin ? "Surat tersimpan: {$surat->nomor_surat}" : "Permintaan berhasil diajukan, menunggu persetujuan Admin.";
                return redirect()->route('dashboard')->with('success', $msg);
            }
        } catch (\Exception $e) {
            Log::error('Error saving surat permintaan nomor', [
                'exception' => $e->getMessage(),
                'user_id' => Auth::id(),
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menyimpan. ' . $e->getMessage()
                ], 500);
            } else {
                return redirect()->back()->with('error', 'Gagal menyimpan. Silakan hubungi admin.');
            }
        }
    }

    public function approve(SuratKeluar $surat)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        if ($surat->status === 'disetujui') {
            return back()->with('error', 'Surat ini sudah disetujui.');
        }

        $year = now()->year;

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($surat, $year) {
                $maxNomorFromSurat = SuratKeluar::whereYear('created_at', $year)
                    ->lockForUpdate()
                    ->max('nomor_urut');
                $nextUrut = $maxNomorFromSurat ? ($maxNomorFromSurat + 1) : 25000;

                $kode = $surat->kodeKearsipan->kode ?? 'X';
                $nomor_surat = sprintf('%s/%d/%d', $kode, $nextUrut, $year);

                $surat->update([
                    'nomor_urut' => $nextUrut,
                    'nomor_surat' => $nomor_surat,
                    'status' => 'disetujui'
                ]);

                try {
                    \App\Models\Nomor::create([
                        'id' => $nextUrut,
                        'tahun' => $year,
                    ]);
                } catch (\Exception $e) {}
            });

            return back()->with('success', 'Permintaan disetujui, nomor surat: ' . $surat->nomor_surat);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyetujui: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, SuratKeluar $surat)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        if ($surat->status === 'disetujui') {
            return back()->with('error', 'Tidak bisa menolak surat yang sudah disetujui.');
        }

        $surat->update([
            'status' => 'ditolak',
            'catatan' => $request->catatan
        ]);
        return back()->with('success', 'Permintaan ditolak.');
    }

    public function revisi(Request $request, SuratKeluar $surat)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        if ($surat->status === 'disetujui') {
            return back()->with('error', 'Tidak bisa merevisi surat yang sudah disetujui.');
        }

        $request->validate([
            'catatan' => 'required|string'
        ]);

        $surat->update([
            'status' => 'revisi',
            'catatan' => $request->catatan
        ]);
        return back()->with('success', 'Permintaan dikembalikan untuk revisi.');
    }

    public function show(SuratKeluar $surat)
    {
        return view('surat.show', compact('surat'));
    }

    // Cetak PDF per nomor surat
    public function cetakPdf(SuratKeluar $surat)
    {
            // Pilih template berdasarkan jenis_naskah
            $jenis = strtolower($surat->jenis_naskah ?? '');
            if (strpos($jenis, 'keputusan') !== false) {
                $view = 'surat.show-pdf-sk';
            } else {
                $view = 'surat.show-pdf-keluar';
            }
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView($view, compact('surat'));
            $safeNomor = str_replace(['/', '\\'], '-', $surat->nomor_surat);
            return $pdf->stream('surat-'.$safeNomor.'.pdf');
        }

    public function edit(SuratKeluar $surat)
    {
        $klasifikasis = Klasifikasi::orderBy('kode')->get();
        return view('surat.edit', compact('surat','klasifikasis'));
    }

    public function update(Request $request, SuratKeluar $surat)
    {
        // Jika mengubah tanggal atau klasifikasi, perlu logika untuk mengubah nomor_surat -> saya sarankan menolak perubahan pada field2 tersebut agar nomor tidak tumpang tindih
        $data = $request->validate([
            'pengolah' => 'nullable|string',
            'jenis_naskah' => 'nullable|string',
            'sifat_naskah' => 'nullable|string',
            'hal' => 'nullable|string',
            'tanggal_berlaku' => 'nullable|date',
        ]);

        $surat->update($data);
        return redirect()->route('surat.index')->with('success','Surat diperbarui');
    }

    public function destroy(SuratKeluar $surat)
    {
        // Hapus juga dari tabel nomors
        \App\Models\Nomor::where('id', $surat->nomor_urut)->where('tahun', date('Y'))->delete();
        $surat->delete();
        return back()->with('success','Surat dihapus');
    }
}
