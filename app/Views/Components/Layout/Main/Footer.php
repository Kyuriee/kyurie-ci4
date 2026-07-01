<footer class="mt-20 border-t border-slate-200 bg-white">
    <div class="container-app py-14">

        <div class="grid gap-10 lg:grid-cols-12">

            <!-- Brand -->
            <div class="lg:col-span-5">

                <div class="flex items-center gap-3">

                    <?php if (!empty($meta['logo'])) : ?>

                        <img
                            src="<?= base_url($meta['logo']) ?>"
                            alt="<?= esc($meta['site_name'] ?? '') ?>"
                            class="h-12 w-12 object-contain"
                        >

                    <?php endif; ?>

                    <div>

                        <h2 class="font-display text-xl font-bold text-heading">
                            <?= esc($meta['site_name'] ?? '') ?>
                        </h2>

                        <?php if (!empty($meta['subtitle'])) : ?>

                            <p class="text-sm text-muted">
                                <?= esc($meta['subtitle']) ?>
                            </p>

                        <?php endif; ?>

                    </div>

                </div>

                <?php if (!empty($meta['description'])) : ?>

                    <p class="mt-5 max-w-md leading-7 text-body">
                        <?= esc($meta['description']) ?>
                    </p>

                <?php endif; ?>

            </div>

            <!-- Navigation -->

            <div class="lg:col-span-2">

                <h3 class="mb-5 font-display text-base font-bold text-heading">
                    Navigasi
                </h3>

                <ul class="space-y-3 text-sm">

                    <li>
                        <a
                            href="<?= base_url('/') ?>"
                            class="transition hover:text-primary"
                        >
                            Home
                        </a>
                    </li>

                    <li>
                        <a
                            href="<?= base_url('games') ?>"
                            class="transition hover:text-primary"
                        >
                            Games
                        </a>
                    </li>

                    <li>
                        <a
                            href="<?= base_url('promo') ?>"
                            class="transition hover:text-primary"
                        >
                            Promo
                        </a>
                    </li>

                </ul>

            </div>
            <!-- Bantuan -->
            <div class="lg:col-span-2">
                <h3 class="mb-5 font-display text-base font-bold text-heading">
                    Bantuan
                </h3>
                <ul class="space-y-3 text-sm">
                    <li>
                        <a
                            href="#"
                            class="transition hover:text-primary"
                        >
                            FAQ
                        </a>
                    </li>
                    <li>
                        <a
                            href="#"
                            class="transition hover:text-primary"
                        >
                            Cara Top Up
                        </a>
                    </li>
                    <li>
                        <a
                            href="#"
                            class="transition hover:text-primary"
                        >
                            Hubungi Kami
                        </a>
                    </li>
                </ul>
            </div>
            <!-- Contact -->
            <div class="lg:col-span-3">
                <h3 class="mb-5 font-display text-base font-bold text-heading">
                    Kontak
                </h3>
                <ul class="space-y-3 text-sm">
                    <li>
                        <?= esc($meta['author']) ?>
                    </li>
                    <li>
                        support@example.com
                    </li>
                    <li>
                        Indonesia
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="border-t border-slate-200">
        <div class="container-app flex flex-col items-center justify-between gap-3 py-5 text-sm text-muted md:flex-row">
            <p>
                © <?= date('Y') ?>
                <?= esc($meta['site_name'] ?? '') ?>.
                All rights reserved.
            </p>
            <div class="flex items-center gap-5">
                <a
                    href="#"
                    class="hover:text-primary"
                >
                    Privacy Policy
                </a>
                <a
                    href="#"
                    class="hover:text-primary"
                >
                    Terms
                </a>
            </div>
        </div>
    </div>
</footer>