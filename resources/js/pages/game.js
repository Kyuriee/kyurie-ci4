import Alpine from 'alpinejs';
import axios from 'axios';

document.addEventListener('alpine:init', () => {
    Alpine.data('gameDetail', (gameSlug, initialProducts) => ({
        gameSlug,
        products: initialProducts ?? [],
        selectedProductId: null,
        customerId: '',
        zoneId: '',
        selectedPaymentMethodId: null,

        previewLoading: false,
        previewResult: null,
        previewError: '',
        debounceTimer: null,

        init() {
            this.$watch('selectedProductId', () => this.schedulePreview());
            this.$watch('customerId', () => this.schedulePreview());
            this.$watch('zoneId', () => this.schedulePreview());
            this.$watch('selectedPaymentMethodId', () => this.schedulePreview());
        },

        selectProduct(id) {
            this.selectedProductId = Number(id);
        },

        selectPaymentMethod(id) {
            this.selectedPaymentMethodId = Number(id);
        },

        get selectedProduct() {
            return this.products.find((p) => Number(p.id) === Number(this.selectedProductId)) ?? null;
        },

        schedulePreview() {
            clearTimeout(this.debounceTimer);
            this.previewError = '';

            if (!this.selectedProductId || this.customerId.trim().length === 0) {
                this.previewResult = null;
                return;
            }

            this.debounceTimer = setTimeout(() => this.fetchPreview(), 500);
        },

        async fetchPreview() {
            this.previewLoading = true;

            try {
                const { data } = await axios.post('/order/prepare', {
                    game: this.gameSlug,
                    product_id: this.selectedProductId,
                    payment_method_id: this.selectedPaymentMethodId,
                    customer_id: this.customerId,
                    zone_id: this.zoneId,
                });

                if (data.success) {
                    this.previewResult = data.data;
                } else {
                    this.previewResult = null;
                    this.previewError = data.message;
                }
            } catch (error) {
                this.previewResult = null;
                this.previewError = 'Gagal memuat preview harga. Coba lagi.';
            } finally {
                this.previewLoading = false;
            }
        },

        get canSubmit() {
            return !!this.selectedProductId
                && this.customerId.trim().length > 0
                && !!this.selectedPaymentMethodId
                && !this.previewLoading
                && !!this.previewResult
                && this.previewError === '';
        },
    }));
});