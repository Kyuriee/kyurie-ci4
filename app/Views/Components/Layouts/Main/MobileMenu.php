<?php
$current = service('uri')->getSegment(1);
?>
<aside
    x-cloak
    x-show="mobileMenu"
    x-transition:enter="transition duration-300"
    x-transition:enter-start="translate-x-full"
    x-transition:enter-end="translate-x-0"
    x-transition:leave="transition duration-300"
    x-transition:leave-start="translate-x-0"
    x-transition:leave-end="translate-x-full"
    class="fixed right-0 top-0 z-50 h-screen w-80 bg-surface shadow-xl border-l border-border">
    <div class="flex h-20 items-center justify-between border-b border-border px-5">
        <span class="text-lg font-bold font-display text-heading">Menu Navigasi</span>
        <button @click="mobileMenu = false" class="text-xl text-body hover:text-heading transition-colors cursor-pointer">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>
    <?php if ($user): ?>
        <div class="border-b border-border p-5 bg-surface-soft">
            <div class="flex items-center gap-3 mb-4">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-primary text-sm font-bold text-white shadow-md shadow-primary/20">
                    <?= strtoupper(substr($user['username'], 0, 1)) ?>
                </div>
                <div>
                    <div class="font-bold text-heading font-display leading-tight"><?= esc($user['username']) ?></div>
                    <div class="text-[11px] text-muted font-semibold uppercase tracking-wider mt-0.5"><?= esc($user['level']) ?></div>
                </div>
            </div>
            <div class="flex items-center justify-between rounded-xl border border-border bg-surface p-3 shadow-xs">
                <div class="flex items-center gap-2.5">
                    <i class="bi bi-wallet2 text-primary text-lg"></i>
                    <div>
                        <div class="text-[10px] uppercase tracking-wider text-muted font-bold leading-none">Saldo Akun</div>
                        <div class="text-sm font-bold text-heading mt-1 leading-none">Rp <?= number_format($user['balance'], 0, ',', '.') ?></div>
                    </div>
                </div>
                <a href="<?= base_url('wallet/topup') ?>" class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary/10 text-primary hover:bg-primary hover:text-white transition-all">
                    <i class="bi bi-plus-lg"></i>
                </a>
            </div>
        </div>
    <?php endif; ?>
    <nav class="flex flex-col gap-1 p-4 overflow-y-auto max-h-[calc(100vh-290px)]">
        <?php foreach ($menus as $menu): ?>
            <a
                href="<?= $menu['url'] ?>"
                class="flex items-center gap-3 rounded-xl px-3 py-2.5 transition-all
                <?= $current === $menu['match'] ? 'bg-primary/10 text-primary font-bold' : 'text-body hover:bg-surface-soft hover:text-heading' ?>">
                <i class="bi <?= $menu['icon'] ?> text-lg"></i>
                <span class="text-sm font-medium"><?= esc($menu['title']) ?></span>
            </a>
        <?php endforeach; ?>
        <?php if ($user): ?>
            <hr class="border-border my-2">
            <a href="<?= base_url('user/profile') ?>" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-body hover:bg-surface-soft hover:text-heading transition-all">
                <i class="bi bi-person text-lg text-muted"></i>
                <span class="text-sm font-medium">Pengaturan Profil</span>
            </a>
            <a href="<?= base_url('user/transactions') ?>" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-body hover:bg-surface-soft hover:text-heading transition-all">
                <i class="bi bi-clock-history text-lg text-muted"></i>
                <span class="text-sm font-medium">Riwayat Transaksi</span>
            </a>
        <?php endif; ?>
    </nav>
    <div class="absolute bottom-0 left-0 w-full border-t border-border p-4 bg-surface">
        <?php if ($user): ?>
            <a href="<?= base_url('auth/logout') ?>" class="flex w-full items-center justify-center gap-2 rounded-xl bg-danger/10 py-2.5 text-sm font-bold text-danger hover:bg-danger hover:text-white transition-all">
                <i class="bi bi-box-arrow-right"></i>
                <span>Keluar Aplikasi</span>
            </a>
        <?php else: ?>
            <div class="flex gap-2">
                <a href="<?= base_url('auth/login') ?>" class="flex w-full justify-center rounded-xl border border-border bg-white py-2 text-sm font-bold text-heading hover:bg-surface-soft transition">
                    Login
                </a>
                <a href="<?= base_url('auth/register') ?>" class="flex w-full justify-center rounded-xl bg-primary py-2 text-sm font-bold text-white shadow-md shadow-primary/10 hover:bg-primary-600 transition">
                    Daftar
                </a>
            </div>
        <?php endif; ?>
    </div>
</aside>