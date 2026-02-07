<?php

namespace App\Filament\Exports;

use App\Models\GuestVisit;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class GuestVisitExporter extends Exporter
{
    protected static ?string $model = GuestVisit::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('visited_at')->label('Tanggal & Jam'),
            ExportColumn::make('full_name')->label('Nama Lengkap'),
            ExportColumn::make('phone')->label('No HP'),
            ExportColumn::make('organization_name')->label('Organisasi'),
            ExportColumn::make('organization_type')->label('Tipe Organisasi'),
            ExportColumn::make('agenda')->label('Agenda'),
            ExportColumn::make('location')->label('Lokasi'),
            ExportColumn::make('group_count')->label('Jumlah Rombongan'),
            ExportColumn::make('notes')->label('Catatan'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        return 'Export buku tamu selesai! ' . number_format($export->successful_rows) . ' baris berhasil.';
    }
}
