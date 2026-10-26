<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Consent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConsentController extends Controller
{
    /** Bump when ข้อกำหนดการใช้งาน/นโยบายความเป็นส่วนตัว actually change. */
    private const DOC_VERSION = '2026-10-26';

    public function store(Request $request)
    {
        $data = $request->validate([
            'terms' => ['accepted'],
            'child_data_consent' => ['accepted'],
            'marketing_opt_in' => ['nullable', 'boolean'],
        ]);

        $user = Auth::user();

        foreach (['terms', 'child_data'] as $docType) {
            Consent::create([
                'user_id' => $user->id,
                'doc_type' => $docType,
                'doc_version' => self::DOC_VERSION,
                'accepted_at' => now(),
                'ip' => $request->ip(),
            ]);
        }

        if ($request->boolean('marketing_opt_in')) {
            Consent::create([
                'user_id' => $user->id,
                'doc_type' => 'marketing_opt_in',
                'doc_version' => self::DOC_VERSION,
                'accepted_at' => now(),
                'ip' => $request->ip(),
            ]);
        }

        return redirect()->route('onboarding');
    }
}
