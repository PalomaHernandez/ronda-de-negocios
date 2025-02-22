import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    build: {
        outDir: 'public/build',
        emptyOutDir: true,    
        rollupOptions: {
            output: {
                assetFileNames: "assets/[name]-[hash][extname]",
                entryFileNames: "assets/[name]-[hash].js",
            },
        },     
      },
});
