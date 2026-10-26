import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        // No laravel-vite-plugin font loader — TOTO Arena's fonts
        // (Anuphan/Prompt/JetBrains Mono) load from Google Fonts via
        // @import in resources/css/app.css, per the design system.
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
