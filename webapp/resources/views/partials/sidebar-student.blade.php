@php
    // TODO: wire to real Student model once Auth/Game Engine modules exist (see 02-architecture.md data model).
    $student = auth('student')->user();
    $nav = fn (string $route) => request()->routeIs($route) ? 'ta-nav is-on' : 'ta-nav';
@endphp

{{-- Ported from Main.dc.html lines ~388-412 ("sidebar: student") --}}
<aside class="ta-side" style="flex: 1 1 232px; box-sizing: border-box;">
    <div class="ta-logo"><b>TOTO</b> Arena</div>

    <div class="ta-profile">
        <div class="ta-avatar" style="width: 44px; height: 44px; border: 2px solid var(--lime-text);">
            {{ $student->initials ?? 'กล้า' }}
        </div>
        <div>
            <div class="body-strong" style="line-height: 20px;">{{ $student->nickname ?? 'กล้าหาญ' }}</div>
            <div class="caption ta-lime">{{ $student->tier_label ?? 'TOTO Challenger' }}</div>
        </div>
    </div>

    <nav aria-label="เมนูหลัก" style="display: flex; flex-direction: column; gap: 4px;">
        <a href="{{ route('student.home') }}" class="{{ $nav('student.home') }}">
            <span class="ta-navic c-lime"><svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M3 10.5 12 3l9 7.5V21h-6v-6H9v6H3z"></path></svg></span>
            Arena Home
        </a>
        <a href="{{ route('student.learn') }}" class="{{ $nav('student.learn') }}">
            <span class="ta-navic c-cyan"><svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M2 4h6a4 4 0 0 1 4 4v13a3 3 0 0 0-3-3H2z"></path><path d="M22 4h-6a4 4 0 0 0-4 4v13a3 3 0 0 1 3-3h7z"></path></svg></span>
            เรียน
        </a>
        <a href="{{ route('student.tutor') }}" class="{{ $nav('student.tutor') }}">
            <span class="ta-navic c-blue"><svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M21 12a8 8 0 0 1-11.6 7.1L4 20l1-4.6A8 8 0 1 1 21 12z"></path></svg></span>
            ติวกับพี่โต้
        </a>
        <a href="{{ route('student.quiz') }}" class="{{ $nav('student.quiz') }}">
            <span class="ta-navic c-orange"><svg class="i" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9"></circle><circle cx="12" cy="12" r="5"></circle><circle cx="12" cy="12" r="1"></circle></svg></span>
            สนามสอบ
        </a>
        <a href="{{ route('student.quests') }}" class="{{ $nav('student.quests') }}">
            <span class="ta-navic c-amber"><svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 21V4h11l-2 4 2 4H5"></path></svg></span>
            ภารกิจ
        </a>
        <a href="{{ route('student.report') }}" class="{{ $nav('student.report') }}">
            <span class="ta-navic c-green"><svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M4 20V10M10 20V4M16 20v-7M22 20H2"></path></svg></span>
            รายงานพลัง
        </a>
        <a href="{{ route('student.hof') }}" class="{{ $nav('student.hof') }}">
            <span class="ta-navic c-gold"><svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M8 21h8M12 17v4M7 4h10v5a5 5 0 0 1-10 0z"></path><path d="M17 5h3v2a3 3 0 0 1-3 3M7 5H4v2a3 3 0 0 0 3 3"></path></svg></span>
            Hall of Fame
        </a>
        <a href="{{ route('student.shop') }}" class="{{ $nav('student.shop') }}">
            <span class="ta-navic c-pink"><svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M3 9h18l-1.5 11h-15z"></path><path d="M8 9V6a4 4 0 0 1 8 0v3"></path></svg></span>
            ร้าน TOTON
        </a>
        <a href="{{ route('student.settings') }}" class="{{ $nav('student.settings') }}">
            <span class="ta-navic c-silver"><svg class="i" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="3"></circle><path d="M12 2v3M12 19v3M2 12h3M19 12h3M4.9 4.9l2.1 2.1M17 17l2.1 2.1M4.9 19.1 7 17M17 7l2.1-2.1"></path></svg></span>
            ตั้งค่า
        </a>
    </nav>

    <div class="ta-sidecard" style="margin-top: 24px;">
        <svg viewBox="0 0 60 60" width="60" height="60" aria-hidden="true" style="position: absolute; right: -6px; top: -6px; opacity: 0.35;"><path d="M10 34 l20 -14 l20 14 v8 l-20 -14 l-20 14 z" fill="currentColor"></path><path d="M10 50 l20 -14 l20 14 v8 l-20 -14 l-20 14 z" fill="currentColor"></path></svg>
        <div style="font-family: var(--font-display); font-weight: 800; font-size: 22px; line-height: 28px;">LV {{ $student->level ?? 18 }}</div>
        <div class="caption" style="opacity: 0.85;">{{ $student->tier_label ?? 'TOTO Challenger' }}</div>
        <div class="ta-xp" style="margin: 10px 0 6px;"><i style="width: {{ $student->xp_pct ?? '62%' }};"></i></div>
        <div class="caption">อีก {{ $student->xp_left ?? 180 }} XP ขึ้นเลเวล</div>
    </div>

    {{-- Fortify's 'logout' route only ever logs out the 'web' (guardian)
         guard — students are on their own 'student' guard, so this posts
         to a dedicated route instead (see routes/web.php). --}}
    <form method="POST" action="{{ route('student.logout') }}" style="margin-top: 12px;">
        @csrf
        <button type="submit" class="ta-nav" style="width: 100%;">
            <span class="ta-navic" style="background: transparent; color: inherit;"><svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M15 4h4v16h-4M10 8l-4 4 4 4M6 12h10"></path></svg></span>
            สลับผู้ใช้
        </button>
    </form>
</aside>
