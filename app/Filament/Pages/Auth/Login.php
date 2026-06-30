<?php

namespace App\Filament\Pages\Auth;

use App\Enums\UserRole;
use Filament\Pages\Auth\Login as BaseLogin;

class Login extends BaseLogin
{
    protected function getRedirectUrl(): string
    {
        $user = auth()->user();

        if ($user && $user->role === UserRole::Admin) {
            return parent::getRedirectUrl();
        }

        return '/';
    }
}
