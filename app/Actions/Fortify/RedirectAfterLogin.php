<?php

namespace App\Actions\Fortify;

use Illuminate\Support\Facades\Auth;

class RedirectAfterLogin
{
    public function __invoke($request)
    {
        $user = Auth::user();

        if (!$user) {
            return '/login';
        }

        return match ($user->type_user_id) {
            1 => route('admin.dashboardAdmin'),
            2 => route('client.dashboardClient'),
            default => '/',
        };
    }
}
