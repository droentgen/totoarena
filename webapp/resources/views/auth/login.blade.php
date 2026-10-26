{{--
    Ported from Main.dc.html lines ~300-383 ("LOGIN / PROFILE PICKER" — the
    game lobby). This is a full page (no x-layouts.app shell — matches the
    mockup, which renders the lobby full-bleed with no sidebar/topbar).

    TODO (backend, not yet wired):
    - $students should come from the authenticated family's child profiles
      (Student model, see 02-architecture.md data model) — one lb-card per child.
    - PIN submit should POST to a `student.pin.verify` route (rate-limited:
      5 attempts → 15 min lock + notify guardian, per 06-registration.md).
    - "เข้าสู่ Console" should simply redirect to parent.dashboard if a
      guardian session already exists; otherwise this card shouldn't render
      as "ready" at all — send to auth.form instead.
    - "ผู้ปกครองใหม่?" link → route('auth.form')
--}}
@php
    $students = $students ?? [
        (object) ['id' => 1, 'initials' => 'กล้า', 'name' => 'กล้าหาญ', 'level' => 18, 'tier_label' => 'TOTO Challenger', 'xp_pct' => 73, 'streak_days' => 12, 'crests' => 7],
    ];
    $guardianReady = auth()->check();
@endphp

{{-- no <x-layouts.app> wrapper on purpose: full-bleed lobby, no sidebar/topbar --}}
<x-layouts.auth title="เข้าสู่ระบบ">
<div
    class="lb"
    style="font-family: 'Anuphan', system-ui, sans-serif;"
    x-data="{
        selected: null,
        pin: [],
        keys: ['1','2','3','4','5','6','7','8','9','clear','0','back'],
        press(k) {
            if (k === 'clear') { this.pin = []; return; }
            if (k === 'back') { this.pin.pop(); return; }
            if (this.pin.length < 4) this.pin.push(k);
        },
    }"
