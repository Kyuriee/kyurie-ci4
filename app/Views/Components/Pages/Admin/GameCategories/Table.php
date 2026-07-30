<div class="overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
    <div class="max-w-full overflow-x-auto custom-scrollbar">
        <table class="w-full text-left">
            <thead class="border-b border-gray-100 dark:border-gray-800">
                <tr>
                    <th class="px-5 py-3 text-theme-xs font-medium text-gray-500 dark:text-gray-400">Kategori</th>
                    <th class="px-5 py-3 text-theme-xs font-medium text-gray-500 dark:text-gray-400">Urutan</th>
                    <th class="px-5 py-3 text-theme-xs font-medium text-gray-500 dark:text-gray-400">Status</th>
                    <th class="px-5 py-3 text-right text-theme-xs font-medium text-gray-500 dark:text-gray-400">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                <template x-if="loading">
                    <tr>
                        <td colspan="4" class="px-5 py-6 text-center text-sm text-gray-400">Memuat data...</td>
                    </tr>
                </template>
                <template x-if="!loading && categories.length === 0">
                    <tr>
                        <td colspan="4" class="px-5 py-6 text-center text-sm text-gray-400">Belum ada kategori.</td>
                    </tr>
                </template>
                <template x-for="category in categories" :key="category.id">
                    <tr>
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3">
                                <img
                                    :src="imageUrl(category.image)"
                                    @error="$event.target.style.visibility='hidden'"
                                    class="h-10 w-10 rounded-lg object-cover bg-gray-100 dark:bg-gray-800"
                                    alt="">
                                <div>
                                    <p class="text-sm font-medium text-gray-800 dark:text-white/90" x-text="category.category"></p>
                                    <p class="text-theme-xs text-gray-400" x-text="category.slug"></p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3 text-sm text-gray-600 dark:text-gray-300" x-text="category.sort"></td>
                        <td class="px-5 py-3">
                            <button
                                type="button"
                                @click="toggleStatus(category)"
                                class="rounded-full px-2.5 py-0.5 text-theme-xs font-medium"
                                :class="category.status === 'On' ? 'bg-success-50 text-success-700 dark:bg-success-500/15 dark:text-success-400' : 'bg-error-50 text-error-700 dark:bg-error-500/15 dark:text-error-400'"
                                x-text="category.status === 'On' ? 'Aktif' : 'Nonaktif'"></button>
                        </td>
                        <td class="px-5 py-3">
                            <div class="flex items-center justify-end gap-3">
                                <button type="button" @click="openEdit(category.id)" class="text-theme-sm font-medium text-brand-500 hover:text-brand-600">Ubah</button>
                                <button type="button" @click="remove(category)" class="text-theme-sm font-medium text-error-500 hover:text-error-600">Hapus</button>
                            </div>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="flex flex-col items-center justify-between gap-3 border-t border-gray-100 px-5 py-4 text-sm text-gray-500 dark:border-gray-800 dark:text-gray-400 sm:flex-row">
        <p x-text="`Total ${pager.total} kategori — halaman ${pager.currentPage} dari ${pager.pageCount || 1}`"></p>
        <div class="flex items-center gap-2">
            <button type="button" @click="goToPage(pager.currentPage - 1)" :disabled="pager.currentPage <= 1"
                class="rounded-lg border border-gray-200 px-3 py-1.5 disabled:opacity-40 dark:border-gray-800">Sebelumnya</button>
            <button type="button" @click="goToPage(pager.currentPage + 1)" :disabled="pager.currentPage >= pager.pageCount"
                class="rounded-lg border border-gray-200 px-3 py-1.5 disabled:opacity-40 dark:border-gray-800">Berikutnya</button>
        </div>
    </div>
</div>
