<?php
$current = service('uri')->getSegment(1);
?>

<header
    class="sticky top-0 z-50 border-b border-slate-200/70 bg-white/90 backdrop-blur-xl"
>
    <div class="container-app">
        <div class="flex h-20 items-center justify-between gap-6">
            <!-- Logo -->
            <a
                href="<?= base_url('/') ?>"
                class="flex shrink-0 items-center gap-3"
            >
                <img
                    src="<?= base_url('assets/images/logos/kyurie-2.png') ?>"
                    alt="Logo"
                    class="h-11 w-11 object-contain"
                >
            </a>
            <!-- Navigation -->
            <nav class="hidden items-center gap-1 lg:flex">
                <?php foreach ($menus as $menu): ?>
                    <a
                        href="<?= $menu['url'] ?>"
                        class="nav-link <?= $current === $menu['match'] ? 'nav-link-active' : '' ?>"
                    >
                        <i class="bi <?= $menu['icon'] ?>"></i>
                        <span><?= esc($menu['title']) ?></span>
                    </a>
                <?php endforeach; ?>
            </nav>

            <!-- Search (desktop) -->
            <div class="hidden flex-1 justify-center lg:flex">
                <div class="w-full max-w-md">
                    <?= view('Components/Layouts/Main/SearchBar') ?>
                </div>
            </div>

            <!-- Right -->
            <?php if ($user): ?>
                <div class="hidden items-center gap-4 lg:flex">
                    <!-- Dompet / Wallet Button -->
                    <a href="<?= base_url('wallet/topup') ?>" class="flex items-center border border-border rounded-xl bg-surface p-1 pr-3 shadow-xs hover:border-primary/70 transition group">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary/10 text-primary group-hover:bg-primary group-hover:text-white transition-all">
                            <i class="bi bi-wallet2"></i>
                        </div>
                        <div class="text-left ml-2.5">
                            <div class="text-[9px] uppercase tracking-wider text-muted font-bold leading-none">Saldo</div>
                            <span class="text-sm font-bold text-heading mt-0.5 inline-block leading-none">
                                Rp <?= number_format($user['balance'], 0, ',', '.') ?>
                            </span>
                        </div>
                        <div class="ml-4 text-[10px] bg-surface-soft border border-border h-5 w-5 rounded-md flex items-center justify-center text-body group-hover:bg-primary/10 group-hover:text-primary group-hover:border-primary/20 transition-all">
                            <i class="bi bi-plus-lg"></i>
                        </div>
                    </a>
                    <!-- User Dropdown Menu -->
                    <div class="relative" @click.away="userMenu = false">
                        <button
                            @click="userMenu = !userMenu"
                            class="flex items-center gap-3 rounded-xl border border-border bg-surface px-3 py-1.5 transition-all hover:border-primary cursor-pointer select-none"
                        >
                            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-primary text-sm font-bold text-white shadow-md shadow-primary/20">
                                <?= strtoupper(substr($user['username'], 0, 1)) ?>
                            </div>
                            <div class="text-left">
                                <div class="text-sm font-bold text-heading leading-tight font-display">
                                    <?= esc($user['username']) ?>
                                </div>
                                <div class="text-[9px] font-semibold text-muted uppercase tracking-wider mt-0.5">
                                    <?= esc($user['level']) ?>
                                </div>
                            </div>
                            <i class="bi bi-chevron-down text-xs text-muted transition-transform duration-200" :class="userMenu ? 'rotate-180' : ''"></i>
                        </button>
                        <!-- Dropdown Card List -->
                        <div
                            x-cloak
                            x-show="userMenu"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute right-0 mt-2 w-56 origin-top-right rounded-2xl border border-border bg-surface p-1.5 shadow-xl z-50"
                        >
                            <div class="px-3 py-2 border-b border-border mb-1.5">
                                <p class="text-[10px] uppercase tracking-wider text-muted font-bold">Email Terdaftar</p>
                                <p class="text-xs font-bold text-heading truncate mt-0.5"><?= esc($user['email'] ?? 'player@kyurie.com') ?></p>
                            </div>

                            <a href="<?= base_url('user/profile') ?>" class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-sm text-body hover:bg-surface-soft hover:text-heading transition-all">
                                <i class="bi bi-person text-base text-muted"></i>
                                <span>Profil Saya</span>
                            </a>
                            <a href="<?= base_url('user/transactions') ?>" class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-sm text-body hover:bg-surface-soft hover:text-heading transition-all">
                                <i class="bi bi-clock-history text-base text-muted"></i>
                                <span>Riwayat Transaksi</span>
                            </a>
                            <a href="<?= base_url('wallet/topup') ?>" class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-sm text-body hover:bg-surface-soft hover:text-heading transition-all">
                                <i class="bi bi-gem text-base text-muted"></i>
                                <span>Top Up Koin</span>
                            </a>
                            <hr class="border-border my-1.5">
                            <a href="<?= base_url('auth/logout') ?>" class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-sm font-bold text-danger hover:bg-danger/10 transition-all">
                                <i class="bi bi-box-arrow-right text-base"></i>
                                <span>Log Out</span>
                            </a>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="hidden items-center gap-3 lg:flex">
                    <a href="<?= base_url('auth/login') ?>" class="inline-flex items-center justify-center rounded-xl border border-border bg-white px-4 py-2 text-sm font-bold text-heading hover:bg-surface-soft transition cursor-pointer">
                        Login
                    </a>
                    <div class="relative">
                        <div class="premium-glow premium-glow-primary -z-10 -top-3 left-1/2 h-14 w-24 -translate-x-1/2 opacity-40"></div>
                        <a href="<?= base_url('auth/register') ?>" class="relative inline-flex items-center justify-center rounded-xl bg-primary px-4 py-2 text-sm font-bold text-white shadow-md shadow-primary/20 hover:bg-primary-600 transition cursor-pointer">
                            Daftar
                        </a>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Mobile triggers -->
            <div class="flex items-center gap-2 lg:hidden">
                <button
                    @click="mobileSearch = !mobileSearch"
                    class="mobile-menu-button"
                >
                    <i class="bi" :class="mobileSearch ? 'bi-x-lg' : 'bi-search'"></i>
                </button>

                <button
                    @click="mobileMenu = true"
                    class="mobile-menu-button"
                >
                    <i class="bi bi-list"></i>
                </button>
            </div>
        </div>

        <!-- Search (mobile) -->
        <div
            x-show="mobileSearch"
            x-cloak
            x-transition
            class="border-t border-slate-100 py-3 lg:hidden"
        >
            <?= view('Components/Layouts/Main/SearchBar') ?>
        </div>
    </div>
</header>