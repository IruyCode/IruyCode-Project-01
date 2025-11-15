<?php

namespace App\Actions\Fortify;

use Laravel\Fortify\Contracts\LoginResponse;
use Illuminate\Http\Request;

class CustomLoginResponse implements LoginResponse
{
    public function toResponse($request)
    {
        $user = $request->user();

        // Exemplo: redirecionar conforme o tipo de usuário
        if ($user->type_user_id == 1) {
            return redirect()->route('admin.home');
        } elseif ($user->type_user_id == 2) {
            return redirect()->route('client.dashboardClient');
        }

        return redirect()->route('home');
    }
}
