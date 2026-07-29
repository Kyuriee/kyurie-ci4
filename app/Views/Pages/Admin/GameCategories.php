<?= $this->extend('Layouts/AdminMain'); ?>

<?= $this->section('content'); ?>
<div
    x-data="gameCategoriesPage({
        listUrl: '<?= admin_url('game-categories/list') ?>',
        resourceUrl: '<?= admin_url('game-categories') ?>',
        imageBaseUrl: '<?= base_url('assets/images/games/categories/') ?>',
    })"
    x-init="init()">

    <!-- Header -->
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-title-sm font-semibold text-gray-800 dark:text-white/90">Kategori Game</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Kelola kategori game yang tampil di storefront dan filter admin.</p>
        </div>
        <button
            type="button"
            @click="openCreate()"
            class="inline-flex items-center justify-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
            <svg width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M10 4v12M4 10h12" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" />
            </svg>
            Tambah Kategori
        </button>
    </div>

    <!-- Filters -->
    <div class="mb-4 grid grid-cols-1 gap-3 sm:grid-cols-3">
        <input
            type="text"
            x-model="q"
            @input="onSearchInput()"
            placeholder="Cari nama atau slug kategori..."
            class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">

        <select
            x-model="statusFilter"
            @change="page = 1; fetchCategories()"
            class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 shadow-theme-xs focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
            <option value="">Semua Status</option>
            <option value="On">Aktif</option>
            <option value="Off">Nonaktif</option>
        </select>
    </div>

    <!-- Table -->
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="max-w-full overflow-x-auto custom-scrollbar">
            <table class="w-full text-left">
                <thead class="border-b border-gray-100 dark:border-gray-800">
                    <tr>
                        <th class="px-5 py-3 text-theme-xs font-medium text-gray-500 dark:text-gray-400">Kategori</th>
                        <th class="px-5 py-3 text-theme-xs font-medium text-gray-500 dark:text-gray-400">Slug</th>
                        <th class="px-5 py-3 text-theme-xs font-medium text-gray-500 dark:text-gray-400">Urutan</th>
                        <th class="px-5 py-3 text-theme-xs font-medium text-gray-500 dark:text-gray-400">Status</th>
                        <th class="px-5 py-3 text-right text-theme-xs font-medium text-gray-500 dark:text-gray-400">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    <template x-if="loading">
                        <tr>
                            <td colspan="5" class="px-5 py-6 text-center text-sm text-gray-400">Memuat data...</td>
                        </tr>
                    </template>
                    <template x-if="!loading && categories.length === 0">
                        <tr>
                            <td colspan="5" class="px-5 py-6 text-center text-sm text-gray-400">Belum ada kategori.</td>
                        </tr>
                    </template>
                    <template x-for="cat in categories" :key="cat.id">
                        <tr>
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-3">
                                    <img
                                        :src="imageUrl(cat.image)"
                                        @error="$event.target.style.visibility='hidden'"
                                        class="h-10 w-10 rounded-lg object-cover bg-gray-100 dark:bg-gray-800"
                                        alt="">
                                    <p class="text-sm font-medium text-gray-800 dark:text-white/90" x-text="cat.category"></p>
                                </div>
                            </td>
                            <td class="px-5 py-3 text-sm text-gray-600 dark:text-gray-300" x-text="cat.slug"></td>
                            <td class="px-5 py-3 text-sm text-gray-600 dark:text-gray-300" x-text="cat.sort"></td>
                            <td class="px-5 py-3">
                                <button
                                    type="button"
                                    @click="toggleStatus(cat)"
                                    class="rounded-full px-2.5 py-0.5 text-theme-xs font-medium"
                                    :class="cat.status === 'On' ? 'bg-success-50 text-success-700 dark:bg-success-500/15 dark:text-success-400' : 'bg-error-50 text-error-700 dark:bg-error-500/15 dark:text-error-400'"
                                    x-text="cat.status === 'On' ? 'Aktif' : 'Nonaktif'"></button>
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex items-center justify-end gap-3">
                                    <button type="button" @click="openEdit(cat.id)" class="text-theme-sm font-medium text-brand-500 hover:text-brand-600">Ubah</button>
                                    <button type="button" @click="remove(cat)" class="text-theme-sm font-medium text-error-500 hover:text-error-600">Hapus</button>
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

    <!-- Create / Edit modal -->
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

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Gambar (nama file ikon)</label>
                    <input type="text" x-model="form.image" placeholder="contoh: mobile-legends.png" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                </div>

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

                <div class="mt-2 flex items-center justify-end gap-3">
                    <button type="button" @click="closeModal()" class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 dark:border-gray-700 dark:text-gray-300">Batal</button>
                    <button type="submit" :disabled="saving" class="rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 disabled:opacity-60">
                        <span x-show="!saving">Simpan</span>
                        <span x-show="saving" x-cloak>Menyimpan...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function gameCategoriesPage(config) {
        return {
            categories: [],
            loading: false,
            q: '',
            statusFilter: '',
            page: 1,
            perPage: 20,
            pager: {
                currentPage: 1,
                pageCount: 1,
                total: 0
            },
            modalOpen: false,
            saving: false,
            editingId: null,
            searchTimer: null,
            form: {},

            init() {
                this.form = this.emptyForm();
                this.fetchCategories();
            },

            emptyForm() {
                return {
                    category: '',
                    slug: '',
                    image: '',
                    sort: 0,
                    status: 'On',
                };
            },

            csrfToken() {
                const match = document.cookie.match(/(?:^|; )csrf_cookie_name=([^;]+)/);
                return match ? decodeURIComponent(match[1]) : '';
            },

            onSearchInput() {
                clearTimeout(this.searchTimer);
                this.searchTimer = setTimeout(() => {
                    this.page = 1;
                    this.fetchCategories();
                }, 400);
            },

            async fetchCategories() {
                this.loading = true;
                const params = new URLSearchParams({
                    q: this.q,
                    status: this.statusFilter,
                    per_page: this.perPage,
                    page: this.page
                });

                try {
                    const res = await fetch(`${config.listUrl}?${params}`);
                    const json = await res.json();
                    this.categories = (json.data && json.data.items) || [];
                    this.pager = (json.data && json.data.pager) || this.pager;
                } catch (e) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal memuat data'
                    });
                } finally {
                    this.loading = false;
                }
            },

            goToPage(p) {
                if (p < 1 || p > this.pager.pageCount) return;
                this.page = p;
                this.fetchCategories();
            },

            openCreate() {
                this.editingId = null;
                this.form = this.emptyForm();
                this.modalOpen = true;
            },

            async openEdit(id) {
                try {
                    const res = await fetch(`${config.resourceUrl}/${id}`);
                    const json = await res.json();
                    if (!json.success) throw new Error(json.message);
                    const c = json.data;
                    this.editingId = id;
                    this.form = {
                        category: c.category ?? '',
                        slug: c.slug ?? '',
                        image: c.image ?? '',
                        sort: c.sort ?? 0,
                        status: c.status ?? 'On',
                    };
                    this.modalOpen = true;
                } catch (e) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal memuat kategori'
                    });
                }
            },

            closeModal() {
                this.modalOpen = false;
            },

            async save() {
                this.saving = true;
                const url = this.editingId ? `${config.resourceUrl}/${this.editingId}` : config.resourceUrl;
                const method = this.editingId ? 'PUT' : 'POST';

                try {
                    const res = await fetch(url, {
                        method,
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': this.csrfToken()
                        },
                        body: JSON.stringify(this.form),
                    });
                    const json = await res.json();
                    if (!json.success) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: json.message
                        });
                        return;
                    }
                    Swal.fire({
                        icon: 'success',
                        title: json.message,
                        timer: 1500,
                        showConfirmButton: false
                    });
                    this.modalOpen = false;
                    this.fetchCategories();
                } catch (e) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi kesalahan'
                    });
                } finally {
                    this.saving = false;
                }
            },

            async remove(cat) {
                const confirmed = await Swal.fire({
                    icon: 'warning',
                    title: `Hapus "${cat.category}"?`,
                    text: 'Data yang dihapus tidak bisa dikembalikan.',
                    showCancelButton: true,
                    confirmButtonText: 'Hapus',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#d92d20',
                });
                if (!confirmed.isConfirmed) return;

                try {
                    const res = await fetch(`${config.resourceUrl}/${cat.id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': this.csrfToken()
                        },
                    });
                    const json = await res.json();
                    if (!json.success) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: json.message
                        });
                        return;
                    }
                    Swal.fire({
                        icon: 'success',
                        title: json.message,
                        timer: 1500,
                        showConfirmButton: false
                    });
                    this.fetchCategories();
                } catch (e) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi kesalahan'
                    });
                }
            },

            async toggleStatus(cat) {
                try {
                    const res = await fetch(`${config.resourceUrl}/${cat.id}/toggle-status`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': this.csrfToken()
                        },
                    });
                    const json = await res.json();
                    if (!json.success) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: json.message
                        });
                        return;
                    }
                    this.fetchCategories();
                } catch (e) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi kesalahan'
                    });
                }
            },

            imageUrl(image) {
                return image ? `${config.imageBaseUrl}${image}` : '';
            },
        };
    }
</script>
<?= $this->endSection(); ?>
