{{--
    Ported from Main.dc.html lines ~554-592 ("STUDENT: TUTOR" / Smart Socratic Tutor).
    Lives inside app/Livewire/Tutor.php (see that file — UNTESTED).

    This is the one screen that genuinely needs Livewire's streaming
    (wire:stream) per CLAUDE.md — the chat reply should type in token by
    token, not pop in all at once.

    No <x-layouts.app> wrapper here — Tutor::render() applies it via the
    view ->layout() macro instead (full-page Livewire components can't
    have a root <html> element; Livewire needs a single trackable root
    element to attach wire:id to).
--}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 16px; align-items: start;">
        <section class="ta-glass ta-col" style="padding: 24px; gap: 18px; grid-column: span 2; min-width: 0;">

            @foreach ($messages as $m)
                @if ($m['role'] === 'student')
                    <div class="ta-msg ta-msg--me">
                        <div class="ta-avatar" style="width: 36px; height: 36px; font-size: 13px;">{{ auth('student')->user()?->initials ?? 'กล้า' }}</div>
                        <div class="ta-col" style="gap: 8px; align-items: flex-end;">
                            @if (($m['type'] ?? 'text') === 'image')
                                <div class="ta-card" style="padding: 12px 16px; background: var(--bg-300);">
                                    <div class="caption ta-muted">รูปโจทย์ · อ่านด้วย OCR</div>
                                    <div style="font-family: var(--font-mono); font-size: 18px; margin-top: 4px;">{{ $m['ocr_text'] ?? '' }}</div>
                                </div>
                            @endif
                            <div class="ta-bubble">{{ $m['content'] }}</div>
                        </div>
                    </div>
                @else
                    <div class="ta-msg ta-msg--toto">
                        <svg class="ta-toto" viewBox="0 0 40 40" aria-hidden="true"><polygon points="12,2 28,2 38,12 38,28 28,38 12,38 2,28 2,12" fill="var(--green-deep)" stroke="var(--lime-text)" stroke-width="2"></polygon><rect x="12" y="14" width="5" height="10" rx="2" fill="var(--lime)"></rect><rect x="23" y="14" width="5" height="10" rx="2" fill="var(--lime)"></rect></svg>
                        <div>
                            @if (!empty($m['hint_label']))
                                <div class="ta-hint">{{ $m['hint_label'] }}</div>
                            @endif
                            <div class="ta-bubble" {{ !empty($m['solved']) ? 'style=border-color:var(--lime-text)' : '' }}>{{ $m['content'] }}</div>
                            @if (!empty($m['solved']))
                                <div class="ta-row" style="gap: 8px; margin-top: 8px;">
                                    <span class="ta-chip ta-chip--lime ta-num">+{{ $m['xp'] ?? 40 }} XP</span>
                                    <span class="ta-chip ta-chip--coin">ภารกิจสำเร็จ +{{ $m['toton'] ?? 30 }} TOTON</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            @endforeach

            {{-- streaming placeholder for the reply currently being generated --}}
            <div class="ta-msg ta-msg--toto" wire:loading.flex wire:target="sendMessage" style="display: none;">
                <svg class="ta-toto" viewBox="0 0 40 40" aria-hidden="true"><polygon points="12,2 28,2 38,12 38,28 28,38 12,38 2,28 2,12" fill="var(--green-deep)" stroke="var(--lime-text)" stroke-width="2"></polygon><rect x="12" y="14" width="5" height="10" rx="2" fill="var(--lime)"></rect><rect x="23" y="14" width="5" height="10" rx="2" fill="var(--lime)"></rect></svg>
                <div class="ta-bubble" wire:stream="reply">{{ $streamingReply ?? '' }}</div>
            </div>

            <div style="border-top: 1px solid var(--line); padding-top: 16px;">
                @if (!empty($quickReplies))
                    <div class="ta-row" style="gap: 8px; margin-bottom: 10px;">
                        @foreach ($quickReplies as $qr)
                            <button type="button" class="ta-chip {{ $qr['primary'] ?? false ? 'ta-chip--lime' : '' }}" wire:click="sendMessage(@js($qr['text']))" style="padding: 6px 12px; font-size: 13px;">{{ $qr['text'] }}</button>
                        @endforeach
                    </div>
                @endif
                <form wire:submit="sendMessage(input)" class="ta-row" style="gap: 8px; flex-wrap: nowrap;" x-data="{ input: '' }">
                    <button type="button" class="ta-btn ta-btn--secondary" aria-label="ถ่ายรูปโจทย์" @click="$refs.photo.click()">
                        <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7h3l2-3h6l2 3h3v13H4z"></path><circle cx="12" cy="13" r="4"></circle></svg>
                    </button>
                    <input type="file" accept="image/*" x-ref="photo" wire:model="photo" class="sr-only" style="position: absolute; left: -9999px;">
                    <label style="flex: 1; min-width: 0;">
                        <span class="caption ta-muted" style="position: absolute; left: -9999px;">พิมพ์คำตอบ</span>
                        <input type="text" x-model="input" placeholder="พิมพ์คำตอบหรือวิธีคิดของน้อง…" style="width: 100%; box-sizing: border-box; min-height: 48px; border: 1px solid var(--line-strong); border-radius: var(--radius-md); background: var(--bg-200); color: var(--ink); padding: 12px 16px; font: inherit;">
                    </label>
                    <button type="submit" class="ta-btn ta-btn--primary" aria-label="ส่ง">
                        <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M4 12 20 4l-6 16-3-7z"></path></svg>
                    </button>
                </form>
            </div>
        </section>

        <aside class="ta-glass ta-col" style="padding: 20px; gap: 12px;">
            <div class="ta-sh" style="margin: 0;"><h3>บันไดคำใบ้</h3></div>
            @foreach ($this->ladderSteps as $i => $st)
                <div class="ta-card ta-row" style="padding: 12px 14px; gap: 10px; border-color: {{ $i === $hintStage ? 'var(--lime-text)' : 'var(--line)' }}; background: {{ $i < $hintStage ? 'var(--lime-soft)' : 'var(--bg-200)' }};">
                    <span class="ta-chip {{ $i <= $hintStage ? 'ta-chip--lime' : '' }}">{{ $i + 1 }}</span>
                    <span class="small">{{ $st }}</span>
                </div>
            @endforeach
            <div class="ta-card">
                <div class="caption ta-muted">ถ้าแก้ได้เองโดยไม่เปิดเฉลย</div>
                <div class="ta-row" style="gap: 8px; margin-top: 6px;"><span class="ta-chip ta-chip--lime ta-num">+40 XP</span><span class="ta-chip ta-chip--coin">+30 TOTON</span></div>
            </div>
            <div class="ta-card ta-row" style="justify-content: space-between;">
                <div><div class="caption ta-muted">Hint Token</div><span class="ta-num">{{ $hintTokens ?? 2 }} อัน</span></div>
                <button type="button" class="ta-btn ta-btn--secondary ta-btn--sm" wire:click="useHintToken" {{ ($hintTokens ?? 0) < 1 ? 'disabled' : '' }}>ใช้เปิดขั้น 3</button>
            </div>
        </aside>
    </div>
