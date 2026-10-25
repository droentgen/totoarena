<?php

namespace App\Livewire;

use Livewire\Component;

/**
 * UNTESTED SCAFFOLDING — written without a PHP runtime available to run
 * `php artisan test` or even boot the app. Review carefully, especially
 * the `finish()` persistence step (marked TODO) before relying on it.
 *
 * Ported from Main.dc.html lines ~257-305 ("step: onboarding wizard").
 * See resources/views/livewire/onboarding-wizard.blade.php for the view.
 */
class OnboardingWizard extends Component
{
    public int $step = 0;

    // step 0
    public string $familyName = '';

    // step 1
    public string $childNickname = '';
    public string $childPin = '';
    public string $childGrade = 'ม.1';

    // step 2
    public string $dailyLimit = '2 ชั่วโมง';
    public bool $curfew = false;
    public bool $lineNotify = false;

    // step 3
    public string $theme = 'system';

    // step 4
    public string $firstContentMode = 'curriculum';

    public array $stepLabels = [
        'ชื่อครอบครัว',
        'โปรไฟล์ลูก',
        'เวลาใช้งาน',
        'ธีม',
        'เนื้อหาแรก',
    ];

    public array $grades = ['ม.1', 'ม.2', 'ม.3', 'ม.4', 'ม.5', 'ม.6'];

    public array $dailyLimitOptions = ['1 ชั่วโมง', '2 ชั่วโมง', '3 ชั่วโมง', 'ไม่จำกัด'];

    public function canSkip(int $step): bool
    {
        // Only the child-profile step (06-registration.md: "ข้ามได้ยกเว้นขั้นสร้างโปรไฟล์ลูก") is required.
        return $step !== 1;
    }

    public function next(): void
    {
        if ($this->step < 4) {
            $this->step++;
        }
    }

    public function back(): void
    {
        if ($this->step > 0) {
            $this->step--;
        }
    }

    public function finish(): void
    {
        $this->validate([
            'childNickname' => 'required|string|max:50',
            'childPin' => 'required|digits_between:4,6',
        ]);

        // TODO: persist — create Family, Student (PIN hashed, see 02-architecture.md
        // Family model), guardian time-limit settings, guardian theme preference,
        // and kick off a GenerationJob (mode B) if firstContentMode === 'curriculum'
        // per 07-knowledge-sourcing.md. Then redirect to parent.dashboard.

        $this->redirect(route('parent.dashboard'), navigate: true);
    }

    public function render()
    {
        return view('livewire.onboarding-wizard');
    }
}
