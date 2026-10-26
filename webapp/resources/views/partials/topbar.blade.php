@php
    $student = $role === 'student' ? auth('student')->user() : null;
@endphp

{{-- Ported from Main.dc.html lines ~433-448 --}}
<header class="ta-topbar" style="flex-wrap: wrap; gap: 12px;">
    <div class="ta-row" style="gap: 12px;">
        <span class="heading" style="font-family: var(--font-display);">{{ $title }}</span>
        <span class="caption ta-muted">{{ $subtitle }}</span>
    </div>
    <div class="ta-row" style="gap: 8px;">
        @if ($role === 'student')
            <div class="ta-row" style="gap: 8px;">
                <span class="ta-pillstat">
                    <span style="color: var(--warning); display: inline-flex;"><svg class="i i-sm" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2s5 5 5 10a5 5 0 0 1-10 0c0-2 1-3.5 2-4.5 0 2 1 3 2 3 0-3-1-5 1-8.5z"></path></svg></span>
                    {{ $student->streak_days ?? 12 }} วัน
                </span>
                <span class="ta-pillstat">
                    <span class="ta-coin" style="display: inline-flex;"><svg class="i i-sm" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9"></circle><path d="M9 9h6M12 9v7"></path></svg></span>
                    <span class="ta-coin">{{ number_format($student->toton_balance ?? 2480) }}</span>
                </span>
                <span class="ta-pillstat">
                    <span class="ta-lime" style="display: inline-flex;"><svg class="i i-sm" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 13l7-7 7 7"></path><path d="M5 20l7-7 7 7"></path></svg></span>
                    LV {{ $student->level ?? 18 }}
                </span>
            </div>
        @endif

        <x-theme-toggle />

        <button class="ta-iconbtn" aria-label="การแจ้งเตือน">
            <svg class="i i-sm" viewBox="0 0 24 24" aria-hidden="true"><path d="M6 16V11a6 6 0 0 1 12 0v5l2 2H4z"></path><path d="M10 21h4"></path></svg>
        </button>
    </div>
</header>
