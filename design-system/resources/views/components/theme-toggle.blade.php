{{--
    Ported from Main.dc.html lines ~443-446 (topbar theme toggle) +
    the login/auth one at lines ~193-194 — same markup, reused as a component.

    Behaviour required by README.md ("ผู้ใช้เลือกธีมเองได้"):
    - flips <div class="ta-root" data-theme="…"> within 200ms, no page reload
    - persists per-profile, survives reload without a flash of the wrong theme

    Alpine.js does the instant client-side flip (no network wait); the
    `@click` also fires a request to persist the choice. Swap the fetch()
    call below for a Livewire `wire:click="setTheme(...)"` once Livewire
    is installed — keeping Alpine only for the instant optimistic flip.
--}}
<button
    class="ta-iconbtn"
    style="color: var(--lime-text);"
    x-data
    x-on:click="
        const root = document.querySelector('.ta-root');
        const next = root.dataset.theme === 'dark' ? 'light' : 'dark';
        root.dataset.theme = next;
        fetch('{{ route('theme.update') }}', {
            method: 'PATCH',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ theme: next }),
        });
    "
    aria-label="สลับธีมมืด/สว่าง"
    title="สลับธีมมืด/สว่าง"
>
    {{-- dark-mode icon (shown when current theme is dark) --}}
    <svg class="i i-sm" viewBox="0 0 24 24" aria-hidden="true" x-show="document.querySelector('.ta-root').dataset.theme !== 'light'">
        <path d="M20 14.5A8 8 0 0 1 9.5 4 8 8 0 1 0 20 14.5z"></path>
    </svg>
    {{-- light-mode icon (shown when current theme is light) --}}
    <svg class="i i-sm" viewBox="0 0 24 24" aria-hidden="true" x-show="document.querySelector('.ta-root').dataset.theme === 'light'">
        <circle cx="12" cy="12" r="4"></circle>
        <path d="M12 2v2M12 20v2M2 12h2M20 12h2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"></path>
    </svg>
</button>
