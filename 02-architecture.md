# โครงสร้างระบบ

## ภาพรวมสถาปัตยกรรม

```
┌──────────────────────────── CLIENT ────────────────────────────┐
│  TOTO Arena (นักเรียน)          Parent Console (ผู้ปกครอง)       │
│  Laravel Blade + Livewire + Alpine.js + Tailwind (route /parent) │
│  PWA · iPad / Desktop / Mobile                                  │
└──────────────┬──────────────────────────────┬──────────────────┘
               │ HTTPS (ปกติ + Livewire wire:stream สำหรับแชตสตรีม) │
┌──────────────▼──────────────────────────────▼──────────────────┐
│              LARAVEL APP (Routes / Controllers / Livewire)      │
│        บัญชีครอบครัว · โปรไฟล์ลูก (PIN) · สิทธิ์ผู้ปกครอง          │
└──┬──────────┬──────────┬──────────┬──────────┬──────────┬──────┘
   │          │          │          │          │          │
┌──▼───┐  ┌───▼───┐  ┌───▼───┐  ┌───▼───┐  ┌───▼───┐  ┌───▼────┐
│Content│ │ Tutor │  │ Quiz &│  │Grader │  │ Game  │  │Analytics│
│Service│ │Service│  │Adaptive│ │Service│  │Engine │  │Service  │
│ingest │ │Socratic│ │Engine │  │rubric │  │XP·coin│  │events·  │
│OCR·tag│ │hint   │  │item   │  │essay  │  │badge· │  │reports  │
│RAG    │ │ladder │  │bank   │  │       │  │quest  │  │         │
└──┬────┘ └───┬───┘  └───┬───┘  └───┬───┘  └───┬───┘  └───┬────┘
   │          └──────┬───┴──────────┘          │          │
   │          ┌──────▼──────┐                  │          │
   │          │   AI CORE   │  Claude Messages API (Http client) · Vision OCR · Embeddings
   │          └──────┬──────┘                  │          │
┌──▼─────────────────▼─────────────────────────▼──────────▼──────┐
│  PostgreSQL (ผู้ใช้ คะแนน สถิติ เกม)  ·  pgvector (เวกเตอร์ในฐานเดียวกัน) │
│  Object Storage (PDF รูปภาพ)  ·  Laravel Queue (งาน OCR/สร้างข้อสอบ)  │
└────────────────────────────────────────────────────────────────┘
```

**Stack ที่แนะนำ** (ทั้งระบบเป็น PHP — ดู `CLAUDE.md`):

| ส่วน | เครื่องมือ | เพราะอะไร |
|---|---|---|
| Framework | **Laravel** (PHP 8.3+) | ORM/Auth/Queue/Storage/Validation มาพร้อมใช้ ลด boilerplate เมื่อขยายฟีเจอร์ AI ในอนาคต |
| หน้าเว็บ (ทั้งนักเรียน+ผู้ปกครอง) | **Blade + Livewire 3** | เขียน UI โต้ตอบได้ด้วย PHP ล้วน (แชตติว, ตัวจับเวลา, แถบ XP) ไม่ต้องตั้ง build pipeline ของ SPA framework |
| CSS | **Tailwind CSS** | ใช้กับ design token ที่มีอยู่แล้วใน `tokens.json`/`tokens.css` ได้ตรง ๆ |
| JS ฝั่ง client | **Alpine.js** (มากับ Livewire) | เฉพาะ interaction เล็ก ๆ (modal, สลับธีมไม่กะพริบ) ไม่ใช่ SPA framework |
| ฐานข้อมูล | **PostgreSQL + pgvector** | เก็บข้อมูลระบบและเวกเตอร์สำหรับ RAG ในฐานเดียว ลดความซับซ้อนของ infra |
| คิวงานเบื้องหลัง | **Laravel Queue** (database → Redis เมื่อโตขึ้น) + **Horizon** (ถ้าใช้ Redis) | OCR, สร้างเนื้อหา/ข้อสอบด้วย AI เป็นงานที่ใช้เวลา ต้องรันแบบ background และดูสถานะย้อนหลังได้ |
| Realtime/สตรีม | **Livewire `wire:stream`** (แชตพี่โต้) · **Laravel Reverb** (ถ้าต้องอัปเดตข้ามผู้ใช้ เช่น Hall of Fame สด) | ทั้งคู่เป็น PHP native ไม่ต้องพึ่ง Node |
| เรียก AI | **Claude Messages API** ผ่าน Laravel `Http` facade | ไม่มี PHP SDK ทางการของ Anthropic แต่ REST ตรงไปตรงมา — ห่อเป็น `AiTutorService`/`ContentGeneratorService`/`ItemGeneratorService` เพื่อสลับโมเดล/เวอร์ชันได้ง่าย |
| Auth ผู้ปกครอง | **Laravel Fortify/Breeze** + **Socialite** (Google) | ตรงกับ `06-registration.md` (อีเมล+OTP+Google+2FA) ทันที |
| Auth นักเรียน | custom guard แยก (PIN hash) | แยกวงจรสิทธิ์จากผู้ปกครองชัดเจน |
| Storage ไฟล์ | **Laravel Filesystem** (S3-compatible) | รองรับ PDF/รูปสำหรับ OCR |

