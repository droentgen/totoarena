{{--
    Ported from Main.dc.html lines ~835-876 ("PARENT: SUMMARY GENERATION &
    REVIEW QUEUE"). Lives inside app/Livewire/ContentSourcing.php (see that
    file — UNTESTED).

    Extended with mode E "เขียนเอง" per 07-knowledge-sourcing.md (not in the
    original mockup, which only had modes A–D) — when selected, the
    scope/source-tier/job-progress UI (which only applies to AI modes) is
    replaced by a plain content editor that publishes immediately, no AI
    job and no fact-check queue involved.
--}}
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: 16px; align-items: start;">
    <section class="ta-glass ta-col" style="padding: 20px; gap: 16px;">
        <div class="ta-sh" style="margin: 0;"><h3>สร้างสรุปใหม่</h3><span class="caption ta-muted ta-num">ใช้ไป {{ $quotaUsed }} / {{ $quotaLimit }} ครั้งเดือนนี้</span></div>

        <div>
            <div class="caption ta-muted">เริ่มจาก</div>
            <div class="ta-col" style="gap: 8px; margin-top: 6px;" role="radiogroup" aria-label="โหมดการสร้างสรุป">
                @foreach ($modes as $m)
                    <button type="button" class="ta-opt {{ $mode === $m['key'] ? 'is-sel' : '' }}" wire:click="$set('mode', '{{ $m['key'] }}')" role="radio" aria-checked="{{ $mode === $m['key'] ? 'true' : 'false' }}">
                        <span class="ta-opt-k">{{ $m['key'] }}</span>
                        <span><span class="body-strong">{{ $m['label'] }}</span><span class="small ta-muted" style="display: block;">{{ $m['desc'] }}</span></span>
                    </button>
                @endforeach
            </div>
        </div>

        @if ($mode === 'E')
            {{-- mode E: write it yourself, no AI, no review queue --}}
            <label class="au-field">หัวข้อ
                <input class="au-input" type="text" wire:model="manualTitle" placeholder="เช่น บทที่ 4 พหุนาม">
            </label>
            <label class="au-field">เนื้อหา (แก่นสำคัญ สูตร ตัวอย่างโจทย์ ฯลฯ)
                <textarea class="au-input" rows="8" wire:model="manualContent" placeholder="พิมพ์หรือวางเนื้อหาที่นี่…" style="min-height: 160px; resize: vertical;"></textarea>
            </label>
            <button type="button" class="ta-btn ta-btn--primary ta-btn--sm" wire:click="publishManual" style="align-self: flex-start;">เผยแพร่ทันที</button>
        @else
            @if ($mode === 'C')
                <label class="au-field">ลิงก์บทความ / PDF / วิดีโอ YouTube
                    <input class="au-input" type="url" wire:model="sourceUrl" placeholder="https://">
                </label>
            @endif

            <div>
                <div class="caption ta-muted">ขอบเขต</div>
                <div class="ta-row" style="gap: 6px; margin-top: 4px;">
                    <span class="ta-chip">คณิตศาสตร์</span><span class="ta-chip">ม.2</span><span class="ta-chip">เทอม 1</span><span class="ta-chip ta-chip--lime">บทที่ 4 พหุนาม</span>
                </div>
            </div>

            <div>
                <div class="caption ta-muted">แหล่งที่อนุญาตให้ค้น (แตะเพื่อเปิด/ปิด)</div>
                <div class="ta-row" style="gap: 6px; margin-top: 4px;">
                    @foreach (['T1', 'T2', 'T3'] as $t)
                        <button type="button" class="ta-chip {{ in_array($t, $enabledTiers) ? 'ta-chip--lime' : '' }}" wire:click="toggleTier('{{ $t }}')" aria-pressed="{{ in_array($t, $enabledTiers) ? 'true' : 'false' }}" style="padding: 6px 12px; font-size: 13px;">{{ $t }}</button>
                    @endforeach
                </div>
                <div class="caption ta-muted" style="margin-top: 6px;">{{ $tierNote }}</div>
            </div>

            <div>
                <div class="caption ta-muted">ผลลัพธ์</div>
                <div class="ta-row" style="gap: 6px; margin-top: 4px;">
                    <span class="ta-chip ta-chip--lime">แก่นสำคัญ ✓</span><span class="ta-chip ta-chip--lime">สรุป 1 หน้า ✓</span><span class="ta-chip ta-chip--lime">Flashcards ✓</span><span class="ta-chip">Cheat sheet</span><span class="ta-chip">Mind Map</span>
                </div>
            </div>

            @if ($jobState === 'idle')
                <button type="button" class="ta-btn ta-btn--primary ta-btn--sm" wire:click="run" style="align-self: flex-start;">สร้างสรุป</button>
            @elseif ($jobState === 'running')
                <div class="ta-card ta-col" style="gap: 10px; background: var(--bg-300); border-color: var(--line-strong);" role="status" aria-live="polite">
                    @foreach ($jobSteps as $i => $label)
                        <div class="job-step {{ $i < $jobStepIndex ? 'done' : ($i === $jobStepIndex ? 'now' : '') }}">
                            <b>{{ $i < $jobStepIndex ? '✓' : $i + 1 }}</b><span>{{ $label }}</span>
                        </div>
                    @endforeach
                </div>
            @elseif ($jobState === 'done')
                <div class="small ta-muted">สร้างเสร็จแล้ว — ดูในคิวตรวจด้านขวาเพื่ออนุมัติก่อนเผยแพร่</div>
            @endif
        @endif
    </section>

    <section class="ta-col" style="gap: 16px;">
        <div class="ta-glass ta-col" style="padding: 20px; gap: 12px;">
            <div class="ta-sh" style="margin: 0;"><h3>คิวตรวจ</h3><span class="ta-chip">{{ count($reviewQueue) }} รอตรวจ</span></div>

            @if ($childRequestPending)
                <div class="ta-card ta-row" style="justify-content: space-between; gap: 12px;">
                    <div><div class="body-strong">น้องกล้าหาญขอสรุป บทที่ 4 พหุนาม</div><div class="small ta-muted">ขอจาก Knowledge Hub · เมื่อสักครู่</div></div>
                    <button type="button" class="ta-btn ta-btn--secondary ta-btn--sm" wire:click="startFromChildRequest">สร้างให้ (โหมด B)</button>
                </div>
            @endif

            @foreach ($reviewQueue as $idx => $item)
                <div class="ta-card ta-col" style="gap: 10px; border-color: var(--warning);">
                    <div class="body-strong">{{ $item['title'] }}</div>
                    <div class="small ta-muted">{{ $item['citation_summary'] }}</div>
                    @if (!empty($item['conflict_note']))
                        <div class="ta-row" style="gap: 8px; flex-wrap: nowrap; align-items: flex-start;">
                            <span class="ta-chip" style="color: var(--warning); flex: none;"><svg class="i i-sm" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 21V4h11l-2 4 2 4H5"></path></svg>แหล่งไม่ตรงกัน 1 จุด</span>
                            <span class="small">{{ $item['conflict_note'] }}</span>
                        </div>
                    @endif
                    <div class="ta-row" style="gap: 8px;">
                        <button type="button" class="ta-btn ta-btn--ghost ta-btn--sm" style="border: 0;">ดูตัวอย่าง</button>
                        <button type="button" class="ta-btn ta-btn--secondary ta-btn--sm" wire:click="reject({{ $idx }})">ปฏิเสธ</button>
                        <button type="button" class="ta-btn ta-btn--primary ta-btn--sm" wire:click="approve({{ $idx }})">อนุมัติและเผยแพร่</button>
                    </div>
                </div>
            @endforeach

            @if ($justPublished)
                <div class="ta-card" style="background: var(--lime-soft); border-color: var(--lime-text); padding: 12px 16px;">
                    <span class="body-strong ta-lime">✓ เผยแพร่แล้ว</span>
                    <div class="small">บทที่ 4 พหุนาม ขึ้นใน Knowledge Hub ของน้องกล้าหาญแล้ว พร้อมป้าย "จากแหล่งภายนอก" ระบบจะเช็กลิงก์ต้นทางทุก 30 วัน</div>
                </div>
            @endif

            <div class="ta-card ta-row" style="justify-content: space-between; gap: 12px;">
                <div><div class="body-strong">บทที่ 3 · Cheat sheet ก่อนสอบ</div><div class="small ta-muted">แหล่ง T1 ล้วน ไม่มีธงเตือน</div></div>
                <span class="ta-chip ta-chip--lime">อนุมัติอัตโนมัติ</span>
            </div>
        </div>

        <div class="ta-glass" style="padding: 16px 20px;">
            <div class="ta-sh"><h3>แหล่งความรู้</h3><button type="button" class="ta-btn ta-btn--ghost ta-btn--sm" style="border: 0;">+ เพิ่มแหล่ง</button></div>
            <div style="overflow-x: auto;">
                <table class="ta-table">
                    <thead><tr><th>แหล่ง</th><th>ระดับ</th><th>สถานะ</th></tr></thead>
                    <tbody>
                        <tr><td>ตัวชี้วัดหลักสูตรแกนกลาง · สพฐ.</td><td class="ta-num">T1</td><td><span class="ta-chip ta-chip--lime">เปิด</span></td></tr>
                        <tr><td>หนังสือเรียนและคู่มือ · สสวท.</td><td class="ta-num">T1</td><td><span class="ta-chip ta-chip--lime">เปิด</span></td></tr>
                        <tr><td>ประกาศและตัวอย่างข้อสอบ · ทปอ.</td><td class="ta-num">T1</td><td><span class="ta-chip ta-chip--lime">เปิด</span></td></tr>
                        <tr><td>DLTV · Khan Academy · OpenStax</td><td class="ta-num">T2</td><td><span class="ta-chip ta-chip--lime">เปิด</span></td></tr>
                        <tr><td>Wikipedia และเว็บความรู้ทั่วไป</td><td class="ta-num">T3</td><td><span class="ta-chip">ใช้เมื่อมี T1/T2 ยืนยัน</span></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>
