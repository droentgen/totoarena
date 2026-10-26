<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;

/**
 * Default Fortify behaviour lands a fresh guardian on config('fortify.home')
 * — this app's actual onboarding path is OTP verify -> consent -> the
 * onboarding wizard (06-registration.md), not a dashboard.
 */
class RegisterResponse implements RegisterResponseContract
{
    public function toResponse($request)
    {
        return $request->wantsJson()
            ? new JsonResponse('', 201)
            : redirect()->route('verification.notice');
    }
}
