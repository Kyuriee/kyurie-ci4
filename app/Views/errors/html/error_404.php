<?php
/**
 * Custom 404 view.
 *
 * PENTING: file ini dirender langsung sama Exception Handler CodeIgniter,
 * BUKAN lewat BaseController — jadi helper 'url' & 'vite' harus di-load
 * manual di sini, gak bisa ngandelin $this->helpers di BaseController.
 */
helper(['url', 'vite']);

$message = $message ?? 'Halaman yang kamu cari tidak tersedia atau sudah dipindahkan.';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#3B82F6">
    <meta name="robots" content="noindex, nofollow">
    <title>404 - Halaman Tidak Ditemukan</title>
    <?= vite_css('resources/css/app.css') ?>
</head>
<body class="min-h-screen bg-background text-body antialiased">
    <div class="flex min-h-screen items-center justify-center px-4 py-12">
        <div class="w-full max-w-lg text-center">

            <div class="relative overflow-hidden rounded-3xl bg-premium-panel p-10 shadow-card sm:p-12">
                <div class="premium-glow premium-glow-primary -left-16 -top-16 h-64 w-64"></div>
                <div class="premium-glow premium-glow-secondary -bottom-20 -right-10 h-56 w-56"></div>
                <div class="premium-dot-pattern"></div>

                <div class="relative z-10">
                    <a href="<?= base_url('/') ?>" class="mb-6 inline-flex">
                        <img
                            src="<?= base_url('assets/images/logos/kyurie-2.png') ?>"
                            alt="Logo"
                            class="h-12 w-12 object-contain"
                        >
                    </a>

                    <p class="font-display text-7xl font-extrabold text-white sm:text-8xl">
                        404
                    </p>

                    <h1 class="mt-4 font-display text-xl font-bold text-white sm:text-2xl">
                        Halaman Tidak Ditemukan
                    </h1>

                    <p class="mx-auto mt-3 max-w-sm text-sm text-slate-400">
                        <?= esc($message) ?>
                    </p>

                    <div class="mt-8 flex flex-col justify-center gap-3 sm:flex-row">
                        <a href="<?= base_url('/') ?>" class="btn btn-primary">
                            <i class="bi bi-house-door"></i>
                            Kembali ke Beranda
                        </a>
                        <a
                            href="<?= base_url('games') ?>"
                            class="inline-flex items-center justify-center gap-2 rounded-xl border border-white/15 bg-white/5 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-white/10"
                        >
                            <i class="bi bi-controller"></i>
                            Lihat Semua Game
                        </a>
                    </div>
                </div>
            </div>

            <p class="mt-6 text-xs text-muted">
                &copy; <?= date('Y') ?> Kyurie. All rights reserved.
            </p>

        </div>
    </div>
</body>
</html>
