{{--
    Ported from Main.dc.html lines ~715-760 ("SETTINGS (both roles)").
    Shared by student and parent — pass role to <x-layouts.app>, and the
    security section only renders for parents (per 06-registration.md,
    students don't manage auth/2FA/devices — guardians do).

    TODO: QR device-link cells need a real QR library (e.g.
    simplesoftwareio/simple-qrcode) server-side — not faked here, since a
    fake QR pattern that doesn't decode would be actively misleading.
--}}
<x-layouts.app :role="$role ?? 'student'" title="ตั้งค่า" subtitle="การแสดงผล">
    @php
        $theme = $theme ?? (auth('student')->user()->theme ?? auth()->user()->theme ?? 'dark');
        $themeLabels = ['dark' => 'มืด · Arena', 'light' => 'สว่าง · Daylight', 'system' => 'ตามอุปกรณ์'];
    @endphp

    <div class="ta-col" style="gap: 20px; max-width: 1040px;" x-data="{ theme: '{{ $theme }}' }">
        <section class="ta-glass ta-col" style="padding: 24px; gap: 16px;">
            <div>
                <div class="heading" style="font-family: var(--font-display);">ธีม</div>
                <div class="small ta-muted">เลือกได้ตามชอบ ระบบจำค่าไว้กับโปรไฟล์ของคุณ ใช้ได้ทุกเครื่องที่ล็อกอิน</div>
            </div>

            <form method="POST" action="{{ route('theme.update') }}" x-on:change="$el.requestSubmit()">
                @csrf
                <div class="ta-theme-cards" role="radiogroup" aria-label="ธีม" style="grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));">
                    <label class="ta-theme-card" :class="{ 'is-on': theme === 'dark' }">
                        <input type="radio" name="theme" value="dark" x-model="theme" class="sr-only" style="position: absolute; opacity: 0;">
                        <div class="ta-thumb ta-root" data-theme="dark"><div class="ta-thumb-side"><i class="on"></i><i></i><i></i><i></i></div><div class="ta-thumb-main"><b></b><b class="hl"></b><div class="ta-thumb-tiles"><s></s><s></s><s></s></div></div></div>
                        <div class="ta-theme-card-foot"><div><div class="body-strong">มืด · Arena</div><div class="caption ta-muted">เท่แบบเกม เหมาะกับกลางคืน</div></div><span class="ta-radio"></span></div>
                    </label>
                    <label class="ta-theme-card" :class="{ 'is-on': theme === 'light' }">
                        <input type="radio" name="theme" value="light" x-model="theme" class="sr-only" style="position: absolute; opacity: 0;">
                        <div class="ta-thumb ta-root" data-theme="light"><div class="ta-thumb-side"><i class="on"></i><i></i><i></i><i></i></div><div class="ta-thumb-main"><b></b><b class="hl"></b><div class="ta-thumb-tiles"><s></s><s></s><s></s></div></div></div>
                        <div class="ta-theme-card-foot"><div><div class="body-strong">สว่าง · Daylight</div><div class="caption ta-muted">อ่านเนื้อหายาวสบายตาตอนกลางวัน</div></div><span class="ta-radio"></span></div>
                    </label>
                    <label class="ta-theme-card" :class="{ 'is-on': theme === 'system' }">
                        <input type="radio" name="theme" value="system" x-model="theme" class="sr-only" style="position: absolute; opacity: 0;">
                        <div class="ta-thumb-split"><div class="ta-thumb ta-root" data-theme="light"><div class="ta-thumb-side"><i class="on"></i><i></i><i></i></div><div class="ta-thumb-main"><b></b><b class="hl"></b></div></div><div class="ta-thumb ta-root" data-theme="dark"><div class="ta-thumb-side"><i class="on"></i><i></i><i></i></div><div class="ta-thumb-main"><b></b><b class="hl"></b></div></div></div>
                        <div class="ta-theme-card-foot"><div><div class="body-strong">ตามอุปกรณ์</div><div class="caption ta-muted">สลับเองตามการตั้งค่าของ iPad/มือถือ</div></div><span class="ta-radio"></span></div>
                    </label>
                </div>
            </form>
            <div class="small ta-muted">ตอนนี้แสดงผล: <b style="color: var(--ink);" x-text="{{ json_encode($themeLabels) }}[theme]"></b></div>
        </section>

        <section class="ta-glass ta-col" style="padding: 24px; gap: 12px;">
            <div class="heading" style="font-family: var(--font-display);">ตัวเลือกเพิ่มเติม</div>
            <form method="POST" action="{{ route('settings.preferences') }}" x-data x-on:change="$el.requestSubmit()">
                @csrf
                <label class="ta-row" style="justify-content: space-between; flex-wrap: nowrap; cursor: pointer;">
                    <span><span class="body-strong" style="display: block;">ใช้ธีมสว่างตอนอ่านเนื้อหายาว</span><span class="small ta-muted">เปิดหน้า "เรียน" เป็น Daylight อัตโนมัติ</span></span>
                    <input type="checkbox" name="daylight_for_learn" {{ ($daylightForLearn ?? false) ? 'checked' : '' }} style="width: 22px; height: 22px; accent-color: var(--lime);">
                </label>
                <label class="ta-row" style="justify-content: space-between; flex-wrap: nowrap; cursor: pointer;">
                    <span><span class="body-strong" style="display: block;">ลดการเคลื่อนไหว</span><span class="small ta-muted">ปิดแอนิเมชันเลเวลอัปและการเปลี่ยนสีแบบค่อย ๆ จาง</span></span>
                    <input type="checkbox" name="reduced_motion" {{ ($reducedMotion ?? false) ? 'checked' : '' }} style="width: 22px; height: 22px; accent-color: var(--lime);">
                </label>
            </form>
        </section>

        @if (($role ?? 'student') === 'parent')
            <section class="ta-glass ta-col" style="padding: 24px; gap: 14px;">
                <div>
                    <div class="heading" style="font-family: var(--font-display);">ความปลอดภัยของบัญชี</div>
                    <div class="small ta-muted">วิธีเข้าสู่ระบบ การยืนยันสองขั้น และเครื่องของลูก</div>
                </div>

                <div class="ta-row" style="justify-content: space-between;">
                    <div><div class="body-strong">อีเมล + รหัสผ่าน</div><div class="small ta-muted">{{ auth()->user()->email }}</div></div>
                    <a href="{{ route('password.request') }}" class="ta-btn ta-btn--secondary ta-btn--sm" style="text-decoration: none;">เปลี่ยนรหัสผ่าน</a>
                </div>

                <div class="ta-row" style="justify-content: space-between;">
                    <div class="ta-row" style="gap: 10px; flex-wrap: nowrap;">
                        <span class="au-g" aria-hidden="true">G</span>
                        <div><div class="body-strong">บัญชี Google</div><div class="small ta-muted">{{ $googleLinked ?? false ? 'เชื่อมแล้ว' : 'ยังไม่เชื่อม' }}</div></div>
                    </div>
                    <form method="POST" action="{{ route('settings.google.toggle') }}">
                        @csrf
                        @if ($googleLinked ?? false)
                            <button type="submit" class="ta-btn ta-btn--ghost ta-btn--sm" style="border: 0;">ยกเลิกการเชื่อม</button>
                        @else
                            <button type="submit" class="ta-btn ta-btn--secondary ta-btn--sm">เชื่อมบัญชี Google</button>
                        @endif
                    </form>
                </div>

                <div class="ta-row" style="justify-content: space-between;">
                    <div>
                        <div class="body-strong">ยืนยันสองขั้น (2FA) <span class="ta-chip {{ ($twoFactorEnabled ?? false) ? 'ta-chip--lime' : '' }}">{{ ($twoFactorEnabled ?? false) ? 'เปิดอยู่' : 'ปิดอยู่' }}</span></div>
                        <div class="small ta-muted">ใช้รหัสจากแอป Authenticator ตอนเข้าสู่ระบบบนเครื่องใหม่</div>
                    </div>
                    <form method="POST" action="{{ route('settings.two-factor.toggle') }}">
                        @csrf
                        <button type="submit" class="ta-btn ta-btn--secondary ta-btn--sm">{{ ($twoFactorEnabled ?? false) ? 'ปิด 2FA' : 'เปิด 2FA' }}</button>
                    </form>
                </div>

                <div style="border-top: 1px solid var(--line); padding-top: 14px;" class="ta-col" x-data="{ showQr: false }">
                    <div class="ta-row" style="justify-content: space-between;">
                        <div>
                            <div class="body-strong">เครื่องของ{{ $childName ?? 'น้อง' }}</div>
                            <div class="small ta-muted">{{ $childDevicesText ?? 'ยังไม่มีเครื่องที่เชื่อมไว้' }}</div>
                        </div>
                        <button type="button" class="ta-btn ta-btn--primary ta-btn--sm" x-show="!showQr" @click="showQr = true">เพิ่มเครื่องให้น้อง</button>
                    </div>
                    <div class="ta-card ta-row" style="gap: 20px; background: var(--bg-300); border-color: var(--line-strong); align-items: center;" x-show="showQr" x-cloak>
                        <div class="ta-col" style="gap: 6px;">
                            {{-- TODO: real QR from a server-generated device-link code (10 min expiry, single use) --}}
                            <div class="caption ta-muted">QR code generated server-side — not shown in this static port</div>
                        </div>
                        <div class="ta-col" style="gap: 6px; flex: 1 1 220px;">
                            <div class="body-strong">ให้น้องสแกนด้วยเครื่องของน้อง</div>
                            <div class="small ta-muted">หรือพิมพ์รหัสในหน้าเลือกผู้เล่น › "เชื่อมเครื่องนี้"</div>
                            <div class="ta-num" style="font-size: 22px; font-weight: 700; letter-spacing: 0.08em;">{{ $deviceLinkCode ?? '••••-••••' }}</div>
                            <div class="caption" style="color: var(--warning);">หมดอายุใน 10 นาที · ใช้ได้ครั้งเดียว</div>
                            <button type="button" class="ta-btn ta-btn--ghost ta-btn--sm" @click="showQr = false" style="border: 0; align-self: flex-start;">ยกเลิก</button>
                        </div>
                    </div>
                </div>

                <div class="ta-row" style="justify-content: space-between; border-top: 1px solid var(--line); padding-top: 14px;">
                    <div>
                        <div class="body-strong">เครื่องที่เข้าสู่ระบบอยู่</div>
                        <div class="small ta-muted">{{ $activeSessionsText ?? 'เครื่องนี้' }}</div>
                    </div>
                    <form method="POST" action="{{ route('settings.sessions.logout-all') }}">
                        @csrf
                        <button type="submit" class="ta-btn ta-btn--ghost ta-btn--sm" style="border: 0; color: var(--danger);">ออกจากทุกเครื่อง</button>
                    </form>
                </div>
            </section>
        @endif
    </div>
</x-layouts.app>
