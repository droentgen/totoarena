{{--
    Ported from Main.dc.html lines ~763-803 ("PARENT: DASHBOARD").
    TODO: $heat / $progressWeeks / $recentActivity / $gaps come from the
    Analytics Service (Event log, MasteryState — 02-architecture.md).
    All data below is fixture, matching the mockup's example numbers.
--}}
<x-layouts.app role="parent" title="ภาพรวม" subtitle="กิจกรรม">
    @php
        $heat = $heat ?? collect(['จ', 'อ', 'พ', 'พฤ', 'ศ', 'ส', 'อา'])->map(fn ($d) => [
            'd' => $d,
            'cells' => collect(range(0, 17))->map(fn ($h) => [
                'bg' => 'var(--green)',
                'op' => $h % 5 === 0 ? 0.1 : (($h + crc32($d)) % 4) / 4,
                't' => sprintf('%02d:00', 6 + $h),
            ])->all(),
        ])->all();
    @endphp

    <div class="ta-col" style="gap: 20px;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
            <div class="ta-stat"><div class="ta-stat-v">{{ $weeklyTime ?? '5 ชม. 40 น.' }}</div><div class="ta-stat-l">เวลาเรียนสัปดาห์นี้ <span class="ta-delta-up">▲ {{ $weeklyTimeDelta ?? '48 น.' }}</span></div></div>
            <div class="ta-stat"><div class="ta-stat-v">{{ $daysActive ?? '6 / 7' }}</div><div class="ta-stat-l">วันที่เข้าใช้ (streak {{ $streakDays ?? 12 }} วัน)</div></div>
            <div class="ta-stat"><div class="ta-stat-v">{{ $accuracyAvg ?? '74%' }}</div><div class="ta-stat-l">ตอบถูกเฉลี่ย <span class="ta-delta-up">▲ {{ $accuracyDelta ?? '9%' }}</span></div></div>
            <div class="ta-stat"><div class="ta-stat-v" style="color: var(--warning);">{{ $curfewBreaches ?? 1 }}</div><div class="ta-stat-l">ครั้งที่ใช้นอกเวลาที่ตั้ง (หลัง 22:00)</div></div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: 16px; align-items: start;">
            <section class="ta-glass" style="padding: 20px;">
                <div class="ta-sh"><h3>วัน–เวลาที่เข้าใช้</h3><span class="caption ta-muted">06:00–23:00</span></div>
                <div style="overflow-x: auto;">
                    <div class="ta-col" style="gap: 4px; min-width: 420px;">
                        @foreach ($heat as $row)
                            <div class="ta-row" style="gap: 4px; flex-wrap: nowrap;">
                                <span class="caption ta-muted" style="width: 24px;">{{ $row['d'] }}</span>
                                @foreach ($row['cells'] as $c)
                                    <span class="ta-heat" style="background: {{ $c['bg'] }}; opacity: {{ $c['op'] }};" title="{{ $c['t'] }}"></span>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="ta-row caption ta-muted" style="gap: 16px; margin-top: 10px;">
                    <span><span class="ta-heat" style="background: var(--green); width: 10px; height: 10px;"></span> ใช้งาน</span>
                    <span><span class="ta-heat" style="background: var(--warning); width: 10px; height: 10px;"></span> หลัง 22:00</span>
                </div>
            </section>

            <section class="ta-glass" style="padding: 20px;">
                <div class="ta-sh"><h3>ความก้าวหน้า (Mastery เฉลี่ย)</h3><span class="caption ta-muted">8 สัปดาห์</span></div>
                {{-- TODO: compute polyline points from real weekly mastery averages --}}
                <svg viewBox="0 0 520 160" width="100%" role="img" aria-label="กราฟความก้าวหน้าจาก 52% เป็น 72% ใน 8 สัปดาห์">
                    <line x1="30" x2="510" y1="140" y2="140" stroke="var(--line)"></line>
                    <line x1="30" x2="510" y1="80" y2="80" stroke="var(--line)"></line>
                    <line x1="30" x2="510" y1="20" y2="20" stroke="var(--line)"></line>
                    <text x="0" y="144" font-size="11" fill="var(--ink-muted)">40%</text>
                    <text x="0" y="84" font-size="11" fill="var(--ink-muted)">60%</text>
                    <text x="0" y="24" font-size="11" fill="var(--ink-muted)">80%</text>
                    <polyline points="30,104 99,95 167,98 236,80 304,71 373,77 441,56 510,44" fill="none" stroke="var(--green)" stroke-width="3" stroke-linejoin="round"></polyline>
                    <circle cx="510" cy="44" r="5" fill="var(--green)"></circle>
                </svg>
                <div class="ta-col" style="gap: 8px; margin-top: 12px;">
                    <div style="display: grid; grid-template-columns: 110px 1fr 70px; gap: 12px; align-items: center;"><span class="small">คณิตศาสตร์</span><div class="ta-mbar-t"><i style="width: 48%;"></i></div><span class="ta-num small">2:45 ชม.</span></div>
                    <div style="display: grid; grid-template-columns: 110px 1fr 70px; gap: 12px; align-items: center;"><span class="small">วิทยาศาสตร์</span><div class="ta-mbar-t"><i style="width: 30%;"></i></div><span class="ta-num small">1:40 ชม.</span></div>
                    <div style="display: grid; grid-template-columns: 110px 1fr 70px; gap: 12px; align-items: center;"><span class="small">ภาษาอังกฤษ</span><div class="ta-mbar-t"><i style="width: 22%;"></i></div><span class="ta-num small">1:15 ชม.</span></div>
                </div>
            </section>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: 16px; align-items: start;">
            <section class="ta-glass" style="padding: 16px 20px;">
                <div class="ta-sh"><h3>กิจกรรมล่าสุด</h3><a href="{{ route('parent.dashboard.export') }}" class="ta-btn ta-btn--ghost ta-btn--sm" style="border: 0; text-decoration: none;">ส่งออก CSV</a></div>
                <div style="overflow-x: auto;">
                    <table class="ta-table">
                        <thead><tr><th>วัน–เวลา</th><th>นาน</th><th>กิจกรรม</th><th>อุปกรณ์</th></tr></thead>
                        <tbody>
                            <tr><td class="ta-num">ศ. 23 · 19:42–20:31</td><td class="ta-num">49 น.</td><td>Boss Battle คณิต บท 3 <span class="ta-chip ta-chip--lime">14/18</span></td><td class="ta-muted">iPad</td></tr>
                            <tr><td class="ta-num">ศ. 23 · 17:05–17:22</td><td class="ta-num">17 น.</td><td>ติวกับพี่โต้ · วิทย์</td><td class="ta-muted">iPad</td></tr>
                            <tr><td class="ta-num">พฤ. 22 · 22:14–22:30</td><td class="ta-num">16 น.</td><td>Flashcards อังกฤษ <span class="ta-chip" style="color: var(--warning);">นอกเวลา</span></td><td class="ta-muted">มือถือ</td></tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="ta-glass ta-col" style="padding: 20px; gap: 10px;">
                <div class="ta-sh" style="margin: 0;"><h3>จุดที่ควรช่วยเสริม</h3></div>
                <div class="ta-row" style="justify-content: space-between;"><span class="small">คณิต · การให้เหตุผลและพิสูจน์</span><span class="ta-chip ta-chip--danger">38%</span></div>
                <div class="ta-row" style="justify-content: space-between;"><span class="small">คณิต · สมการที่มีเศษส่วน</span><span class="ta-chip ta-chip--danger">44%</span></div>
                <div class="ta-row" style="justify-content: space-between;"><span class="small">วิทย์ · แรงลัพธ์</span><span class="ta-chip" style="color: var(--warning);">55%</span></div>
                <div class="small ta-muted">ระบบตั้งภารกิจทบทวน ม.1 เรื่องเศษส่วนให้อัตโนมัติแล้ว</div>
                <a href="{{ route('parent.content') }}" class="ta-btn ta-btn--primary ta-btn--sm" style="align-self: flex-start; text-decoration: none;">สร้างชุดข้อสอบเสริมจุดอ่อน</a>
            </section>
        </div>
    </div>
</x-layouts.app>
