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
    class="fixed right-0 top-0 z-50 h-screen w-80 bg-white shadow-xl"
>
    <div class="flex h-20 items-center justify-between border-b px-5">
        <span class="text-lg font-semibold">Menu</span>
        <button @click="mobileMenu = false" class="text-xl">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>
    <nav class="flex flex-col gap-1 p-4">
        <?php foreach ($menus as $menu): ?>
            <a
                href="<?= $menu['url'] ?>"
                class="flex items-center gap-3 rounded-lg px-3 py-3 transition
                <?= $current === $menu['match'] ? 'bg-primary/10 text-primary font-semibold' : 'text-slate-700 hover:bg-slate-100' ?>"
            >
                <i class="bi <?= $menu['icon'] ?> text-lg"></i>
                <span><?= esc($menu['title']) ?></span>
            </a>
        <?php endforeach; ?>
    </nav>
    <div class="absolute bottom-0 left-0 w-full border-t p-4">
        <?php if ($user): ?>
            <div class="text-sm text-slate-600">
                Signed in as <b><?= esc($user['username']) ?></b>
            </div>
        <?php else: ?>
            <div class="flex gap-2">
                <a href="<?= base_url('login') ?>" class="btn btn-outline w-full">
                    Login
                </a>
                <a href="<?= base_url('register') ?>" class="btn btn-primary w-full">
                    Daftar
                </a>
            </div>
        <?php endif; ?>
    </div>
</aside>