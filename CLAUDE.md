# TOTO Arena — กฎเฉพาะโปรเจกต์นี้

กฎนี้ override กฎ "ใช้ JavaScript เสมอ" ใน `../.agents/AGENTS.md` **เฉพาะในโฟลเดอร์ `totoarena/` เท่านั้น** — โปรเจกต์อื่นใน workspace นี้ (เช่น greenoffice, IDP, cer, borrow-buddy) ยังใช้กฎเดิมตามปกติ

## ภาษาและสถาปัตยกรรม

- **PHP ทั้งระบบ** — ไม่ใช้ JS framework แยก (ไม่มี React/Vue/Next.js)
- **Backend + Frontend**: Laravel (PHP) — Blade สำหรับ render หน้า, Livewire สำหรับส่วนที่ต้องโต้ตอบ (เช่น แชตติว, ตัวจับเวลาสอบ, แถบ XP) โดยไม่ต้องเขียน JS เพิ่ม
- **JS ที่อนุญาต**: เฉพาะ Alpine.js (มาคู่กับ Livewire อยู่แล้ว) สำหรับ interaction เล็ก ๆ ฝั่ง client (เช่น เปิด/ปิด modal, สลับธีมแบบไม่กะพริบ) — ห้ามเพิ่ม build pipeline ของ JS framework แยกต่างหาก
- **Database**: PostgreSQL + extension `pgvector` (ฐานเดียวกับข้อมูลระบบ ใช้ทำ RAG ด้วย)
- **Queue**: Laravel Queue (ใช้ driver `database` หรือ Redis) สำหรับงาน OCR, สร้างเนื้อหา/ข้อสอบด้วย AI แบบ background
- **Realtime/Streaming**: Livewire `wire:stream` สำหรับสตรีมคำตอบแชตของพี่โต้แบบ token-by-token; ถ้าต้องการ realtime ข้ามผู้ใช้ (เช่น Hall of Fame อัปเดตสด) ใช้ Laravel Reverb (WebSocket server เขียนด้วย PHP ของ Laravel เอง)
- **AI (Claude)**: ไม่มี PHP SDK ทางการ — เรียกผ่าน Laravel `Http` facade ตรงไปที่ Anthropic Messages API (รวม Vision สำหรับ OCR)
- **Auth**: Laravel Fortify/Breeze (อีเมล+รหัสผ่าน+OTP) + Socialite (Google OAuth) สำหรับผู้ปกครอง; custom guard แยกสำหรับนักเรียน (PIN)
- **Storage**: Laravel Filesystem (S3-compatible driver) สำหรับ PDF/รูปภาพ

## Hosting & Deployment

- **Source control**: GitHub (CI/CD ผ่าน GitHub Actions)
- **App host**: **Railway** (แนะนำ — มี buildpack/template สำหรับ Laravel พร้อม queue worker ในตัว) หรือ **Render** เป็นตัวเลือกสำรอง — **ไม่ใช้ Vercel** เพราะ Vercel เป็น serverless function รันสั้น ๆ ไม่รองรับ queue worker ที่ต้องรันค้างตลอดเวลา (OCR/สร้างเนื้อหา-ข้อสอบด้วย AI) และไม่รองรับ WebSocket server (Laravel Reverb)
- **Database**: **Supabase** (Postgres managed + extension pgvector เปิดใช้ได้) — เชื่อมจาก Laravel ด้วย connection string ปกติ (Eloquent), ไม่ต้องพึ่ง Supabase client library ของ JS
- **Storage ไฟล์** (PDF/รูป): ใช้ Supabase Storage (S3-compatible) ผ่าน Laravel Filesystem driver หรือ Railway/Render persistent volume ก็ได้

## ฟีเจอร์ AI-assisted content/exam generation

ทุกจุดที่ AI สร้างเนื้อหาหรือข้อสอบ **ผู้ปกครองต้องเลือกได้ว่าจะให้ AI สร้างอัตโนมัติ หรือสร้าง/แก้ไขเอง** — ดูรายละเอียดใน `01-system-flow.md` (ไปป์ไลน์ ② และ ③) และ `07-knowledge-sourcing.md` (โหมด E)
