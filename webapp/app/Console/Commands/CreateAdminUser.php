<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

/**
 * Run once per admin account, directly on the server (Render Shell), so the
 * generated password is only ever seen in that terminal — never in chat,
 * git, or logs. `is_admin` is orthogonal to the guardian `role` enum, so
 * this doesn't need (and doesn't create) a Family.
 */
#[Signature('app:create-admin-user {email} {--name=Admin} {--password=}')]
#[Description('Create or promote a user to site-wide admin. Prints a generated password if --password is omitted.')]
class CreateAdminUser extends Command
{
    public function handle(): int
    {
        $email = $this->argument('email');

        $validator = Validator::make(['email' => $email], ['email' => ['required', 'email']]);
        if ($validator->fails()) {
            $this->error($validator->errors()->first('email'));

            return self::FAILURE;
        }

        $password = $this->option('password') ?: Str::password(20);

        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $this->option('name'),
                'password' => Hash::make($password),
                'role' => 'owner',
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );

        $this->info("Admin user ready: {$user->email}");

        if (! $this->option('password')) {
            $this->warn("Generated password (shown once): {$password}");
        }

        return self::SUCCESS;
    }
}
