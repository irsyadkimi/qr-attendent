<?php

namespace App\Filament\Resources\GuestVisitResource\Pages;

use App\Filament\Resources\GuestVisitResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGuestVisits extends ListRecords
{
    protected static string $resource = GuestVisitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
