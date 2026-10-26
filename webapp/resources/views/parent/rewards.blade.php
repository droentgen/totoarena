{{--
    Ported from Main.dc.html lines ~878-895 ("PARENT: REWARDS").
    TODO: $pendingRedemptions / $rewards come from Redemption/Reward
    (02-architecture.md, 03-gamification.md).
--}}
<x-layouts.app role="parent" title="ร้านรางวัล" subtitle="">
    @php
        $pendingRedemptions = $pendingRedemptions ?? [
            ['student' => 'น้องกล้าหาญ', 'reward' => 'เล่นเกม 30 นาที', 'cost' => 500, 'requested_ago' => '10 นาทีที่แล้ว'],
        ];
        $rewards = $rewards ?? [
            ['name' => 'เล่นเกม 30 นาที', 'price' => 500, 'limit' => '3 ครั้ง/สัปดาห์'],
            ['name' => 'เลือกร้านอาหารวันเสาร์', 'price' => 2000, 'limit' => '1 ครั้ง/เดือน'],
            ['name' => 'Hint Token ×3', 'price' => 300, 'limit' => 'ไม่จำกัด'],
            ['name' => 'กรอบอวาตาร์ Neon Lime', 'price' => 800, 'limit' => 'ครั้งเดียว'],
        ];
    @endphp

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 16px; align-items: start;">
        <section class="ta-glass" style="padding: 20px;">
            <div class="ta-sh"><h3>คำขอแลกที่รออนุมัติ</h3></div>

            @forelse ($pendingRedemptions as $i => $r)
                <div class="ta-card ta-row" style="justify-content: space-between; gap: 12px;">
                    <div>
                        <div class="body-strong">{{ $r['reward'] }}</div>
                        <div class="small ta-muted">{{ $r['student'] }} · {{ number_format($r['cost']) }} TOTON · ขอเมื่อ {{ $r['requested_ago'] }}</div>
                    </div>
                    <div class="ta-row" style="gap: 8px;">
                        <form method="POST" action="{{ route('parent.rewards.decide', ['redemption' => $i]) }}">
                            @csrf
                            <input type="hidden" name="decision" value="reject">
                            <button type="submit" class="ta-btn ta-btn--secondary ta-btn--sm">ปฏิเสธ</button>
                        </form>
                        <form method="POST" action="{{ route('parent.rewards.decide', ['redemption' => $i]) }}">
                            @csrf
                            <input type="hidden" name="decision" value="approve">
                            <button type="submit" class="ta-btn ta-btn--primary ta-btn--sm">อนุมัติ</button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="small ta-muted" style="margin: 0;">ไม่มีคำขอค้างอยู่ — ลองไปที่ร้าน TOTON ฝั่งนักเรียนแล้วกด "ขอแลก" ดูได้</p>
            @endforelse
        </section>

        <section class="ta-glass" style="padding: 20px;">
            <div class="ta-sh"><h3>รางวัลในร้าน</h3><button type="button" class="ta-btn ta-btn--ghost ta-btn--sm" style="border: 0;">+ เพิ่มรางวัล</button></div>
            <div style="overflow-x: auto;">
                <table class="ta-table">
                    <thead><tr><th>รางวัล</th><th>ราคา</th><th>จำกัด</th></tr></thead>
                    <tbody>
                        @foreach ($rewards as $rw)
                            <tr>
                                <td>{{ $rw['name'] }}</td>
                                <td class="ta-num">{{ number_format($rw['price']) }}</td>
                                <td class="small ta-muted">{{ $rw['limit'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</x-layouts.app>
