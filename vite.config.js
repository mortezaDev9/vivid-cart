import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'Modules/Home/resources/js/app.js',
                'Modules/Home/resources/css/app.css',
            ],
            refresh: true,
        }),
    ],
});
