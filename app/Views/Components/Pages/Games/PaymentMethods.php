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
                    :class="{ 'is-selected': selectedPaymentMethodId === <?= (int) $method['id'] ?> }">
                    <?php if (!empty($method['image'])) : ?>
                        <img
                            src="<?= base_url('assets/images/payments/' . $method['image']) ?>"
                            alt=""
                            loading="lazy">
                    <?php endif ?>
                    <span class="payment-tile-name"><?= esc($method['name']) ?></span>
                </button>
            <?php endforeach ?>
        </div>
    <?php endif ?>
</div>