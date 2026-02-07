<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\Login as BaseLogin;

class Login extends BaseLogin
{
    protected static ?string $navigationIcon = 'heroicon-o-lock-closed';
    
    protected static string $view = 'filament.pages.auth.login';
}
