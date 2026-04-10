<?php
namespace App\Exports;

use App\Models\SuratKeluar;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\AfterSheet;
use Carbon\Carbon;

class DaftarNomorExport implements FromCollection, WithHeadings, WithStyles, WithEvents
{
    protected $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    public function collection()
    {
        return SuratKeluar::with('kodeKearsipan')
            ->where('user_id', $this->userId)
            ->whereIn('status', ['disetujui', 'ditolak'])
            ->latest()
            ->get()
            ->map(function($item) {
                $tanggal = $item->tanggal_ditetapkan ? Carbon::parse($item->tanggal_ditetapkan)->format('Y-m-d') : '-';
                $jam = $item->created_at ? Carbon::parse($item->created_at)->format('H:i') : '-';
                return [
                    'Nomor' => $item->nomor_surat,
                    'Jenis Naskah' => $item->jenis_naskah,
                    'Kode Kear' => $item->kodeKearsipan->kode ?? '-',
                    'Nama Keg' => $item->kodeKearsipan->nama ?? '-',
                    'Tanggal' => $tanggal,
                    'Jam' => $jam,
                    'Status' => $item->status,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Nomor',
            'Jenis Naskah',
            'Kode Kear',
            'Nama Keg',
            'Tanggal',
            'Jam',
            'Status',
        ];
    }

    /**
     * Membuat heading (baris 1) menjadi bold
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    /**
     * Autosize kolom sesuai isi
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                foreach (range('A', 'G') as $col) {
                    $event->sheet->getDelegate()->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }
}
