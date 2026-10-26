{{--
    Not in any design doc/manifest (06-registration.md never designed a
    forgot-password screen) — this reuses the existing `au`/`ta-*` classes
    from auth/form.blade.php rather than inventing new visual design, just
    enough so route('password.request') doesn't dead-end.
--}}
<x-layouts.auth title="ลืมรหัสผ่าน">
<div class="au ta-dots">
    <header class="au-top">
        <div class="ta-row" style="gap: 10px;">
            <div class="ta-logo" style="padding: 0;"><b>TOTO</b> Arena</div>
            <span class="caption ta-muted">สำหรับผู้ปกครอง</span>
        </div>
        <x-theme-toggle />
    </header>

    <div class="au-wrap">
        <div class="au-card">
            <div>
                <h1 class="au-h">ลืมรหัสผ่าน?</h1>
                <p class="small ta-muted" style="margin: 4px 0 0;">กรอกอีเมลที่ใช้สมัคร เราจะส่งลิงก์ตั้งรหัสผ่านใหม่ไปให้</p>
            </div>

            @if (session('status'))
                <p class="small" style="color: var(--lime-text);">{{ session('status') }}</p>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="ta-col" style="gap: 16px;">
                @csrf
                <label class="au-field">อีเมล
                    <input class="au-input" type="email" name="email" autocomplete="email" placeholder="you@example.com" value="{{ old('email') }}">
                </label>
                <button type="submit" class="ta-btn ta-btn--primary" style="justify-content: center;">ส่งลิงก์ตั้งรหัสผ่านใหม่</button>
                <a href="{{ route('auth.form', ['tab' => 'login']) }}" class="au-link" style="text-align: center; text-decoration: underline;">กลับไปเข้าสู่ระบบ</a>
            </form>
        </div>
    </div>
</div>
</x-layouts.auth>
