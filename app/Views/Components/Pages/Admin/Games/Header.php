<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-title-sm font-semibold text-gray-800 dark:text-white/90">Game</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Kelola daftar game, kategori, dan status tampilnya di storefront.</p>
    </div>
    <button
        type="button"
        @click="openCreate()"
        class="inline-flex items-center justify-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
        <svg width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M10 4v12M4 10h12" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" />
        </svg>
        Tambah Game
    </button>
</div>