>
    <div class="lb-glow" aria-hidden="true"></div>
    <div class="lb-floor" aria-hidden="true"></div>
    <div class="lb-rings" aria-hidden="true"><div class="lb-ring r1"></div><div class="lb-ring r2"></div><div class="lb-ring r3"></div></div>
    @for ($i = 0; $i < 18; $i++)
        <span class="lb-p{{ $i % 3 === 0 ? ' alt' : '' }}" style="left: {{ rand(2, 98) }}%; animation-duration: {{ rand(4, 9) }}s; animation-delay: {{ rand(0, 5) }}s;" aria-hidden="true"></span>
    @endfor

    <div class="lb-season"><span class="lb-dot"></span>Season 1 · เทอม 2/2569 · Live</div>
    <h1 class="lb-title"><b>TOTO</b> Arena</h1>
    <p class="lb-sub" style="margin: 0;">สนามประลองความรู้ ม.1–ม.6 — เลือกผู้เล่นเพื่อเข้าสู่สนาม</p>

    <div class="lb-cards" role="radiogroup" aria-label="เลือกผู้เล่น">
        @foreach ($students as $student)
            <button
                type="button"
                class="lb-card"
                :class="{ 'is-sel': selected === 'student-{{ $student->id }}', 'is-dim': selected && selected !== 'student-{{ $student->id }}' }"
                @click="selected = (selected === 'student-{{ $student->id }}' ? null : 'student-{{ $student->id }}'); pin = []"
                role="radio"
                :aria-checked="selected === 'student-{{ $student->id }}'"
            >
                <span class="lb-frame" aria-hidden="true"></span>
                <span class="lb-inner">
                    <span class="lb-tag" x-show="selected === 'student-{{ $student->id }}'">READY</span>
                    <span class="lb-emblem">
                        <svg class="spin" viewBox="0 0 120 120" aria-hidden="true"><circle cx="60" cy="60" r="56" fill="none" stroke="#c8f53c" stroke-width="2" stroke-dasharray="18 10"></circle><circle cx="60" cy="60" r="50" fill="none" stroke="#22d47b" stroke-opacity="0.5" stroke-width="1"></circle><path d="M60 0 l5 8 h-10 z M60 120 l5 -8 h-10 z M0 60 l8 5 v-10 z M120 60 l-8 5 v-10 z" fill="#c8f53c"></path></svg>
                        <span class="core">{{ $student->initials }}</span>
                    </span>
                    <span class="lb-name">{{ $student->name }}</span>
                    <span class="lb-rank">LV {{ $student->level }} · {{ $student->tier_label }}</span>
                    <span class="lb-stats">
                        <span class="lb-stat"><span>XP</span><span class="lb-bar"><i style="width: {{ $student->xp_pct }}%;"></i></span><span>{{ $student->xp_pct }}%</span></span>
                        <span class="lb-stat"><span>STREAK</span><span class="lb-bar"><i style="width: 40%;"></i></span><span>{{ $student->streak_days }}d</span></span>
                        <span class="lb-stat"><span>CRESTS</span><span class="lb-bar"><i style="width: 28%;"></i></span><span>{{ $student->crests }}</span></span>
                    </span>
                    <span class="ta-chip" style="background: rgba(200,245,60,0.12); color: #c8f53c;">PLAYER · นักเรียน</span>
                </span>
            </button>
        @endforeach

        @if ($guardianReady)
            <button
                type="button"
                class="lb-card"
                :class="{ 'is-sel': selected === 'parent', 'is-dim': selected && selected !== 'parent' }"
                @click="selected = (selected === 'parent' ? null : 'parent'); pin = []"
                role="radio"
                :aria-checked="selected === 'parent'"
            >
                <span class="lb-frame" aria-hidden="true"></span>
                <span class="lb-inner">
                    <span class="lb-tag" style="background: #6fcfff;" x-show="selected === 'parent'">READY</span>
                    <span class="lb-emblem">
                        <svg class="spin" viewBox="0 0 120 120" aria-hidden="true"><circle cx="60" cy="60" r="56" fill="none" stroke="#6fcfff" stroke-width="2" stroke-dasharray="6 8"></circle><circle cx="60" cy="60" r="50" fill="none" stroke="#6fcfff" stroke-opacity="0.35" stroke-width="1"></circle><path d="M60 0 l5 8 h-10 z M60 120 l5 -8 h-10 z" fill="#6fcfff"></path></svg>
                        <span class="core alt">{{ auth()->user()->initials ?? 'พ่อ' }}</span>
                    </span>
                    <span class="lb-name">ผู้ปกครอง</span>
                    <span class="lb-rank alt">Guardian · Parent Console</span>
                    <span class="lb-stats">
                        <span class="lb-stat"><span>ชีทในคลัง</span><span class="lb-bar alt"><i style="width: 62%;"></i></span><span>{{ $contentCount ?? 14 }}</span></span>
                        <span class="lb-stat"><span>ข้อสอบ</span><span class="lb-bar alt"><i style="width: 80%;"></i></span><span>{{ $examCount ?? 136 }}</span></span>
                        <span class="lb-stat"><span>รออนุมัติ</span><span class="lb-bar alt"><i style="width: 15%;"></i></span><span>{{ $pendingReview ?? 1 }}</span></span>
                    </span>
                    <span class="ta-chip" style="background: rgba(111,207,255,0.12); color: #6fcfff;">ADMIN · ผู้ปกครอง</span>
                </span>
            </button>
        @endif
    </div>

    <div class="lb-panel">
        <span class="lb-hint" x-show="!selected">▲ แตะการ์ดเพื่อเลือกผู้เล่น ▲</span>

        <template x-if="selected && selected !== 'parent'">
            <div class="ta-col" style="align-items: center; gap: 14px;">
                <span class="lb-hint" style="animation: none;">ใส่ PIN 4 หลัก</span>
                <div class="lb-pin" aria-label="สถานะการใส่ PIN">
                    <template x-for="n in 4" :key="n">
                        <span :class="{ on: pin.length >= n }"></span>
                    </template>
                </div>
                <div class="lb-keys">
                    <template x-for="k in keys" :key="k">
                        <button type="button" class="lb-key" @click="press(k)" :aria-label="k === 'back' ? 'ลบ' : (k === 'clear' ? 'ล้าง' : k)" x-text="k === 'back' ? '⌫' : (k === 'clear' ? 'C' : k)"></button>
                    </template>
                </div>
                <form method="POST" action="{{ route('student.pin.verify') }}">
                    @csrf
                    <input type="hidden" name="student_id" :value="selected?.replace('student-', '')">
                    <input type="hidden" name="pin" x-bind:value="pin.join('')">
                    <button type="submit" class="lb-go" :disabled="pin.length < 4">Enter Arena</button>
                </form>
            </div>
        </template>

        <template x-if="selected === 'parent'">
            <div class="ta-col" style="align-items: center; gap: 14px;">
                <span class="lb-hint" style="animation: none; color: #6fcfff;">ยืนยันตัวตนด้วยอีเมลแล้ว</span>
                <a href="{{ route('parent.dashboard') }}" class="lb-go" style="background: #6fcfff; box-shadow: 0 0 30px rgba(111,207,255,0.55); text-decoration: none; display: inline-flex; align-items: center; justify-content: center;">เข้าสู่ Console</a>
            </div>
        </template>
    </div>

    @unless ($guardianReady)
        <a href="{{ route('auth.form') }}" class="lb-link" style="text-decoration: none; display: inline-flex; align-items: center;">ผู้ปกครองใหม่? สมัครสมาชิก หรือเข้าสู่ระบบด้วยอีเมล / Google</a>
    @endunless

    <div class="lb-foot"><span>Players online · {{ $onlineCount ?? 3 }}</span><span>Server · TH-01 · {{ $pingMs ?? 24 }} ms</span><span>v0.1 prototype</span></div>
</div>
</x-layouts.auth>
