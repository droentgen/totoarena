@php
    $nav = fn (string $route) => request()->routeIs($route) ? 'ta-nav is-on' : 'ta-nav';
@endphp

{{-- Ported from Main.dc.html lines ~416-429 ("sidebar: parent") --}}
<aside class="ta-side" style="flex: 1 1 232px; box-sizing: border-box;">
    <div class="ta-logo"><b>TOTO</b> Arena</div>
    <div class="ta-muted" style="font-size: 12px; padding: 0 12px 16px; margin-top: -16px;">Parent Console</div>

    <nav aria-label="เมนูผู้ปกครอง" style="display: flex; flex-direction: column; gap: 4px;">
        <a href="{{ route('parent.dashboard') }}" class="{{ $nav('parent.dashboard') }}">
            <span class="ta-navic c-lime"><svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M3 10.5 12 3l9 7.5V21h-6v-6H9v6H3z"></path></svg></span>
            ภาพรวม &amp; กิจกรรม
        </a>
        <a href="{{ route('parent.content') }}" class="{{ $nav('parent.content') }}">
            <span class="ta-navic c-cyan"><svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M3 6h6l2 2h10v12H3z"></path></svg></span>
            คลังเนื้อหา &amp; ข้อสอบ
        </a>
        <a href="{{ route('parent.sourcing') }}" class="{{ $nav('parent.sourcing') }}">
            <span class="ta-navic c-blue"><svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3l1.8 4.7L18.5 9l-4.7 1.8L12 15.5l-1.8-4.7L5.5 9l4.7-1.3z"></path><path d="M5 18h14M8 21h8"></path></svg></span>
            สร้างสรุป &amp; คิวตรวจ
        </a>
        <a href="{{ route('parent.rewards') }}" class="{{ $nav('parent.rewards') }}">
            <span class="ta-navic c-pink"><svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M3 9h18v4H3zM5 13h14v8H5zM12 9v12"></path></svg></span>
            ร้านรางวัล
        </a>
        <a href="{{ route('parent.settings') }}" class="{{ $nav('parent.settings') }}">
            <span class="ta-navic c-silver"><svg class="i" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="3"></circle><path d="M12 2v3M12 19v3M2 12h3M19 12h3M4.9 4.9l2.1 2.1M17 17l2.1 2.1M4.9 19.1 7 17M17 7l2.1-2.1"></path></svg></span>
            ตั้งค่า
        </a>
    </nav>

    <form method="POST" action="{{ route('logout') }}" style="margin-top: 16px;">
        @csrf
        <button type="submit" class="ta-nav" style="width: 100%;">
            <span class="ta-navic" style="background: transparent; color: inherit;"><svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M15 4h4v16h-4M10 8l-4 4 4 4M6 12h10"></path></svg></span>
            สลับผู้ใช้
        </button>
    </form>
</aside>
