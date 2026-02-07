<?php

namespace App\Exports;

use App\Models\GuestVisit;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GuestVisitsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = GuestVisit::query()->orderBy('visited_at', 'desc');
        
        if (isset($this->filters['date_from'])) {
            $query->whereDate('visited_at', '>=', $this->filters['date_from']);
        }
        
        if (isset($this->filters['date_to'])) {
            $query->whereDate('visited_at', '<=', $this->filters['date_to']);
        }
        
        return $query->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal & Jam',
            'Nama Lengkap',
            'No HP',
            'Organisasi',
            'Tipe Organisasi',
            'Agenda',
            'Lokasi',
            'Jumlah Rombongan',
            'Sampai Tanggal',
            'Durasi (Hari)',
            'Catatan',
        ];
    }

    public function map($visit): array
    {
        static $no = 0;
        $no++;
        
        return [
            $no,
            $visit->visited_at->format('d/m/Y H:i'),
            $visit->full_name,
            $visit->phone,
            $visit->organization_name,
            $visit->organization_type,
            $visit->agenda,
            $visit->location,
            $visit->group_count,
            $visit->visit_end_date ? $visit->visit_end_date->format('d/m/Y') : '-',
            $visit->duration_days ?? 1,
            $visit->notes,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
