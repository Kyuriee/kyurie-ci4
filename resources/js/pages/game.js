import Alpine from 'alpinejs';
import axios from 'axios';

document.addEventListener('alpine:init', () => {
    Alpine.data('gameDetail', (gameSlug, initialProducts, targetForm) => ({
        gameSlug,
        products: initialProducts ?? [],
        targetForm: targetForm ?? { inputs: [] },
        targetValues: {},
        selectedProductId: null,
        selectedPaymentMethodId: null,

        previewLoading: false,
        previewResult: null,
        previewError: '',
        debounceTimer: null,

        init() {
            (this.targetForm.inputs ?? []).forEach((input) => {
                this.targetValues[input.key] = '';
            });

            this.$watch('selectedProductId', () => this.schedulePreview());
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

            if (!this.selectedProductId || !this.selectedPaymentMethodId || !this.hasRequiredTargetInputs) {
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
                    ...this.targetPayload,
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
                && this.hasRequiredTargetInputs
                && !!this.selectedPaymentMethodId
                && !this.previewLoading
                && !!this.previewResult
                && this.previewError === '';
        },

        get hasRequiredTargetInputs() {
            return (this.targetForm.inputs ?? []).every((input) => {
                if (!input.required) {
                    return true;
                }

                return String(this.targetValues[input.key] ?? '').trim().length > 0;
            });
        },

        get targetPayload() {
            return (this.targetForm.inputs ?? []).reduce((payload, input) => {
                payload[input.key] = String(this.targetValues[input.key] ?? '').trim();
                return payload;
            }, {});
        },
    }));
});
