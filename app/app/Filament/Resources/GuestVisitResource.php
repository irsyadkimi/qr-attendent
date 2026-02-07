<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GuestVisitResource\Pages;
use App\Models\GuestVisit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\GuestVisitsExport;

class GuestVisitResource extends Resource
{
    protected static ?string $model = GuestVisit::class;
    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationLabel = 'Buku Tamu Permanen';
    protected static ?string $navigationGroup = 'Data Master';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('full_name')
                    ->label('Nama Lengkap')
                    ->required()
                    ->maxLength(255),
                    
                Forms\Components\TextInput::make('phone')
                    ->label('No HP')
                    ->tel()
                    ->required()
                    ->maxLength(20),
                    
                Forms\Components\TextInput::make('organization_name')
                    ->label('Nama Organisasi')
                    ->maxLength(255),
                    
                Forms\Components\Select::make('organization_type')
                    ->label('Tipe Organisasi')
                    ->options([
                        'PRM' => 'PRM',
                        'PCM' => 'PCM',
                        'PDM' => 'PDM',
                        'PWM' => 'PWM',
                        'PRA' => 'PRA',
                        'PCA' => 'PCA',
                        'PDA' => 'PDA',
                        'PWA' => 'PWA',
                        'External' => 'External/Tamu',
                        'Other' => 'Lainnya',
                    ])
                    ->searchable(),
                    
                Forms\Components\TextInput::make('agenda')
                    ->label('Agenda/Keperluan')
                    ->maxLength(255),
                    
                Forms\Components\TextInput::make('location')
                    ->label('Lokasi Kunjungan')
                    ->maxLength(255),
                    
                Forms\Components\TextInput::make('group_count')
                    ->label('Jumlah Rombongan')
                    ->numeric()
                    ->default(1)
                    ->minValue(1),
                    
                Forms\Components\Textarea::make('notes')
                    ->label('Catatan')
                    ->rows(3),
                    
                Forms\Components\DateTimePicker::make('visited_at')
                    ->label('Waktu Kunjungan')
                    ->required()
                    ->default(now()),
                    
                Forms\Components\DatePicker::make('visit_end_date')
                    ->label('Sampai Tanggal (Opsional)')
                    ->nullable()
                    ->minDate(fn ($get) => $get('visited_at') ?? today())
                    ->helperText('Kosongkan jika kunjungan hanya 1 hari'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('visited_at')
                    ->label('Tanggal & Jam')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('visit_end_date')
                    ->label('Sampai Tanggal')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable()
                    ->placeholder('-'),
                    
                Tables\Columns\TextColumn::make('duration_days')
                    ->label('Durasi')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => $state > 1 ? "{$state} hari" : '1 hari')
                    ->badge()
                    ->color(fn ($state) => $state > 1 ? 'warning' : 'secondary'),
                    
                Tables\Columns\TextColumn::make('full_name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('phone')
                    ->label('HP')
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('organization_name')
                    ->label('Organisasi')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\BadgeColumn::make('organization_type')
                    ->label('Tipe')
                    ->colors([
                        'primary' => ['PRM', 'PCM', 'PDM'],
                        'success' => 'PRA',
                        'info' => 'External',
                        'secondary' => 'Other',
                    ]),
                    
                Tables\Columns\TextColumn::make('agenda')
                    ->label('Agenda')
                    ->limit(30)
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('group_count')
                    ->label('Rombongan')
                    ->sortable(),
            ])
            ->filters([
                Filter::make('today')
                    ->label('Hari Ini')
                    ->query(fn (Builder $query): Builder => $query->whereDate('visited_at', today())),
                    
                Filter::make('this_week')
                    ->label('Minggu Ini')
                    ->query(fn (Builder $query): Builder => $query->whereBetween('visited_at', [now()->startOfWeek(), now()->endOfWeek()])),
                    
                Filter::make('this_month')
                    ->label('Bulan Ini')
                    ->query(fn (Builder $query): Builder => $query->whereMonth('visited_at', now()->month)),
                    
                Filter::make('multi_day')
                    ->label('Kunjungan Multi-Hari')
                    ->query(fn (Builder $query): Builder => $query->where('duration_days', '>', 1)),
                    
                Tables\Filters\SelectFilter::make('organization_type')
                    ->label('Tipe Organisasi')
                    ->options([
                        'PRM' => 'PRM',
                        'PCM' => 'PCM',
                        'PDM' => 'PDM',
                        'External' => 'External',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    
                    Tables\Actions\BulkAction::make('export_excel')
                        ->label('Export Excel')
                        ->icon('heroicon-o-document-arrow-down')
                        ->color('success')
                        ->requiresConfirmation()
                        ->form([
                            Forms\Components\DatePicker::make('date_from')
                                ->label('Dari Tanggal')
                                ->default(now()->subMonth()),
                            Forms\Components\DatePicker::make('date_to')
                                ->label('Sampai Tanggal')
                                ->default(now()),
                        ])
                        ->action(function (array $data, $records) {
                            return Excel::download(
                                new GuestVisitsExport($data),
                                'buku-tamu-' . now()->format('Y-m-d') . '.xlsx'
                            );
                        }),
                ]),
            ])
            ->defaultSort('visited_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGuestVisits::route('/'),
            'create' => Pages\CreateGuestVisit::route('/create'),
            'edit' => Pages\EditGuestVisit::route('/{record}/edit'),
        ];
    }
}
