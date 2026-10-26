<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ThemeController extends Controller
{
    public function update(Request $request)
    {
        $data = $request->validate([
            'theme' => ['required', 'in:dark,light,system'],
        ]);

        $user = Auth::guard('student')->user() ?? Auth::guard('web')->user();

        if ($user) {
            $user->update(['theme' => $data['theme']]);
        }

        return response()->noContent();
    }
}
