<div class="sticky top-24">
    <form action="<?= base_url('order/create') ?>" method="POST">
        <?= csrf_field() ?>
        <input type="hidden" name="game" value="<?= esc($game['slug']) ?>">
        <input type="hidden" name="product_id" :value="selectedProductId">
        <input type="hidden" name="payment_method_id" :value="selectedPaymentMethodId">
        <template x-for="input in targetForm.inputs" :key="input.key">
            <input
                type="hidden"
                :name="input.key"
                :value="targetValues[input.key] ?? ''">
        </template>
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
                        x-text="selectedProduct ? selectedProduct.product : '—'"></span>
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
                class="btn btn-primary mt-5 w-full disabled:cursor-not-allowed disabled:opacity-40">
                Bayar Sekarang
            </button>
        </div>
    </form>
</div>