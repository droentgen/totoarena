{{--
    Ported from Main.dc.html lines ~701-714 ("STUDENT: SHOP" / ร้าน TOTON).
    TODO: $shop comes from Reward (parent-configured, 02-architecture.md);
    "ขอแลก" creates a Redemption pending guardian approval (03-gamification.md).
--}}
<x-layouts.app role="student" title="ร้าน TOTON" subtitle="">
    @php
        $balance = $student->toton_balance ?? 2480;
        $shop = $shop ?? [
            ['kind' => 'เวลาเล่นเกม', 'name' => 'เล่นเกม 30 นาที', 'price' => 500, 'state' => 'can'],
            ['kind' => 'กิจกรรมครอบครัว', 'name' => 'เลือกร้านอาหารวันเสาร์', 'price' => 2000, 'state' => 'pending'],
            ['kind' => 'ของใช้ในระบบ', 'name' => 'Hint Token ×3', 'price' => 300, 'state' => 'can'],
            ['kind' => 'แต่งโปรไฟล์', 'name' => 'กรอบอวาตาร์ Neon Lime', 'price' => 800, 'state' => 'short', 'need' => 0],
        ];
    @endphp

    <div class="ta-col" style="gap: 20px;">
        <div class="ta-glass ta-row" style="padding: 16px 20px; justify-content: space-between;">
            <div><div class="ta-ovl">ยอด TOTON ของคุณ</div><div class="ta-istat-v ta-coin">{{ number_format($balance) }}</div></div>
            <div class="small ta-muted" style="max-width: 360px;">รางวัลในร้านตั้งโดยผู้ปกครอง ทุกการแลกต้องรอผู้ปกครองอนุมัติก่อนใช้</div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px;">
            @foreach ($shop as $it)
                <div class="ta-glass ta-col" style="padding: 20px; gap: 10px;">
                    <span class="ta-ovl">{{ $it['kind'] }}</span>
                    <div class="heading" style="font-family: var(--font-display);">{{ $it['name'] }}</div>
                    <div class="ta-num ta-coin" style="font-weight: 700;">{{ number_format($it['price']) }} TOTON</div>

                    @if ($it['state'] === 'can')
                        <form method="POST" action="{{ route('student.shop.redeem') }}">
                            @csrf
                            <input type="hidden" name="reward" value="{{ $it['name'] }}">
                            <button type="submit" class="ta-btn ta-btn--primary ta-btn--sm" style="align-self: flex-start;">ขอแลก</button>
                        </form>
                    @elseif ($it['state'] === 'pending')
                        <span class="ta-chip" style="color: var(--warning); align-self: flex-start;">รอผู้ปกครองอนุมัติ</span>
                    @elseif ($it['state'] === 'short')
                        <span class="small ta-muted">ขาดอีก {{ number_format(max($it['price'] - $balance, 0)) }} TOTON</span>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</x-layouts.app>
