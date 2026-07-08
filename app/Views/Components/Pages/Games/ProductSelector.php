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
                    :class="{ 'is-selected': selectedProductId === <?= (int) $product['id'] ?> }">
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