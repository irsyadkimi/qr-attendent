<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Users & Permissions';
    protected static ?string $navigationGroup = 'Pengaturan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama')
                    ->required()
                    ->maxLength(255),
                    
                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                    
                Forms\Components\TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $context): bool => $context === 'create')
                    ->maxLength(255)
                    ->helperText('Kosongkan jika tidak ingin mengubah password'),
                    
                Forms\Components\Select::make('role')
                    ->label('Role')
                    ->options([
                        'admin' => 'Admin',
                        'operator' => 'Operator',
                    ])
                    ->required()
                    ->default('operator')
                    ->reactive(),
                    
                Forms\Components\Section::make('Permissions')
                    ->schema([
                        Forms\Components\Toggle::make('can_create_event')
                            ->label('Dapat Membuat Event')
                            ->default(true)
                            ->inline(false),
                            
                        Forms\Components\Toggle::make('can_delete')
                            ->label('Dapat Menghapus Data')
                            ->default(false)
                            ->inline(false)
                            ->helperText('Izinkan operator menghapus event/attendance'),
                            
                        Forms\Components\Toggle::make('can_manage_users')
                            ->label('Dapat Manage Users')
                            ->default(false)
                            ->inline(false)
                            ->helperText('Hanya admin yang direkomendasikan'),
                    ])
                    ->visible(fn (Forms\Get $get) => $get('role') === 'operator')
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\BadgeColumn::make('role')
                    ->label('Role')
                    ->colors([
                        'danger' => 'admin',
                        'warning' => 'operator',
                    ])
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),
                    
                Tables\Columns\IconColumn::make('can_create_event')
                    ->label('Create Event')
                    ->boolean()
                    ->toggleable(),
                    
                Tables\Columns\IconColumn::make('can_delete')
                    ->label('Delete')
                    ->boolean()
                    ->toggleable(),
                    
                Tables\Columns\IconColumn::make('can_manage_users')
                    ->label('Manage Users')
                    ->boolean()
                    ->toggleable(),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->options([
                        'admin' => 'Admin',
                        'operator' => 'Operator',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn () => auth()->user()->isAdmin()),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn () => auth()->user()->isAdmin()),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function canAccess(): bool
    {
        return auth()->user()->isAdmin() || auth()->user()->can_manage_users;
    }
}
