<?= $this->extend('Layouts/Auth') ?>

<?= $this->section('content') ?>
<div
    x-data="{
        showPassword: false,
        showConfirmPassword: false,
        password: '',
        passwordConfirm: '',
    }"
>
    <div class="mb-8 text-center">
        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-primary text-white shadow-md shadow-primary/20">
            <i class="bi bi-shield-lock-fill text-2xl"></i>
        </div>

        <h2 class="mt-4 text-2xl font-bold tracking-tight text-heading">
            Buat Password Baru
        </h2>

        <p class="mt-1.5 text-sm text-muted">
            Password baru harus beda dari yang sebelumnya.
        </p>
    </div>

    <form action="<?= base_url('auth/reset/' . $token) ?>" method="POST" class="space-y-5">
        <?= csrf_field() ?>

        <!-- Input Password -->
        <div>
            <label for="password" class="mb-1.5 block text-sm font-semibold text-heading">
                Password Baru
            </label>

            <div class="relative">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted">
                    <i class="bi bi-lock"></i>
                </div>

                <input
                    x-model="password"
                    :type="showPassword ? 'text' : 'password'"
                    name="password"
                    id="password"
                    required
                    autofocus
                    placeholder="Minimal 8 karakter"
                    class="input input-icon pr-10"
                >

                <button
                    type="button"
                    @click="showPassword = !showPassword"
                    :aria-pressed="showPassword"
                    aria-label="Tampilkan atau sembunyikan password"
                    class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-muted transition-colors hover:text-body"
                >
                    <i :class="showPassword ? 'bi-eye-slash' : 'bi-eye'" class="bi"></i>
                </button>
            </div>
        </div>

        <!-- Input Konfirmasi Password -->
        <div>
            <label for="password_confirm" class="mb-1.5 block text-sm font-semibold text-heading">
                Konfirmasi Password Baru
            </label>

            <div class="relative">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted">
                    <i class="bi bi-shield-lock"></i>
                </div>

                <input
                    x-model="passwordConfirm"
                    :type="showConfirmPassword ? 'text' : 'password'"
                    name="password_confirm"
                    id="password_confirm"
                    required
                    placeholder="Ulangi password baru"
                    class="input input-icon pr-10"
                >

                <button
                    type="button"
                    @click="showConfirmPassword = !showConfirmPassword"
                    :aria-pressed="showConfirmPassword"
                    aria-label="Tampilkan atau sembunyikan konfirmasi password"
                    class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-muted transition-colors hover:text-body"
                >
                    <i :class="showConfirmPassword ? 'bi-eye-slash' : 'bi-eye'" class="bi"></i>
                </button>
            </div>

            <p
                x-show="passwordConfirm.length > 0 && password !== passwordConfirm"
                x-cloak
                class="mt-1.5 flex items-center gap-1 text-xs text-danger"
            >
                <i class="bi bi-exclamation-circle"></i>
                Password tidak sama
            </p>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary w-full">
            Reset Password
        </button>
    </form>

    <!-- Login Link Footer -->
    <p class="mt-8 flex items-center justify-center gap-1.5 text-center text-sm text-muted">
        <i class="bi bi-arrow-left"></i>
        <a href="<?= base_url('auth/login') ?>" class="font-semibold text-primary hover:text-primary-600 transition-colors">
            Kembali ke Login
        </a>
    </p>
</div>
<?= $this->endSection() ?>