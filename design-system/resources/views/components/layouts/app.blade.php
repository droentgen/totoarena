@props([
    'theme' => auth()->user()?->theme ?? 'dark', // 'dark' | 'light' — never hardcode colors, always via data-theme
    'role' => 'student', // 'student' | 'parent'
    'title' => '',
    'subtitle' => '',
])

{{--
    Ported from the design mockup's "APP SHELL" block
    (TOTO Arena ต้นแบบระบบ-html/Main.dc.html, lines ~384-453, ~896-901).
    `sc-if value="{{cond}}"` → @if($cond) ... @endif
    `{{var}}` (mockup template binding) → Blade {{ $var }}
--}}
<div class="ta-root" data-theme="{{ $theme }}" style="min-height: 100vh;">
    <div style="display: flex; flex-wrap: wrap; min-height: 100vh;">

        @if ($role === 'student')
            @include('partials.sidebar-student')
        @else
            @include('partials.sidebar-parent')
        @endif

        <div style="flex: 999 1 560px; min-width: 0; display: flex; flex-direction: column;">
            @include('partials.topbar', ['role' => $role, 'title' => $title, 'subtitle' => $subtitle])

            <main class="ta-main ta-dots" style="flex: 1; padding: 24px clamp(16px, 3vw, 32px) 40px;">
                {{ $slot }}
            </main>
        </div>

    </div>
</div>
