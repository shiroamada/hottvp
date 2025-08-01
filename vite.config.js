import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/metronic/dist/assets/css/styles.css',
                'resources/metronic/dist/assets/js/core.bundle.js',
                'node_modules/bootstrap/dist/css/bootstrap.min.css',
                'node_modules/bootstrap/dist/js/bootstrap.bundle.min.js',
                'resources/js/license-generator.js'
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    esbuild: {
        target: ['chrome97', 'firefox92', 'safari15.4'], // update versions that support :is and :has
      },
});