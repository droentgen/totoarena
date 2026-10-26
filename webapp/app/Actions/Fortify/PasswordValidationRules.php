<?php

namespace App\Actions\Fortify;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Validation\Rules\Password;

trait PasswordValidationRules
{
    /**
     * Get the validation rules used to validate passwords.
     *
     * @param  bool  $confirmed  Only true where the form actually has a
     *                           password_confirmation field — auth/form.blade.php's
     *                           signup form doesn't, so CreateNewUser must pass false
     *                           or every registration fails validation silently.
     * @return array<int, Rule|array<mixed>|string>
     */
    protected function passwordRules(bool $confirmed = true): array
    {
        return array_filter([
            'required',
            'string',
            Password::default(),
            $confirmed ? 'confirmed' : null,
        ]);
    }
}
