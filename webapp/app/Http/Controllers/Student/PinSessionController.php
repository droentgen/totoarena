<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * 06-registration.md: 5 wrong PIN attempts -> 15 min lock. Guardian
 * notification on lockout is a TODO (no notification channel wired yet).
 */
class PinSessionController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'student_id' => ['required', 'integer'],
            'pin' => ['required', 'digits:4'],
        ]);

        $student = Student::findOrFail($data['student_id']);

        if ($student->isPinLocked()) {
            return back()->withErrors(['pin' => 'ใส่ PIN ผิดหลายครั้ง ลองใหม่อีกครั้งใน 15 นาที']);
        }

        if (! Hash::check($data['pin'], $student->pin_hash)) {
            $student->pin_fail_count++;

            if ($student->pin_fail_count >= 5) {
                $student->pin_locked_until = now()->addMinutes(15);
                $student->pin_fail_count = 0;
                // TODO: notify guardian (06-registration.md) — no notification channel wired yet.
            }

            $student->save();

            return back()->withErrors(['pin' => 'PIN ไม่ถูกต้อง']);
        }

        $student->pin_fail_count = 0;
        $student->pin_locked_until = null;
        $student->save();

        Auth::guard('student')->login($student);
        $request->session()->regenerate();

        return redirect()->route('student.home');
    }

    public function destroy(Request $request)
    {
        Auth::guard('student')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
