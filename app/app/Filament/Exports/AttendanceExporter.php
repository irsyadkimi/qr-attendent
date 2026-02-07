<?php

namespace App\Filament\Exports;

use App\Models\Attendance;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class AttendanceExporter extends Exporter
{
    protected static ?string $model = Attendance::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('event.event_name')->label('Event'),
            ExportColumn::make('attendee_name')->label('Nama'),
            ExportColumn::make('manual_phone')->label('HP'),
            ExportColumn::make('manual_origin')->label('Asal/Instansi'),
            ExportColumn::make('manual_org_type')->label('Organisasi'),
            ExportColumn::make('manual_org_name')->label('Nama Organisasi'),
            ExportColumn::make('group_count')->label('Jumlah Rombongan'),
            ExportColumn::make('checked_in_at')->label('Waktu Check-in'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        return 'Export kehadiran selesai! ' . number_format($export->successful_rows) . ' baris berhasil.';
    }
}
