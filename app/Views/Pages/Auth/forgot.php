<?= $this->extend('Layouts/Main'); ?>

<?= $this->section('content'); ?>
<section class="auth-glow-bg min-h-[calc(100vh-64px)] flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md space-y-8 rounded-3xl border border-slate-200 bg-surface/90 p-8 shadow-2xl backdrop-blur-xl">
        <div class="text-center">
            <h2 class="text-3xl font-black tracking-tight text-slate-900 font-display">Lupa Password</h2>
            <p class="mt-2 text-sm text-slate-500">Masukkan email Anda untuk menerima tautan reset password.</p>
        </div>
        <form class="mt-8 space-y-6" action="<?= base_url('auth/forgot'); ?>" method="POST">
            <?= csrf_field(); ?>
            <div>
                <label for="email" class="block text-sm font-semibold text-slate-700">Alamat Email</label>
                <input id="email" name="email" type="email" required class="auth-input-transition mt-1 block w-full rounded-2xl border border-slate-200 bg-white h-11 px-4 text-slate-800 outline-none focus:bg-slate-50" placeholder="nama@email.com">
            </div>
            <button type="submit" class="w-full h-11 rounded-2xl bg-primary font-black text-white shadow-lg shadow-primary/20 hover:bg-primary-soft transition">Kirim Tautan Reset</button>
        </form>
        <p class="text-center text-sm text-slate-500">Kembali ke halaman <a href="<?= base_url('auth/login'); ?>" class="font-semibold text-primary hover:text-primary-soft">Login</a></p>
    </div>
</section>
<?= $this->endSection(); ?>
