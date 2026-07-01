<?= $this->extend('Layouts/Main'); ?>

<?= $this->section('content'); ?>
<section class="auth-glow-bg min-h-[calc(100vh-64px)] flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md space-y-8 rounded-3xl border border-slate-200 bg-surface/90 p-8 shadow-2xl backdrop-blur-xl" x-data="authForm">
        <div class="text-center">
            <h2 class="text-3xl font-black tracking-tight text-slate-900 font-display">Masuk ke Akun</h2>
            <p class="mt-2 text-sm text-slate-500">Selamat datang kembali! Masuk untuk melanjutkan.</p>
        </div>
        <form class="mt-8 space-y-6" action="<?= base_url('auth/login'); ?>" method="POST">
            <?= csrf_field(); ?>
            <div class="space-y-4">
                <div>
                    <label for="username" class="block text-sm font-semibold text-slate-700">Username</label>
                    <input id="username" name="username" type="text" required value="<?= old('username'); ?>" class="auth-input-transition mt-1 block w-full rounded-2xl border border-slate-200 bg-white h-11 px-4 text-slate-800 outline-none focus:bg-slate-50" placeholder="Username Anda">
                </div>
                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-700">Password</label>
                    <div class="relative mt-1">
                        <input id="password" name="password" :type="showPassword ? 'text' : 'password'" required class="auth-input-transition block w-full rounded-2xl border border-slate-200 bg-white h-11 px-4 pr-10 text-slate-800 outline-none focus:bg-slate-50" placeholder="••••••••">
                        <button type="button" @click="togglePassword" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-700" aria-label="Toggle password visibility">
                            <span x-text="showPassword ? '🔒' : '👁️'"></span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-between text-sm">
                <label class="flex items-center gap-2 cursor-pointer text-slate-500 hover:text-slate-800">
                    <input type="checkbox" name="remember" class="rounded border-slate-300 bg-white text-primary focus:ring-0">
                    Ingat Saya
                </label>
                <a href="<?= base_url('auth/forgot'); ?>" class="font-semibold text-primary hover:text-primary-soft">Lupa Password?</a>
            </div>
            <button type="submit" class="w-full h-11 rounded-2xl bg-primary font-black text-white shadow-lg shadow-primary/20 hover:bg-primary-soft transition">Masuk</button>
        </form>
        <p class="text-center text-sm text-slate-500">Belum punya akun? <a href="<?= base_url('auth/register'); ?>" class="font-semibold text-primary hover:text-primary-soft">Daftar Sekarang</a></p>
    </div>
</section>
<?= $this->endSection(); ?>
