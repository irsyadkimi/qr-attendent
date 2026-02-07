<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventParticipantResource\Pages;
use App\Models\EventParticipant;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection;

class EventParticipantResource extends Resource
{
    protected static ?string $model = EventParticipant::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'Roster Peserta';
    protected static ?string $recordTitleAttribute = 'full_name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('event_id')
                    ->label('Event')
                    ->relationship('event', 'event_name')
                    ->required()
                    ->searchable()
                    ->preload(),
                    
                Forms\Components\TextInput::make('full_name')
                    ->label('Nama Lengkap')
                    ->required()
                    ->maxLength(255),
                    
                Forms\Components\TextInput::make('phone')
                    ->label('No HP')
                    ->tel()
                    ->maxLength(20),
                    
                Forms\Components\Select::make('payment_status')
                    ->label('Status Pembayaran')
                    ->options([
                        'unpaid' => 'Belum Bayar',
                        'dp' => 'DP',
                        'paid' => 'Lunas',
                        'refund' => 'Refund',
                        'na' => 'N/A',
                    ])
                    ->default('na'),
                    
                Forms\Components\TextInput::make('seat_number')
                    ->label('Nomor Kursi/Bus')
                    ->maxLength(20)
                    ->placeholder('Contoh: A12 atau Bus 1-12'),
                    
                Forms\Components\TextInput::make('group_label')
                    ->label('Grup/Rombongan')
                    ->maxLength(50)
                    ->placeholder('Contoh: Bus 1, Rombongan A'),
                    
                Forms\Components\Textarea::make('notes')
                    ->label('Catatan')
                    ->rows(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('event.event_name')
                    ->label('Event')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('full_name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('phone')
                    ->label('HP')
                    ->searchable(),
                    
                Tables\Columns\BadgeColumn::make('payment_status')
                    ->label('Bayar')
                    ->colors([
                        'danger' => 'unpaid',
                        'warning' => 'dp',
                        'success' => 'paid',
                        'secondary' => 'refund',
                        'gray' => 'na',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'unpaid' => 'Belum',
                        'dp' => 'DP',
                        'paid' => 'Lunas',
                        'refund' => 'Refund',
                        'na' => '-',
                        default => $state,
                    }),
                    
                Tables\Columns\TextColumn::make('seat_number')
                    ->label('Kursi')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('group_label')
                    ->label('Grup'),
                    
                Tables\Columns\IconColumn::make('has_checked_in')
                    ->label('Hadir')
                    ->boolean()
                    ->getStateUsing(fn ($record) => $record->hasCheckedIn()),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('event_id')
                    ->label('Event')
                    ->relationship('event', 'event_name'),
                    
                Tables\Filters\SelectFilter::make('payment_status')
                    ->label('Status Bayar')
                    ->options([
                        'unpaid' => 'Belum Bayar',
                        'dp' => 'DP',
                        'paid' => 'Lunas',
                        'refund' => 'Refund',
                        'na' => 'N/A',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    BulkAction::make('mark_paid')
                        ->label('Tandai Lunas')
                        ->icon('heroicon-o-check')
                        ->action(fn (Collection $records) => $records->each->update(['payment_status' => 'paid']))
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->headerActions([
                Tables\Actions\Action::make('import')
                    ->label('Import CSV')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->url(fn (): string => route('filament.admin.resources.event-participants.import')),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEventParticipants::route('/'),
            'create' => Pages\CreateEventParticipant::route('/create'),
            'edit' => Pages\EditEventParticipant::route('/{record}/edit'),
            'import' => Pages\ImportEventParticipants::route('/import'),
        ];
    }
}
