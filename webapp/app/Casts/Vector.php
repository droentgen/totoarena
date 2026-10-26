<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

/**
 * Minimal pgvector cast — converts between a PHP array (what app code
 * works with) and pgvector's text literal "[1,2,3]" (what Postgres
 * stores/returns). UNTESTED — if the `pgvector/pgvector-php` package ends
 * up installed, prefer its own Laravel cast instead of this one; this
 * exists only because this environment can't `composer require` anything
 * to check what's actually available.
 */
class Vector implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes): ?array
    {
        if ($value === null) {
            return null;
        }

        return array_map('floatval', explode(',', trim($value, '[]')));
    }

    public function set($model, string $key, $value, array $attributes): ?string
    {
        if ($value === null) {
            return null;
        }

        return '[' . implode(',', array_map('floatval', $value)) . ']';
    }
}
