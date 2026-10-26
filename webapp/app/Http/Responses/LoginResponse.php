<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        if ($request->wantsJson()) {
            return new JsonResponse('', 200);
        }

        $home = $request->user()->isAdmin() ? route('admin.dashboard') : route('parent.dashboard');

        return redirect()->intended($home);
    }
}
