import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.js'], // Ajusta la ruta según tu proyecto
            refresh: true, // Para recarga automática con Laravel
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    build: {
        // tu configuración build aquí si es necesario
    },
    server: {
        hmr: {
            host: 'localhost',
        },
    },
});
