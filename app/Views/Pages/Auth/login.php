<?= $this->extend('Layouts/Auth') ?>

<?= $this->section('content') ?>
<!-- Card Wrapper -->
<div 
    x-data="{ showPassword: false }" 
    class="bg-white py-8 px-4 shadow-xl rounded-2xl border border-slate-200/60 sm:px-10"
>
    <!-- Logo & Title -->
    <div class="mb-8 text-center">
        <div class="inline-flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-600 text-white shadow-md shadow-indigo-200">
            <!-- Icon Kunci / Auth (SVG) -->
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
            </svg>
        </div>
        <h2 class="mt-4 text-2xl font-bold tracking-tight text-slate-900">Selamat Datang Kembali</h2>
        <p class="mt-1.5 text-sm text-slate-500">Silakan masuk ke akun Anda</p>
    </div>

    <!-- Flash Message Notification -->
    <?php if (session()->getFlashdata('error')): ?>
        <div class="mb-5 flex items-center gap-2 rounded-xl bg-red-50 p-4 text-sm text-red-600 border border-red-100">
            <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <span><?= session()->getFlashdata('error') ?></span>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="mb-5 flex items-center gap-2 rounded-xl bg-emerald-50 p-4 text-sm text-emerald-600 border border-emerald-100">
            <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span><?= session()->getFlashdata('success') ?></span>
        </div>
    <?php endif; ?>

    <!-- Form Login -->
    <form action="<?= base_url('auth/login') ?>" method="POST" class="space-y-5">
        <?= csrf_field() ?>

        <!-- Input Username / Email -->
        <div>
            <label for="username" class="block text-sm font-semibold text-slate-700 mb-1.5">Username atau Email</label>
            <div class="relative rounded-xl shadow-xs">
                <input 
                    type="text" 
                    name="username" 
                    id="username" 
                    value="<?= old('username') ?>"
                    required 
                    autofocus
                    placeholder="Masukkan username Anda"
                    class="block w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2 text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:outline-hidden focus:ring-2 focus:ring-indigo-500/20 transition-all text-sm"
                >
            </div>
        </div>

        <!-- Input Password -->
        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label for="password" class="block text-sm font-semibold text-slate-700">Password</label>
                <a href="<?= base_url('auth/forgot') ?>" class="text-sm font-medium text-indigo-600 hover:text-indigo-500 transition">
                    Lupa password?
                </a>
            </div>
            <div class="relative rounded-xl shadow-xs">
                <input 
                    :type="showPassword ? 'text' : 'password'" 
                    name="password" 
                    id="password" 
                    required 
                    placeholder="••••••••"
                    class="block w-full rounded-xl border border-slate-300 bg-white pl-3.5 pr-10 py-2 text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:outline-hidden focus:ring-2 focus:ring-indigo-500/20 transition-all text-sm"
                >
                <!-- Toggle Show/Hide Password Button -->
                <button 
                    type="button" 
                    @click="showPassword = !showPassword"
                    class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600 transition"
                >
                    <!-- Eye Icon -->
                    <svg x-show="!showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                    <!-- Eye Slash Icon -->
                    <svg x-show="showPassword" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Remember Me Checkbox -->
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <input 
                    id="remember" 
                    name="remember" 
                    type="checkbox" 
                    class="h-4 w-4 rounded-sm border-slate-300 text-indigo-600 focus:ring-indigo-500"
                >
                <label for="remember" class="ml-2 block text-sm text-slate-600 select-none">Ingat saya di perangkat ini</label>
            </div>
        </div>

        <!-- Submit Button -->
        <div>
            <button 
                type="submit" 
                class="flex w-full justify-center rounded-xl bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition-all cursor-pointer"
            >
                Masuk ke Akun
            </button>
        </div>
    </form>

    <!-- Register Link Footer -->
    <p class="mt-8 text-center text-sm text-slate-500">
        Belum punya akun? 
        <a href="<?= base_url('auth/register') ?>" class="font-semibold text-indigo-600 hover:text-indigo-500 transition">
            Daftar gratis sekarang
        </a>
    </p>
</div>
<?= $this->endSection() ?>