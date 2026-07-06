const copyButtons = document.querySelectorAll('[data-copy-value]');
const invoiceInput = document.querySelector('[data-invoice-input]');
const checkForm = document.querySelector('[data-payment-check-form]');

const showToast = (message, icon = 'success') => {
    if (!window.Toast) return;

    window.Toast.fire({
        icon,
        title: message,
    });
};

copyButtons.forEach((button) => {
    button.addEventListener('click', async () => {
        const value = button.dataset.copyValue ?? '';
        const label = button.dataset.copyLabel ?? 'Data disalin';

        if (!value) {
            showToast('Data belum tersedia', 'error');
            return;
        }

        try {
            await navigator.clipboard.writeText(value);
            showToast(label);
        } catch (error) {
            showToast('Gagal menyalin data', 'error');
        }
    });
});

if (invoiceInput) {
    invoiceInput.addEventListener('input', () => {
        invoiceInput.value = invoiceInput.value.trimStart().toUpperCase();
    });
}

if (checkForm) {
    checkForm.addEventListener('submit', () => {
        const submitButton = checkForm.querySelector('button[type="submit"]');

        if (!submitButton) return;

        submitButton.disabled = true;
        submitButton.classList.add('opacity-70', 'cursor-not-allowed');
        submitButton.innerHTML = '<i class="bi bi-arrow-repeat animate-spin"></i> Mengecek...';
    });
}
