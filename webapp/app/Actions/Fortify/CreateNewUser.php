<?php

namespace App\Actions\Fortify;

use App\Models\Family;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * Every guardian owns (or co-manages) exactly one Family — per
     * 02-architecture.md's data model, Student/content/everything else
     * hangs off family_id, so a User without one is unusable. Signup
     * always creates a brand-new Family with this guardian as 'owner';
     * 06-registration.md's "invite a co-guardian" flow (not built yet)
     * is what would add a second User to an *existing* Family instead.
     *
     * @param  array<string, string>  $input
     *
     * @throws ValidationException
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
        ])->validate();

        return DB::transaction(function () use ($input) {
            $family = Family::create([
                'name' => 'ครอบครัว'.explode(' ', trim($input['name']))[0],
            ]);

            return User::create([
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
                'family_id' => $family->id,
                'role' => 'owner',
            ]);
        });
    }
}
