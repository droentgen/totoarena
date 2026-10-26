<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Family;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Same account-shape as CreateNewUser: a fresh Family per new guardian.
     * Matching by email (not google_id — no such column exists yet) means
     * a guardian who registered with a password can also sign in with
     * Google later, same account.
     */
    public function callback()
    {
        $googleUser = Socialite::driver('google')->user();

        $user = User::firstWhere('email', $googleUser->getEmail());

        if (! $user) {
            $user = DB::transaction(function () use ($googleUser) {
                $family = Family::create([
                    'name' => 'ครอบครัว'.explode(' ', trim($googleUser->getName() ?: 'ผู้ปกครอง'))[0],
                ]);

                return User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'password' => Hash::make(Str::random(40)),
                    'family_id' => $family->id,
                    'role' => 'owner',
                    'email_verified_at' => now(),
                ]);
            });
        }

        Auth::login($user, remember: true);

        return redirect()->route('parent.dashboard');
    }
}
