{{-- See forgot-password.blade.php — same "no design doc exists" note. --}}
<x-layouts.auth title="ตั้งรหัสผ่านใหม่">
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
                <h1 class="au-h">ตั้งรหัสผ่านใหม่</h1>
            </div>

            <form method="POST" action="{{ route('password.update') }}" class="ta-col" style="gap: 16px;">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">
                <label class="au-field">อีเมล
                    <input class="au-input" type="email" name="email" autocomplete="email" value="{{ old('email', $request->email) }}">
                </label>
                <label class="au-field">รหัสผ่านใหม่
                    <input class="au-input" type="password" name="password" autocomplete="new-password" placeholder="อย่างน้อย 8 ตัวอักษร">
                </label>
                <label class="au-field">ยืนยันรหัสผ่านใหม่
                    <input class="au-input" type="password" name="password_confirmation" autocomplete="new-password">
                </label>
                <button type="submit" class="ta-btn ta-btn--primary" style="justify-content: center;">บันทึกรหัสผ่านใหม่</button>
            </form>
        </div>
    </div>
</div>
</x-layouts.auth>
