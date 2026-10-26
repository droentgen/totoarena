<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;

/**
 * Ported from Main.dc.html lines ~554-592 ("STUDENT: TUTOR").
 * `$this->stream(to: ...)` + `wire:stream="reply"` verified against the
 * installed livewire/livewire ^4.4 (HandlesStreaming::stream signature
 * still accepts `$to`) — streaming itself still untested end-to-end since
 * sendMessage() doesn't call a real AI backend yet (see TODO below).
 */
class Tutor extends Component
{
    use WithFileUploads;

    public array $messages = [];

    public $photo = null;

    public string $streamingReply = '';

    public int $hintStage = 1; // 0-indexed into ladderSteps; mockup's tutor.s1/s2 states

    public int $hintTokens = 2;

    public array $quickReplies = [
        ['text' => 'ลบ 5 ทั้งสองข้าง', 'primary' => true],
        ['text' => 'เริ่มโจทย์ใหม่', 'primary' => false],
        ['text' => 'ยังไม่เข้าใจ ขอตัวอย่าง', 'primary' => false],
    ];

    public function getLadderStepsProperty(): array
    {
        return [
            'ถามนำ — ชวนสังเกตว่าโจทย์ให้อะไรมา',
            'ชี้ทิศ — แนะทิศทางการแก้ไม่ใช่คำตอบ',
            'ทำขั้นแรกให้ดู — แสดง 1 ขั้น ให้ทำต่อ',
            'เฉลยพร้อมวิธีทำ — เปิดได้หลังพยายามครบ 3 ครั้ง',
        ];
    }

    public function mount(): void
    {
        // TODO: load from TutorSession/Message (see 02-architecture.md) instead
        // of this hardcoded seed conversation (kept to match the mockup 1:1).
        $this->messages = [
            ['role' => 'student', 'type' => 'image', 'ocr_text' => '(x − 3) / 2 + 5 = 9', 'content' => 'ข้อนี้ทำยังไงครับ'],
            ['role' => 'toto', 'hint_label' => 'คำใบ้ขั้นที่ 1 · ถามนำ', 'content' => 'โจทย์ดีเลย! ลองบอกพี่หน่อยว่า x ถูกทำอะไรบ้าง ตามลำดับ ตั้งแต่ x ไปจนถึงได้ 9?'],
            ['role' => 'student', 'content' => 'ลบ 3 แล้วหาร 2 แล้วบวก 5'],
            ['role' => 'toto', 'hint_label' => 'คำใบ้ขั้นที่ 2 · ชี้ทิศ', 'content' => 'ถูกเป๊ะ! จะ "ถอด" ให้เหลือ x ต้องย้อนจากตัวสุดท้ายก่อน ตัวสุดท้ายคือบวก 5 น้องจะทำอะไรกับทั้งสองข้าง?'],
        ];
    }

    public function sendMessage(string $input): void
    {
        if (trim($input) === '') {
            return;
        }

        $this->messages[] = ['role' => 'student', 'content' => $input];

        // TODO: replace with a real call to the Claude Messages API (Http
        // facade, per CLAUDE.md) applying the 4-step Socratic hint ladder
        // (01-system-flow.md pipeline ⑤). Stream tokens into $this->streamingReply
        // via $this->stream(to: 'reply', content: $chunk) as they arrive, then
        // push the finished message into $this->messages and clear the buffer.
        $this->hintStage = min($this->hintStage + 1, 3);
    }

    public function useHintToken(): void
    {
        if ($this->hintTokens < 1) {
            return;
        }

        $this->hintTokens--;
        $this->hintStage = 3; // jump to step-by-step solver

        // TODO: per 03-gamification.md, opening the solver forfeits this item's XP.
    }

    public function render()
    {
        return view('livewire.tutor')
            ->layout('components.layouts.app', ['role' => 'student', 'title' => 'ติวกับพี่โต้', 'subtitle' => 'Smart Socratic Tutor']);
    }
}
