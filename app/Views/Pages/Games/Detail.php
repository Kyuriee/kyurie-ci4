<?= $this->extend('Layouts/Main'); ?>

<?= $this->section('content'); ?>
<div
    x-data="gameDetail('<?= esc($game['slug'], 'js') ?>', <?= json_encode($products) ?>)"
    class="section"
>
    <div class="container-app">
        <!-- Hero -->
        <div class="game-hero">
            <img
                src="<?= !empty($game['banner']) ? base_url('assets/images/games/banners/' . $game['banner']) : 'https://placehold.co/1200x514' ?>"
                alt="<?= esc($game['games']) ?>"
                loading="lazy"
            >
            <div class="game-hero-scrim"></div>

            <div class="game-hero-content">
                <div class="game-hero-icon">
                    <img
                        src="<?= !empty($game['image']) ? base_url('assets/images/games/icons/' . $game['image']) : 'https://placehold.co/200x200' ?>"
                        alt=""
                        loading="lazy"
                    >
                </div>

                <div class="game-hero-title">
                    <h1><?= esc($game['games']) ?></h1>
                    <p>
                        <?= esc($game['publisher'] ?? '') ?>
                        <?php if (!empty($game['category'])) : ?>
                            &middot; <?= esc($game['category']) ?>
                        <?php endif ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="mt-8 grid grid-cols-1 gap-8 lg:grid-cols-3">
            <!-- Form Column -->
            <div class="space-y-8 lg:col-span-2">
                <!-- Step 1: Target ID -->
                <div>
                    <div class="section-title mb-4">
                        <div>
                            <h2 class="text-lg">1. Masukkan ID Akun</h2>
                            <p>Cek ulang ID kamu biar item masuk ke akun yang benar.</p>
                        </div>
                    </div>

                    <?php if (($game['target'] ?? 'default') === 'zone') : ?>
                        <div class="target-input-grid">
                            <div>
                                <label for="customer_id" class="mb-1.5 block text-sm font-semibold text-heading">
                                    User ID
                                </label>
                                <input
                                    x-model="customerId"
                                    type="text"
                                    id="customer_id"
                                    name="customer_id"
                                    placeholder="Contoh: 123456789"
                                    class="input"
                                >
                            </div>

                            <div>
                                <label for="zone_id" class="mb-1.5 block text-sm font-semibold text-heading">
                                    Zone ID
                                </label>
                                <input
                                    x-model="zoneId"
                                    type="text"
                                    id="zone_id"
                                    name="zone_id"
                                    placeholder="Contoh: 2001"
                                    class="input"
                                >
                            </div>
                        </div>
                    <?php else : ?>
                        <div>
                            <label for="customer_id" class="mb-1.5 block text-sm font-semibold text-heading">
                                User ID / Player ID
                            </label>
                            <input
                                x-model="customerId"
                                type="text"
                                id="customer_id"
                                name="customer_id"
                                placeholder="Masukkan ID akunmu"
                                class="input"
                            >
                        </div>
                    <?php endif ?>
                </div>

                <!-- Step 2: Denomination -->
                <div>
                    <div class="section-title mb-4">
                        <div>
                            <h2 class="text-lg">2. Pilih Nominal</h2>
                            <p>Semua harga sudah termasuk item digital.</p>
                        </div>
                    </div>

                    <?php if (empty($products)) : ?>
                        <div class="card p-8 text-center text-sm text-muted">
                            Produk untuk game ini belum tersedia.
                        </div>
                    <?php else : ?>
                        <div class="denom-grid">
                            <?php foreach ($products as $product) : ?>
                                <button
                                    type="button"
                                    @click="selectProduct(<?= (int) $product['id'] ?>)"
                                    class="denom-tile"
                                    :class="{ 'is-selected': selectedProductId === <?= (int) $product['id'] ?> }"
                                >
                                    <template x-if="selectedProductId === <?= (int) $product['id'] ?>">
                                        <div class="denom-tile-check">
                                            <i class="bi bi-check"></i>
                                        </div>
                                    </template>

                                    <?php if (!empty($product['is_flashsale'])) : ?>
                                        <span class="denom-tile-badge">Flash Sale</span>
                                    <?php endif ?>

                                    <span class="denom-tile-name"><?= esc($product['product']) ?></span>

                                    <span class="denom-tile-price">
                                        <?= esc($product['final_price_formatted']) ?>
                                    </span>

                                    <?php if (!empty($product['is_flashsale'])) : ?>
                                        <span class="denom-tile-price-normal">
                                            <?= esc($product['price_formatted']) ?>
                                        </span>
                                    <?php endif ?>
                                </button>
                            <?php endforeach ?>
                        </div>
                    <?php endif ?>
                </div>

                <!-- Step 3: Payment Method -->
                <div>
                    <div class="section-title mb-4">
                        <div>
                            <h2 class="text-lg">3. Metode Pembayaran</h2>
                            <p>Pilih salah satu metode buat menyelesaikan pesanan.</p>
                        </div>
                    </div>

                    <?php if (empty($payment_methods)) : ?>
                        <div class="card p-8 text-center text-sm text-muted">
                            Belum ada metode pembayaran aktif.
                        </div>
                    <?php else : ?>
                        <div class="payment-grid">
                            <?php foreach ($payment_methods as $method) : ?>
                                <button
                                    type="button"
                                    @click="selectPaymentMethod(<?= (int) $method['id'] ?>)"
                                    class="payment-tile"
                                    :class="{ 'is-selected': selectedPaymentMethodId === <?= (int) $method['id'] ?> }"
                                >
                                    <?php if (!empty($method['image'])) : ?>
                                        <img
                                            src="<?= base_url('assets/images/payments/' . $method['image']) ?>"
                                            alt=""
                                            loading="lazy"
                                        >
                                    <?php endif ?>

                                    <span class="payment-tile-name"><?= esc($method['name']) ?></span>
                                </button>
                            <?php endforeach ?>
                        </div>
                    <?php endif ?>
                </div>
            </div>

            <!-- Summary Column -->
            <div class="lg:col-span-1">
                <div class="sticky top-24">
                    <form action="<?= base_url('order/create') ?>" method="POST">
                        <?= csrf_field() ?>
                        <input type="hidden" name="product_id" :value="selectedProductId">
                        <input type="hidden" name="customer_id" x-model="customerId">
                        <input type="hidden" name="zone_id" x-model="zoneId">
                        <input type="hidden" name="payment_method_id" :value="selectedPaymentMethodId">

                        <div class="order-summary">
                            <h3 class="font-display text-base font-bold text-heading">Ringkasan Pesanan</h3>

                            <div class="mt-4 space-y-2">
                                <div class="order-summary-row">
                                    <span class="text-muted">Game</span>
                                    <span class="font-semibold text-heading"><?= esc($game['games']) ?></span>
                                </div>

                                <div class="order-summary-row">
                                    <span class="text-muted">Item</span>
                                    <span
                                        class="font-semibold text-heading"
                                        x-text="selectedProduct ? selectedProduct.product : '—'"
                                    ></span>
                                </div>
                            </div>

                            <div class="order-summary-divider"></div>

                            <template x-if="previewLoading">
                                <p class="flex items-center gap-2 text-sm text-muted">
                                    <i class="bi bi-arrow-repeat animate-spin"></i>
                                    Menghitung harga...
                                </p>
                            </template>

                            <template x-if="!previewLoading && previewError">
                                <p class="flex items-center gap-2 text-sm text-danger">
                                    <i class="bi bi-exclamation-circle"></i>
                                    <span x-text="previewError"></span>
                                </p>
                            </template>

                            <template x-if="!previewLoading && !previewError && !previewResult">
                                <p class="text-sm text-muted">
                                    Lengkapi ID akun, nominal, dan metode pembayaran buat lihat total harga.
                                </p>
                            </template>

                            <template x-if="!previewLoading && previewResult">
                                <div class="order-summary-total">
                                    <span>Total</span>
                                    <span x-text="previewResult?.price_formatted"></span>
                                </div>
                            </template>

                            <button
                                type="submit"
                                :disabled="!canSubmit"
                                class="btn btn-primary mt-5 w-full disabled:cursor-not-allowed disabled:opacity-40"
                            >
                                Bayar Sekarang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>