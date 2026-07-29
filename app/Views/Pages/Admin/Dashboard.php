<?= $this->extend('Layouts/AdminMain'); ?>

<?= $this->section('content'); ?>
<div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
        <p class="text-sm text-gray-500 dark:text-gray-400">Total Order</p>
        <p class="mt-2 text-title-sm font-semibold text-gray-800 dark:text-white/90">0</p>
    </div>
    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
        <p class="text-sm text-gray-500 dark:text-gray-400">Pendapatan Hari Ini</p>
        <p class="mt-2 text-title-sm font-semibold text-gray-800 dark:text-white/90">Rp 0</p>
    </div>
    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
        <p class="text-sm text-gray-500 dark:text-gray-400">Kupon Aktif</p>
        <p class="mt-2 text-title-sm font-semibold text-gray-800 dark:text-white/90">0</p>
    </div>
    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
        <p class="text-sm text-gray-500 dark:text-gray-400">Pengguna Terdaftar</p>
        <p class="mt-2 text-title-sm font-semibold text-gray-800 dark:text-white/90">0</p>
    </div>
</div>

<div class="mt-6 rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
    <h2 class="mb-1 text-lg font-semibold text-gray-800 dark:text-white/90">
        Halo, <?= esc($admin['name'] ?? '-') ?> 👋
    </h2>
    <p class="text-sm text-gray-500 dark:text-gray-400">
        Level: <?= esc($admin['level']   ?? '-') ?>. Layout shell (sidebar, header, dark mode, collapse) sudah jalan —
        widget statistik di atas masih dummy, tinggal disambung ke service masing-masing domain.
    </p>
</div>
<?= $this->endSection(); ?>