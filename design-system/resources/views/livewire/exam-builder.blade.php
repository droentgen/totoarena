{{--
    Ported from Main.dc.html lines ~822-828 ("สร้างข้อสอบให้ลูก").
    Lives inside app/Livewire/ExamBuilder.php (see that file — UNTESTED).

    Adds the build-mode toggle from CLAUDE.md / 01-system-flow.md pipeline ③
    (not in the original mockup, which only had the AI-auto path) — the
    difficulty/topic chip pickers only make sense for ai_auto / mixed.
--}}
<section class="ta-glass ta-col" style="padding: 20px; gap: 14px;">
    <div class="ta-sh" style="margin: 0;"><h3>สร้างข้อสอบให้ลูก</h3></div>

    <div>
        <div class="caption ta-muted">วิธีสร้าง</div>
        <div class="ta-row" style="gap: 6px; margin-top: 4px;" role="radiogroup" aria-label="วิธีสร้างข้อสอบ">
            <button type="button" class="ta-chip {{ $buildMode === 'ai_auto' ? 'ta-chip--lime' : '' }}" wire:click="$set('buildMode', 'ai_auto')" role="radio" aria-checked="{{ $buildMode === 'ai_auto' ? 'true' : 'false' }}">AI สุ่มอัตโนมัติ</button>
            <button type="button" class="ta-chip {{ $buildMode === 'manual_pick' ? 'ta-chip--lime' : '' }}" wire:click="$set('buildMode', 'manual_pick')" role="radio" aria-checked="{{ $buildMode === 'manual_pick' ? 'true' : 'false' }}">เลือกจากคลังเอง</button>
            <button type="button" class="ta-chip {{ $buildMode === 'mixed' ? 'ta-chip--lime' : '' }}" wire:click="$set('buildMode', 'mixed')" role="radio" aria-checked="{{ $buildMode === 'mixed' ? 'true' : 'false' }}">ผสม (AI ร่าง แก้เอง)</button>
        </div>
    </div>

    @if ($buildMode === 'manual_pick')
        <div class="ta-card" style="background: var(--bg-300); border-color: var(--line-strong);">
            <div class="small">เลือกข้อจากคลังข้อสอบของบทนี้โดยตรง ข้ามการตรวจคุณภาพ 2 ชั้นของ AI เพราะเป็นข้อที่คุณคัดมาแล้ว</div>
            <a href="{{ route('parent.content.item-bank') }}" class="ta-btn ta-btn--secondary ta-btn--sm" style="margin-top: 10px;">เปิดคลังข้อสอบ</a>
        </div>
    @else
        <div>
            <div class="caption ta-muted">หัวข้อ</div>
            <div class="ta-row" style="gap: 6px; margin-top: 4px;">
                @foreach ($topics as $t)
                    <button type="button" class="ta-chip {{ in_array($t, $selectedTopics) ? 'ta-chip--lime' : '' }}" wire:click="toggleTopic('{{ $t }}')">{{ $t }}{{ in_array($t, $selectedTopics) ? ' ✓' : '' }}</button>
                @endforeach
            </div>
        </div>

        <div>
            <div class="caption ta-muted">ระดับความยาก (แตะเพื่อเลือก)</div>
            <div class="ta-row" style="gap: 6px; margin-top: 4px;">
                @foreach (['D1', 'D2', 'D3', 'D4', 'D5'] as $d)
                    <button type="button" class="ta-chip {{ $difficulty === $d ? 'ta-chip--lime' : '' }}" wire:click="$set('difficulty', '{{ $d }}')" aria-pressed="{{ $difficulty === $d ? 'true' : 'false' }}" style="padding: 6px 12px; font-size: 13px;">{{ $d }}</button>
                @endforeach
            </div>
        </div>
    @endif

    <div>
        <div class="caption ta-muted">จำนวนข้อ · เวลา · รูปแบบ</div>
        <div class="ta-row" style="gap: 8px; margin-top: 4px;">
            <span class="ta-pillstat">{{ $itemCount }} ข้อ</span>
            <span class="ta-pillstat">{{ $durationMinutes }} นาที</span>
            <span class="ta-pillstat">ปรนัย {{ $mcqPercent }}% · เขียน {{ 100 - $mcqPercent }}%</span>
        </div>
    </div>

    <div>
        <div class="caption ta-muted">กำหนดส่ง · รางวัล</div>
        <div class="ta-row" style="gap: 8px; margin-top: 4px;">
            <span class="ta-pillstat">{{ $dueAt }}</span>
            <span class="ta-pillstat ta-coin">+{{ $bonusToton }} TOTON</span>
        </div>
    </div>

    @if (!$sent)
        <button type="button" class="ta-btn ta-btn--primary ta-btn--sm" wire:click="assign" style="align-self: flex-start;">{{ $buildMode === 'manual_pick' ? 'มอบหมาย' : 'สร้างและมอบหมาย' }}</button>
    @else
        <div class="ta-card" style="background: var(--lime-soft); border-color: var(--lime-text); padding: 12px 16px;">
            <span class="body-strong ta-lime">✓ มอบหมายแล้ว</span>
            <div class="small">น้องกล้าหาญจะเห็นเป็น "ภารกิจจากผู้ปกครอง" ทันที ({{ $difficulty }} · {{ $itemCount }} ข้อ)</div>
        </div>
    @endif
</section>
