<?php

namespace App\Livewire;

use Livewire\Component;

/**
 * UNTESTED SCAFFOLDING — no PHP runtime available to verify.
 * Ported from Main.dc.html lines ~822-828 ("สร้างข้อสอบให้ลูก"), extended
 * with the AI/manual/mixed build-mode toggle decided for this project
 * (see ../../../CLAUDE.md and 01-system-flow.md pipeline ③).
 */
class ExamBuilder extends Component
{
    public string $buildMode = 'ai_auto'; // ai_auto | manual_pick | mixed

    public array $topics = ['สมการเชิงเส้น', 'โจทย์ปัญหา', 'เลขยกกำลัง'];
    public array $selectedTopics = ['สมการเชิงเส้น', 'โจทย์ปัญหา'];
    public string $difficulty = 'D3';

    public int $itemCount = 20;
    public int $durationMinutes = 30;
    public int $mcqPercent = 70;

    public string $dueAt = 'อา. 25 ต.ค. 20:00';
    public int $bonusToton = 200;

    public bool $sent = false;

    public function toggleTopic(string $topic): void
    {
        if (in_array($topic, $this->selectedTopics, true)) {
            $this->selectedTopics = array_values(array_diff($this->selectedTopics, [$topic]));
        } else {
            $this->selectedTopics[] = $topic;
        }
    }

    public function assign(): void
    {
        // TODO: persist an ExamTemplate(build_mode, topics, difficulty, …)
        // per 02-architecture.md. For ai_auto/mixed, enqueue a GenerationJob
        // (Item Generator, 01-system-flow.md pipeline ③) on the queue — do
        // NOT generate synchronously in this request. For manual_pick,
        // attach the Items the guardian selected in the item bank instead
        // (no GenerationJob, no 2-layer AI quality check).
        $this->sent = true;
    }

    public function render()
    {
        return view('livewire.exam-builder');
    }
}
