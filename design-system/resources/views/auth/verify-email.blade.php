{{--
    Ported from Main.dc.html lines ~233-241 ("step: verify email (OTP)").
    TODO: 6-digit OTP is not Fortify's default (Fortify ships signed-link
    verification) — needs a custom action: generate+store a hashed OTP,
    15-min expiry, resend max 5/hour/60s cooldown (06-registration.md).
--}}
<x-layouts.auth title="ยืนยันอีเมล">
<div class="au ta-dots">
    <header class="au-top">
        <div class="ta-row" style="gap: 10px;">
            <div class="ta-logo" style="padding: 0;"><b>TOTO</b> Arena</div>
            <span class="caption ta-muted">สำหรับผู้ปกครอง</span>
        </div>
        <x-theme-toggle />
    </header>

    <div class="au-wrap">
        <div class="au-card" x-data="{ cooldown: 48 }" x-init="setInterval(() => { if (cooldown > 0) cooldown-- }, 1000)">
            <span class="ta-ic-circle">
                <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M3 6h18v12H3z"></path><path d="m3 7 9 6 9-6"></path></svg>
            </span>
            <div>
                <h1 class="au-h">ยืนยันอีเมลของคุณ</h1>
                <p class="small ta-muted" style="margin: 4px 0 0;">
                    ส่งรหัส 6 หลักไปที่ <b style="color: var(--ink);">{{ $email ?? auth()->user()?->email ?? 'name@example.com' }}</b> แล้ว
                    รหัสใช้ได้ 15 นาที (หรือกดลิงก์ในอีเมลเดียวกันก็ได้)
                </p>
            </div>

            <form method="POST" action="{{ route('verification.verify-otp') }}" class="ta-col" style="gap: 16px;">
                @csrf
                <label class="au-field">รหัสยืนยัน
                    <input class="au-input au-otp" type="text" name="otp" inputmode="numeric" maxlength="6" autocomplete="one-time-code" placeholder="••••••">
                </label>
                <button type="submit" class="ta-btn ta-btn--primary" style="justify-content: center;">ยืนยันอีเมล</button>
                <div class="ta-row" style="justify-content: space-between;">
                    <span class="caption ta-muted" x-show="cooldown > 0">ส่งรหัสใหม่ได้ใน <span x-text="`0:${cooldown.toString().padStart(2,'0')}`"></span></span>
                    <button type="button" class="au-link" x-show="cooldown === 0" @click="cooldown = 60" formaction="{{ route('verification.send-otp') }}" formmethod="POST">ส่งรหัสใหม่</button>
                    <a href="{{ route('auth.form', ['tab' => 'signup']) }}" class="au-link" style="text-decoration: underline;">แก้อีเมล</a>
                </div>
            </form>
        </div>
    </div>
</div>
</x-layouts.auth>
