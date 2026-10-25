# TOTO Arena — Design System (Laravel port, staging)

เป้าหมาย: พอร์ตดีไซน์จาก `../TOTO Arena ต้นแบบระบบ-html/Main.dc.html` (ต้นแบบที่ Claude ออกแบบไว้) ให้ **เหมือนเดิมทุกพิกเซล** เข้าสู่ Laravel Blade — ไม่ใช่ออกแบบใหม่

ไฟล์ในโฟลเดอร์นี้เป็น **staging** ยังไม่อยู่ในโปรเจกต์ Laravel จริง (เครื่องนี้ยังไม่มี PHP/Composer ติดตั้ง — ดู `../CLAUDE.md`) เมื่อ scaffold โปรเจกต์ Laravel แล้ว ให้ย้ายไฟล์ตามตารางด้านล่าง

## ย้ายไปไว้ที่ไหน

| จากไฟล์นี้ | ไปที่ (ใน Laravel app) |
|---|---|
| `resources/css/*.css` | `resources/css/` (ทั้งหมด) แล้วให้ `resources/css/app.css` เป็นตัวที่ Vite entry point เรียก |
| `resources/views/components/layouts/app.blade.php` | `resources/views/components/layouts/app.blade.php` |
| `resources/views/components/theme-toggle.blade.php` | `resources/views/components/theme-toggle.blade.php` |
| `resources/views/partials/*.blade.php` | `resources/views/partials/` |

## ความแม่นยำต่อดีไซน์ต้นฉบับ (fidelity)

- `resources/css/components.css` = คัดลอกจาก `ds/totoarena/components/bundle.css` **ทุกตัวอักษร** (ไม่แก้)
- `resources/css/prototype.css` = คัดลอกจาก `<style>` block ใน `<helmet>` ของ `Main.dc.html` **ทุกตัวอักษร** (บรรทัด 17–181 ของไฟล์นั้น)
- `resources/css/laravel-adjustments.css` = ไฟล์เดียวที่ **ไม่ได้คัดลอกมา** — มีคำอธิบายในไฟล์ว่าทำไมต้องมี (เพราะเปลี่ยน `<button onClick>` ของ SPA เป็น `<a href>` จริงสำหรับแอปหลายหน้า ต้อง mirror selector `button.ta-nav` → `a.ta-nav`) ถ้าของจริงดูเพี้ยนจากต้นแบบ ให้ตรวจไฟล์นี้เป็นอันดับแรก
- Blade views: แปลง `sc-if value="{{cond}}"` → `@if($cond)`, `{{var}}` (ภาษา template ของเครื่องมือออกแบบ) → Blade `{{ $var }}` — โครงสร้าง HTML/class/inline style เดิมทั้งหมดคงไว้ตรง ๆ

## ธีม (data-theme)

สีทั้งหมดมาจาก CSS variable ที่สลับด้วย `data-theme="dark|light"` บน `.ta-root` (ดู `prototype.css`) — **ห้ามเพิ่มสีตายตัวที่ไหนทั้งนั้น** ให้ตรงกับกฎในคู่มือแบรนด์ (`../README.md` หัวข้อ "ผู้ใช้เลือกธีมเองได้"): ต้องตั้ง `data-theme` ให้ตรงตั้งแต่ server-render (จากค่าธีมที่บันทึกไว้ในโปรไฟล์ผู้ใช้) เพื่อไม่ให้จอกะพริบสีผิดตอนโหลด — ดู comment ใน `components/theme-toggle.blade.php`

Tailwind (ผ่าน `@theme` ใน `app.css`) เป็นแค่ชั้น utility เสริมสำหรับ layout/spacing ของโค้ดใหม่ — **ไม่ใช้แทนสีของดีไซน์** สีของ TOTO Arena มาจากโทเคนเท่านั้น

## Route names ที่ Blade views เหล่านี้คาดหวัง

ต้องสร้างใน `routes/web.php` ก่อนหน้าจะ render ได้จริง:

