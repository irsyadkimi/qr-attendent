<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrganizationResource\Pages;
use App\Models\Organization;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OrganizationResource extends Resource
{
    protected static ?string $model = Organization::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';
    protected static ?string $navigationLabel = 'Organisasi';
    protected static ?string $navigationGroup = 'Pengaturan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama Organisasi')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                    
                Forms\Components\Select::make('type')
                    ->label('Tipe')
                    ->options([
                        'PRM' => 'PRM',
                        'PCM' => 'PCM',
                        'PDM' => 'PDM',
                        'PWM' => 'PWM',
                        'PRA' => 'PRA',
                        'PCA' => 'PCA',
                        'PDA' => 'PDA',
                        'PWA' => 'PWA',
                        'ICMI' => 'ICMI',
                        'Tabligh' => 'Tabligh',
                        'ISM' => 'ISM',
                        'Ekonomi' => 'Ekonomi',
                        'BTM' => 'BTM',
                        'External' => 'External/Tamu',
                        'Other' => 'Lainnya',
                    ])
                    ->searchable(),
                    
                Forms\Components\TextInput::make('description')
                    ->label('Deskripsi/Keterangan')
                    ->maxLength(255),
                    
                Forms\Components\Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true)
                    ->inline(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Organisasi')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\BadgeColumn::make('type')
                    ->label('Tipe')
                    ->colors([
                        'primary' => ['PRM', 'PCM', 'PDM', 'PWM'],
                        'success' => ['PRA', 'PCA', 'PDA', 'PWA'],
                        'warning' => ['ICMI', 'Tabligh', 'ISM', 'Ekonomi', 'BTM'],
                        'info' => 'External',
                        'secondary' => 'Other',
                    ]),
                    
                Tables\Columns\TextColumn::make('description')
                    ->label('Deskripsi')
                    ->limit(50),
                    
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Tipe')
                    ->options([
                        'PRM' => 'PRM',
                        'PCM' => 'PCM',
                        'External' => 'External',
                    ]),
                    
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status')
                    ->placeholder('Semua')
                    ->trueLabel('Aktif')
                    ->falseLabel('Nonaktif'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('name');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrganizations::route('/'),
            'create' => Pages\CreateOrganization::route('/create'),
            'edit' => Pages\EditOrganization::route('/{record}/edit'),
        ];
    }
}
