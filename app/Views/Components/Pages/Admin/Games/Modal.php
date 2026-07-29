<div
    x-show="modalOpen"
    x-cloak
    class="fixed inset-0 z-99999 flex items-center justify-center p-4"
    style="background: rgba(16, 24, 40, .5)">
    <div
        @click.outside="closeModal()"
        x-show="modalOpen"
        x-transition
        class="max-h-[90vh] w-full max-w-2xl overflow-y-auto custom-scrollbar rounded-2xl bg-white p-6 dark:bg-gray-900">
        <h2 class="mb-5 text-lg font-semibold text-gray-800 dark:text-white/90" x-text="editingId ? 'Ubah Game' : 'Tambah Game'"></h2>

        <form @submit.prevent="save()" class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div class="sm:col-span-2">
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Nama Game<span class="text-error-500">*</span></label>
                <input type="text" x-model="form.games" required class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Kategori<span class="text-error-500">*</span></label>
                <select x-model="form.game_category_id" required class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                    <option value="">Pilih kategori</option>
                    <template x-for="cat in categories" :key="cat.id">
                        <option :value="cat.id" x-text="cat.category"></option>
                    </template>
                </select>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Slug</label>
                <input type="text" x-model="form.slug" placeholder="otomatis dari nama bila kosong" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Kode</label>
                <input type="text" x-model="form.code" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Provider</label>
                <input type="text" x-model="form.provider" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
            </div>

            <div class="sm:col-span-2">
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Publisher</label>
                <input type="text" x-model="form.publisher" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Target Input</label>
                <select x-model="form.target" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                    <option value="default">Default (User ID saja)</option>
                    <option value="custom">Custom (User ID + Zone/Server)</option>
                </select>
            </div>

            <div class="sm:col-span-2" x-show="form.target === 'custom'" x-cloak>
                <div class="mb-1.5 flex items-center justify-between">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">Input Custom <span class="text-gray-400">(maks. 6 kolom termasuk User ID)</span></label>
                    <button
                        type="button"
                        @click="addCustomInputRow()"
                        :disabled="customInputRows.length >= 6"
                        class="rounded-lg border border-gray-300 px-3 py-1 text-theme-xs font-medium text-gray-700 disabled:opacity-40 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/5">
                        + Tambah Kolom
                    </button>
                </div>

                <div class="space-y-2">
                    <template x-for="(row, index) in customInputRows" :key="index">
                        <div class="grid grid-cols-1 gap-2 rounded-lg border border-gray-200 p-3 dark:border-gray-800 sm:grid-cols-[1fr_140px_1fr_auto] sm:items-end">
                            <div>
                                <label class="mb-1 block text-theme-xs text-gray-400" x-text="index === 0 ? 'Label (User ID) *' : 'Label'"></label>
                                <input type="text" x-model="row.label" placeholder="Label" class="h-9 w-full rounded-lg border border-gray-300 bg-transparent px-3 text-theme-xs text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                            </div>
                            <div>
                                <label class="mb-1 block text-theme-xs text-gray-400">Tipe</label>
                                <select x-model="row.type" class="h-9 w-full rounded-lg border border-gray-300 bg-transparent px-3 text-theme-xs text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                    <option value="text">Text</option>
                                    <option value="number">Number</option>
                                    <option value="hidden">Hidden</option>
                                    <option value="select">Dropdown (Select)</option>
                                </select>
                            </div>
                            <div>
                                <label class="mb-1 block text-theme-xs text-gray-400">Placeholder</label>
                                <input type="text" x-model="row.placeholder" placeholder="Placeholder" class="h-9 w-full rounded-lg border border-gray-300 bg-transparent px-3 text-theme-xs text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                            </div>
                            <div>
                                <button type="button" x-show="index !== 0" @click="removeCustomInputRow(index)"
                                    class="h-9 w-full rounded-lg border border-error-300 px-3 text-theme-xs font-medium text-error-600 hover:bg-error-50 dark:border-error-500/30 dark:text-error-400 dark:hover:bg-error-500/10">Hapus</button>
                                <span x-show="index === 0" class="flex h-9 items-center text-theme-xs text-gray-400">Wajib, gak bisa dihapus</span>
                            </div>
                        </div>
                    </template>
                </div>
                <p class="mt-1.5 text-theme-xs text-gray-400">Kolom pertama otomatis dipetakan jadi <code>customer_id</code> di backend (lihat GameAccountInputService), sisanya jadi zone/server sesuai urutan.</p>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Gambar (ikon)</label>
                <input type="file" x-ref="imageFile" accept="image/*" @change="onImageFileChange($event)" class="hidden">
                <div class="flex items-center gap-3">
                    <div class="flex h-16 w-16 shrink-0 items-center justify-center overflow-hidden rounded-lg border border-dashed border-gray-300 bg-gray-50 dark:border-gray-700 dark:bg-gray-800">
                        <img
                            x-show="imagePreviewUrl"
                            :src="imagePreviewUrl"
                            @error="$event.target.style.visibility='hidden'"
                            class="h-full w-full object-cover"
                            alt="">
                        <svg x-show="!imagePreviewUrl" width="20" height="20" viewBox="0 0 20 20" fill="none" class="text-gray-300 dark:text-gray-600">
                            <path d="M3 6a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V6z" stroke="currentColor" stroke-width="1.5" />
                            <path d="M3 13l4-4 3 3 4-4 3 3" stroke="currentColor" stroke-width="1.5" />
                        </svg>
                    </div>
                    <div class="flex flex-col gap-2">
                        <input type="text" x-model="form.image" placeholder="contoh: mobile-legends.png" class="h-9 w-48 rounded-lg border border-gray-300 bg-transparent px-3 text-theme-xs text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                        <div class="flex gap-2">
                            <button type="button" @click="$refs.imageFile.click()" class="rounded-lg border border-gray-300 px-3 py-1.5 text-theme-xs font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/5">Pilih File</button>
                            <button type="button" x-show="imagePreviewUrl" @click="clearImage()" class="rounded-lg border border-error-300 px-3 py-1.5 text-theme-xs font-medium text-error-600 hover:bg-error-50 dark:border-error-500/30 dark:text-error-400 dark:hover:bg-error-500/10">Hapus</button>
                        </div>
                    </div>
                </div>
                <p class="mt-1 text-theme-xs text-gray-400">File dipilih cuma buat preview + isi nama file otomatis. Upload fisik file ke <code>public/assets/images/games/icons/</code> tetap manual.</p>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Banner (nama file)</label>
                <input type="text" x-model="form.banner" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
            </div>

            <div class="sm:col-span-2">
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Deskripsi</label>
                <textarea x-model="form.description" rows="3" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"></textarea>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Populer</label>
                <select x-model="form.is_popular" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                    <option value="N">Tidak</option>
                    <option value="Y">Ya</option>
                </select>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Urutan</label>
                <input type="number" x-model.number="form.sort" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Status</label>
                <select x-model="form.status" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                    <option value="On">Aktif</option>
                    <option value="Off">Nonaktif</option>
                </select>
            </div>

            <div class="sm:col-span-2 mt-2 flex items-center justify-end gap-3">
                <button type="button" @click="closeModal()" class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 dark:border-gray-700 dark:text-gray-300">Batal</button>
                <button type="submit" :disabled="saving" class="rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 disabled:opacity-60">
                    <span x-show="!saving">Simpan</span>
                    <span x-show="saving" x-cloak>Menyimpan...</span>
                </button>
            </div>
        </form>
    </div>
</div>
