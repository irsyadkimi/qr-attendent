<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages;
use App\Models\Event;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;
    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationLabel = 'Events';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('event_name')
                    ->label('Nama Event')
                    ->required()
                    ->maxLength(255),
                    
                Forms\Components\Select::make('event_type')
                    ->label('Tipe Event')
                    ->options([
                        'pengajian' => 'Pengajian',
                        'rekreasi' => 'Rekreasi / Bus Trip',
                        'kunjungan' => 'Kunjungan Tamu',
                        'rapat' => 'Rapat / Meeting',
                        'custom' => 'Custom',
                    ])
                    ->required()
                    ->default('custom'),
                    
                Forms\Components\Textarea::make('agenda_topic')
                    ->label('Agenda / Topik')
                    ->required()
                    ->rows(3),
                    
                Forms\Components\TextInput::make('location')
                    ->label('Lokasi')
                    ->required()
                    ->maxLength(255),
                    
                Forms\Components\DatePicker::make('date_start')
                    ->label('Tanggal Mulai')
                    ->required()
                    ->default(now()),
                    
                Forms\Components\DatePicker::make('date_end')
                    ->label('Tanggal Selesai')
                    ->nullable(),
                    
                Forms\Components\TextInput::make('capacity_expected')
                    ->label('Kapasitas Peserta (Opsional)')
                    ->numeric()
                    ->nullable(),
                    
                Forms\Components\Toggle::make('allow_manual_entry')
                    ->label('Izinkan Input Manual')
                    ->default(true)
                    ->inline(false),
                    
                Forms\Components\Toggle::make('allow_preloaded_select')
                    ->label('Izinkan Pilih dari Roster')
                    ->default(true)
                    ->inline(false),
                    
                Forms\Components\Hidden::make('created_by')
                    ->default(Auth::id()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('event_code')
                    ->label('Kode')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('event_name')
                    ->label('Nama Event')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\BadgeColumn::make('event_type')
                    ->label('Tipe')
                    ->colors([
                        'success' => 'pengajian',
                        'warning' => 'rekreasi',
                        'info' => 'kunjungan',
                        'primary' => 'rapat',
                        'secondary' => 'custom',
                    ]),
                    
                Tables\Columns\TextColumn::make('date_start')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('attendances_count')
                    ->label('Hadir')
                    ->counts('attendances')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('participants_count')
                    ->label('Roster')
                    ->counts('participants')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('event_type')
                    ->label('Tipe Event')
                    ->options([
                        'pengajian' => 'Pengajian',
                        'rekreasi' => 'Rekreasi',
                        'kunjungan' => 'Kunjungan',
                        'rapat' => 'Rapat',
                        'custom' => 'Custom',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('duplicate')
                    ->label('Duplicate')
                    ->icon('heroicon-o-document-duplicate')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $newEvent = $record->replicate();
                        $newEvent->event_code = strtoupper(\Illuminate\Support\Str::random(8));
                        $newEvent->event_name = $record->event_name . ' (Copy)';
                        $newEvent->created_by = auth()->id();
                        $newEvent->save();
                        \Filament\Notifications\Notification::make()
                            ->title('Event berhasil diduplicate!')
                            ->success()
                            ->send();
                    }),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'view' => Pages\ViewEvent::route('/{record}'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }
}
