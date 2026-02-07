<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactResource\Pages;
use App\Models\Contact;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ContactResource extends Resource
{
    protected static ?string $model = Contact::class;
    protected static ?string $navigationIcon = 'heroicon-o-identification';
    protected static ?string $navigationLabel = 'Kontak/Buku Tamu';
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
                    ->maxLength(20),
                    
                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->maxLength(255),
                    
                Forms\Components\TextInput::make('organization_name')
                    ->label('Nama Organisasi')
                    ->maxLength(255)
                    ->datalist(fn() => \App\Models\Organization::active()->pluck('name')->toArray()),
                    
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
                    
                Forms\Components\TextInput::make('position')
                    ->label('Jabatan')
                    ->maxLength(255),
                    
                Forms\Components\Textarea::make('notes')
                    ->label('Catatan')
                    ->rows(3),
                    
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
                Tables\Columns\TextColumn::make('full_name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('phone')
                    ->label('HP')
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable(),
                    
                Tables\Columns\TextColumn::make('organization_name')
                    ->label('Organisasi')
                    ->searchable(),
                    
                Tables\Columns\BadgeColumn::make('organization_type')
                    ->label('Tipe')
                    ->colors([
                        'primary' => ['PRM', 'PCM', 'PDM'],
                        'success' => ['PRA', 'PCA', 'PDA'],
                        'info' => 'External',
                        'secondary' => 'Other',
                    ]),
                    
                Tables\Columns\TextColumn::make('position')
                    ->label('Jabatan')
                    ->toggleable(),
                    
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('organization_type')
                    ->label('Tipe Organisasi')
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
            ->defaultSort('full_name');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContacts::route('/'),
            'create' => Pages\CreateContact::route('/create'),
            'edit' => Pages\EditContact::route('/{record}/edit'),
        ];
    }
}
