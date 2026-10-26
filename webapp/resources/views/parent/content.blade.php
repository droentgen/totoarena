{{--
    Ported from Main.dc.html lines ~805-828 ("PARENT: CONTENT & EXAMS").
    Curriculum tree / upload / file table are plain Blade (simple lists,
    no interaction that needs Livewire). The exam builder on the right is
    <livewire:exam-builder /> — it needs live chip-toggling state
    (topics/difficulty/build-mode) without a page reload.
--}}
<x-layouts.app role="parent" title="คลังเนื้อหา &amp; ข้อสอบ" subtitle="">
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 16px; align-items: start;">
        <section class="ta-glass ta-col" style="padding: 16px; gap: 4px;">
            <div class="ta-sh"><h3>โครงหลักสูตร</h3></div>
            <span class="caption ta-muted" style="padding: 0 10px 8px;">คณิตศาสตร์ › ม.2 › เทอม 1</span>
            @foreach ($units ?? [
                ['label' => 'บทที่ 1 การแปลงทางเรขาคณิต', 'count' => '42 ข้อ', 'active' => false],
                ['label' => 'บทที่ 2 สมบัติของเลขยกกำลัง', 'count' => '36 ข้อ', 'active' => false],
                ['label' => 'บทที่ 3 สมการเชิงเส้น', 'count' => '58 ข้อ', 'active' => true],
                ['label' => 'บทที่ 4 พหุนาม', 'count' => 'ยังไม่มี', 'active' => false],
            ] as $unit)
                <div class="ta-nav {{ $unit['active'] ? 'is-on' : '' }}" style="font-size: 14px;">
                    {{ $unit['label'] }}<span class="caption {{ $unit['active'] ? '' : 'ta-muted' }}" style="margin-left: auto;">{{ $unit['count'] }}</span>
                </div>
            @endforeach
            <button type="button" class="ta-btn ta-btn--ghost ta-btn--sm" style="border: 0; align-self: flex-start;">+ เพิ่มบท / หัวข้อ</button>
        </section>

        <section class="ta-col" style="gap: 16px;">
            <form method="POST" action="{{ route('parent.content.upload') }}" enctype="multipart/form-data">
                @csrf
                <label class="ta-glass" style="border-style: dashed; border-color: var(--line-strong); text-align: center; padding: 28px; display: block; cursor: pointer;">
                    <span class="ta-lime" style="display: inline-flex;"><svg class="i i-lg" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 16V4M7 9l5-5 5 5M4 20h16"></path></svg></span>
                    <div class="body-strong" style="margin-top: 6px;">ลากไฟล์มาวาง หรือถ่ายรูปชีทด้วย iPad</div>
                    <div class="small ta-muted">PDF, JPG, PNG, PPTX · ระบบอ่านด้วย OCR และติดแท็กบท/หัวข้อให้อัตโนมัติ</div>
                    <input type="file" name="file" accept=".pdf,.jpg,.jpeg,.png,.pptx" class="sr-only" style="position: absolute; left: -9999px;" onchange="this.form.requestSubmit()">
                    <span class="ta-btn ta-btn--primary ta-btn--sm" style="margin-top: 12px;">เลือกไฟล์</span>
                </label>
            </form>

            <div class="ta-glass" style="padding: 16px 20px;">
                <div class="ta-sh"><h3>ไฟล์ในบทนี้</h3></div>
                <div style="overflow-x: auto;">
                    <table class="ta-table">
                        <thead><tr><th>ไฟล์</th><th>สถานะ</th></tr></thead>
                        <tbody>
                            <tr><td>ชีทคณิต ม.2 บท 3.pdf<div class="caption ta-muted">สรุป 1 · Mind Map 1 · Flashcards 24 · ข้อสอบ 58</div></td><td><span class="ta-chip ta-chip--lime">พร้อมใช้</span></td></tr>
                            <tr><td>ข้อสอบกลางภาค 2567.pdf<div class="caption ta-muted">คลังข้อสอบเก่า · ตรวจเฉลย 2 ชั้น</div></td><td><span class="ta-chip" style="color: var(--info);">กำลังสร้าง 60%</span></td></tr>
                            <tr><td>รูปกระดาน 21 ต.ค.jpg<div class="caption ta-muted">AI เดาว่าเป็น บทที่ 3 · หัวข้อ โจทย์ปัญหา</div></td><td><button type="button" class="ta-btn ta-btn--secondary ta-btn--sm">ตรวจแท็ก</button></td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <livewire:exam-builder />
    </div>
</x-layouts.app>
