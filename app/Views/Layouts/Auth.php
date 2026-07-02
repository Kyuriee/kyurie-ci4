<!DOCTYPE html>
<html lang="<?= esc($meta['lang'] ?? 'id') ?>">
<head>
    <?= view('Components/Layouts/Auth/Head') ?>
</head>
<body class="min-h-screen bg-white text-body antialiased">
    <div class="grid min-h-screen lg:grid-cols-2">
        <!-- Brand Panel -->
        <div class="relative hidden overflow-hidden bg-auth-panel lg:flex lg:flex-col lg:justify-between lg:p-12">
            <div class="auth-glow auth-glow-primary"></div>
            <div class="auth-glow auth-glow-secondary"></div>
            <div class="auth-dot-pattern"></div>

            <a href="<?= base_url('/') ?>" class="relative z-10 flex items-center gap-3">
                <img
                    src="<?= base_url('assets/images/logos/kyurie-2.png') ?>"
                    alt="Logo"
                    class="h-10 w-10 object-contain"
                >
            </a>

            <div class="relative z-10 max-w-md">
                <h1 class="font-display text-3xl font-extrabold leading-tight text-white xl:text-4xl">
                    Top Up Game Favorit,
                    <span class="text-primary-100">Cepat &amp; Aman</span>
                </h1>

                <p class="mt-4 text-sm text-slate-400">
                    Proses instan, harga bersahabat, dan ratusan game populer siap kamu top up kapan aja.
                </p>

                <div class="mt-8 flex items-center gap-6">
                    <div>
                        <div class="font-display text-2xl font-bold text-white">50rb+</div>
                        <div class="text-xs text-slate-400">Transaksi Sukses</div>
                    </div>

                    <div class="h-8 w-px bg-white/10"></div>

                    <div>
                        <div class="font-display text-2xl font-bold text-white">24/7</div>
                        <div class="text-xs text-slate-400">Proses Otomatis</div>
                    </div>

                    <div class="h-8 w-px bg-white/10"></div>

                    <div>
                        <div class="font-display text-2xl font-bold text-white">100+</div>
                        <div class="text-xs text-slate-400">Game Tersedia</div>
                    </div>
                </div>
            </div>

            <p class="relative z-10 text-xs text-slate-500">
                © <?= date('Y') ?> Kyurie. All rights reserved.
            </p>
        </div>

        <!-- Form Panel -->
        <div class="flex flex-col justify-center px-6 py-12 sm:px-12 lg:px-16 xl:px-24">
            <div class="mx-auto w-full max-w-sm">
                <!-- Logo (mobile only, panel kiri disembunyikan) -->
                <a href="<?= base_url('/') ?>" class="mb-8 flex items-center justify-center lg:hidden">
                    <img
                        src="<?= base_url('assets/images/logos/kyurie-2.png') ?>"
                        alt="Logo"
                        class="h-11 w-11 object-contain"
                    >
                </a>

                <?= $this->renderSection('content') ?>
            </div>
        </div>
    </div>

    <?= view('Components/Layouts/Auth/Scripts') ?>
</body>
</html>