import { defineConfig } from 'vite';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        tailwindcss(),
    ],

    publicDir: false,
    base: '/build/',

    build: {
        manifest: true,
        outDir: 'public/build',
        emptyOutDir: true,

        rollupOptions: {
            input: {
                /* ========= Global ========= */
                app_css: 'resources/css/app.css',
                app_js: 'resources/js/app.js',
                /* ========= Layout Auth (Independent) ========= */
                auth_css: 'resources/css/auth.css',
                auth_js: 'resources/js/auth.js',
                /* ========= Pages ========= */
                home_css: 'resources/css/pages/home.css',
                home_js: 'resources/js/pages/home.js',

                // login_css: 'resources/css/pages/login.css',
                // login_js: 'resources/js/pages/login.js',

                // profile_css: 'resources/css/pages/profile.css',
                // profile_js: 'resources/js/pages/profile.js',

                // order_css: 'resources/css/pages/order.css',
                // order_js: 'resources/js/pages/order.js',
            },
        },
    },
});