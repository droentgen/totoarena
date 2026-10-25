<?php

namespace App\Livewire;

use Livewire\Component;

/**
 * UNTESTED SCAFFOLDING — no PHP runtime available to verify.
 * Ported from Main.dc.html lines ~595-633 ("STUDENT: QUIZ").
 */
class Quiz extends Component
{
    public int $questionIndex = 7;
    public int $totalQuestions = 15;
    public int $correctCount = 6;
    public int $combo = 4;
    public string $difficulty = 'D3';
    public ?string $nextDifficulty = null;
    public int $deadlineTimestamp;

    public ?string $selected = null;
    public string $state = 'answering'; // answering | right | wrong | explained
    public ?int $lastXp = null;
    public ?string $lastXpBreakdown = null;
    public ?string $remedialTopic = null;

    public array $item = [];

    public function mount(): void
    {
        $this->deadlineTimestamp = now()->addMinutes(8)->addSeconds(42)->timestamp;

        // TODO: load from Item/Attempt (02-architecture.md) via the Adaptive
        // Engine (01-system-flow.md pipeline ④) instead of this fixture.
        $this->item = [
            'format' => 'ปรนัย',
            'bloom' => 'ประยุกต์ (Apply)',
            'stem' => 'ร้านค้าขายปากกาด้ามละ <b>x</b> บาท ถ้าซื้อ 3 ด้ามแล้วได้ส่วนลด 6 บาท จ่ายเงินไป 45 บาท ปากการาคาด้ามละเท่าไร?',
            'options' => [
                ['key' => 'ก', 'text' => '13 บาท'],
                ['key' => 'ข', 'text' => '15 บาท'],
                ['key' => 'ค', 'text' => '17 บาท'],
                ['key' => 'ง', 'text' => '51 บาท'],
            ],
            'correct_key' => 'ค',
            'solution_steps' => [
                'ตั้งสมการ: 3x − 6 = 45',
                'บวก 6 ทั้งสองข้าง: 3x = 51',
                'หาร 3 ทั้งสองข้าง: x = 17',
            ],
            'common_mistake_note' => 'ตัวเลือก ง (51) คือคนที่ลืมหาร 3 — กับดักที่พบบ่อย',
        ];
    }

    public function select(string $key): void
    {
        if ($this->state === 'answering') {
            $this->selected = $key;
        }
    }

    public function submit(): void
    {
        if (!$this->selected) {
            return;
        }

        $correct = $this->selected === $this->item['correct_key'];

        if ($correct) {
            $this->correctCount++;
            $this->combo++;
            $this->state = 'right';
            // TODO: real XP calc per 03-gamification.md (difficulty base, first-try
            // +50%, combo multiplier) — written to XPEvent, not computed inline here.
            $this->lastXp = 38;
            $this->lastXpBreakdown = '25 XP (D3) +50% ครั้งแรก · COMBO ×1.2';
            $this->nextDifficulty = 'D4';
        } else {
            $this->combo = 0;
            $this->state = 'wrong';
            $this->nextDifficulty = 'D2';
            $this->remedialTopic = 'การตั้งสมการจากโจทย์';
        }
    }

    public function nextQuestion(): void
    {
        $this->questionIndex++;
        $this->selected = null;
        $this->state = 'answering';
        // TODO: fetch next Item from Adaptive Engine using $this->nextDifficulty ?? $this->difficulty.
    }

    public function render()
    {
        return view('livewire.quiz');
    }
}
