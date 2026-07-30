<div
    x-show="modalOpen"
    x-cloak
    class="fixed inset-0 z-99999 flex items-center justify-center p-4"
    style="background: rgba(16, 24, 40, .5)">
    <div
        @click.outside="closeModal()"
        x-show="modalOpen"
        x-transition
        class="max-h-[90vh] w-full max-w-lg overflow-y-auto custom-scrollbar rounded-2xl bg-white p-6 dark:bg-gray-900">
        <h2 class="mb-5 text-lg font-semibold text-gray-800 dark:text-white/90" x-text="editingId ? 'Ubah Kategori' : 'Tambah Kategori'"></h2>

        <form @submit.prevent="save()" class="grid grid-cols-1 gap-4">
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Nama Kategori<span class="text-error-500">*</span></label>
                <input type="text" x-model="form.category" required class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Slug</label>
                <input type="text" x-model="form.slug" placeholder="otomatis dari nama bila kosong" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
            </div>

            <?= view('Components/Pages/Admin/Shared/MediaPicker', [
                'field'       => 'image',
                'label'       => 'Icon Kategori',
                'refName'     => 'imageFile',
                'aspectClass' => 'h-16 w-16',
                'folderHint'  => 'public/assets/images/games/categories/',
                'placeholder' => 'contoh: moba.png',
            ]) ?>

            <div class="grid grid-cols-2 gap-4">
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
            </div>

            <div class="mt-2 flex items-center justify-end gap-3 border-t border-gray-100 pt-4 dark:border-gray-800">
                <button type="button" @click="closeModal()" class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 dark:border-gray-700 dark:text-gray-300">Batal</button>
                <button type="submit" :disabled="saving" class="rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 disabled:opacity-60">
                    <span x-show="!saving">Simpan</span>
                    <span x-show="saving" x-cloak>Menyimpan...</span>
                </button>
            </div>
        </form>
    </div>
</div>
