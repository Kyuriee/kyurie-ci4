<?= $this->extend('Layouts/Auth') ?>

<?= $this->section('content') ?>
<!-- Card Wrapper -->
<div
    x-data="{
        showPassword: false,
        showConfirmPassword: false,
        password: '',
        passwordConfirm: '',
    }"
    >
    <!-- Header Title -->
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold tracking-tight text-heading">
            Buat Akun Baru
        </h2>

        <p class="mt-1 text-sm text-muted">
            Bergabunglah dan mulai petualanganmu
        </p>
    </div>

    <!-- Form Register -->
    <form action="<?= base_url('auth/register') ?>" method="POST" class="space-y-4">
        <?= csrf_field() ?>

        <!-- Input Username -->
        <div>
            <label for="username" class="mb-1 block text-sm font-semibold text-heading">
                Username
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
                    placeholder="Masukkan username unik"
                    class="input input-icon"
                >
            </div>
        </div>

        <!-- Input Email -->
        <div>
            <label for="email" class="mb-1 block text-sm font-semibold text-heading">
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
                    placeholder="nama@email.com"
                    class="input input-icon"
                >
            </div>
        </div>

        <!-- Input Nomor Telepon -->
        <div>
            <label for="phone" class="mb-1 block text-sm font-semibold text-heading">
                Nomor Telepon
            </label>

            <div class="relative">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted">
                    <i class="bi bi-telephone"></i>
                </div>

                <input
                    type="tel"
                    name="phone"
                    id="phone"
                    value="<?= esc(old('phone') ?? '') ?>"
                    placeholder="0812xxxxxxxx"
                    class="input input-icon"
                >
            </div>
        </div>

        <!-- Input Password -->
        <div>
            <label for="password" class="mb-1 block text-sm font-semibold text-heading">
                Password
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
            <label for="password_confirm" class="mb-1 block text-sm font-semibold text-heading">
                Konfirmasi Password
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
                    placeholder="Ulangi password Anda"
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

        <!-- Terms and Condition -->
        <div class="flex items-start py-1">
            <input
                id="terms"
                name="terms"
                type="checkbox"
                required
                class="mt-1 h-4 w-4 rounded-sm border-border text-primary focus:ring-primary"
            >

            <label for="terms" class="ml-2 block select-none text-xs text-body">
                Saya menyetujui <a href="#" class="font-medium text-primary hover:underline">Ketentuan Layanan</a> dan <a href="#" class="font-medium text-primary hover:underline">Kebijakan Privasi</a> game.
            </label>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary w-full">
            Daftar Akun Sekarang
        </button>
    </form>

    <!-- Login Link Footer -->
    <p class="mt-6 text-center text-sm text-muted">
        Sudah punya akun?
        <a href="<?= base_url('auth/login') ?>" class="font-semibold text-primary hover:text-primary-600 transition-colors">
            Login di sini
        </a>
    </p>
</div>
<?= $this->endSection() ?>