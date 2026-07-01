<?= $this->extend('Layouts/Main'); ?>

<?= $this->section('content'); ?>
<section class="auth-glow-bg min-h-[calc(100vh-64px)] flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md space-y-8 rounded-3xl border border-slate-200 bg-surface/90 p-8 shadow-2xl backdrop-blur-xl" x-data="authForm">
        <div class="text-center">
            <h2 class="text-3xl font-black tracking-tight text-slate-900 font-display">Reset Password</h2>
            <p class="mt-2 text-sm text-slate-500">Silakan masukkan password baru Anda.</p>
        </div>
        <form class="mt-8 space-y-4" action="<?= base_url('auth/reset/' . esc($token)); ?>" method="POST">
            <?= csrf_field(); ?>
            <div>
                <label for="password" class="block text-sm font-semibold text-slate-700">Password Baru</label>
                <div class="relative mt-1">
                    <input id="password" name="password" :type="showPassword ? 'text' : 'password'" required class="auth-input-transition block w-full rounded-2xl border border-slate-200 bg-white h-11 px-4 pr-10 text-slate-800 outline-none focus:bg-slate-50" placeholder="••••••••">
                    <button type="button" @click="togglePassword" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-700" aria-label="Toggle password visibility">
                        <span x-text="showPassword ? '🔒' : '👁️'"></span>
                    </button>
                </div>
            </div>
            <div>
                <label for="password_confirm" class="block text-sm font-semibold text-slate-700">Konfirmasi Password Baru</label>
                <div class="relative mt-1">
                    <input id="password_confirm" name="password_confirm" :type="showPassword ? 'text' : 'password'" required class="auth-input-transition block w-full rounded-2xl border border-slate-200 bg-white h-11 px-4 pr-10 text-slate-800 outline-none focus:bg-slate-50" placeholder="••••••••">
                </div>
            </div>
            <button type="submit" class="w-full h-11 rounded-2xl bg-primary font-black text-white shadow-lg shadow-primary/20 hover:bg-primary-soft transition mt-6">Simpan Password Baru</button>
        </form>
    </div>
</section>
<?= $this->endSection(); ?>
