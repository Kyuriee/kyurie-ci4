<div
    x-show="modalOpen"
    x-cloak
    class="fixed inset-0 z-99999 flex items-center justify-center p-4"
    style="background: rgba(16, 24, 40, .5)">
    <div
        @click.outside="closeModal()"
        x-show="modalOpen"
        x-transition
        class="max-h-[90vh] w-full max-w-3xl overflow-y-auto custom-scrollbar rounded-2xl bg-white p-6 dark:bg-gray-900">
        <h2 class="mb-1 text-lg font-semibold text-gray-800 dark:text-white/90" x-text="editingId ? 'Ubah Game' : 'Tambah Game'"></h2>
        <p class="mb-5 text-theme-xs text-gray-400">Isi urut dari atas ke bawah — makin ke bawah makin opsional.</p>

        <form @submit.prevent="save()" class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">

            <!-- 1. Informasi Dasar -->
            <div class="sm:col-span-2">
                <h3 class="text-sm font-semibold text-gray-800 dark:text-white/90">1. Informasi Dasar</h3>
                <p class="text-theme-xs text-gray-400">Nama, kategori, dan identitas game.</p>
            </div>

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
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Kode</label>
                <input type="text" x-model="form.code" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Slug</label>
                <input type="text" x-model="form.slug" placeholder="otomatis dari nama bila kosong" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Provider</label>
                <input type="text" x-model="form.provider" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
            </div>

            <div class="sm:col-span-2">
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Publisher</label>
                <input type="text" x-model="form.publisher" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
            </div>

            <!-- 2. Media -->
            <div class="sm:col-span-2 mt-2 border-t border-gray-100 pt-4 dark:border-gray-800">
                <h3 class="text-sm font-semibold text-gray-800 dark:text-white/90">2. Media</h3>
                <p class="text-theme-xs text-gray-400">Icon buat kartu game, banner buat halaman detail.</p>
            </div>

            <?= view('Components/Pages/Admin/Shared/MediaPicker', [
                'field'       => 'image',
                'label'       => 'Icon Game',
                'refName'     => 'imageFile',
                'aspectClass' => 'h-16 w-16',
                'folderHint'  => 'public/assets/images/games/icons/',
                'placeholder' => 'contoh: mobile-legends.png',
            ]) ?>

            <?= view('Components/Pages/Admin/Shared/MediaPicker', [
                'field'       => 'banner',
                'label'       => 'Banner (halaman detail)',
                'refName'     => 'bannerFile',
                'aspectClass' => 'h-16 w-28',
                'folderHint'  => 'public/assets/images/games/banners/',
                'placeholder' => 'contoh: mobile-legends-banner.jpg',
            ]) ?>

            <!-- 3. Deskripsi -->
            <div class="sm:col-span-2 mt-2 border-t border-gray-100 pt-4 dark:border-gray-800">
                <h3 class="text-sm font-semibold text-gray-800 dark:text-white/90">3. Deskripsi</h3>
                <p class="text-theme-xs text-gray-400">Opsional, tampil di halaman detail game.</p>
            </div>

            <div class="sm:col-span-2">
                <textarea x-model="form.description" rows="3" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"></textarea>
            </div>

            <!-- 4. Cara Isi Akun -->
            <div class="sm:col-span-2 mt-2 border-t border-gray-100 pt-4 dark:border-gray-800">
                <h3 class="text-sm font-semibold text-gray-800 dark:text-white/90">4. Cara Isi Akun</h3>
                <p class="text-theme-xs text-gray-400">Field yang diisi customer pas checkout.</p>
            </div>

            <div class="sm:col-span-2">
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Target Input</label>
                <select x-model="form.target" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                    <option value="default">Default — cuma User ID</option>
                    <option value="custom">Custom — User ID + Zone/Server</option>
                </select>
            </div>

            <div class="sm:col-span-2" x-show="form.target === 'custom'" x-cloak>
                <div class="mb-1.5 flex items-center justify-between">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">Kolom Input <span class="text-gray-400">(maks. 6 termasuk User ID)</span></label>
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
                        <div class="rounded-lg border border-gray-200 p-3 dark:border-gray-800">
                            <div class="grid grid-cols-1 gap-2 sm:grid-cols-[1fr_140px_1fr_auto] sm:items-end">
                                <div>
                                    <label class="mb-1 block text-theme-xs text-gray-400" x-text="index === 0 ? 'Label (User ID) *' : 'Label'"></label>
                                    <input type="text" x-model="row.label" placeholder="Label" class="h-9 w-full rounded-lg border border-gray-300 bg-transparent px-3 text-theme-xs text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                </div>
                                <div>
                                    <label class="mb-1 block text-theme-xs text-gray-400">Tipe</label>
                                    <select x-model="row.type" @change="onRowTypeChange(row)" class="h-9 w-full rounded-lg border border-gray-300 bg-transparent px-3 text-theme-xs text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
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

                            <div x-show="row.type === 'select'" x-cloak class="mt-3 border-t border-gray-100 pt-3 dark:border-gray-800">
                                <div class="mb-2 flex items-center justify-between">
                                    <span class="text-theme-xs font-medium text-gray-500 dark:text-gray-400">Pilihan Dropdown</span>
                                    <button type="button" @click="addRowOption(row)"
                                        class="rounded-lg border border-gray-300 px-2.5 py-1 text-theme-xs font-medium text-gray-700 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/5">+ Tambah Pilihan</button>
                                </div>
                                <div class="space-y-1.5">
                                    <template x-for="(opt, optIndex) in row.options" :key="optIndex">
                                        <div class="flex gap-2">
                                            <input type="text" x-model="opt.value" placeholder="Value (disimpan)" class="h-8 w-2/5 rounded-lg border border-gray-300 bg-transparent px-2.5 text-theme-xs text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                            <input type="text" x-model="opt.label" placeholder="Label (keliatan customer)" class="h-8 flex-1 rounded-lg border border-gray-300 bg-transparent px-2.5 text-theme-xs text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                            <button type="button" @click="removeRowOption(row, optIndex)"
                                                class="h-8 w-8 shrink-0 rounded-lg border border-error-300 text-theme-xs text-error-600 hover:bg-error-50 dark:border-error-500/30 dark:text-error-400 dark:hover:bg-error-500/10">&times;</button>
                                        </div>
                                    </template>
                                </div>
                                <p x-show="row.options.length === 0" class="text-theme-xs text-gray-400">Belum ada pilihan — tambah minimal 1, kalau kosong otomatis balik jadi Text pas disimpan.</p>
                            </div>
                        </div>
                    </template>
                </div>
                <p class="mt-1.5 text-theme-xs text-gray-400">Kolom pertama otomatis dipetakan jadi <code>customer_id</code> di backend (lihat GameAccountInputService), sisanya jadi zone/server sesuai urutan.</p>
            </div>

            <!-- 5. Tampilan & Status -->
            <div class="sm:col-span-2 mt-2 border-t border-gray-100 pt-4 dark:border-gray-800">
                <h3 class="text-sm font-semibold text-gray-800 dark:text-white/90">5. Tampilan &amp; Status</h3>
                <p class="text-theme-xs text-gray-400">Ngatur muncul di mana aja &amp; urutannya.</p>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Populer</label>
                <select x-model="form.is_popular" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                    <option value="N">Tidak</option>
                    <option value="Y">Ya, tampil di section Populer</option>
                </select>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Urutan</label>
                <input type="number" x-model.number="form.sort" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Status</label>
                <select x-model="form.status" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                    <option value="On">Aktif — tampil di storefront</option>
                    <option value="Off">Nonaktif — disembunyiin</option>
                </select>
            </div>

            <div class="sm:col-span-2 mt-2 flex items-center justify-end gap-3 border-t border-gray-100 pt-4 dark:border-gray-800">
                <button type="button" @click="closeModal()" class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 dark:border-gray-700 dark:text-gray-300">Batal</button>
                <button type="submit" :disabled="saving" class="rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 disabled:opacity-60">
                    <span x-show="!saving">Simpan</span>
                    <span x-show="saving" x-cloak>Menyimpan...</span>
                </button>
            </div>
        </form>
    </div>
</div>
