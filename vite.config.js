import { defineConfig } from 'vite';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        tailwindcss(),
    ],

    publicDir: false,
    base: '/build/',
    cssMinify: true,
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
                /* ========= Admin Panel (Independent, own design tokens) ========= */
                admin_css: 'resources/css/admin.css',
                admin_js: 'resources/js/admin.js',
                /* ---- Admin pages (resources/js/pages/admin/*.js), 1 entry per view page ---- */
                admin_games_js: 'resources/js/pages/admin/games.js',

                // Belum ada view-nya (baru API/CRUD backend), aktifin pas viewnya dibikin:
                // admin_game_categories_js: 'resources/js/pages/admin/game-categories.js',
                // admin_products_js: 'resources/js/pages/admin/products.js',
                // admin_banners_js: 'resources/js/pages/admin/banners.js',
                // admin_flashsales_js: 'resources/js/pages/admin/flashsales.js',
                // admin_payment_methods_js: 'resources/js/pages/admin/payment-methods.js',
                // admin_coupons_js: 'resources/js/pages/admin/coupons.js',
                // admin_orders_js: 'resources/js/pages/admin/orders.js',
                // admin_users_js: 'resources/js/pages/admin/users.js',
                /* ========= Pages ========= */
                home_css: 'resources/css/pages/home.css',
                home_js: 'resources/js/pages/home.js',
                game_css: 'resources/css/pages/game.css',
                game_js: 'resources/js/pages/game.js',
                payment_css: 'resources/css/pages/payment.css',
                payment_js: 'resources/js/pages/payment.js',

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