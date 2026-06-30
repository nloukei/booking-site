<?php

namespace App\Filament\Pages\Auth;

use App\Enums\UserRole;
use Filament\Pages\Auth\Register as BaseRegister;

class Register extends BaseRegister
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
