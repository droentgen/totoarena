{{--
    Ported from Main.dc.html lines ~244-255 ("step: terms + consent").
    TODO: on submit, record Consent(doc_type, doc_version, accepted_at, ip)
    per guardian per 06-registration.md, then redirect to onboarding.
--}}
<x-layouts.auth title="ข้อตกลงและความยินยอม">
<div class="au ta-dots">
    <header class="au-top">
        <div class="ta-row" style="gap: 10px;">
            <div class="ta-logo" style="padding: 0;"><b>TOTO</b> Arena</div>
            <span class="caption ta-muted">สำหรับผู้ปกครอง</span>
        </div>
        <x-theme-toggle />
    </header>

    <div class="au-wrap">
        <div class="au-card" x-data="{ c1: false, c2: false }">
            @if ($viaGoogle ?? false)
                <div class="ta-card ta-row" style="background: var(--lime-soft); border-color: var(--lime-text); padding: 12px 16px; gap: 12px; flex-wrap: nowrap;">
                    <span class="au-g" aria-hidden="true">G</span>
                    <div>
                        <div class="body-strong ta-lime">เชื่อมกับ Google แล้ว</div>
                        <div class="small">{{ $email ?? '' }} · Google ยืนยันอีเมลให้แล้ว ไม่ต้องรอรหัส</div>
                    </div>
                </div>
            @else
                <div class="ta-card ta-row" style="background: var(--lime-soft); border-color: var(--lime-text); padding: 12px 16px; gap: 12px; flex-wrap: nowrap;">
                    <span class="ta-lime" style="display: inline-flex;"><svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12.5 10 17l9-10"></path></svg></span>
                    <div>
                        <div class="body-strong ta-lime">ยืนยันอีเมลแล้ว</div>
                        <div class="small">{{ $email ?? '' }}</div>
                    </div>
                </div>
            @endif

            <div>
                <h1 class="au-h">ข้อตกลงและความยินยอม</h1>
                <p class="small ta-muted" style="margin: 4px 0 0;">น้องเป็นผู้เยาว์ ระบบจึงต้องได้ความยินยอมจากผู้ปกครองก่อนเก็บข้อมูลการเรียน</p>
            </div>

            <form method="POST" action="{{ route('consent.accept') }}" class="ta-col" style="gap: 16px;">
                @csrf
                <label class="au-check">
                    <input type="checkbox" name="terms" x-model="c1">
                    <span>ยอมรับ <span class="au-u">ข้อกำหนดการใช้งาน</span> และ <span class="au-u">นโยบายความเป็นส่วนตัว</span></span>
                </label>
                <label class="au-check">
                    <input type="checkbox" name="child_data_consent" x-model="c2">
                    <span>ในฐานะผู้ปกครอง ยินยอมให้ระบบประมวลผลข้อมูลการเรียนของบุตร (คะแนน เวลาใช้งาน ข้อความติว) เพื่อใช้ในบัญชีครอบครัวนี้เท่านั้น</span>
                </label>
                <label class="au-check">
                    <input type="checkbox" name="marketing_opt_in">
                    <span class="ta-muted">รับเคล็ดลับการเรียนทางอีเมล (ไม่บังคับ)</span>
                </label>
                <button type="submit" class="ta-btn ta-btn--primary" :disabled="!(c1 && c2)" style="justify-content: center;">ยอมรับและตั้งค่าครอบครัว</button>
                <span class="caption ta-muted">ระบบบันทึกเวอร์ชันเอกสารและเวลาที่ยินยอมไว้เป็นหลักฐาน ถอนความยินยอมได้ที่ ตั้งค่า › ความปลอดภัย</span>
            </form>
        </div>
    </div>
</div>
</x-layouts.auth>
