{{--
    Ported from Main.dc.html lines ~595-633 ("STUDENT: QUIZ" / Arena Quiz,
    Boss Battle). Lives inside app/Livewire/Quiz.php (see that file — UNTESTED).
    Timer display is Alpine (client countdown from a server timestamp);
    actual time-limit enforcement must happen server-side on submit.
--}}
<x-layouts.app role="student" title="สนามสอบ" subtitle="Boss Battle">
    <div class="ta-col" style="gap: 16px;">
        <div class="ta-glass ta-row" style="padding: 14px 20px; justify-content: space-between;">
            <div>
                <div class="body-strong">Boss Battle · บทที่ 3 สมการเชิงเส้น</div>
                <div class="caption ta-muted">ข้อ {{ $questionIndex }} จาก {{ $totalQuestions }} · ถูก {{ $correctCount }}</div>
            </div>
            <div class="ta-row" style="gap: 8px;">
                <span class="ta-pillstat">COMBO ×{{ $combo }}</span>
                <span class="ta-pillstat">ความยาก <b class="ta-lime">{{ $difficulty }}</b></span>
                <span class="ta-pillstat" style="color: var(--warning);" x-data="{ end: {{ $deadlineTimestamp }} * 1000, left: '--:--' }" x-init="setInterval(() => { const s = Math.max(0, Math.round((end - Date.now())/1000)); left = String(Math.floor(s/60)).padStart(2,'0') + ':' + String(s%60).padStart(2,'0') }, 1000)" x-text="left"></span>
            </div>
        </div>

        <div class="ta-xp"><i style="width: {{ (int) (($questionIndex / max($totalQuestions, 1)) * 100) }}%;"></i></div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 24px; align-items: start;">
            <section class="ta-col" style="gap: 14px;">
                <div class="ta-row" style="gap: 8px;">
                    <span class="ta-chip">{{ $item['format'] ?? 'ปรนัย' }}</span>
                    <span class="ta-chip">{{ $item['bloom'] ?? 'ประยุกต์ (Apply)' }}</span>
                    <span class="ta-chip">แนวข้อสอบสาธิต</span>
                </div>
                <div style="font-size: 20px; line-height: 34px;">{!! $item['stem'] !!}</div>
                <div class="ta-col" style="gap: 10px;" role="radiogroup" aria-label="ตัวเลือกคำตอบ">
                    @foreach ($item['options'] as $o)
                        @php
                            $cls = 'ta-opt';
                            if ($state !== 'answering') {
                                if ($o['key'] === $item['correct_key']) $cls .= ' is-right';
                                elseif ($o['key'] === $selected) $cls .= ' is-wrong';
                            } elseif ($selected === $o['key']) {
                                $cls .= ' is-sel';
                            }
                        @endphp
                        <button type="button" class="{{ $cls }}" wire:click="select('{{ $o['key'] }}')" {{ $state !== 'answering' ? 'disabled' : '' }} role="radio" aria-checked="{{ $selected === $o['key'] ? 'true' : 'false' }}">
                            <span class="ta-opt-k">{{ $o['key'] }}</span><span>{{ $o['text'] }}</span>
                            @if ($state !== 'answering' && $o['key'] === $item['correct_key'])
                                <span class="ta-opt-tag" style="color: var(--lime-text);">คำตอบที่ถูก</span>
                            @endif
                        </button>
                    @endforeach
                </div>
                @if ($state === 'answering')
                    <button type="button" class="ta-btn ta-btn--primary" wire:click="submit" {{ !$selected ? 'disabled' : '' }} style="align-self: flex-start;">ส่งคำตอบ</button>
                @endif
            </section>

            <aside class="ta-col" style="gap: 16px;">
                @if ($state === 'right')
                    <div class="ta-glass ta-col" style="padding: 20px; gap: 10px; border-color: var(--lime-text); box-shadow: var(--glow-lime);">
                        <div class="ta-row" style="justify-content: space-between;"><span class="body-strong ta-lime">✓ ถูกต้อง! ถูก {{ $correctCount }} ข้อติด</span><span class="ta-chip ta-chip--lime ta-num">+{{ $lastXp ?? 38 }} XP</span></div>
                        <div class="small ta-muted">{{ $lastXpBreakdown ?? '25 XP (D3) +50% ครั้งแรก · COMBO ×1.2' }} → ข้อต่อไประบบจะดันขึ้น <b class="ta-lime">{{ $nextDifficulty ?? 'D4' }}</b></div>
                    </div>
                @elseif ($state === 'wrong')
                    <div class="ta-glass ta-col" style="padding: 20px; gap: 10px; border-color: var(--danger);">
                        <span class="body-strong" style="color: var(--danger);">✕ ยังไม่ใช่ — ไม่เป็นไรนะ</span>
                        <div class="small ta-muted">COMBO รีเซ็ต · ข้อต่อไประบบจะถอยมา <b>{{ $nextDifficulty ?? 'D2' }}</b> และเปิดการ์ดทบทวน "{{ $remedialTopic ?? 'การตั้งสมการจากโจทย์' }}"</div>
                    </div>
                @endif

                @if ($state !== 'answering')
                    {{-- shown for both right and wrong — the mockup reveals the
                         step-by-step solution either way, per README.md's Socratic
                         ladder step 4 (shown after an attempt, not gated on correctness) --}}
                    <div class="ta-col" style="gap: 16px;">
                        <div class="ta-glass ta-col" style="padding: 20px; gap: 8px;">
                            <div class="ta-hint">วิธีคิด (Step-by-Step)</div>
                            @foreach ($item['solution_steps'] as $i => $step)
                                <div class="small"><b>{{ $i + 1 }}.</b> {{ $step }}</div>
                            @endforeach
                            @if (!empty($item['common_mistake_note']))
                                <div class="caption ta-muted" style="margin-top: 4px;">{{ $item['common_mistake_note'] }}</div>
                            @endif
                        </div>
                        <button type="button" class="ta-btn ta-btn--primary" wire:click="nextQuestion" style="justify-content: center;">ข้อต่อไป</button>
                    </div>
                @else
                    <div class="ta-glass" style="padding: 20px;">
                        <div class="ta-hint">ติดอยู่ใช่ไหม?</div>
                        <p class="small ta-muted" style="margin: 4px 0 12px;">ขอคำใบ้จากพี่โต้ได้ แต่ข้อนี้จะไม่ได้โบนัส "ถูกครั้งแรก"</p>
                        <a href="{{ route('student.tutor') }}" class="ta-btn ta-btn--secondary ta-btn--sm" style="text-decoration: none;">ขอคำใบ้ขั้นที่ 1</a>
                    </div>
                @endif
            </aside>
        </div>
    </div>
</x-layouts.app>
