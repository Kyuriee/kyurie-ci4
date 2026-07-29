<div class="overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
    <div class="max-w-full overflow-x-auto custom-scrollbar">
        <table class="w-full text-left">
            <thead class="border-b border-gray-100 dark:border-gray-800">
                <tr>
                    <th class="px-5 py-3 text-theme-xs font-medium text-gray-500 dark:text-gray-400">Game</th>
                    <th class="px-5 py-3 text-theme-xs font-medium text-gray-500 dark:text-gray-400">Kategori</th>
                    <th class="px-5 py-3 text-theme-xs font-medium text-gray-500 dark:text-gray-400">Provider</th>
                    <th class="px-5 py-3 text-theme-xs font-medium text-gray-500 dark:text-gray-400">Populer</th>
                    <th class="px-5 py-3 text-theme-xs font-medium text-gray-500 dark:text-gray-400">Urutan</th>
                    <th class="px-5 py-3 text-theme-xs font-medium text-gray-500 dark:text-gray-400">Status</th>
                    <th class="px-5 py-3 text-right text-theme-xs font-medium text-gray-500 dark:text-gray-400">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                <template x-if="loading">
                    <tr>
                        <td colspan="7" class="px-5 py-6 text-center text-sm text-gray-400">Memuat data...</td>
                    </tr>
                </template>
                <template x-if="!loading && games.length === 0">
                    <tr>
                        <td colspan="7" class="px-5 py-6 text-center text-sm text-gray-400">Belum ada game.</td>
                    </tr>
                </template>
                <template x-for="game in games" :key="game.id">
                    <tr>
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3">
                                <img
                                    :src="imageUrl(game.image)"
                                    @error="$event.target.style.visibility='hidden'"
                                    class="h-10 w-10 rounded-lg object-cover bg-gray-100 dark:bg-gray-800"
                                    alt="">
                                <div>
                                    <p class="text-sm font-medium text-gray-800 dark:text-white/90" x-text="game.games"></p>
                                    <p class="text-theme-xs text-gray-400" x-text="game.code || game.slug"></p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3 text-sm text-gray-600 dark:text-gray-300" x-text="game.category || '-'"></td>
                        <td class="px-5 py-3 text-sm text-gray-600 dark:text-gray-300" x-text="game.provider || '-'"></td>
                        <td class="px-5 py-3">
                            <span
                                class="rounded-full px-2.5 py-0.5 text-theme-xs font-medium"
                                :class="game.is_popular === 'Y' ? 'bg-brand-50 text-brand-500 dark:bg-brand-500/15 dark:text-brand-400' : 'bg-gray-100 text-gray-500 dark:bg-white/5 dark:text-gray-400'"
                                x-text="game.is_popular === 'Y' ? 'Ya' : 'Tidak'"></span>
                        </td>
                        <td class="px-5 py-3 text-sm text-gray-600 dark:text-gray-300" x-text="game.sort"></td>
                        <td class="px-5 py-3">
                            <button
                                type="button"
                                @click="toggleStatus(game)"
                                class="rounded-full px-2.5 py-0.5 text-theme-xs font-medium"
                                :class="game.status === 'On' ? 'bg-success-50 text-success-700 dark:bg-success-500/15 dark:text-success-400' : 'bg-error-50 text-error-700 dark:bg-error-500/15 dark:text-error-400'"
                                x-text="game.status === 'On' ? 'Aktif' : 'Nonaktif'"></button>
                        </td>
                        <td class="px-5 py-3">
                            <div class="flex items-center justify-end gap-3">
                                <button type="button" @click="openEdit(game.id)" class="text-theme-sm font-medium text-brand-500 hover:text-brand-600">Ubah</button>
                                <button type="button" @click="remove(game)" class="text-theme-sm font-medium text-error-500 hover:text-error-600">Hapus</button>
                            </div>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="flex flex-col items-center justify-between gap-3 border-t border-gray-100 px-5 py-4 text-sm text-gray-500 dark:border-gray-800 dark:text-gray-400 sm:flex-row">
        <p x-text="`Total ${pager.total} game — halaman ${pager.currentPage} dari ${pager.pageCount || 1}`"></p>
        <div class="flex items-center gap-2">
            <button type="button" @click="goToPage(pager.currentPage - 1)" :disabled="pager.currentPage <= 1"
                class="rounded-lg border border-gray-200 px-3 py-1.5 disabled:opacity-40 dark:border-gray-800">Sebelumnya</button>
            <button type="button" @click="goToPage(pager.currentPage + 1)" :disabled="pager.currentPage >= pager.pageCount"
                class="rounded-lg border border-gray-200 px-3 py-1.5 disabled:opacity-40 dark:border-gray-800">Berikutnya</button>
        </div>
    </div>
</div>
