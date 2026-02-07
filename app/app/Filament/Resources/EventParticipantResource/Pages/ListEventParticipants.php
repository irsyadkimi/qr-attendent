<?php

namespace App\Filament\Resources\EventParticipantResource\Pages;

use App\Filament\Resources\EventParticipantResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEventParticipants extends ListRecords
{
    protected static string $resource = EventParticipantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('import_csv')
                ->label('Import CSV')
                ->icon('heroicon-o-arrow-up-tray')
                ->url(fn (): string => route('filament.admin.resources.event-participants.import'))
                ->color('warning'),
            Actions\Action::make('quick_add_from_contacts')
                ->label('Quick Add dari Kontak')
                ->icon('heroicon-o-users')
                ->form([
                    \Filament\Forms\Components\Select::make('event_id')
                        ->label('Event')
                        ->relationship('event', 'event_name')
                        ->required()
                        ->searchable(),
                    \Filament\Forms\Components\Select::make('contact_ids')
                        ->label('Pilih Kontak')
                        ->multiple()
                        ->searchable()
                        ->options(\App\Models\Contact::active()->pluck('full_name', 'id'))
                        ->required(),
                ])
                ->action(function (array $data) {
                    $contacts = \App\Models\Contact::whereIn('id', $data['contact_ids'])->get();
                    $added = 0;
                    
                    foreach ($contacts as $contact) {
                        \App\Models\EventParticipant::create([
                            'event_id' => $data['event_id'],
                            'full_name' => $contact->full_name,
                            'phone' => $contact->phone,
                            'payment_status' => 'na',
                        ]);
                        $added++;
                    }
                    
                    \Filament\Notifications\Notification::make()
                        ->title("Berhasil menambahkan {$added} peserta dari kontak!")
                        ->success()
                        ->send();
                })
                ->color('info'),
        ];
    }
}
