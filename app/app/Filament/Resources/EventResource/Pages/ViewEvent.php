<?php

namespace App\Filament\Resources\EventResource\Pages;

use App\Filament\Resources\EventResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ViewEvent extends ViewRecord
{
    protected static string $resource = EventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Components\Section::make('QR Code Event')
                    ->description('Scan QR Code ini untuk check-in ke event')
                    ->schema([
                        Components\ViewEntry::make('qr_code')
                            ->label('')
                            ->view('filament.infolists.qr-code-display'),
                        
                        Components\TextEntry::make('qr_url')
                            ->label('Link Check-in')
                            ->copyable()
                            ->url(fn ($record) => $record->qr_url)
                            ->openUrlInNewTab(),
                    ])
                    ->collapsible(),

                Components\Section::make('Detail Event')
                    ->schema([
                        Components\TextEntry::make('event_code')
                            ->label('Kode Event'),
                        Components\TextEntry::make('event_name')
                            ->label('Nama Event'),
                        Components\TextEntry::make('event_type')
                            ->label('Tipe Event')
                            ->badge(),
                        Components\TextEntry::make('agenda_topic')
                            ->label('Agenda / Topik')
                            ->columnSpanFull(),
                        Components\TextEntry::make('location')
                            ->label('Lokasi'),
                        Components\TextEntry::make('date_start')
                            ->label('Tanggal Mulai')
                            ->date('d M Y'),
                        Components\TextEntry::make('date_end')
                            ->label('Tanggal Selesai')
                            ->date('d M Y')
                            ->placeholder('Tidak ada'),
                        Components\TextEntry::make('capacity_expected')
                            ->label('Kapasitas')
                            ->placeholder('Tidak dibatasi'),
                    ])
                    ->columns(2),

                Components\Section::make('Statistik')
                    ->schema([
                        Components\TextEntry::make('total_hadir')
                            ->label('Total Hadir')
                            ->getStateUsing(fn ($record) => $record->attendances()->count())
                            ->badge()
                            ->color('success'),
                        Components\TextEntry::make('total_roster')
                            ->label('Total Roster')
                            ->getStateUsing(fn ($record) => $record->participants()->count())
                            ->badge()
                            ->color('info'),
                    ])
                    ->columns(2),
            ]);
    }
}