```
student.home, student.learn, student.tutor, student.quiz, student.quests,
student.report, student.hof, student.shop, student.settings
parent.dashboard, parent.content, parent.sourcing, parent.rewards, parent.settings
logout            — POST, ออกจากระบบ (ทั้งสองบทบาท)
theme.update      — PATCH, บันทึกธีมที่เลือกกับโปรไฟล์ผู้ใช้ปัจจุบัน

student.pin.verify     — POST, ตรวจ PIN แล้วสร้างเซสชันนักเรียน
auth.form              — GET, หน้าสมัคร/เข้าสู่ระบบ (รับ query ?tab=signup|login)
auth.google.redirect   — GET, เริ่ม OAuth กับ Google (Socialite)
register, login, password.request  — มาจาก laravel/fortify
verification.send-otp, verification.verify-otp  — custom (Fortify ไม่มี OTP email ให้โดยตรง)
consent.accept         — POST, บันทึกความยินยอมแล้วพาไป onboarding (Livewire component)

student.learn.request-summary  — POST, "ขอสรุปบทนี้" → เข้าคิวตรวจของผู้ปกครอง
student.quests.claim           — POST ({quest}), รับรางวัลภารกิจที่ทำสำเร็จ
student.shop.redeem            — POST, ขอแลกรางวัล → สร้าง Redemption รออนุมัติ

parent.content.upload          — POST, อัปโหลดไฟล์เข้าบทที่เลือกอยู่
parent.content.item-bank       — GET, เปิดคลังข้อสอบ (สำหรับโหมด manual_pick ใน ExamBuilder)
parent.dashboard.export        — GET, ส่งออกกิจกรรมล่าสุดเป็น CSV
parent.rewards.decide          — POST ({redemption}), อนุมัติ/ปฏิเสธคำขอแลกรางวัล

settings.preferences            — POST, บันทึก daylight-for-learn / reduced-motion
settings.google.toggle          — POST, เชื่อม/ยกเลิกเชื่อม Google (ผู้ปกครอง)
settings.two-factor.toggle      — POST, เปิด/ปิด 2FA (ผู้ปกครอง)
settings.sessions.logout-all    — POST, ออกจากทุกเครื่อง (ผู้ปกครอง)
```

## Livewire components ที่ใช้

```
<livewire:exam-builder />        — ใน parent/content.blade.php
<livewire:content-sourcing />    — ใน parent/sourcing.blade.php
```
`onboarding-wizard`, `tutor`, `quiz` เป็น full-page Livewire components — ต่อ route ตรงกับ `Route::get(...)->uses(OnboardingWizard::class)` หรือมอบให้ controller คืน view ที่มี `<livewire:onboarding-wizard />` ก็ได้ ขึ้นกับว่าต้องการ URL แยกจากกันแค่ไหน

## สถานะการพอร์ต (เทียบกับ `manifest.json`)

**พอร์ตครบทุกหน้าใน `Main.dc.html` แล้ว** (ยังไม่ทดสอบรันจริง — ไม่มี PHP ในเครื่องนี้ ดู "ข้อควรระวัง" ด้านล่าง)

