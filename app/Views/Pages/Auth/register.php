<?= $this->extend('Layouts/Auth') ?>

<?= $this->section('content') ?>
<!-- Card Wrapper Berwarna Surface dengan font heading -->
<div 
    x-data="{ showPassword: false, showConfirmPassword: false }" 
    class="bg-surface py-8 px-4 shadow-xl rounded-2xl border border-border/60 sm:px-10"
>
    <!-- Header Title -->
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold font-display tracking-tight text-heading">Buat Akun Baru</h2>
        <p class="mt-1 text-sm text-muted">Bergabunglah dan mulai petualanganmu</p>
    </div>

    <!-- Validasi Error Flashdata (Jika Ada) -->
    <?php if (session()->getFlashdata('error')): ?>
        <div class="mb-4 flex items-center gap-2 rounded-xl bg-danger/10 p-4 text-sm text-danger border border-danger/20">
            <i class="bi bi-exclamation-triangle-fill text-base shrink-0"></i>
            <span><?= session()->getFlashdata('error') ?></span>
        </div>
    <?php endif; ?>

    <!-- Form Register -->
    <form action="<?= base_url('auth/register') ?>" method="POST" class="space-y-4">
        <?= csrf_field() ?>

        <!-- Input Username -->
        <div>
            <label for="username" class="block text-sm font-semibold text-heading mb-1">Username</label>
            <div class="relative rounded-xl">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-muted">
                    <i class="bi bi-person text-base"></i>
                </div>
                <input 
                    type="text" 
                    name="username" 
                    id="username" 
                    value="<?= old('username') ?>"
                    required 
                    placeholder="Masukkan username unik"
                    class="block w-full rounded-xl border border-border bg-white pl-10 pr-3.5 py-2 text-heading placeholder-muted focus:border-primary focus:outline-hidden focus:ring-2 focus:ring-primary/20 transition-all text-sm"
                >
            </div>
        </div>

        <!-- Input Email -->
        <div>
            <label for="email" class="block text-sm font-semibold text-heading mb-1">Alamat Email</label>
            <div class="relative rounded-xl">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-muted">
                    <i class="bi bi-envelope text-base"></i>
                </div>
                <input 
                    type="email" 
                    name="email" 
                    id="email" 
                    value="<?= old('email') ?>"
                    required 
                    placeholder="nama@email.com"
                    class="block w-full rounded-xl border border-border bg-white pl-10 pr-3.5 py-2 text-heading placeholder-muted focus:border-primary focus:outline-hidden focus:ring-2 focus:ring-primary/20 transition-all text-sm"
                >
            </div>
        </div>

        <!-- Input Nomor Telepon (Phone) -->
        <div>
            <label for="phone" class="block text-sm font-semibold text-heading mb-1">Nomor Telepon</label>
            <div class="relative rounded-xl">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-muted">
                    <i class="bi bi-telephone text-base"></i>
                </div>
                <input 
                    type="tel" 
                    name="phone" 
                    id="phone" 
                    value="<?= old('phone') ?>"
                    placeholder="0812xxxxxxxx"
                    class="block w-full rounded-xl border border-border bg-white pl-10 pr-3.5 py-2 text-heading placeholder-muted focus:border-primary focus:outline-hidden focus:ring-2 focus:ring-primary/20 transition-all text-sm"
                >
            </div>
        </div>

        <!-- Input Password -->
        <div>
            <label for="password" class="block text-sm font-semibold text-heading mb-1">Password</label>
            <div class="relative rounded-xl">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-muted">
                    <i class="bi bi-lock text-base"></i>
                </div>
                <input 
                    :type="showPassword ? 'text' : 'password'" 
                    name="password" 
                    id="password" 
                    required 
                    placeholder="Minimal 8 karakter"
                    class="block w-full rounded-xl border border-border bg-white pl-10 pr-10 py-2 text-heading placeholder-muted focus:border-primary focus:outline-hidden focus:ring-2 focus:ring-primary/20 transition-all text-sm"
                >
                <button 
                    type="button" 
                    @click="showPassword = !showPassword"
                    class="absolute inset-y-0 right-0 flex items-center pr-3 text-muted hover:text-body transition-colors"
                >
                    <i :class="showPassword ? 'bi bi-eye-slash' : 'bi bi-eye'" class="text-base"></i>
                </button>
            </div>
        </div>

        <!-- Input Konfirmasi Password -->
        <div>
            <label for="password_confirm" class="block text-sm font-semibold text-heading mb-1">Konfirmasi Password</label>
            <div class="relative rounded-xl">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-muted">
                    <i class="bi bi-shield-lock text-base"></i>
                </div>
                <input 
                    :type="showConfirmPassword ? 'text' : 'password'" 
                    name="password_confirm" 
                    id="password_confirm" 
                    required 
                    placeholder="Ulangi password Anda"
                    class="block w-full rounded-xl border border-border bg-white pl-10 pr-10 py-2 text-heading placeholder-muted focus:border-primary focus:outline-hidden focus:ring-2 focus:ring-primary/20 transition-all text-sm"
                >
                <button 
                    type="button" 
                    @click="showConfirmPassword = !showConfirmPassword"
                    class="absolute inset-y-0 right-0 flex items-center pr-3 text-muted hover:text-body transition-colors"
                >
                    <i :class="showConfirmPassword ? 'bi bi-eye-slash' : 'bi bi-eye'" class="text-base"></i>
                </button>
            </div>
        </div>

        <!-- Terms and Condition Minimalis -->
        <div class="flex items-start py-1">
            <input 
                id="terms" 
                name="terms" 
                type="checkbox" 
                required
                class="mt-1 h-4 w-4 rounded-sm border-border text-primary focus:ring-primary"
            >
            <label for="terms" class="ml-2 block text-xs text-body select-none">
                Saya menyetujui <a href="#" class="text-primary font-medium hover:underline">Ketentuan Layanan</a> dan <a href="#" class="text-primary font-medium hover:underline">Kebijakan Privasi</a> game.
            </label>
        </div>

        <!-- Submit Button -->
        <div class="pt-2">
            <button 
                type="submit" 
                class="flex w-full justify-center rounded-xl bg-primary px-4 py-2.5 text-sm font-bold font-display text-white shadow-md shadow-primary/10 hover:bg-primary-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary transition-all cursor-pointer"
            >
                Daftar Akun Sekarang
            </button>
        </div>
    </form>

    <!-- Login Link Footer -->
    <p class="mt-6 text-center text-sm text-body">
        Sudah punya ID? 
        <a href="<?= base_url('auth/login') ?>" class="font-bold text-primary hover:text-primary-600 transition-colors">
            Login di sini
        </a>
    </p>
</div>
<?= $this->endSection() ?>