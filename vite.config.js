import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    server: {
        host: '0.0.0.0',
        hmr: {
            host: 'localhost',
        },
    },
    plugins: [
        laravel({
            input: [
                'resources/scss/app.scss',
                'resources/js/app.js',
                'resources/js/custom-material.js',
                'resources/js/theme-manager.js',
                'resources/css/login/login.css',
                'resources/js/login/login.js',
                'resources/css/dashboard/dashboard.css',
                'resources/js/dashboard/dashboard.js'
            ],
            refresh: true,
        }),
    ],
});
