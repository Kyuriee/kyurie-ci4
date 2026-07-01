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

            <!-- Search -->
           <div class="hidden flex-1 justify-center lg:flex">
                <form
                    class="relative w-full max-w-md"
                    @submit.prevent="submitSearch"
                >
                    <input
                        x-model="searchKeyword"
                        type="search"
                        placeholder="Cari game atau produk"
                        class="input rounded-full bg-slate-50 pr-12"
                    >
                    <button
                        type="submit"
                        class="absolute right-4 top-1/2 -translate-y-1/2 text-muted transition hover:text-primary"
                    >
                        <i class="bi bi-search"></i>
                    </button>
                </form>
            </div>
            <!-- Right -->
            <?php if ($user): ?>
                <div class="hidden items-center gap-3 lg:flex">
                    <button class="wallet-button">
                        <div class="wallet-icon">
                            <i class="bi bi-wallet2"></i>
                        </div>
                        <span class="wallet-balance">
                            Rp <?= number_format($user['balance'], 0, ',', '.') ?>
                        </span>
                        <div class="wallet-add">
                            <i class="bi bi-plus-lg"></i>
                        </div>
                    </button>
                    <div class="relative">
                        <button
                            @click="userMenu = !userMenu"
                            class="flex items-center gap-3 rounded-xl border border-slate-200 bg-white px-3 py-2 transition hover:border-primary"
                        >
                            <div
                                class="flex h-10 w-10 items-center justify-center rounded-full bg-primary text-sm font-bold text-white"
                            >
                                <?= strtoupper(substr($user['username'], 0, 1)) ?>
                            </div>
                            <div class="text-left">
                                <div class="text-sm font-semibold text-heading">
                                    <?= esc($user['username']) ?>
                                </div>
                                <div class="text-xs text-muted">
                                    <?= esc($user['level']) ?>
                                </div>
                            </div>
                            <i class="bi bi-chevron-down"></i>
                        </button>
                        <!-- dropdown -->
                    </div>
                </div>
            <?php else: ?>
                <div class="hidden items-center gap-3 lg:flex">
                    <a href="<?= base_url('login') ?>" class="btn btn-outline">
                        Login
                    </a>
                    <a href="<?= base_url('register') ?>" class="btn btn-primary">
                        Daftar
                    </a>
                </div>
            <?php endif; ?>
            <!-- Mobile -->
            <button
                @click="mobileMenu = true"
                class="flex lg:hidden mobile-menu-button"
            >
                <i class="bi bi-list"></i>
            </button>
        </div>
    </div>
</header>