{{--
    Ported from Main.dc.html lines ~453-488 ("STUDENT: HOME" / Arena Home).
    TODO: all values below come from the authenticated Student + Game Engine
    (XPEvent/Level/Wallet/Quest, see 02-architecture.md) — currently hardcoded
    to match the mockup's example data so the page is reviewable as-is.
--}}
<x-layouts.app role="student" title="Arena Home" subtitle="">
    @php
        $board = $board ?? [
            ['rank' => 1, 'rc' => 'ta-rc r1', 'av' => 'พลอย', 'name' => 'พลอย', 'tier' => 'TOTO Vanguard', 'xp' => '18,920', 'rowCls' => ''],
            ['rank' => 2, 'rc' => 'ta-rc r2', 'av' => 'กล้า', 'name' => 'กล้าหาญ (คุณ)', 'tier' => 'TOTO Challenger', 'xp' => '12,480', 'rowCls' => 'is-me'],
            ['rank' => 3, 'rc' => 'ta-rc r3', 'av' => 'ปัน', 'name' => 'ปัน', 'tier' => 'TOTO Challenger', 'xp' => '11,050', 'rowCls' => ''],
            ['rank' => 4, 'rc' => '', 'av' => 'ก่อน', 'name' => 'ตัวเอง (สัปดาห์ก่อน)', 'tier' => 'Ghost Rival', 'xp' => '10,600', 'rowCls' => 'is-ghost'],
        ];
    @endphp

    <div class="ta-col" style="gap: 24px;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 24px; align-items: end;">
            <div>
                <h1 class="ta-greet" style="font-size: clamp(32px, 4vw, 44px); line-height: 1.25;">
                    สวัสดี {{ $student->nickname ?? 'กล้าหาญ' }}!<br>
                    <em>ตอนนี้คุณคือ Level {{ $student->level ?? 18 }}</em><br>
                    <em>{{ $student->tier_label ?? 'TOTO Challenger' }}</em>
                </h1>
                <p class="ta-muted" style="margin: 12px 0 0; font-size: 18px; line-height: 30px;">
                    รักษาจังหวะไว้ อีกนิดเดียวจะปลดล็อก <b style="color: var(--ink);">Boss Battle บทที่ 3</b> แล้ว
                </p>
            </div>
            <div class="ta-glass" style="padding: 20px 24px;">
                <div class="ta-row" style="justify-content: space-between;">
                    <span class="body-strong ta-lime">ความคืบหน้า Level {{ ($student->level ?? 18) + 1 }}</span>
                    <span class="ta-num" style="font-weight: 700;">{{ $student->xp_in_level ?? 450 }} / 630 XP</span>
                </div>
                <div class="ta-xp ta-xp--lg" style="margin: 12px 0 8px;"><i style="width: {{ $student->xp_pct ?? '71%' }};"></i></div>
                <div class="caption ta-muted" style="text-align: right;">อีก {{ $student->xp_left ?? 180 }} XP ถึงเลเวลถัดไป</div>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
            <div class="ta-istat">
                <div class="ta-istat-top"><span class="ta-ic-circle"><svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M8 21h8M12 17v4M7 4h10v5a5 5 0 0 1-10 0z"></path></svg></span><span class="ta-ovl">Level</span></div>
                <div class="ta-istat-v">{{ $student->level ?? 18 }}</div>
                <div class="small ta-muted">{{ $student->tier_label ?? 'TOTO Challenger' }}</div>
            </div>
            <div class="ta-istat">
                <div class="ta-istat-top"><span class="ta-ic-circle is-green"><svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 13l7-7 7 7"></path><path d="M5 20l7-7 7 7"></path></svg></span><span class="ta-ovl">Total XP</span></div>
                <div class="ta-istat-v">{{ number_format($student->total_xp ?? 12480) }}</div>
                <div class="small ta-lime">+{{ $student->xp_today ?? 150 }} วันนี้</div>
            </div>
            <div class="ta-istat">
                <div class="ta-istat-top"><span class="ta-ic-circle is-coin"><svg class="i" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9"></circle><path d="M9 9h6M12 9v7"></path></svg></span><span class="ta-ovl">TOTON</span></div>
                <div class="ta-istat-v ta-coin">{{ number_format($student->toton_balance ?? 2480) }}</div>
                <div class="small ta-muted">แลกรางวัลได้ {{ $redeemableCount ?? 2 }} รายการ</div>
            </div>
            <div class="ta-istat">
                <div class="ta-istat-top"><span class="ta-ic-circle is-warn"><svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2s5 5 5 10a5 5 0 0 1-10 0c0-2 1-3.5 2-4.5 0 2 1 3 2 3 0-3-1-5 1-8.5z"></path></svg></span><span class="ta-ovl">Streak</span></div>
                <div class="ta-istat-v">{{ $student->streak_days ?? 12 }} วัน</div>
                <div class="small" style="color: var(--warning);">ไฟยังลุกอยู่ อย่าให้ดับ!</div>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 16px; align-items: start;">
            <section class="ta-glass" style="overflow: hidden;">
                <div class="ta-row" style="justify-content: space-between; padding: 18px 20px;">
                    <span class="heading" style="font-family: var(--font-display);">TOTO Hall of Fame</span>
                    <a href="{{ route('student.hof') }}" class="ta-btn ta-btn--ghost ta-btn--sm" style="border: 0; text-decoration: none;">ดูทั้งหมด</a>
                </div>
                <div style="overflow-x: auto;">
                    <table class="ta-lbt">
                        <thead><tr><th class="ta-ovl">Rank</th><th class="ta-ovl">Player</th><th class="ta-ovl" style="text-align: right;">XP</th></tr></thead>
                        <tbody>
                            @foreach ($board as $r)
                                <tr class="{{ $r['rowCls'] }}">
                                    <td><span class="{{ $r['rc'] }}">{{ $r['rank'] }}</span></td>
                                    <td>
                                        <div class="ta-row" style="gap: 12px; flex-wrap: nowrap;">
                                            <div class="ta-avatar" style="width: 36px; height: 36px; font-size: 12px;">{{ $r['av'] }}</div>
                                            <div><div class="body-strong" style="line-height: 20px;">{{ $r['name'] }}</div><span class="caption ta-muted">{{ $r['tier'] }}</span></div>
                                        </div>
                                    </td>
                                    <td class="ta-num" style="font-weight: 700; text-align: right;">{{ $r['xp'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="ta-col" style="gap: 16px;">
                <div class="ta-glass" style="padding: 20px;">
                    <div class="ta-ovl">ภารกิจวันนี้</div>
                    <div style="font-family: var(--font-display); font-weight: 800; font-size: 36px; line-height: 44px;">
                        {{ $questsDone ?? 2 }}<span class="ta-muted" style="font-size: 20px;"> / 3</span>
                    </div>
                    <p class="small ta-muted" style="margin: 8px 0 16px;">เหลือ: ติวกับพี่โต้จนแก้เองได้ 1 โจทย์</p>
                    <a href="{{ route('student.tutor') }}" class="ta-btn ta-btn--primary" style="width: 100%; justify-content: center; text-decoration: none;">ไปติวกับพี่โต้ (+30 TOTON)</a>
                </div>
                <div class="ta-glass" style="padding: 20px;">
                    <div class="ta-row" style="gap: 10px; margin-bottom: 12px;">
                        <svg class="ta-toto" viewBox="0 0 40 40" aria-hidden="true"><polygon points="12,2 28,2 38,12 38,28 28,38 12,38 2,28 2,12" fill="var(--green-deep)" stroke="var(--lime-text)" stroke-width="2"></polygon><rect x="12" y="14" width="5" height="10" rx="2" fill="var(--lime)"></rect><rect x="23" y="14" width="5" height="10" rx="2" fill="var(--lime)"></rect></svg>
                        <span class="heading" style="font-family: var(--font-display);">พี่โต้แนะนำ</span>
                    </div>
                    <div class="small">เมื่อวานติดเรื่อง <b>สมการที่มีเศษส่วน</b> พี่เตรียมโจทย์อุ่นเครื่อง D2 ไว้ 5 ข้อแล้ว</div>
                    <a href="{{ route('student.quiz') }}" class="ta-btn ta-btn--secondary ta-btn--sm" style="margin-top: 12px; text-decoration: none;">เริ่มอุ่นเครื่อง</a>
                </div>
            </section>
        </div>
    </div>
</x-layouts.app>
