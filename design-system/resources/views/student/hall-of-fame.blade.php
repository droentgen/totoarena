{{--
    Ported from Main.dc.html lines ~682-698 ("STUDENT: HALL OF FAME").
    Tabs are plain GET links (?tab=weekly|apex|ghost).
--}}
<x-layouts.app role="student" title="TOTO Hall of Fame" subtitle="">
    @php
        $tab = request('tab', 'weekly');
        $board = $board ?? [
            ['rank' => 1, 'rc' => 'ta-rc r1', 'av' => 'พลอย', 'name' => 'พลอย', 'tier' => 'TOTO Vanguard', 'xp' => '18,920', 'badges' => 9, 'rowCls' => ''],
            ['rank' => 2, 'rc' => 'ta-rc r2', 'av' => 'กล้า', 'name' => 'กล้าหาญ (คุณ)', 'tier' => 'TOTO Challenger', 'xp' => '12,480', 'badges' => 7, 'rowCls' => 'is-me'],
            ['rank' => 3, 'rc' => 'ta-rc r3', 'av' => 'ปัน', 'name' => 'ปัน', 'tier' => 'TOTO Challenger', 'xp' => '11,050', 'badges' => 6, 'rowCls' => ''],
            ['rank' => 4, 'rc' => '', 'av' => 'นิว', 'name' => 'นิว', 'tier' => 'TOTO Challenger', 'xp' => '9,800', 'badges' => 5, 'rowCls' => ''],
            ['rank' => 5, 'rc' => '', 'av' => 'เอม', 'name' => 'เอม', 'tier' => 'Novice Learner', 'xp' => '8,420', 'badges' => 4, 'rowCls' => ''],
        ];
    @endphp

    <div class="ta-col" style="gap: 20px;">
        <div class="ta-seg" role="tablist" style="align-self: flex-start;">
            <a href="?tab=weekly" class="ta-seg-b {{ $tab === 'weekly' ? 'is-on' : '' }}" style="text-decoration: none;">Weekly Board</a>
            <a href="?tab=apex" class="ta-seg-b {{ $tab === 'apex' ? 'is-on' : '' }}" style="text-decoration: none;">The Apex Board</a>
            <a href="?tab=ghost" class="ta-seg-b {{ $tab === 'ghost' ? 'is-on' : '' }}" style="text-decoration: none;">Ghost Rival</a>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 16px; align-items: start;">
            <section class="ta-glass" style="overflow: hidden;">
                <div style="overflow-x: auto;">
                    <table class="ta-lbt">
                        <thead><tr><th class="ta-ovl">Rank</th><th class="ta-ovl">Player</th><th class="ta-ovl" style="text-align: right;">XP</th><th class="ta-ovl" style="text-align: right;">Crests</th></tr></thead>
                        <tbody>
                            @foreach ($board as $r)
                                <tr class="{{ $r['rowCls'] }}">
                                    <td><span class="{{ $r['rc'] }}">{{ $r['rank'] }}</span></td>
                                    <td>
                                        <div class="ta-row" style="gap: 12px; flex-wrap: nowrap;">
                                            <div class="ta-avatar" style="width: 36px; height: 36px; font-size: 12px;">{{ $r['av'] }}</div>
                                            <div><div class="body-strong" style="line-height: 20px;">{{ $r['name'] }}</div><span class="caption ta-muted">{{ $r['tier'] }}</span></div>
                                        </div>
                                    </td>
                                    <td class="ta-num" style="font-weight: 700; text-align: right;">{{ $r['xp'] }}</td>
                                    <td class="ta-num ta-muted" style="text-align: right;">{{ $r['badges'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="ta-col" style="gap: 16px;">
                <div class="ta-glass" style="padding: 20px; border-style: dashed; border-color: var(--line-strong);">
                    <div class="ta-ovl">Ghost Rival</div>
                    <div class="body-strong" style="margin-top: 6px;">คุณเมื่อสัปดาห์ก่อน: {{ $ghostXp ?? '2,200' }} XP</div>
                    <div class="small ta-lime">ชนะแล้ว! นำอยู่ {{ $ghostLead ?? '410' }} XP</div>
                </div>
                <div class="ta-glass" style="padding: 20px;">
                    <div class="small">อีก <b class="ta-lime">{{ $xpToNextRank ?? '330' }} XP</b> แซงขึ้นอันดับ 1 · ชนะ Boss Battle บทที่ 3 ได้ ~180 XP</div>
                    <a href="{{ route('student.quiz') }}" class="ta-btn ta-btn--primary ta-btn--sm" style="margin-top: 12px; text-decoration: none;">ไปสนามสอบ</a>
                </div>
            </section>
        </div>
    </div>
</x-layouts.app>
