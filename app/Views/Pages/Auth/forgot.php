<?= $this->extend('Layouts/Auth') ?>

<?= $this->section('content') ?>
<div class="mb-8 text-center">
    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-primary text-white shadow-md shadow-primary/20">
        <i class="bi bi-key-fill text-2xl"></i>
    </div>

    <h2 class="mt-4 text-2xl font-bold tracking-tight text-heading">
        Lupa Password?
    </h2>

    <p class="mt-1.5 text-sm text-muted">
        Masukkan email akun Anda, kami kirim link buat reset password.
    </p>
</div>

<form action="<?= base_url('auth/forgot') ?>" method="POST" class="space-y-5">
    <?= csrf_field() ?>

    <!-- Input Email -->
    <div>
        <label for="email" class="mb-1.5 block text-sm font-semibold text-heading">
            Alamat Email
        </label>

        <div class="relative">
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted">
                <i class="bi bi-envelope"></i>
            </div>

            <input
                type="email"
                name="email"
                id="email"
                value="<?= esc(old('email') ?? '') ?>"
                required
                autofocus
                placeholder="nama@email.com"
                class="input input-icon"
            >
        </div>
    </div>

    <!-- Submit Button -->
    <button type="submit" class="btn btn-primary w-full">
        Kirim Link Reset
    </button>
</form>

<!-- Login Link Footer -->
<p class="mt-8 flex items-center justify-center gap-1.5 text-center text-sm text-muted">
    <i class="bi bi-arrow-left"></i>
    <a href="<?= base_url('auth/login') ?>" class="font-semibold text-primary hover:text-primary-600 transition-colors">
        Kembali ke Login
    </a>
</p>
<?= $this->endSection() ?>