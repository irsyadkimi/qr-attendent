<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class GuestBookQR extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-qr-code';
    protected static string $view = 'filament.pages.guest-book-qr';
    protected static ?string $navigationLabel = 'QR Code Buku Tamu';
    protected static ?string $navigationGroup = 'Data Master';
    protected static ?string $title = 'QR Code Buku Tamu Permanen';
}
