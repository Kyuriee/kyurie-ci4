<?= $this->extend('Layouts/Main'); ?>

<?= $this->section('content'); ?>
<section class="section">
    <div class="container-app">
        <div class="mb-6">
            <h1 class="font-display text-2xl font-extrabold text-heading sm:text-3xl">Riwayat Order</h1>
            <p class="mt-1 text-sm text-muted">Daftar transaksi yang pernah kamu buat.</p>
        </div>

        <div class="grid grid-cols-1 gap-5 lg:grid-cols-[240px_1fr]">
            <?= view('Components/Pages/User/Sidebar', ['active' => 'orders']) ?>

            <div class="card">
                <?php if (empty($orders)): ?>
                    <div class="p-8 text-center">
                        <i class="bi bi-receipt text-3xl text-muted"></i>
                        <p class="mt-3 text-sm text-muted">Belum ada order.</p>
                    </div>
                <?php else: ?>
                    <div class="divide-y divide-border">
                        <?php foreach ($orders as $order): ?>
                            <a
                                href="<?= base_url('payment/' . esc($order['payment_token'] ?? '')) ?>"
                                class="flex items-center justify-between gap-3 p-4 transition hover:bg-surface-soft sm:p-5"
                            >
                                <div>
                                    <p class="font-semibold text-heading"><?= esc($order['product_name'] ?? '-') ?></p>
                                    <p class="text-sm text-muted"><?= esc($order['game_name'] ?? '-') ?> · <?= esc($order['invoice'] ?? '-') ?></p>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-heading">
                                        Rp<?= number_format((float) ($order['total'] ?? 0), 0, ',', '.') ?>
                                    </p>
                                    <?php
                                        $status = $order['status'] ?? 'pending';
                                        $statusBadge = match ($status) {
                                            'success' => 'badge-success',
                                            'failed', 'expired', 'cancelled' => 'badge-danger',
                                            default => 'badge-warning',
                                        };
                                    ?>
                                    <span class="badge <?= $statusBadge ?> mt-1"><?= esc(ucfirst($status)) ?></span>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <?php if (! empty($orders) && count($orders) === 10): ?>
            <div class="mt-5 flex justify-center">
                <a href="<?= base_url('user/transactions?page=' . ((int) $page + 1)) ?>" class="btn btn-outline">
                    Muat Lebih Banyak
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>
<?= $this->endSection(); ?>
