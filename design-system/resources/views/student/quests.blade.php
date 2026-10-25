{{--
    Ported from Main.dc.html lines ~635-647 ("STUDENT: QUESTS").
    Tabs are plain GET links (?tab=daily|weekly|guardian) — no client JS
    needed, this screen has no interaction that benefits from staying on
    the same page without a reload.
--}}
<x-layouts.app role="student" title="ภารกิจ" subtitle="">
    @php($tab = request('tab', 'daily'))
    <div class="ta-col" style="gap: 20px;">
        <div class="ta-row" style="justify-content: space-between;">
            <div class="ta-seg" role="tablist">
                <a href="?tab=daily" class="ta-seg-b {{ $tab === 'daily' ? 'is-on' : '' }}" style="text-decoration: none;">รายวัน</a>
                <a href="?tab=weekly" class="ta-seg-b {{ $tab === 'weekly' ? 'is-on' : '' }}" style="text-decoration: none;">รายสัปดาห์</a>
                <a href="?tab=guardian" class="ta-seg-b {{ $tab === 'guardian' ? 'is-on' : '' }}" style="text-decoration: none;">จากผู้ปกครอง</a>
            </div>
            <span class="small ta-muted">รีเซ็ตใน {{ $resetsIn ?? '9 ชม. 12 น.' }}</span>
        </div>

        <div class="ta-col" style="gap: 10px; max-width: 760px;">
            <div class="ta-quest is-done">
                <div class="ta-quest-ic"><svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12.5 10 17l9-10"></path></svg></div>
                <div><div class="body-strong">ทบทวน Flashcard คำศัพท์ Unit 3</div><div class="small ta-lime">ทำสำเร็จแล้ว</div></div>
                @if ($questClaimed ?? false)
                    <span class="ta-chip ta-chip--lime">รับแล้ว</span>
                @else
                    <form method="POST" action="{{ route('student.quests.claim', ['quest' => 1]) }}">
                        @csrf
                        <button type="submit" class="ta-btn ta-btn--primary ta-btn--sm">รับ +30</button>
                    </form>
                @endif
            </div>

            <div class="ta-quest">
                <div class="ta-quest-ic"><svg class="i" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9"></circle><circle cx="12" cy="12" r="5"></circle></svg></div>
                <div>
                    <div class="body-strong">ตอบถูก 5 ข้อติด เรื่องสมการเชิงเส้น</div>
                    <div class="ta-row" style="gap: 8px; margin-top: 6px;"><div class="ta-xp" style="width: 180px; height: 8px;"><i style="width: 60%;"></i></div><span class="small ta-muted ta-num">3/5</span></div>
                </div>
                <a href="{{ route('student.quiz') }}" class="ta-btn ta-btn--secondary ta-btn--sm" style="text-decoration: none;">ไปทำ</a>
            </div>

            <div class="ta-quest">
                <div class="ta-quest-ic"><svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M21 12a8 8 0 0 1-11.6 7.1L4 20l1-4.6A8 8 0 1 1 21 12z"></path></svg></div>
                <div><div class="body-strong">ติวกับพี่โต้จนแก้เองได้ 1 โจทย์</div><div class="small ta-muted">ภารกิจท้าทาย · +30 TOTON</div></div>
                <a href="{{ route('student.tutor') }}" class="ta-btn ta-btn--secondary ta-btn--sm" style="text-decoration: none;">ไปทำ</a>
            </div>
        </div>

        <div class="ta-glass" style="padding: 20px; max-width: 760px;">
            <div class="ta-sh"><h3>Boss Battle ที่เปิดอยู่</h3></div>
            <div class="ta-row" style="justify-content: space-between;">
                <div class="small">บทที่ 3 สมการเชิงเส้น · 15 ข้อ · 20 นาที · ชนะได้ Crest อัปเกรดเป็น Silver</div>
                <a href="{{ route('student.quiz') }}" class="ta-btn ta-btn--primary ta-btn--sm" style="text-decoration: none;">ท้าสู้</a>
            </div>
        </div>
    </div>
</x-layouts.app>