## โมดูลและความรับผิดชอบ

| โมดูล | หน้าที่ | ตรงกับความต้องการ |
|---|---|---|
| Content Service | อัปโหลด, OCR, ติดแท็ก, chunk, embed, สร้างสื่อสรุปภาษาไทย | Knowledge Hub & Exam Archive, ดึงเนื้อหาภาษาไทย |
| Knowledge Sourcing Service | รายการแหล่งที่อนุญาต (T1–T3), ค้นหาและดึงเนื้อหาภายนอก, สังเคราะห์สรุปพร้อมอ้างอิง, ตรวจข้อเท็จจริง, คิวตรวจผู้ปกครอง, เช็กลิงก์ต้นทางทุก 30 วัน | สรุปเนื้อหาสำคัญ, ประมวลความรู้จากแหล่งอื่น |
| Tutor Service | แชต Socratic, บันไดคำใบ้, Step-by-Step Solver, อ่านรูปโจทย์ | Smart Socratic Tutor |
| Quiz & Adaptive Engine | คลังข้อสอบ, สร้างข้อสอบอัตโนมัติ, เลือกข้อตาม mastery, จับเวลา | Interactive Quiz & Dynamic Difficulty, Exam Simulator |
| Grader Service | ตรวจข้อเขียนตาม Rubric, คำแนะนำการเขียน | Essay & Subjective Answer Grader |
| Game Engine | XP, เลเวล, tier, TOTON, เหรียญตรา, ภารกิจ, กระดานอันดับ, ร้านรางวัล | Gamification ทั้งหมด |
| Analytics Service | log การเข้าใช้, เวลาต่อวิชา, mastery, รายงานผู้ปกครอง, แจ้งเตือน | บอกเลเวลความสามารถ, ผู้ปกครองเช็กวัน–เวลา |
| Auth & Family | สมัครด้วยอีเมล + OTP หรือ Google (OIDC + PKCE), ผูก/ถอดวิธีเข้าสู่ระบบ, 2FA, ลืมรหัสผ่าน, เซสชัน/refresh token, บันทึกความยินยอม (PDPA), บัญชีครอบครัว, โปรไฟล์ลูก, PIN, เชื่อมเครื่องด้วย QR, สิทธิ์, เวลาจำกัด, ธีม | สมัครสมาชิก, ผู้ปกครองบริหารจัดการ |

## โครงสร้างข้อมูลหลัก (Data model)

```
Auth:   User(email, email_verified, password_hash?: Argon2id) ─ Identity(provider: password | google, provider_sub)
        AuthSession(refresh_token_hash, device, expires) · EmailOTP · PasswordReset · TwoFactor(TOTP)
        Consent(doc_type, doc_version, accepted_at, ip) · DeviceLink(student, code/QR, expires, revoked)
Family ─┬─ Guardian (User · role: owner | co-guardian · theme: dark | light | system)
        └─ Student (โปรไฟล์ลูก: ชั้น, สายการเรียน, PIN hash, เวลาจำกัด, theme, school_google_email?)
                │
Curriculum: Subject ─ Grade ─ Term ─ Unit(บท) ─ Topic(หัวข้อ) ─ Indicator(ตัวชี้วัด)
                │                                    ▲
Content: SourceFile ─ Chunk(+vector) ────────────────┤ (แท็ก)
         StudyMaterial (key ideas / summary / cheatsheet / mindmap / flashcard / example · origin: school | external | mixed | manual)
Sourcing: SourceDomain(tier T1–T3) ─ ExternalSource ─ SourceChunk(+vector)
         GenerationJob(mode A–E) · Citation(material × chunk) · ReviewItem(flags, decision)
                                                     │
Assessment: Item(ข้อสอบ: type, difficulty D1–D5, bloom, answer, explanation, rubric, source: ai_generated | manual) 
            ExamTemplate(ผู้ปกครองตั้ง: หัวข้อ, จำนวน, ความยาก, เวลา, กำหนดส่ง, build_mode: ai_auto | manual_pick | mixed)
            Attempt ─ Response(คำตอบ, ถูก/ผิด, เวลา, จำนวนคำใบ้)
Learning:   TutorSession ─ Message · MasteryState(Student × Topic: 0–100%)
Game:       XPEvent · Level · Wallet ─ CoinTxn · CrestAward · Quest ─ QuestProgress
            LeaderboardSnapshot · Reward(ร้านที่ผู้ปกครองตั้ง) ─ Redemption
Activity:   Session(เข้า–ออก, อุปกรณ์) · Event(ทุกการกระทำ)
```

## แผนผังหน้าจอ (Sitemap)