| หน้า | ไฟล์ | หมายเหตุ |
|---|---|---|
| App shell | `components/layouts/app.blade.php`, `partials/sidebar-{student,parent}.blade.php`, `partials/topbar.blade.php`, `components/theme-toggle.blade.php` | Blade + Alpine |
| Login/Profile Picker | `auth/login.blade.php` | Blade + Alpine (PIN keypad, card select) |
| สมัคร/เข้าสู่ระบบ | `auth/form.blade.php` | Blade + Alpine (แท็บ) |
| ยืนยันอีเมล OTP | `auth/verify-email.blade.php` | Blade + Alpine (countdown) |
| ข้อตกลง+ความยินยอม | `auth/consent.blade.php` | Blade + Alpine |
| Onboarding (5 ขั้น) | `livewire/onboarding-wizard.blade.php` + `app/Livewire/OnboardingWizard.php` | **Livewire** |
| Arena Home | `student/home.blade.php` | Blade |
| Knowledge Hub (เรียน) | `student/learn.blade.php` | Blade + Alpine (แท็บ, citation popover, flashcard flip) |
| ติวกับพี่โต้ | `livewire/tutor.blade.php` + `app/Livewire/Tutor.php` | **Livewire** (`wire:stream` สำหรับคำตอบ AI) |
| สนามสอบ (Quiz/Boss Battle) | `livewire/quiz.blade.php` + `app/Livewire/Quiz.php` | **Livewire** |
| ภารกิจ | `student/quests.blade.php` | Blade (tabs เป็น `?tab=`) |
| รายงานพลัง | `student/report.blade.php` | Blade (tabs เป็น `?subject=`) |
| Hall of Fame | `student/hall-of-fame.blade.php` | Blade (tabs เป็น `?tab=`) |
| ร้าน TOTON | `student/shop.blade.php` | Blade |
| ตั้งค่า (ทั้งสองบทบาท) | `settings/index.blade.php` | Blade + Alpine |
| Parent Dashboard | `parent/dashboard.blade.php` | Blade |
| Parent: คลังเนื้อหา & ข้อสอบ | `parent/content.blade.php` + `livewire/exam-builder.blade.php` + `app/Livewire/ExamBuilder.php` | ต้นไม้หลักสูตร/อัปโหลดเป็น Blade · ตัวสร้างข้อสอบเป็น **Livewire** (มีตัวเลือก AI/manual_pick/mixed) |
| Parent: สร้างสรุป & คิวตรวจ | `parent/sourcing.blade.php` + `livewire/content-sourcing.blade.php` + `app/Livewire/ContentSourcing.php` | **Livewire** — โหมด A–E รวม "เขียนเอง" |
| Parent: ร้านรางวัล | `parent/rewards.blade.php` | Blade |

### ข้อควรระวัง (สำคัญ)

- **ไม่มี PHP ในเครื่องนี้** — ไฟล์ Livewire ทั้งหมด (`app/Livewire/*.php` + `resources/views/livewire/*.blade.php`) เขียนจากความเข้าใจ Livewire 3 โดยไม่ได้รันทดสอบจริง **ต้องตรวจสอบก่อนใช้งานจริง** โดยเฉพาะ `Tutor.php` ที่ใช้ `wire:stream`/`$this->stream()` — ยืนยัน API กับเวอร์ชัน Livewire ที่ติดตั้งจริงก่อน
- ทุกจุดที่มี `TODO:` ในไฟล์ยังไม่ต่อกับฐานข้อมูล/AI จริง — เป็นแค่ fixture data ให้หน้าตาตรงกับต้นแบบ
- Route names ทั้งหมดที่ใช้ยังไม่ได้ลงทะเบียนจริงใน `routes/web.php` — ดูรายการทั้งหมดด้านล่าง

บรรทัดอ้างอิงใน `Main.dc.html` สำหรับแต่ละหน้า (ไว้พอร์ตต่อ): ดู comment `<!-- ======== … ======== -->` ในไฟล์นั้น เช่น `STUDENT: HOME` บรรทัด 453, `PARENT: DASHBOARD` บรรทัด 762 ฯลฯ

## Backend: migrations + models (ตาม data model ใน `02-architecture.md`)

**40 migrations** (`database/migrations/`) + **39 Eloquent models** (`app/Models/`) + 1 custom cast (`app/Casts/Vector.php`) ครอบคลุมทุก entity ที่ระบุไว้ — Auth & Family, Curriculum, Content, Sourcing, Assessment, Learning, Game, Activity — **ยังไม่ทดสอบรันจริง** (ไม่มี PHP ในเครื่องนี้)

จุดที่ต้องรู้ก่อนรัน:

