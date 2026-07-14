<?= $this->extend('Layouts/Main'); ?>

<?= $this->section('content'); ?>
<section class="section">
    <div class="container-app">
        <div class="mb-6">
            <h1 class="font-display text-2xl font-extrabold text-heading sm:text-3xl">Profil Saya</h1>
            <p class="mt-1 text-sm text-muted">Kelola informasi akun kamu.</p>
        </div>

        <div class="grid grid-cols-1 gap-5 lg:grid-cols-[240px_1fr]">
            <?= view('Components/Pages/User/Sidebar', ['active' => 'profile']) ?>

            <div class="space-y-5">
                <div class="card p-5 sm:p-6">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <p class="text-sm text-muted">Username</p>
                            <p class="font-display text-lg font-bold text-heading"><?= esc($profile['username'] ?? '-') ?></p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-muted">Level</p>
                            <span class="badge badge-primary"><?= esc($profile['level'] ?? 'Member') ?></span>
                        </div>
                    </div>
                    <div class="order-summary-divider"></div>
                    <div class="order-summary-row">
                        <span class="text-muted">Saldo</span>
                        <span class="font-semibold text-heading">
                            Rp<?= number_format((float) ($profile['balance'] ?? 0), 0, ',', '.') ?>
                        </span>
                    </div>
                </div>

                <form action="<?= base_url('user/update') ?>" method="POST" class="card p-5 sm:p-6">
                    <?= csrf_field() ?>
                    <h3 class="font-display text-base font-bold text-heading">Edit Kontak</h3>

                    <div class="mt-4">
                        <label for="email" class="mb-1.5 block text-sm font-semibold text-heading">Email</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="input"
                            value="<?= esc($profile['email'] ?? '') ?>"
                            placeholder="Email">
                    </div>

                    <div class="mt-4">
                        <label for="phone" class="mb-1.5 block text-sm font-semibold text-heading">No. HP/WhatsApp</label>
                        <input
                            type="tel"
                            id="phone"
                            name="phone"
                            class="input"
                            value="<?= esc($profile['phone'] ?? '') ?>"
                            placeholder="Contoh: +6281234567890">
                    </div>

                    <button type="submit" class="btn btn-primary mt-5 w-full sm:w-auto">
                        Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection(); ?>
