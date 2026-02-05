import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
    build: {
        // Optimize for production - use esbuild instead of terser
        minify: 'esbuild',
        cssMinify: true,
        rollupOptions: {
            output: {
                manualChunks: undefined,
            },
        },
        // Faster builds
        reportCompressedSize: false,
        chunkSizeWarningLimit: 1000,
    },
    server: {
        // Important for ngrok: use the ngrok URL
        host: '0.0.0.0',
        strictPort: false,
        hmr: {
            // Disable HMR in production/ngrok
            overlay: false,
        },
    },
});
