<?php
$active = $active ?? 'profile';
$menu = [
    'profile'  => ['label' => 'Profil', 'icon' => 'bi-person', 'url' => 'user/profile'],
    'orders'   => ['label' => 'Riwayat Order', 'icon' => 'bi-receipt', 'url' => 'user/transactions'],
    'deposit'  => ['label' => 'Deposit', 'icon' => 'bi-wallet2', 'url' => null],
    'deposit_history' => ['label' => 'Riwayat Deposit', 'icon' => 'bi-clock-history', 'url' => null],
    'settings' => ['label' => 'Pengaturan', 'icon' => 'bi-gear', 'url' => 'user/settings'],
];
?>
<aside class="card p-3 sm:p-4">
    <nav class="space-y-1">
        <?php foreach ($menu as $key => $item): ?>
            <?php if ($item['url']): ?>
                <a
                    href="<?= base_url($item['url']) ?>"
                    class="flex items-center gap-2.5 rounded-lg px-3 py-2.5 text-sm font-semibold transition <?= $active === $key ? 'bg-primary/10 text-primary' : 'text-muted hover:bg-surface-soft hover:text-heading' ?>"
                >
                    <i class="bi <?= esc($item['icon']) ?>"></i>
                    <?= esc($item['label']) ?>
                </a>
            <?php else: ?>
                <span class="flex cursor-not-allowed items-center gap-2.5 rounded-lg px-3 py-2.5 text-sm font-semibold text-muted/50">
                    <i class="bi <?= esc($item['icon']) ?>"></i>
                    <?= esc($item['label']) ?>
                    <span class="badge ml-auto text-[10px]">Segera</span>
                </span>
            <?php endif; ?>
        <?php endforeach; ?>
    </nav>
</aside>
