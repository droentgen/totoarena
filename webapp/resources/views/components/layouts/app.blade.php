@props([
    // prototype.css only defines color tokens for [data-theme="dark"|"light"]
    // — the users.theme column also allows 'system' (its default), which
    // isn't a real CSS value and silently drops every token, rendering
    // unstyled. Resolve it to 'dark' until actual OS-preference detection
    // (prefers-color-scheme, pre-paint) is wired up.
    'theme' => in_array(auth()->user()?->theme, ['dark', 'light'], true) ? auth()->user()->theme : 'dark',
    'role' => 'student', // 'student' | 'parent'
    'title' => '',
    'subtitle' => '',
])

{{--
    Ported from the design mockup's "APP SHELL" block
    (TOTO Arena ต้นแบบระบบ-html/Main.dc.html, lines ~384-453, ~896-901).
    `sc-if value="{{cond}}"` → @if($cond) ... @endif
    `{{var}}` (mockup template binding) → Blade {{ $var }}

    data-theme is set server-side from the current user's saved profile
    theme so the correct colors render on first paint (no flash of the
    wrong theme) — see README.md "ผู้ใช้เลือกธีมเองได้".
--}}
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ? $title.' · TOTO Arena' : 'TOTO Arena' }}</title>

    @vite(['resources/css/app.css'])
    @livewireStyles
</head>
<body>
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

    @livewireScripts
</body>
</html>
