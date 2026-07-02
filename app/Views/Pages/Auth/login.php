<?= $this->extend('Layouts/Auth') ?>

<?= $this->section('content') ?>
<!-- Card Wrapper -->
<div x-data="{ showPassword: false }">
    <!-- Logo & Title -->
    <div class="mb-8 text-center">
        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-primary text-white shadow-md shadow-primary/20">
            <i class="bi bi-shield-lock-fill text-2xl"></i>
        </div>

        <h2 class="mt-4 text-2xl font-bold tracking-tight text-heading">
            Selamat Datang Kembali
        </h2>

        <p class="mt-1.5 text-sm text-muted">
            Silakan masuk ke akun Anda
        </p>
    </div>

    <!-- Form Login -->
    <form action="<?= base_url('auth/login') ?>" method="POST" class="space-y-5">
        <?= csrf_field() ?>

        <!-- Input Username / Email -->
        <div>
            <label for="username" class="mb-1.5 block text-sm font-semibold text-heading">
                Username atau Email
            </label>

            <div class="relative">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted">
                    <i class="bi bi-person"></i>
                </div>

                <input
                    type="text"
                    name="username"
                    id="username"
                    value="<?= esc(old('username') ?? '') ?>"
                    required
                    autofocus
                    placeholder="Masukkan username Anda"
                    class="input input-icon"
                >
            </div>
        </div>

        <!-- Input Password -->
        <div>
            <div class="mb-1.5 flex items-center justify-between">
                <label for="password" class="block text-sm font-semibold text-heading">
                    Password
                </label>

                <a href="<?= base_url('auth/forgot') ?>" class="text-sm font-medium text-primary hover:text-primary-600 transition-colors">
                    Lupa password?
                </a>
            </div>

            <div class="relative">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted">
                    <i class="bi bi-lock"></i>
                </div>

                <input
                    :type="showPassword ? 'text' : 'password'"
                    name="password"
                    id="password"
                    required
                    placeholder="••••••••"
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

        <!-- Remember Me -->
        <div class="flex items-center">
            <input
                id="remember"
                name="remember"
                type="checkbox"
                class="h-4 w-4 rounded-sm border-border text-primary focus:ring-primary"
            >

            <label for="remember" class="ml-2 block select-none text-sm text-body">
                Ingat saya di perangkat ini
            </label>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary w-full">
            Masuk ke Akun
        </button>
    </form>

    <!-- Register Link Footer -->
    <p class="mt-8 text-center text-sm text-muted">
        Belum punya akun?
        <a href="<?= base_url('auth/register') ?>" class="font-semibold text-primary hover:text-primary-600 transition-colors">
            Daftar gratis sekarang
        </a>
    </p>
</div>
<?= $this->endSection() ?>