- **Migration แรก ๆ คาดว่าจะรันทับบน Laravel skeleton ที่สร้างใหม่แล้ว** — `2024_01_01_000002_add_guardian_fields_to_users_table.php` **แก้ (ALTER)** ตาราง `users` ที่ Laravel สร้างให้อยู่แล้ว ไม่ได้สร้างใหม่ — ถ้า Fortify สร้างคอลัมน์ `two_factor_*` ไว้แล้ว (ตอน publish ด้วย `--2fa`) ต้องลบส่วนซ้ำในไมเกรชันนี้ก่อนรัน
- **`password_reset_tokens` และ `sessions`** (ของ framework เอง) มาจาก migration เริ่มต้นของ Laravel อยู่แล้ว **ไม่ต้องสร้างใหม่** — ตารางกิจกรรมเข้าใช้งานของเราเปลี่ยนชื่อเป็น `device_sessions` เพื่อไม่ชนกัน
- **pgvector**: ต้องเปิด extension ก่อน (migration ของ `chunks` ทำให้อัตโนมัติด้วย `CREATE EXTENSION IF NOT EXISTS vector`) — คอลัมน์ `embedding` ใส่ด้วย raw SQL เพราะ Laravel's schema builder ไม่มี type นี้ให้ในตัว ขนาด 1536 มิติ (สมมติ embedding model แบบ OpenAI-compatible) — **เปลี่ยนก่อนใช้งานจริงถ้า embedding model ให้ขนาดต่างออกไป** เพราะเปลี่ยนทีหลังต้อง re-embed ทั้งหมด
- **`App\Casts\Vector`**: cast มือเขียนเอง (array ↔ pgvector text literal) เพราะไม่รู้ว่าจะติดตั้ง package `pgvector/pgvector-php` หรือไม่ — ถ้าติดตั้ง แนะนำให้ใช้ cast ของแพ็กเกจนั้นแทน
- **`App\Models\Response`** ชื่อชนกับ `Illuminate\Http\Response` — ต้อง alias import ถ้าใช้ทั้งคู่ในไฟล์เดียวกัน
- **`Citation`** บังคับด้วย DB CHECK constraint ว่าต้องมี `chunk_id` หรือ `source_chunk_id` อย่างใดอย่างหนึ่งเท่านั้น (ไม่ใช่ทั้งคู่ ไม่ใช่ไม่มีเลย)
- **`Student::tierLabel()`** คำนวณจาก level band เท่านั้น — ยังไม่ครบตามกฎจริงที่ต้องผ่าน Promotion Trial ด้วย (`03-gamification.md`) มี comment ไว้ในไฟล์แล้ว
- ทุก migration/model เป็น **structure เท่านั้น** ยังไม่มี business logic (XP calculation, Adaptive Engine, AI calls, quota enforcement ฯลฯ) — ใส่ไว้แค่โครงและ TODO ตามจุดที่เอกสารพูดถึง

## ขั้นต่อไป (เมื่อมี PHP/Composer พร้อม)

```bash
composer create-project laravel/laravel webapp
cd webapp
composer require livewire/livewire laravel/socialite laravel/fortify
npm install -D tailwindcss @tailwindcss/vite
php artisan livewire:publish --config
```

จากนั้นคัดลอกไฟล์จากโฟลเดอร์นี้เข้า `webapp/` ตามตารางด้านบน (รวม `database/migrations/`, `app/Models/`, `app/Casts/`, `app/Livewire/`) ตั้งค่า `.env` ให้ชี้ไป Supabase Postgres แล้วรัน:

```bash
php artisan migrate
php artisan db:seed   # ยังไม่มี seeder ให้ — ต้องเขียนเพิ่มสำหรับ subjects/grades/terms/source_domains เริ่มต้น
```

สร้าง route names ให้ครบตามรายการข้างต้นก่อนรันหน้าแรก แล้วตรวจ Livewire components ทั้ง 5 ตัวตามหัวข้อ "ข้อควรระวัง" ก่อนเชื่อถือว่าใช้งานได้จริง
