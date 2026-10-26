<?php

namespace App\Livewire;

use Livewire\Component;

/**
 * UNTESTED SCAFFOLDING — no PHP runtime available to verify.
 * Ported from Main.dc.html lines ~835-876 ("PARENT: SUMMARY GENERATION &
 * REVIEW QUEUE"), extended with mode E "เขียนเอง" per
 * 07-knowledge-sourcing.md and ../../../CLAUDE.md.
 */
class ContentSourcing extends Component
{
    public string $mode = 'B'; // A | B | C | D | E

    public int $quotaUsed = 7;
    public int $quotaLimit = 30;

    public ?string $sourceUrl = null;
    public ?string $manualTitle = null;
    public ?string $manualContent = null;

    public array $enabledTiers = ['T1', 'T2'];

    public string $jobState = 'idle'; // idle | running | done
    public int $jobStepIndex = 0;
    public array $jobSteps = [];

    public bool $childRequestPending = true;
    public bool $justPublished = false;

    public array $reviewQueue = [];

    public array $modes = [
        ['key' => 'A', 'label' => 'จากชีทโรงเรียน', 'desc' => 'มีชีทครบ — ตรงกับที่ครูสอนมากที่สุด'],
        ['key' => 'B', 'label' => 'จากหลักสูตร', 'desc' => 'ยังไม่มีชีท, อ่านล่วงหน้า, ทบทวนบทที่ชีทหาย'],
        ['key' => 'C', 'label' => 'จากลิงก์ที่วาง', 'desc' => 'ครูแนะนำแหล่งเพิ่ม หรือเจอสื่อดี'],
        ['key' => 'D', 'label' => 'เติมช่องว่าง (ผสม)', 'desc' => 'ชีทไม่ครอบคลุมตัวชี้วัดบางข้อ'],
        ['key' => 'E', 'label' => 'เขียนเอง (ไม่ใช้ AI)', 'desc' => 'คุมเนื้อหา 100% เอง ไม่ต้องรอคิวตรวจ'],
    ];

    public function mount(): void
    {
        $this->reviewQueue = [[
            'title' => 'บทที่ 4 พหุนาม · แก่นสำคัญ + สรุป 1 หน้า + Flashcards 18 ใบ',
            'citation_summary' => 'อ้างอิง 6 แหล่ง: T1 ×3 · T2 ×3 · ทุกประโยคมีแหล่งรองรับ · ตัดประโยคที่ไม่มีแหล่งรองรับออก 2 ประโยค',
            'conflict_note' => 'ดีกรีของพหุนามศูนย์: หนังสือเรียน (T1) กับเว็บสถาบัน (T2) อธิบายต่างกัน ระบบใช้ตามหนังสือเรียน',
        ]];
    }

    public function getTierNoteProperty(): string
    {
        return match (true) {
            in_array('T3', $this->enabledTiers, true) => 'เปิด T3 ด้วย — ใช้ได้เมื่อ T1/T2 ยืนยันตรงกัน',
            default => 'ใช้เฉพาะแหล่งทางการและสถาบันการศึกษา',
        };
    }

    public function toggleTier(string $tier): void
    {
        if (in_array($tier, $this->enabledTiers, true)) {
            $this->enabledTiers = array_values(array_diff($this->enabledTiers, [$tier]));
        } else {
            $this->enabledTiers[] = $tier;
        }
    }

    public function startFromChildRequest(): void
    {
        $this->mode = 'B';
        $this->childRequestPending = false;
    }

    public function run(): void
    {
        // TODO: dispatch a real GenerationJob to the queue (01-system-flow.md
        // pipeline ⑧) instead of faking progress inline — this should not
        // block the request. $jobStepIndex here is a placeholder for however
        // the real job reports progress back (e.g. broadcast + Livewire poll).
        $this->jobState = 'running';
        $this->jobSteps = [
            'ค้นหาแหล่ง (allowlist)',
            'ดึงและทำความสะอาด',
            'จับคู่กับตัวชี้วัด',
            'สังเคราะห์สรุปไทย (RAG)',
            'ตรวจข้อเท็จจริง',
        ];
        $this->jobStepIndex = count($this->jobSteps); // TODO: fake "done" — replace with real progress polling
        $this->jobState = 'done';
    }

    public function publishManual(): void
    {
        // TODO: create StudyMaterial(origin: manual) directly, no
        // GenerationJob, no Citation, no ReviewItem — publish immediately
        // per 07-knowledge-sourcing.md mode E.
        $this->manualTitle = null;
        $this->manualContent = null;
    }

    public function approve(int $index): void
    {
        unset($this->reviewQueue[$index]);
        $this->reviewQueue = array_values($this->reviewQueue);
        $this->justPublished = true;
        // TODO: mark ReviewItem decision=approved, publish StudyMaterial.
    }

    public function reject(int $index): void
    {
        unset($this->reviewQueue[$index]);
        $this->reviewQueue = array_values($this->reviewQueue);
        // TODO: mark ReviewItem decision=rejected, notify requester if from a child request.
    }

    public function render()
    {
        return view('livewire.content-sourcing');
    }
}
