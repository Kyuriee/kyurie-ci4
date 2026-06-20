<?= $this->extend('Layouts/Main'); ?>

<?= $this->section('content'); ?>

<section class="relative overflow-hidden">
    <div class="mx-auto grid max-w-7xl gap-10 px-4 py-10 sm:px-6 lg:grid-cols-[1.1fr_0.9fr] lg:px-8 lg:py-16">
        <div class="flex flex-col justify-center">
            <div class="mb-5 inline-flex w-fit items-center gap-2 rounded-full border border-violet-400/20 bg-violet-400/10 px-4 py-2 text-xs font-black uppercase tracking-wide text-violet-200">
                Top Up Game Online
            </div>

            <h1 class="font-display max-w-3xl text-4xl font-black leading-tight text-white sm:text-5xl lg:text-6xl">
                Top Up Game Cepat, Aman, dan Harga Bersahabat
            </h1>

            <p class="mt-5 max-w-2xl text-base leading-7 text-slate-400 sm:text-lg">
                Beli diamond, voucher, dan kebutuhan game favorit kamu dengan proses cepat,
                metode pembayaran lengkap, dan riwayat transaksi yang mudah dicek.
            </p>

            <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                <a href="#games" class="rounded-2xl bg-violet-500 px-6 py-3 text-center text-sm font-black text-white shadow-lg shadow-violet-500/20 transition hover:bg-violet-400">
                    Mulai Top Up
                </a>

                <a href="<?= base_url('history'); ?>" class="rounded-2xl border border-white/10 bg-white/5 px-6 py-3 text-center text-sm font-black text-white transition hover:bg-white/10">
                    Cek Transaksi
                </a>
            </div>
        </div>

        <div class="rounded-[2rem] border border-white/10 bg-white/5 p-4 shadow-2xl shadow-black/20">
            <div class="rounded-[1.5rem] bg-slate-900 p-5">
                <div class="mb-4 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-black text-white">Popular Games</p>
                        <p class="text-xs text-slate-400">Pilih game favorit kamu</p>
                    </div>

                    <span class="rounded-full bg-emerald-400/10 px-3 py-1 text-xs font-black text-emerald-300">
                        Online
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <?php for ($i = 1; $i <= 4; $i++) : ?>
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-3">
                            <div class="mb-3 aspect-square rounded-xl bg-gradient-to-br from-violet-500/40 to-cyan-500/20"></div>
                            <div class="h-3 w-20 rounded-full bg-white/20"></div>
                            <div class="mt-2 h-2 w-14 rounded-full bg-white/10"></div>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="games" class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
    <div class="mb-6 flex items-end justify-between gap-4">
        <div>
            <h2 class="text-2xl font-black text-white">
                Pilih Game
            </h2>
            <p class="mt-1 text-sm text-slate-400">
                Layout dulu, data game nanti tinggal inject dari controller/service.
            </p>
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <?php for ($i = 1; $i <= 8; $i++) : ?>
            <a href="#" class="group rounded-3xl border border-white/10 bg-white/5 p-4 transition hover:-translate-y-1 hover:bg-white/10">
                <div class="mb-4 aspect-[4/3] rounded-2xl bg-gradient-to-br from-violet-500/40 via-slate-700 to-cyan-500/20"></div>

                <h3 class="font-black text-white group-hover:text-violet-200">
                    Game Placeholder
                </h3>

                <p class="mt-1 text-sm text-slate-400">
                    Top up cepat dan aman
                </p>
            </a>
        <?php endfor; ?>
    </div>
</section>

<?= $this->endSection(); ?>