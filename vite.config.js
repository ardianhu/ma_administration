import {
    defineConfig
} from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: [`resources/views/**/*`],
        }),
        tailwindcss(),
    ],
    server: {
        cors: true,
        // mobile testing
        host: '0.0.0.0',
        port: 5173, // or your Vite port
        strictPort: true,
        hmr: {
            host: '192.168.1.19', // e.g., '192.168.1.5'
        },
    },
});