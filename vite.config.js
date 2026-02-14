import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.scss', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    css: {
        preprocessorOptions: {
            scss: {
                silenceDeprecations: ['legacy-js-api', 'import', 'global-builtin', 'color-functions', 'mixed-decls', 'if-function'],
            },
        },
    },
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
