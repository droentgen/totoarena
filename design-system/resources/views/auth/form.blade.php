{{--
    Ported from Main.dc.html lines ~187-231 ("step: form (sign up / log in)").
    One page, tab-switched client-side (Alpine) — same as the mockup —
    but posts to real Fortify routes depending on which tab is active.

    TODO (backend, not yet wired):
    - register/login routes come from laravel/fortify once installed.
    - password strength meter is decorative here; wire to a real check
      (zxcvbn or similar) if you want the live "ความแข็งแรง: …" text back.
    - duplicate-email case (06-registration.md: "อีเมลนี้มีบัญชีแล้ว") needs
      a custom Fortify RegisterResponse / form error, not shown here yet.
--}}
<x-layouts.auth title="สมัคร/เข้าสู่ระบบ">
<div class="au ta-dots">
    <header class="au-top">
        <div class="ta-row" style="gap: 10px;">
            <div class="ta-logo" style="padding: 0;"><b>TOTO</b> Arena</div>
            <span class="caption ta-muted">สำหรับผู้ปกครอง</span>
        </div>
        <div class="ta-row" style="gap: 8px;">
            <a href="{{ route('login') }}" class="ta-btn ta-btn--ghost ta-btn--sm" style="border: 0; text-decoration: none;">นักเรียน? เข้าด้วยโปรไฟล์</a>
            <x-theme-toggle />
        </div>
    </header>

    <div class="au-wrap">
        <div class="au-card" x-data="{ tab: '{{ $tab ?? 'signup' }}' }">
            <div class="ta-seg" role="tablist" aria-label="สมัครหรือเข้าสู่ระบบ" style="display: grid; grid-template-columns: 1fr 1fr;">
                <button type="button" class="ta-seg-b" :class="{ 'is-on': tab === 'signup' }" @click="tab = 'signup'" role="tab" :aria-selected="tab === 'signup'" style="justify-content: center;">สมัครสมาชิก</button>
                <button type="button" class="ta-seg-b" :class="{ 'is-on': tab === 'login' }" @click="tab = 'login'" role="tab" :aria-selected="tab === 'login'" style="justify-content: center;">เข้าสู่ระบบ</button>
            </div>

            <div>
                <h1 class="au-h" x-text="tab === 'signup' ? 'สร้างบัญชีครอบครัว' : 'เข้าสู่ระบบ'"></h1>
                <p class="small ta-muted" style="margin: 4px 0 0;" x-text="tab === 'signup' ? 'ใช้เวลาไม่ถึง 2 นาที' : 'ยินดีต้อนรับกลับมา'"></p>
            </div>

            <a href="{{ route('auth.google.redirect') }}" class="au-google" style="text-decoration: none;">
                <span class="au-g" aria-hidden="true">G</span>
                <span x-text="tab === 'signup' ? 'สมัครด้วย Google' : 'เข้าสู่ระบบด้วย Google'"></span>
            </a>
            <div class="au-or">หรือใช้อีเมล</div>

            {{-- signup form --}}
            <form method="POST" action="{{ route('register') }}" x-show="tab === 'signup'" class="ta-col" style="gap: 16px;">
                @csrf
                <label class="au-field">ชื่อ–นามสกุลผู้ปกครอง
                    <input class="au-input" type="text" name="name" autocomplete="name" placeholder="เช่น สมชาย ใจดี">
                </label>
                <label class="au-field">อีเมล
                    <input class="au-input" type="email" name="email" autocomplete="email" placeholder="you@example.com">
                </label>
                <label class="au-field">รหัสผ่าน
                    <input class="au-input" type="password" name="password" autocomplete="new-password" placeholder="อย่างน้อย 8 ตัวอักษร">
                </label>
                <div class="ta-col" style="gap: 6px;">
                    <div class="ta-mbar-t"><i style="width: 0%;"></i></div>
                    <span class="caption ta-muted">ความแข็งแรง: ยาวอย่างน้อย 8 ตัว และไม่อยู่ในรายการรหัสที่เคยรั่วไหล</span>
                </div>
                <button type="submit" class="ta-btn ta-btn--primary" style="justify-content: center;">สร้างบัญชี</button>
                <p class="small ta-muted" style="margin: 0; text-align: center;">มีบัญชีแล้ว? <button type="button" class="au-link" @click="tab = 'login'">เข้าสู่ระบบ</button></p>
            </form>

            {{-- login form --}}
            <form method="POST" action="{{ route('login') }}" x-show="tab === 'login'" class="ta-col" style="gap: 16px;">
                @csrf
                <label class="au-field">อีเมล
                    <input class="au-input" type="email" name="email" autocomplete="email" placeholder="you@example.com">
                </label>
                <label class="au-field">รหัสผ่าน
                    <input class="au-input" type="password" name="password" autocomplete="current-password" placeholder="รหัสผ่าน">
                </label>
                <a href="{{ route('password.request') }}" class="au-link" style="align-self: flex-end; text-decoration: underline;">ลืมรหัสผ่าน?</a>
                <button type="submit" class="ta-btn ta-btn--primary" style="justify-content: center;">เข้าสู่ระบบ</button>
                <p class="small ta-muted" style="margin: 0; text-align: center;">ยังไม่มีบัญชี? <button type="button" class="au-link" @click="tab = 'signup'">สมัครสมาชิก</button></p>
            </form>
        </div>
    </div>
</div>
</x-layouts.auth>
