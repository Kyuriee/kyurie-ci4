<?php
$status = strtolower($order['status'] ?? 'pending');
$statusLabels = [
    'pending'    => 'Menunggu Pembayaran',
    'processing' => 'Sedang Diproses',
    'success'    => 'Berhasil',
    'failed'     => 'Gagal',
    'expired'    => 'Kedaluwarsa',
    'cancelled'  => 'Dibatalkan',
];
$statusClass = [
    'pending'    => 'badge-warning',
    'processing' => 'badge-primary',
    'success'    => 'badge-success',
    'failed'     => 'badge-danger',
    'expired'    => 'badge-danger',
    'cancelled'  => 'badge-danger',
][$status] ?? 'badge-warning';

$totalFormatted = 'Rp ' . number_format((float) ($order['total'] ?? 0), 0, ',', '.');
$priceFormatted = 'Rp ' . number_format((float) ($order['price'] ?? 0), 0, ',', '.');
$feeFormatted   = 'Rp ' . number_format((float) ($order['fee'] ?? 0), 0, ',', '.');
$zoneId         = trim((string) ($order['zone_id'] ?? ''));
$zoneDisplay    = $zoneId !== '' ? str_replace(',', ', ', $zoneId) : '-';
$paymentName    = $order['payment_method_name'] ?? 'Metode pembayaran';
?>

<?= $this->extend('Layouts/Main'); ?>

<?= $this->section('content'); ?>
<section class="section">
    <div class="container-app">
        <div class="mx-auto max-w-5xl">
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <span class="badge <?= esc($statusClass, 'attr') ?>">
                        <?= esc($statusLabels[$status] ?? ucfirst($status)) ?>
                    </span>
                    <h1 class="mt-4 font-display text-2xl font-extrabold text-heading sm:text-3xl">
                        Detail Pembayaran
                    </h1>
                    <p class="mt-2 text-sm text-muted">
                        Simpan halaman ini untuk memantau status pesanan tanpa perlu login.
                    </p>
                </div>

                <a href="<?= base_url('payment/check') ?>" class="btn btn-outline">
                    <i class="bi bi-search"></i>
                    Cek Invoice Lain
                </a>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <div class="space-y-6 lg:col-span-2">
                    <div class="card p-5 sm:p-6">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-muted">Invoice</p>
                                <h2 class="mt-1 font-display text-xl font-extrabold text-heading">
                                    <?= esc($order['invoice'] ?? '-') ?>
                                </h2>
                            </div>

                            <div class="rounded-xl border border-border bg-background px-4 py-3 text-sm">
                                <span class="block text-xs font-semibold uppercase tracking-wide text-muted">Total Bayar</span>
                                <span class="mt-1 block text-lg font-extrabold text-primary"><?= esc($totalFormatted) ?></span>
                            </div>
                        </div>

                        <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-muted">Game</p>
                                <p class="mt-1 font-semibold text-heading"><?= esc($order['game_name'] ?? ($game['games'] ?? '-')) ?></p>
                            </div>
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-muted">Produk</p>
                                <p class="mt-1 font-semibold text-heading"><?= esc($order['product_name'] ?? '-') ?></p>
                            </div>
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-muted">User ID</p>
                                <p class="mt-1 font-semibold text-heading"><?= esc($order['customer_id'] ?? '-') ?></p>
                            </div>
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-muted">Zone ID</p>
                                <p class="mt-1 font-semibold text-heading"><?= esc($zoneDisplay) ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="card p-5 sm:p-6">
                        <h2 class="font-display text-lg font-extrabold text-heading">Metode Pembayaran</h2>
                        <div class="mt-4 flex items-center gap-4 rounded-xl border border-border bg-background p-4">
                            <?php if (! empty($order['payment_method_image'])) : ?>
                                <img
                                    src="<?= base_url('assets/images/payments/' . $order['payment_method_image']) ?>"
                                    alt=""
                                    class="h-10 w-10 shrink-0 rounded-lg object-contain"
                                    loading="lazy"
                                >
                            <?php else : ?>
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-primary/10 text-primary">
                                    <i class="bi bi-credit-card"></i>
                                </div>
                            <?php endif ?>

                            <div>
                                <p class="font-semibold text-heading"><?= esc($paymentName) ?></p>
                                <p class="mt-0.5 text-sm text-muted">
                                    Selesaikan pembayaran sesuai instruksi metode yang kamu pilih.
                                </p>
                            </div>
                        </div>

                        <?php if ($status === 'pending') : ?>
                            <p class="mt-4 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                                Pesanan masih menunggu pembayaran. Setelah pembayaran terkonfirmasi, status akan berubah otomatis atau melalui proses admin.
                            </p>
                        <?php elseif ($status === 'success') : ?>
                            <p class="mt-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                                Pembayaran berhasil. Pesanan akan diproses sesuai alur layanan.
                            </p>
                        <?php endif ?>
                    </div>
                </div>

                <aside class="lg:col-span-1">
                    <div class="sticky top-24 rounded-2xl border border-border bg-white p-5 shadow-soft">
                        <h2 class="font-display text-lg font-extrabold text-heading">Ringkasan</h2>

                        <div class="mt-4 space-y-3 text-sm">
                            <div class="flex items-center justify-between gap-4">
                                <span class="text-muted">Harga</span>
                                <span class="font-semibold text-heading"><?= esc($priceFormatted) ?></span>
                            </div>
                            <div class="flex items-center justify-between gap-4">
                                <span class="text-muted">Biaya</span>
                                <span class="font-semibold text-heading"><?= esc($feeFormatted) ?></span>
                            </div>
                            <div class="border-t border-dashed border-border pt-3">
                                <div class="flex items-center justify-between gap-4 text-base">
                                    <span class="font-semibold text-heading">Total</span>
                                    <span class="font-extrabold text-primary"><?= esc($totalFormatted) ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 space-y-2 text-xs text-muted">
                            <p>Dibuat: <?= esc($order['created_at'] ?? '-') ?></p>
                            <?php if (! empty($order['paid_at'])) : ?>
                                <p>Dibayar: <?= esc($order['paid_at']) ?></p>
                            <?php endif ?>
                        </div>

                        <a href="<?= base_url('/') ?>" class="btn btn-primary mt-5 w-full">
                            Kembali ke Beranda
                        </a>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection(); ?>
