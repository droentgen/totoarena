{{--
    Ported from Main.dc.html lines ~257-305 ("step: onboarding wizard").
    Lives inside app/Livewire/OnboardingWizard.php (see that file — UNTESTED,
    no PHP available in this environment to run it; review before trusting).

    `sc-for list="{{ob.steps}}"` → @foreach($this->stepLabels as $i => $label)
    `{{st.cls}}` → au-step classes derived from $step
--}}
<div class="au ta-dots">
    <header class="au-top">
        <div class="ta-row" style="gap: 10px;">
            <div class="ta-logo" style="padding: 0;"><b>TOTO</b> Arena</div>
            <span class="caption ta-muted">สำหรับผู้ปกครอง</span>
        </div>
        <x-theme-toggle />
    </header>

    <div class="au-wrap">
        <div class="au-card wide">
            <div class="au-steps" role="list" aria-label="ขั้นตอนตั้งค่าครอบครัว">
                @foreach ($this->stepLabels as $i => $label)
                    <div class="au-step {{ $i < $step ? 'done' : ($i === $step ? 'on' : '') }}" role="listitem">
                        <i></i><span>{{ $label }}</span>
                    </div>
                @endforeach
            </div>

            {{-- step 0: family name --}}
            @if ($step === 0)
                <div class="ta-col" style="gap: 14px;">
                    <div>
                        <h1 class="au-h">ตั้งชื่อครอบครัว</h1>
                        <p class="small ta-muted" style="margin: 4px 0 0;">ชื่อนี้แสดงบนหน้าเลือกผู้เล่น และในอีเมลรายงานประจำสัปดาห์</p>
                    </div>
                    <label class="au-field">ชื่อครอบครัว
                        <input class="au-input" type="text" wire:model="familyName" placeholder="บ้านกล้าหาญ">
                    </label>
                </div>
            @endif

            {{-- step 1: first child profile + PIN --}}
            @if ($step === 1)
                <div class="ta-col" style="gap: 14px;">
                    <div>
                        <h1 class="au-h">สร้างโปรไฟล์ลูกคนแรก</h1>
                        <p class="small ta-muted" style="margin: 4px 0 0;">น้องไม่ต้องมีอีเมล ใช้ PIN ที่ตั้งตรงนี้เข้า Arena · เพิ่มลูกคนอื่นได้ภายหลังในตั้งค่า</p>
                    </div>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 14px;">
                        <label class="au-field">ชื่อเล่น
                            <input class="au-input" type="text" wire:model="childNickname" placeholder="กล้าหาญ">
                        </label>
                        <label class="au-field">PIN 4–6 หลัก
                            <input class="au-input" type="password" wire:model="childPin" inputmode="numeric" maxlength="6" autocomplete="new-password" placeholder="••••">
                        </label>
                    </div>
                    <div>
                        <div class="au-field" style="margin-bottom: 6px;">ชั้นเรียน</div>
                        <div class="ta-row" style="gap: 6px;" role="radiogroup" aria-label="ชั้นเรียน">
                            @foreach ($this->grades as $grade)
                                <button type="button" class="ta-seg-b {{ $childGrade === $grade ? 'is-on' : '' }}" wire:click="$set('childGrade', '{{ $grade }}')" role="radio" aria-checked="{{ $childGrade === $grade ? 'true' : 'false' }}" style="padding: 8px 14px; font-size: 14px; min-height: 40px;">{{ $grade }}</button>
                            @endforeach
                        </div>
                    </div>
                    <div class="caption ta-muted">เลือกชั้นเรียนปัจจุบันของน้อง · ใส่ PIN ผิด 5 ครั้งระบบล็อก 15 นาทีและแจ้งคุณ</div>
                </div>
            @endif

            {{-- step 2: usage time limits --}}
            @if ($step === 2)
                <div class="ta-col" style="gap: 14px;">
                    <div>
                        <h1 class="au-h">เวลาใช้งาน</h1>
                        <p class="small ta-muted" style="margin: 4px 0 0;">ปรับได้ตลอดในตั้งค่า ข้ามได้ถ้ายังไม่แน่ใจ</p>
                    </div>
                    <div>
                        <div class="au-field" style="margin-bottom: 6px;">ใช้งานได้สูงสุดต่อวัน</div>
                        <div class="ta-seg" role="radiogroup" aria-label="เวลาสูงสุดต่อวัน">
                            @foreach ($this->dailyLimitOptions as $limit)
                                <button type="button" class="ta-seg-b {{ $dailyLimit === $limit ? 'is-on' : '' }}" wire:click="$set('dailyLimit', '{{ $limit }}')" role="radio" aria-checked="{{ $dailyLimit === $limit ? 'true' : 'false' }}">{{ $limit }}</button>
                            @endforeach
                        </div>
                    </div>
                    <label class="au-check">
                        <input type="checkbox" wire:model="curfew">
                        <span>ห้ามใช้หลัง 22:00 <span class="ta-muted">(ถ้าเปิดใช้นอกเวลา ระบบบันทึกและแจ้งคุณ)</span></span>
                    </label>
                    <label class="au-check">
                        <input type="checkbox" wire:model="lineNotify">
                        <span>ส่งสรุปรายสัปดาห์ทาง LINE ด้วย <span class="ta-muted">(ค่าเริ่มต้นส่งทางอีเมล)</span></span>
                    </label>
                </div>
            @endif

            {{-- step 3: guardian theme --}}
            @if ($step === 3)
                <div class="ta-col" style="gap: 14px;">
                    <div>
                        <h1 class="au-h">ธีมของคุณ</h1>
                        <p class="small ta-muted" style="margin: 4px 0 0;">เป็นค่าของผู้ปกครองเท่านั้น น้องเลือกธีมของตัวเองได้ (ค่าเริ่มต้นของน้องคือ มืด)</p>
                    </div>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 10px;" role="radiogroup" aria-label="ธีม">
                        <button type="button" class="au-pick {{ $theme === 'system' ? 'is-on' : '' }}" wire:click="$set('theme', 'system')" role="radio" aria-checked="{{ $theme === 'system' ? 'true' : 'false' }}">
                            <span class="body-strong">ตามอุปกรณ์</span><span class="small ta-muted">แนะนำสำหรับผู้ปกครอง</span>
                        </button>
                        <button type="button" class="au-pick {{ $theme === 'light' ? 'is-on' : '' }}" wire:click="$set('theme', 'light')" role="radio" aria-checked="{{ $theme === 'light' ? 'true' : 'false' }}">
                            <span class="body-strong">สว่าง · Daylight</span><span class="small ta-muted">อ่านรายงานสบายตา</span>
                        </button>
                        <button type="button" class="au-pick {{ $theme === 'dark' ? 'is-on' : '' }}" wire:click="$set('theme', 'dark')" role="radio" aria-checked="{{ $theme === 'dark' ? 'true' : 'false' }}">
                            <span class="body-strong">มืด · Arena</span><span class="small ta-muted">โทนเดียวกับของน้อง</span>
                        </button>
                    </div>
                </div>
            @endif

            {{-- step 4: first content --}}
            @if ($step === 4)
                <div class="ta-col" style="gap: 14px;">
                    <div>
                        <h1 class="au-h">เริ่มต้นเนื้อหาแรก</h1>
                        <p class="small ta-muted" style="margin: 4px 0 0;">ยังไม่มีชีทก็เริ่มได้ ระบบสร้างสรุปจากหลักสูตรและแหล่งทางการให้ แล้วให้คุณตรวจก่อน</p>
                    </div>
                    <div class="ta-col" style="gap: 10px;" role="radiogroup" aria-label="เนื้อหาแรก">
                        <button type="button" class="au-pick {{ $firstContentMode === 'curriculum' ? 'is-on' : '' }}" wire:click="$set('firstContentMode', 'curriculum')" role="radio" aria-checked="{{ $firstContentMode === 'curriculum' ? 'true' : 'false' }}">
                            <span class="body-strong">สร้างสรุปจากหลักสูตร <span class="ta-chip ta-chip--lime">แนะนำ</span></span>
                            <span class="small ta-muted">เลือกบท แล้วระบบค้นจากแหล่ง T1 (สพฐ. สสวท.) มาสรุปเป็นภาษาไทยพร้อมอ้างอิง</span>
                        </button>
                        <button type="button" class="au-pick {{ $firstContentMode === 'upload' ? 'is-on' : '' }}" wire:click="$set('firstContentMode', 'upload')" role="radio" aria-checked="{{ $firstContentMode === 'upload' ? 'true' : 'false' }}">
                            <span class="body-strong">อัปโหลดชีทของโรงเรียน</span>
                            <span class="small ta-muted">PDF รูปถ่าย หรือสไลด์ · ตรงกับที่ครูสอนที่สุด</span>
                        </button>
                    </div>
                    <div class="ta-card ta-row" style="background: var(--bg-300); border-color: var(--line-strong); gap: 12px; flex-wrap: nowrap;">
                        <span style="color: var(--info); display: inline-flex;"><svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3 4 6v6c0 5 3.5 8 8 9 4.5-1 8-4 8-9V6z"></path></svg></span>
                        <div class="small"><b>แนะนำ:</b> เปิดการยืนยันสองขั้น (แอป Authenticator) ได้ที่ ตั้งค่า › ความปลอดภัย เพื่อป้องกันบัญชีที่มีข้อมูลของลูก</div>
                    </div>
                </div>
            @endif

            <div class="ta-row" style="justify-content: space-between; border-top: 1px solid var(--line); padding-top: 16px;">
                <button type="button" class="ta-btn ta-btn--secondary ta-btn--sm" wire:click="back" {{ $step === 0 ? 'disabled' : '' }}>ย้อนกลับ</button>
                <div class="ta-row" style="gap: 8px;">
                    @if ($this->canSkip($step))
                        <button type="button" class="ta-btn ta-btn--ghost ta-btn--sm" wire:click="next" style="border: 0;">ข้ามขั้นนี้</button>
                    @endif
                    <button type="button" class="ta-btn ta-btn--primary ta-btn--sm" wire:click="{{ $step === 4 ? 'finish' : 'next' }}">{{ $step === 4 ? 'เริ่มใช้งาน' : 'ถัดไป' }}</button>
                </div>
            </div>
        </div>
    </div>
</div>
