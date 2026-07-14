<?= $this->extend('Layouts/Main'); ?>

<?= $this->section('content'); ?>
<section class="section">
    <div class="container-app">
        <div class="mb-6">
            <h1 class="font-display text-2xl font-extrabold text-heading sm:text-3xl">Pengaturan Akun</h1>
            <p class="mt-1 text-sm text-muted">Kelola keamanan akun kamu.</p>
        </div>

        <div class="grid grid-cols-1 gap-5 lg:grid-cols-[240px_1fr]">
            <?= view('Components/Pages/User/Sidebar', ['active' => 'settings']) ?>

            <form action="<?= base_url('user/change-password') ?>" method="POST" class="card max-w-md p-5 sm:p-6">
                <?= csrf_field() ?>
                <h3 class="font-display text-base font-bold text-heading">Ubah Password</h3>

                <div class="mt-4">
                    <label for="current_password" class="mb-1.5 block text-sm font-semibold text-heading">
                        Password Saat Ini
                    </label>
                    <input
                        type="password"
                        id="current_password"
                        name="current_password"
                        class="input"
                        autocomplete="current-password"
                        required>
                </div>

                <div class="mt-4">
                    <label for="new_password" class="mb-1.5 block text-sm font-semibold text-heading">
                        Password Baru
                    </label>
                    <input
                        type="password"
                        id="new_password"
                        name="new_password"
                        class="input"
                        autocomplete="new-password"
                        minlength="8"
                        required>
                    <p class="mt-1 text-xs text-muted">Minimal 8 karakter.</p>
                </div>

                <button type="submit" class="btn btn-primary mt-5 w-full">
                    Simpan Password Baru
                </button>
            </form>
        </div>
    </div>
</section>
<?= $this->endSection(); ?>
