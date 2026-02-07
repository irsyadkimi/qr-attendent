<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AttendanceResource\Pages;
use App\Filament\Resources\AttendanceResource\RelationManagers;
use App\Models\Attendance;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AttendanceResource extends Resource
{
    protected static ?string $model = Attendance::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('event_id')
                    ->relationship('event', 'id')
                    ->required(),
                Forms\Components\TextInput::make('event_participant_id'),
                Forms\Components\TextInput::make('manual_name')
                    ->maxLength(255),
                Forms\Components\TextInput::make('manual_phone')
                    ->tel()
                    ->maxLength(20),
                Forms\Components\TextInput::make('manual_origin')
                    ->maxLength(255),
                Forms\Components\TextInput::make('manual_org_type')
                    ->maxLength(50),
                Forms\Components\TextInput::make('manual_org_name')
                    ->maxLength(255),
                Forms\Components\TextInput::make('group_count')
                    ->numeric(),
                Forms\Components\TextInput::make('answers_json'),
                Forms\Components\DateTimePicker::make('checked_in_at')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID'),
                Tables\Columns\TextColumn::make('event.id'),
                Tables\Columns\TextColumn::make('event_participant_id'),
                Tables\Columns\TextColumn::make('manual_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('manual_phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('manual_origin')
                    ->searchable(),
                Tables\Columns\TextColumn::make('manual_org_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('manual_org_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('group_count')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('checked_in_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
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
            'index' => Pages\ListAttendances::route('/'),
            'create' => Pages\CreateAttendance::route('/create'),
            'edit' => Pages\EditAttendance::route('/{record}/edit'),
        ];
    }
}
