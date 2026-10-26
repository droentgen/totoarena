@props([
    'theme' => auth()->user()?->theme ?? 'dark',
    'title' => '',
])

{{--
    Minimal document shell for pre-login pages (game lobby, sign up/in,
    OTP, consent) — these intentionally skip <x-layouts.app> (no
    sidebar/topbar) but still need the .ta-root[data-theme] wrapper that
    prototype.css/components.css key all color tokens off of.
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
        {{ $slot }}
    </div>

    @livewireScripts
</body>
</html>
