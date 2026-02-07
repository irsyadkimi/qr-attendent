<?php

namespace App\Filament\Resources\EventParticipantResource\Pages;

use App\Filament\Resources\EventParticipantResource;
use App\Models\Event;
use App\Models\EventParticipant;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;

class ImportEventParticipants extends Page
{
    protected static string $resource = EventParticipantResource::class;
    protected static string $view = 'filament.resources.event-participant-resource.pages.import';
    protected static ?string $title = 'Import Roster dari CSV';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Import CSV')
                    ->description('Upload file CSV dengan kolom: full_name, phone, payment_status, seat_number, group_label, notes')
                    ->schema([
                        Forms\Components\Select::make('event_id')
                            ->label('Pilih Event')
                            ->options(Event::orderBy('created_at', 'desc')->pluck('event_name', 'id'))
                            ->required()
                            ->searchable(),
                            
                        Forms\Components\FileUpload::make('csv_file')
                            ->label('File CSV')
                            ->acceptedFileTypes(['text/csv', 'text/plain', 'application/csv'])
                            ->required()
                            ->helperText('Format: full_name,phone,payment_status,seat_number,group_label,notes'),
                            
                        Forms\Components\Placeholder::make('template')
                            ->label('Template CSV')
                            ->content(fn () => view('filament.pages.csv-template')),
                    ]),
            ])
            ->statePath('data');
    }

    public function import()
    {
        $data = $this->form->getState();
        
        try {
            $path = Storage::disk('local')->path($data['csv_file']);
            $csv = Reader::createFromPath($path, 'r');
            $csv->setHeaderOffset(0);
            
            $imported = 0;
            foreach ($csv as $record) {
                EventParticipant::create([
                    'event_id' => $data['event_id'],
                    'full_name' => $record['full_name'] ?? '',
                    'phone' => $record['phone'] ?? null,
                    'payment_status' => $record['payment_status'] ?? 'na',
                    'seat_number' => $record['seat_number'] ?? null,
                    'group_label' => $record['group_label'] ?? null,
                    'notes' => $record['notes'] ?? null,
                ]);
                $imported++;
            }
            
            Notification::make()
                ->title("Berhasil import {$imported} peserta!")
                ->success()
                ->send();
                
            return redirect()->route('filament.admin.resources.event-participants.index');
            
        } catch (\Exception $e) {
            Notification::make()
                ->title('Import gagal!')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