**TOTO Arena — นักเรียน**
```
เลือกโปรไฟล์ + PIN  (หรือเชื่อมเครื่องด้วย QR / Google ของโรงเรียน)
└─ Arena Home
├─ เรียน (Knowledge Hub)      วิชา › ชั้น › เทอม › บท › แก่นสำคัญ · สรุป · Cheat sheet · Mind Map · Flashcards · ขอสรุปบทนี้
├─ ติวกับพี่โต้ (Tutor)        แชต · ถ่ายรูปโจทย์ · ประวัติการติว
├─ สนามสอบ (Arena Quiz)        ฝึกรายหัวข้อ · Boss Battle (ท้ายบท) · Exam Simulator (ม.ปลาย)
│                               └─ หน้าผลสอบ + เฉลยละเอียด
├─ ภารกิจ (Quests)             รายวัน · รายสัปดาห์ · เหตุการณ์พิเศษ
├─ รายงานพลัง (Skill Report)   เลเวลรายวิชา · เรดาร์ทักษะ · จุดที่ต้องพัฒนา
├─ Hall of Fame                 รายสัปดาห์ · Apex Board (ตลอดกาล) · Ghost Rival
├─ ร้าน TOTON                   รางวัลที่ผู้ปกครองตั้ง · ไอเทมแต่งโปรไฟล์ · Hint Token
├─ Essay Lab (ม.ปลาย)           ส่งเรียงความ/รายงาน/พอร์ต · ผลตรวจตาม Rubric
└─ โปรไฟล์                      เหรียญตรา · ประวัติเลเวล · เลือกสายการเรียน (ม.ปลาย)
   └─ ตั้งค่า                   การแสดงผล (ธีม มืด/สว่าง/ตามอุปกรณ์) · การแจ้งเตือน · PIN
```

**Parent Console — ผู้ปกครอง**
```
สมัครสมาชิก / เข้าสู่ระบบ       อีเมล + รหัสผ่าน · Google · ยืนยันอีเมล (OTP) · ลืมรหัสผ่าน
└─ ตั้งค่าครอบครัว (Onboarding)  ชื่อครอบครัว · โปรไฟล์ลูก + PIN · เวลาจำกัด · ธีม · เนื้อหาแรก
ภาพรวม (Dashboard)              สรุปสัปดาห์ · กราฟความก้าวหน้า · จุดอ่อน · แจ้งเตือน
├─ กิจกรรมการใช้งาน             log วัน–เวลาเข้า/ออก · heatmap · เวลาต่อวิชา
├─ คลังเนื้อหา                   อัปโหลด PDF/รูป · สร้างสรุปใหม่ (โหมด A–E รวมเขียนเอง) · แหล่งความรู้ · โครงหลักสูตร · คิวตรวจสื่อที่ AI สร้าง
├─ ข้อสอบ                        ตัวสร้างข้อสอบ (เลือก: AI สุ่มอัตโนมัติ / เขียนเอง / ผสม) · คลังข้อสอบ · มอบหมาย/ตั้งเวลา · ผลสอบ
├─ ร้านรางวัล                    ตั้งของรางวัลและราคา TOTON · อนุมัติการแลก
└─ ตั้งค่า                       โปรไฟล์ลูก · PIN · เครื่องของลูก · เวลาจำกัด · การแจ้งเตือน (LINE/อีเมล) · ธีมของผู้ปกครอง
   └─ ความปลอดภัย              วิธีเข้าสู่ระบบ (รหัสผ่าน/Google) · 2FA · เครื่องที่เข้าอยู่ · ผู้ปกครองร่วม · ลบบัญชี
```

## แผนพัฒนา (Roadmap)

| เฟส | ขอบเขต | หน้าจอที่ต้องมี |
|---|---|---|
| **1 · MVP** | คณิต วิทย์ อังกฤษ ม.1–ม.2 · **สมัครด้วยอีเมล + Google, Onboarding, PIN** · อัปโหลด PDF · สร้างสรุป/ข้อสอบไทยอัตโนมัติ · **แก่นสำคัญ + สรุปจากหลักสูตร (โหมด B, แหล่ง T1)** · พี่โต้ไม่เฉลยตรง · XP/เลเวล/ภารกิจพื้นฐาน · log การเข้าใช้ | สมัคร/เข้าสู่ระบบ, Onboarding, เลือกโปรไฟล์ + PIN, Arena Home, Tutor, Quiz, Knowledge Hub, Parent: คลังเนื้อหา + คิวตรวจ + กิจกรรม |
| **2 · Dashboard & Analytics** | แหล่งภายนอก T2–T3 + โหมด C/D, 2FA, เชื่อมเครื่องด้วย QR, ผู้ปกครองร่วม, กราฟความก้าวหน้า, Gaps, เวลาต่อวิชา, Skill Report, เหรียญตราเต็มระบบ, Hall of Fame, ร้านรางวัล | Skill Report, Hall of Fame, Parent Dashboard, ร้าน TOTON, แหล่งความรู้, ความปลอดภัย |
| **3 · Scale ม.4–ม.6** | Track Selection, คลังข้อสอบ A-Level/TGAT/TPAT, Exam Simulator, Essay & Portfolio Lab, tier Grandmaster | Exam Simulator, Essay Lab |
