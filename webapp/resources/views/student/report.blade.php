{{--
    Ported from Main.dc.html lines ~650-680 ("STUDENT: REPORT" / Skill Report).
    Subject tabs are plain GET links (?subject=math|science|english).
    TODO: radar chart polygon points and $mastery come from MasteryState
    per topic (02-architecture.md) — hardcoded here to match the mockup.
--}}
<x-layouts.app role="student" title="รายงานพลัง" subtitle="Skill Report">
    @php
        $subject = request('subject', 'math');
        $mastery = $mastery ?? [
            ['name' => 'สมการเชิงเส้น', 'chip' => 'กำลังฝึก', 'chipCls' => 'ta-chip', 'pct' => '58%', 'barCls' => 'is-mid'],
            ['name' => 'การคูณไขว้เศษส่วน', 'chip' => 'อ่อน', 'chipCls' => 'ta-chip ta-chip--danger', 'pct' => '34%', 'barCls' => 'is-weak'],
            ['name' => 'เลขยกกำลัง', 'chip' => 'แข็งแรง', 'chipCls' => 'ta-chip ta-chip--lime', 'pct' => '82%', 'barCls' => ''],
            ['name' => 'อัตราส่วนและสัดส่วน', 'chip' => 'แข็งแรง', 'chipCls' => 'ta-chip ta-chip--lime', 'pct' => '76%', 'barCls' => ''],
            ['name' => 'กราฟเส้นตรง', 'chip' => 'กำลังฝึก', 'chipCls' => 'ta-chip', 'pct' => '61%', 'barCls' => 'is-mid'],
        ];
    @endphp

    <div class="ta-col" style="gap: 20px;">
        <div class="ta-seg" role="tablist" style="align-self: flex-start;">
            <a href="?subject=math" class="ta-seg-b {{ $subject === 'math' ? 'is-on' : '' }}" style="text-decoration: none;">คณิตศาสตร์</a>
            <a href="?subject=science" class="ta-seg-b {{ $subject === 'science' ? 'is-on' : '' }}" style="text-decoration: none;">วิทยาศาสตร์</a>
            <a href="?subject=english" class="ta-seg-b {{ $subject === 'english' ? 'is-on' : '' }}" style="text-decoration: none;">ภาษาอังกฤษ</a>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
            <div class="ta-istat"><span class="ta-ovl">เลเวลความสามารถ</span><div class="ta-istat-v ta-lime">{{ $gradeLevel ?? 'B+' }}</div><div class="small ta-muted">ข้อสอบโรงเรียน (D3) แม่นแล้ว</div></div>
            <div class="ta-istat"><span class="ta-ovl">ตอบถูก 30 วัน</span><div class="ta-istat-v">{{ $accuracy30d ?? '74%' }}</div><div class="small ta-lime">▲ {{ $accuracyDelta ?? '9%' }}</div></div>
            <div class="ta-istat"><span class="ta-ovl">เวลาเฉลี่ยต่อข้อ</span><div class="ta-istat-v">{{ $avgTimePerItem ?? '1:12' }}</div><div class="small ta-muted">นาที</div></div>
            <div class="ta-istat"><span class="ta-ovl">Crests</span><div class="ta-istat-v">{{ $crestCount ?? 7 }}</div><div class="small ta-muted">{{ $crestBreakdown ?? 'Gold 2 · Silver 3 · Bronze 2' }}</div></div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 16px; align-items: start;">
            <section class="ta-glass ta-col" style="padding: 20px; align-items: center;">
                <div class="ta-sh" style="align-self: stretch;"><h3>เรดาร์ทักษะ</h3></div>
                {{-- TODO: compute polygon points from live per-skill mastery scores instead of this fixture --}}
                <svg viewBox="0 0 280 280" width="280" height="280" role="img" aria-label="กราฟเรดาร์ทักษะ{{ $subjectLabel ?? 'คณิตศาสตร์' }} 6 ด้าน">
                    <polygon points="140,40 226.6,90 226.6,190 140,240 53.4,190 53.4,90" fill="none" stroke="var(--line)"></polygon>
                    <polygon points="140,90 183.3,115 183.3,165 140,190 96.7,165 96.7,115" fill="none" stroke="var(--line)"></polygon>
                    <polygon points="140,102 175.5,98.8 211,181 140,188 92.4,167.5 105.4,120" fill="var(--lime)" fill-opacity="0.22" stroke="var(--lime-text)" stroke-width="2"></polygon>
                    <text x="140" y="28" text-anchor="middle" font-size="12" fill="var(--ink-muted)">จำนวน</text>
                    <text x="236" y="88" font-size="12" fill="var(--ink-muted)">พีชคณิต</text>
                    <text x="236" y="198" font-size="12" fill="var(--ink-muted)">เรขาคณิต</text>
                    <text x="140" y="262" text-anchor="middle" font-size="12" fill="var(--ink-muted)">สถิติ</text>
                    <text x="44" y="198" text-anchor="end" font-size="12" fill="var(--ink-muted)">แก้โจทย์</text>
                    <text x="44" y="88" text-anchor="end" font-size="12" fill="var(--ink-muted)">ให้เหตุผล</text>
                </svg>
            </section>

            <section class="ta-glass ta-col" style="padding: 20px; gap: 14px;">
                <div class="ta-sh" style="margin: 0;"><h3>ความเชี่ยวชาญรายหัวข้อ</h3><span class="caption ta-muted">อ่อนสุดอยู่บน</span></div>
                @foreach ($mastery as $m)
                    <div style="display: grid; grid-template-columns: minmax(0, 1fr) 120px 44px; gap: 12px; align-items: center; font-size: 15px;">
                        <span>{{ $m['name'] }} <span class="{{ $m['chipCls'] }}">{{ $m['chip'] }}</span></span>
                        <div class="ta-mbar-t"><i class="{{ $m['barCls'] }}" style="width: {{ $m['pct'] }};"></i></div>
                        <span class="ta-num small">{{ $m['pct'] }}</span>
                    </div>
                @endforeach
                <div class="ta-card ta-row" style="background: var(--bg-300); border-color: var(--line-strong); gap: 12px;">
                    <div class="small" style="flex: 1 1 240px;"><b>พี่โต้แนะนำ:</b> รากของปัญหาคือ <b>การคูณไขว้เศษส่วน</b> (ม.1) ทบทวน 10 นาทีแล้วค่อยกลับมาเรื่องสมการ</div>
                    <a href="{{ route('student.quiz') }}" class="ta-btn ta-btn--primary ta-btn--sm" style="text-decoration: none;">เริ่มฝึก</a>
                </div>
            </section>
        </div>
    </div>
</x-layouts.app>
