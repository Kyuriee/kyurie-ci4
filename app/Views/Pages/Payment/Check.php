<?= $this->extend('Layouts/Main'); ?>

<?= $this->section('content'); ?>
<section class="section">
    <div class="container-app">
        <div class="mx-auto max-w-xl">
            <div class="mb-6 text-center">
                <span class="badge badge-primary">
                    <i class="bi bi-receipt"></i>
                    Cek Pembayaran
                </span>
                <h1 class="mt-4 font-display text-2xl font-extrabold text-heading sm:text-3xl">
                    Cek Status Pesanan
                </h1>
                <p class="mt-2 text-sm text-muted">
                    Masukkan nomor invoice untuk membuka halaman pembayaran guest.
                </p>
            </div>

            <form action="<?= base_url('payment/check') ?>" method="POST" class="card p-5 sm:p-6">
                <?= csrf_field() ?>

                <label for="invoice" class="mb-1.5 block text-sm font-semibold text-heading">
                    Nomor Invoice
                </label>
                <input
                    type="text"
                    id="invoice"
                    name="invoice"
                    class="input"
                    placeholder="Contoh: INV/20260706/0001"
                    autocomplete="off"
                    required
                >

                <button type="submit" class="btn btn-primary mt-5 w-full">
                    <i class="bi bi-search"></i>
                    Cek Pembayaran
                </button>
            </form>

            <p class="mt-5 text-center text-sm text-muted">
                Invoice tersedia setelah kamu membuat pesanan.
            </p>
        </div>
    </div>
</section>
<?= $this->endSection(); ?>
