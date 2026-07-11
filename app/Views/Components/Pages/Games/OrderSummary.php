<div class="sticky top-24">
    <form action="<?= base_url('order/create') ?>" method="POST" x-ref="orderForm">
        <?= csrf_field() ?>
        <input type="hidden" name="game" value="<?= esc($game['slug']) ?>">
        <input type="hidden" name="product_id" :value="selectedProductId">
        <input type="hidden" name="payment_method_id" :value="selectedPaymentMethodId">
        <input type="hidden" name="coupon_code" :value="couponCode">
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

                <div class="order-summary-row">
                    <span class="text-muted">Metode Pembayaran</span>
                    <span
                        class="font-semibold text-heading"
                        x-text="selectedPaymentMethod ? selectedPaymentMethod.name : '—'"></span>
                </div>
            </div>

            <div class="order-summary-divider"></div>

            <div class="mb-3 space-y-2">
                <p class="text-sm font-semibold text-heading">Kontak Konfirmasi <span class="font-normal text-muted">(opsional)</span></p>
                <input
                    type="email"
                    name="contact_email"
                    x-model="contactEmail"
                    @input.debounce.500ms="schedulePreview()"
                    placeholder="Email (opsional)"
                    class="input w-full">
                <input
                    type="tel"
                    name="contact_phone"
                    x-model="contactPhone"
                    @input.debounce.500ms="schedulePreview()"
                    placeholder="No. HP/WhatsApp, contoh: +6281234567890"
                    class="input w-full">
            </div>

            <div class="order-summary-divider"></div>

            <div class="mb-3">
                <label class="mb-1.5 block text-sm font-semibold text-heading">Kode Kupon</label>
                <template x-if="!couponCode">
                    <div class="flex gap-2">
                        <input
                            type="text"
                            x-model="couponInput"
                            @keydown.enter.prevent="applyCoupon()"
                            placeholder="Masukkan kode kupon"
                            class="input flex-1 uppercase">
                        <button
                            type="button"
                            @click="applyCoupon()"
                            :disabled="!couponInput.trim()"
                            class="btn btn-secondary shrink-0 disabled:cursor-not-allowed disabled:opacity-40">
                            Terapkan
                        </button>
                    </div>
                </template>
                <template x-if="couponCode">
                    <div class="flex items-center justify-between rounded-lg bg-success/10 px-3 py-2">
                        <span class="text-sm font-semibold text-success" x-text="couponCode"></span>
                        <button type="button" @click="removeCoupon()" class="text-sm text-muted hover:text-danger">
                            Hapus
                        </button>
                    </div>
                </template>
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
                <div class="space-y-1.5">
                    <template x-if="previewResult?.coupon_discount > 0">
                        <div class="order-summary-row text-sm">
                            <span class="text-muted">Subtotal</span>
                            <span x-text="previewResult?.subtotal_formatted"></span>
                        </div>
                    </template>
                    <template x-if="previewResult?.coupon_discount > 0">
                        <div class="order-summary-row text-sm">
                            <span class="text-muted">Diskon Kupon</span>
                            <span class="text-success" x-text="'-' + previewResult?.coupon_discount_formatted"></span>
                        </div>
                    </template>
                    <div class="order-summary-total">
                        <span>Total</span>
                        <span x-text="previewResult?.price_formatted"></span>
                    </div>
                </div>
            </template>
            <button
                type="button"
                @click="openConfirm()"
                :disabled="!canSubmit"
                class="btn btn-primary mt-5 w-full disabled:cursor-not-allowed disabled:opacity-40">
                Bayar Sekarang
            </button>
        </div>
    </form>

    <div
        x-show="showConfirmModal"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
        @keydown.escape.window="closeConfirm()">
        <div
            x-show="showConfirmModal"
            x-transition
            @click.outside="closeConfirm()"
            class="card w-full max-w-md p-6">
            <h3 class="font-display text-lg font-bold text-heading">Konfirmasi Pesanan</h3>
            <p class="mt-1 text-sm text-muted">Pastikan data di bawah ini sudah benar sebelum lanjut bayar.</p>

            <div class="order-summary-divider"></div>

            <div class="space-y-2 text-sm">
                <div class="order-summary-row">
                    <span class="text-muted">Game</span>
                    <span class="font-semibold text-heading"><?= esc($game['games']) ?></span>
                </div>
                <div class="order-summary-row">
                    <span class="text-muted">Item</span>
                    <span class="font-semibold text-heading" x-text="selectedProduct ? selectedProduct.product : '—'"></span>
                </div>
                <template x-for="input in targetForm.inputs" :key="'confirm-' + input.key">
                    <div class="order-summary-row" x-show="targetValues[input.key]">
                        <span class="text-muted" x-text="input.label"></span>
                        <span class="font-semibold text-heading" x-text="targetValues[input.key]"></span>
                    </div>
                </template>
                <div class="order-summary-row">
                    <span class="text-muted">Metode Pembayaran</span>
                    <span class="font-semibold text-heading" x-text="selectedPaymentMethod ? selectedPaymentMethod.name : '—'"></span>
                </div>
                <div class="order-summary-row" x-show="contactEmail">
                    <span class="text-muted">Email</span>
                    <span class="font-semibold text-heading" x-text="contactEmail"></span>
                </div>
                <div class="order-summary-row" x-show="contactPhone">
                    <span class="text-muted">No. HP/WhatsApp</span>
                    <span class="font-semibold text-heading" x-text="contactPhone"></span>
                </div>
                <div class="order-summary-row" x-show="couponCode">
                    <span class="text-muted">Kupon</span>
                    <span class="font-semibold text-success" x-text="couponCode"></span>
                </div>
            </div>

            <div class="order-summary-divider"></div>

            <div class="space-y-1.5">
                <template x-if="previewResult?.coupon_discount > 0">
                    <div class="order-summary-row text-sm">
                        <span class="text-muted">Subtotal</span>
                        <span x-text="previewResult?.subtotal_formatted"></span>
                    </div>
                </template>
                <template x-if="previewResult?.coupon_discount > 0">
                    <div class="order-summary-row text-sm">
                        <span class="text-muted">Diskon Kupon</span>
                        <span class="text-success" x-text="'-' + previewResult?.coupon_discount_formatted"></span>
                    </div>
                </template>
                <div class="order-summary-total">
                    <span>Total</span>
                    <span x-text="previewResult?.price_formatted"></span>
                </div>
            </div>

            <div class="mt-5 flex gap-3">
                <button type="button" @click="closeConfirm()" class="btn btn-outline flex-1">
                    Periksa Lagi
                </button>
                <button type="button" @click="confirmSubmit()" class="btn btn-primary flex-1">
                    Ya, Sudah Benar
                </button>
            </div>
        </div>
    </div>
</div>