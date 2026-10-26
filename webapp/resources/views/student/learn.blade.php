{{--
    Ported from Main.dc.html lines ~489-553 ("STUDENT: LEARN" / Knowledge Hub).
    TODO: $keyIdeas / $sources / breadcrumb chips / flashcards come from
    StudyMaterial + Citation (see 02-architecture.md and 07-knowledge-sourcing.md).
    Citation popover and tab switching are page-local UI state → Alpine only,
    no Livewire needed here (content itself is server-rendered, not live).
--}}
<x-layouts.app role="student" title="เรียน" subtitle="Knowledge Hub">
    @php
        $keyIdeas = $keyIdeas ?? [
            ['n' => 1, 't' => 'สมการเชิงเส้นตัวแปรเดียวมีรูปทั่วไปคือ ax + b = 0 โดย a ≠ 0', 'hot' => true, 'cites' => [1]],
            ['n' => 2, 't' => 'ใช้สมบัติการเท่ากัน: ทำสิ่งเดียวกันกับทั้งสองข้างของสมการเสมอ', 'hot' => false, 'cites' => [2]],
            ['n' => 3, 't' => 'ย้ายข้ามเครื่องหมาย = ต้องเปลี่ยนเครื่องหมายเป็นตรงข้ามทุกครั้ง', 'hot' => true, 'cites' => [1, 3]],
            ['n' => 4, 't' => 'โจทย์ปัญหา: กำหนดตัวแปร → ตั้งสมการ → แก้ → ตรวจคำตอบกับโจทย์เดิม', 'hot' => false, 'cites' => [2]],
            ['n' => 5, 't' => 'คูณ/หารทั้งสองข้างด้วยจำนวนเดียวกัน (ที่ไม่ใช่ 0) สมการยังสมมูลกัน', 'hot' => false, 'cites' => [2]],
            ['n' => 6, 't' => 'ตรวจคำตอบด้วยการแทนค่า x กลับเข้าสมการเดิม ถ้าสองข้างเท่ากันคือถูก', 'hot' => false, 'cites' => [3]],
        ];
        $sources = $sources ?? [
            1 => ['tier' => 'ชีทโรงเรียน', 'title' => 'ชีทคณิต ม.2 บทที่ 3 หน้า 12–15', 'support' => 'นิยามสมการเชิงเส้นตัวแปรเดียวและตัวอย่างโจทย์', 'meta' => 'อัปโหลดโดยผู้ปกครอง · 3 ต.ค. 2569'],
            2 => ['tier' => 'T1', 'title' => 'หนังสือเรียนคณิตศาสตร์ ม.2 เล่ม 1 (สสวท.)', 'support' => 'สมบัติการเท่ากันและขั้นตอนการแก้สมการ', 'meta' => 'สสวท. · ดึงเมื่อ 1 ต.ค. 2569'],
            3 => ['tier' => 'T1', 'title' => 'ตัวชี้วัดหลักสูตรแกนกลาง สาระจำนวนและพีชคณิต', 'support' => 'ขอบเขตเนื้อหาที่ตรงตัวชี้วัด ม.2', 'meta' => 'สพฐ. · ดึงเมื่อ 1 ต.ค. 2569'],
        ];
    @endphp

    <div
        class="ta-col"
        style="gap: 20px;"
        x-data="{ tab: 'summary', activeCite: null, flipped: false }"
    >
        <div class="ta-row" style="gap: 8px;">
            <span class="ta-chip">คณิตศาสตร์</span>
            <span class="ta-chip">ม.2</span>
            <span class="ta-chip">เทอม 1</span>
            <span class="ta-chip ta-chip--lime">บทที่ 3 สมการเชิงเส้นตัวแปรเดียว</span>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 16px; align-items: start;">
            <section class="ta-glass ta-col" style="padding: 24px; gap: 16px;">
                <div class="ta-row" style="justify-content: space-between; gap: 12px;">
                    <div class="ta-seg" role="tablist" aria-label="รูปแบบสรุป">
                        <button type="button" class="ta-seg-b" :class="{ 'is-on': tab === 'summary' }" @click="tab = 'summary'" role="tab" :aria-selected="tab === 'summary'">แก่นสำคัญ</button>
                        <button type="button" class="ta-seg-b" :class="{ 'is-on': tab === 'key' }" @click="tab = 'key'" role="tab" :aria-selected="tab === 'key'">รายละเอียด</button>
                        <button type="button" class="ta-seg-b" :class="{ 'is-on': tab === 'cheat' }" @click="tab = 'cheat'" role="tab" :aria-selected="tab === 'cheat'">Cheat sheet</button>
                    </div>
                    <span class="ta-chip" style="color: var(--info);">
                        <svg class="i i-sm" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3 3 8l9 5 9-5z"></path><path d="m3 13 9 5 9-5"></path></svg>
                        ผสม · ชีทโรงเรียน + แหล่งภายนอก
                    </span>
                </div>

                {{-- tab: แก่นสำคัญ --}}
                <div class="ta-col" style="gap: 16px;" x-show="tab === 'summary'">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px;">
                        <div class="ta-card" style="background: var(--lime); color: var(--on-lime); border-color: transparent; grid-column: 1 / -1; text-align: center; font-family: var(--font-display); font-weight: 700; font-size: 20px; padding: 16px;">สมการเชิงเส้นตัวแปรเดียว</div>
                        <div class="ta-card" style="background: var(--bg-300); border-color: var(--line-strong); padding: 16px;"><div class="body-strong">รูปทั่วไป</div><div class="small ta-muted">ax + b = 0 โดย a ≠ 0</div></div>
                        <div class="ta-card" style="background: var(--bg-300); border-color: var(--line-strong); padding: 16px;"><div class="body-strong">สมบัติการเท่ากัน</div><div class="small ta-muted">บวก ลบ คูณ หาร ทั้งสองข้างด้วยจำนวนเดียวกัน</div></div>
                        <div class="ta-card" style="background: var(--bg-300); border-color: var(--line-strong); padding: 16px;"><div class="body-strong">ย้ายข้าง</div><div class="small ta-muted">ย้ายข้ามเครื่องหมาย = ให้เปลี่ยนการกระทำเป็นตรงข้าม</div></div>
                        <div class="ta-card" style="background: var(--bg-300); border-color: var(--line-strong); padding: 16px;"><div class="body-strong">โจทย์ปัญหา</div><div class="small ta-muted">กำหนดตัวแปร → ตั้งสมการ → แก้ → ตรวจคำตอบ</div></div>
                    </div>
                    <div class="ta-card" style="background: var(--bg-300); border-color: var(--line-strong);">
                        <div class="ta-hint">จุดที่คนพลาดบ่อย</div>
                        <div class="body">ย้าย <b>−3</b> ข้ามเครื่องหมาย = ต้องกลายเป็น <b>+3</b> เสมอ ลองนึกว่า "บวก 3 ทั้งสองข้าง" จะไม่สับสน</div>
                    </div>
                </div>

                {{-- tab: รายละเอียด (key ideas + citations) --}}
                <div class="ta-col" style="gap: 10px;" x-show="tab === 'key'">
                    <p class="small ta-muted" style="margin: 0;">แก่นสำคัญ {{ count($keyIdeas) }} ข้อของบทนี้ อ่านจบใน 2 นาที · กดตัวเลขท้ายข้อเพื่อดูว่ามาจากแหล่งไหน</p>
                    @foreach ($keyIdeas as $k)
                        <div class="ta-card ta-row" style="background: var(--bg-300); border-color: var(--line-strong); padding: 12px 16px; gap: 12px; flex-wrap: nowrap; align-items: flex-start;">
                            <span class="ta-rc" style="flex: none;">{{ $k['n'] }}</span>
                            <div class="body" style="flex: 1; min-width: 0;">
                                {{ $k['t'] }}
                                @foreach ($k['cites'] as $c)
                                    <button type="button" class="cite" :class="{ 'is-on': activeCite === {{ $c }} }" @click="activeCite = (activeCite === {{ $c }} ? null : {{ $c }})" aria-label="แหล่งอ้างอิง {{ $c }}" :aria-pressed="activeCite === {{ $c }}">{{ $c }}</button>
                                @endforeach
                                @if ($k['hot'])
                                    <span class="ta-chip" style="color: var(--warning);">ออกสอบบ่อย</span>
                                @endif
                            </div>
                        </div>
                    @endforeach

                    @foreach ($sources as $n => $src)
                        <div class="ta-card ta-col" style="gap: 6px; border-color: var(--info);" role="status" aria-live="polite" x-show="activeCite === {{ $n }}">
                            <div class="ta-row" style="justify-content: space-between;"><span class="ta-hint" style="margin: 0;">แหล่งอ้างอิง [{{ $n }}]</span><span class="ta-chip">{{ $src['tier'] }}</span></div>
                            <div class="body-strong">{{ $src['title'] }}</div>
                            <div class="small">{{ $src['support'] }}</div>
                            <div class="caption ta-muted">{{ $src['meta'] }}</div>
                        </div>
                    @endforeach

                    <div class="ta-card" style="border-style: dashed; border-color: var(--line-strong);">
                        <div class="ta-hint" style="color: var(--warning);">ชีทของโรงเรียนอธิบายต่างออกไป</div>
                        <div class="small">ชีทใช้คำว่า "ย้ายข้าง" ส่วนหนังสือเรียนใช้ "สมบัติการเท่ากัน" — เป็นเรื่องเดียวกัน ตอบในห้องเรียนให้ยึดตามที่ครูสอนเป็นหลัก</div>
                    </div>
                </div>

                {{-- tab: Cheat sheet --}}
                <div class="ta-col" style="gap: 12px;" x-show="tab === 'cheat'">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 12px;">
                        <div class="ta-card" style="padding: 16px;"><div class="ta-ovl">สูตร · นิยาม</div><div class="ta-num" style="font-size: 18px; margin: 6px 0;">ax + b = 0, a ≠ 0</div><div class="small ta-muted">คำตอบ x = −b ÷ a</div></div>
                        <div class="ta-card" style="padding: 16px;"><div class="ta-ovl">ขั้นแก้สมการ</div><div class="small" style="margin-top: 6px;">1 กระจาย/ตัดวงเล็บ · 2 รวมพจน์ที่มี x ไว้ข้างเดียว · 3 ถอดตัวทำหลังสุดก่อน · 4 แทนค่ากลับ</div></div>
                        <div class="ta-card" style="padding: 16px;"><div class="ta-ovl">กับดักที่เจอบ่อย</div><div class="small" style="margin-top: 6px;">ลืมเปลี่ยนเครื่องหมายเมื่อย้ายข้าง · ลืมหารสัมประสิทธิ์ · คูณไม่ครบทุกพจน์เมื่อกำจัดเศษส่วน</div></div>
                    </div>
                    <button type="button" class="ta-btn ta-btn--secondary ta-btn--sm" style="align-self: flex-start;" onclick="window.print()">
                        <svg class="i i-sm" viewBox="0 0 24 24" aria-hidden="true"><path d="M6 9V3h12v6M6 18H4v-7h16v7h-2M8 14h8v7H8z"></path></svg>
                        พิมพ์ / บันทึกเป็น PDF
                    </button>
                </div>

                <div class="caption ta-muted" style="border-top: 1px solid var(--line); padding-top: 12px;">
                    แหล่งที่มา: [1] ชีทคณิต ม.2 บทที่ 3 หน้า 12–15 (ชีทโรงเรียน) · [2] หนังสือเรียนคณิตศาสตร์ ม.2 เล่ม 1 (สสวท. · T1) · [3] ตัวชี้วัดหลักสูตรแกนกลาง สาระจำนวนและพีชคณิต (สพฐ. · T1) · [4] Khan Academy ภาษาไทย (T2)
                </div>
            </section>

            <section class="ta-col" style="gap: 16px;">
                <button type="button" class="ta-card ta-flash" @click="flipped = !flipped" aria-label="พลิกการ์ดคำศัพท์" style="padding: 32px 24px; text-align: center; min-height: 200px; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 8px; background: var(--bg-200);">
                    <span class="caption ta-muted">Flashcard 7 / 24 · <span x-text="flipped ? 'ด้านหลัง — กดเพื่อดูคำถาม' : 'ด้านหน้า — กดเพื่อดูคำตอบ'"></span></span>
                    <span style="font-family: var(--font-display); font-size: 30px; line-height: 40px; font-weight: 700;" x-text="flipped ? 'x = −b ÷ a' : 'linear equation'"></span>
                    <span class="small ta-muted" x-text="flipped ? 'สูตรคำตอบของสมการเชิงเส้นตัวแปรเดียว' : 'สมการเชิงเส้น (ตัวแปรเดียว)'"></span>
                </button>
                <div class="ta-row">
                    <button type="button" class="ta-btn ta-btn--secondary ta-btn--sm" @click="flipped = !flipped">ยังไม่แม่น</button>
                    <button type="button" class="ta-btn ta-btn--primary ta-btn--sm" @click="flipped = !flipped">จำได้แล้ว</button>
                </div>

                <div class="ta-glass" style="padding: 20px;">
                    <div class="ta-sh"><h3>เช็กความเข้าใจ</h3></div>
                    <div class="small ta-muted" style="margin-bottom: 12px;">5 ข้อ · D1–D2 · ประมาณ 4 นาที</div>
                    <a href="{{ route('student.quiz') }}" class="ta-btn ta-btn--primary" style="width: 100%; justify-content: center; text-decoration: none;">ทำแบบทดสอบบทนี้</a>
                </div>

                <div class="ta-glass ta-col" style="padding: 20px; gap: 8px;">
                    <span class="ta-ovl">บทถัดไป</span>
                    <div class="body-strong">บทที่ 4 พหุนาม</div>
                    <div class="small ta-muted">{{ $nextChapterStatus ?? 'ยังไม่มีสรุป — ขอให้ผู้ปกครองสร้างให้ได้เลย' }}</div>
                    @if (($nextChapterState ?? 'can') === 'can')
                        <form method="POST" action="{{ route('student.learn.request-summary') }}">
                            @csrf
                            <button type="submit" class="ta-btn ta-btn--secondary ta-btn--sm" style="align-self: flex-start;">ขอสรุปบทนี้</button>
                        </form>
                    @elseif (($nextChapterState ?? '') === 'sent')
                        <span class="ta-chip" style="color: var(--warning); align-self: flex-start;">ส่งคำขอถึงผู้ปกครองแล้ว</span>
                    @elseif (($nextChapterState ?? '') === 'ready')
                        <span class="ta-chip ta-chip--lime" style="align-self: flex-start;">✓ สรุปพร้อมแล้ว</span>
                    @endif
                </div>

                <div class="ta-glass ta-row" style="padding: 16px 20px;">
                    <span class="ta-lime" style="display: inline-flex;"><svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M21 12a8 8 0 0 1-11.6 7.1L4 20l1-4.6A8 8 0 1 1 21 12z"></path></svg></span>
                    <div class="small">ยังงงตรงไหน? <a href="{{ route('student.tutor') }}" class="ta-btn ta-btn--ghost ta-btn--sm" style="border: 0; padding: 0 4px; min-height: 0; text-decoration: none;">ถามพี่โต้</a> โดยอ้างถึงบทนี้ได้เลย</div>
                </div>
            </section>
        </div>
    </div>
</x-layouts.app>
