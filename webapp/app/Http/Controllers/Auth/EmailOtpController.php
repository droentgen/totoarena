<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

/**
 * Fortify ships signed-link email verification, not a 6-digit OTP, so
 * Features::emailVerification() is left disabled (config/fortify.php) and
 * these two routes do the whole flow by hand per 06-registration.md: 15-min
 * expiry, 60s resend cooldown. The 5/hour resend cap isn't enforced yet —
 * TODO once a rate limiter policy for it is decided.
 *
 * No Mailable class — Mail::raw() is enough while MAIL_MAILER=log (the
 * Laravel default); swap in a real Mailable before going to production.
 */
class EmailOtpController extends Controller
{
    /**
     * The view used to just claim "we already sent you a code" without
     * ever sending one — nothing fired an OTP until the user hit "resend",
     * so the first code they could actually use was the second one. Send
     * it here instead, gated by the same 60s cooldown as a manual resend.
     */
    public function notice(): View
    {
        $user = Auth::user();

        if ($user && ! $this->withinResendCooldown($user)) {
            $this->issueOtp($user);
        }

        return view('auth.verify-email');
    }

    public function send(Request $request)
    {
        $user = Auth::user();

        if ($this->withinResendCooldown($user)) {
            return back()->withErrors(['otp' => 'ส่งรหัสใหม่ได้อีกครั้งในอีกสักครู่']);
        }

        $this->issueOtp($user);

        return back()->with('status', 'ส่งรหัสยืนยันแล้ว');
    }

    private function withinResendCooldown(User $user): bool
    {
        $lastSentAt = $user->emailOtps()->latest()->first()?->created_at;

        return $lastSentAt && $lastSentAt->diffInSeconds(now()) < 60;
    }

    private function issueOtp(User $user): void
    {
        $code = (string) random_int(100000, 999999);

        $user->emailOtps()->create([
            'code_hash' => Hash::make($code),
            'expires_at' => now()->addMinutes(15),
        ]);

        Mail::raw("รหัสยืนยันอีเมล TOTO Arena ของคุณคือ: {$code} (ใช้ได้ 15 นาที)", function ($message) use ($user) {
            $message->to($user->email)->subject('ยืนยันอีเมล TOTO Arena');
        });
    }

    public function verify(Request $request)
    {
        $data = $request->validate(['otp' => ['required', 'digits:6']]);

        $user = Auth::user();

        $otp = $user->emailOtps()->whereNull('consumed_at')->latest()->first();

        if (! $otp || $otp->isExpired()) {
            return back()->withErrors(['otp' => 'รหัสหมดอายุแล้ว กดส่งรหัสใหม่']);
        }

        if (! Hash::check($data['otp'], $otp->code_hash)) {
            $otp->increment('attempts');

            return back()->withErrors(['otp' => 'รหัสไม่ถูกต้อง']);
        }

        $otp->update(['consumed_at' => now()]);
        $user->forceFill(['email_verified_at' => now()])->save();

        return redirect()->route('consent.show');
    }
}
