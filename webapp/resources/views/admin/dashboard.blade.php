<x-layouts.auth title="Admin">
<div class="ta-dots" style="min-height: 100vh; padding: 24px clamp(16px, 3vw, 32px) 40px;">
    <header class="ta-row" style="justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div class="ta-row" style="gap: 10px;">
            <div class="ta-logo"><b>TOTO</b> Arena</div>
            <span class="caption ta-muted">Admin</span>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="ta-btn ta-btn--ghost ta-btn--sm">ออกจากระบบ</button>
        </form>
    </header>

    <div class="ta-col" style="gap: 20px; max-width: 960px; margin: 0 auto;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
            <div class="ta-stat"><div class="ta-stat-v">{{ $guardianCount }}</div><div class="ta-stat-l">ผู้ปกครอง (users)</div></div>
            <div class="ta-stat"><div class="ta-stat-v">{{ $studentCount }}</div><div class="ta-stat-l">นักเรียน</div></div>
            <div class="ta-stat"><div class="ta-stat-v">{{ $familyCount }}</div><div class="ta-stat-l">ครอบครัว</div></div>
        </div>

        <section class="ta-glass" style="padding: 20px;">
            <div class="ta-sh"><h3>ผู้ปกครองล่าสุด</h3></div>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="text-align: left; border-bottom: 1px solid var(--line);">
                            <th style="padding: 8px 10px;" class="caption ta-muted">ชื่อ</th>
                            <th style="padding: 8px 10px;" class="caption ta-muted">อีเมล</th>
                            <th style="padding: 8px 10px;" class="caption ta-muted">บทบาท</th>
                            <th style="padding: 8px 10px;" class="caption ta-muted">สมัครเมื่อ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentGuardians as $guardian)
                            <tr style="border-bottom: 1px solid var(--line);">
                                <td style="padding: 8px 10px;">{{ $guardian->name }}</td>
                                <td style="padding: 8px 10px;">{{ $guardian->email }}</td>
                                <td style="padding: 8px 10px;">{{ $guardian->is_admin ? 'admin' : $guardian->role }}</td>
                                <td style="padding: 8px 10px;" class="caption ta-muted">{{ $guardian->created_at->format('d M Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="caption ta-muted" style="padding: 12px 10px;">ยังไม่มีผู้ปกครองสมัครเข้ามา</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</div>
</x-layouts.auth>
