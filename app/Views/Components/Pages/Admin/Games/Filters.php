<div class="mb-4 grid grid-cols-1 gap-3 sm:grid-cols-3">
    <input
        type="text"
        x-model="q"
        @input="onSearchInput()"
        placeholder="Cari nama, kode, atau slug..."
        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">

    <select
        x-model="categoryFilter"
        @change="page = 1; fetchGames()"
        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 shadow-theme-xs focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
        <option value="">Semua Kategori</option>
        <template x-for="cat in categories" :key="cat.id">
            <option :value="cat.id" x-text="cat.category"></option>
        </template>
    </select>

    <select
        x-model="statusFilter"
        @change="page = 1; fetchGames()"
        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 shadow-theme-xs focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
        <option value="">Semua Status</option>
        <option value="On">Aktif</option>
        <option value="Off">Nonaktif</option>
    </select>
</div>
