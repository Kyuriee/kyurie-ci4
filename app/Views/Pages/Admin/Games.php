<?= $this->extend('Layouts/AdminMain'); ?>

<?= $this->section('content'); ?>
<div
    x-data="gamesPage({
        categories: <?= esc(json_encode($categories), 'attr') ?>,
        listUrl: '<?= admin_url('games/list') ?>',
        resourceUrl: '<?= admin_url('games') ?>',
        imageBaseUrl: '<?= base_url('assets/images/games/icons/') ?>',
    })"
    x-init="init()">

    <!-- Header -->
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

    <!-- Filters -->
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

    <!-- Table -->
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
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Gambar (nama file ikon)</label>
                    <input type="text" x-model="form.image" placeholder="contoh: mobile-legends.png" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
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
</div>

<script>
    function gamesPage(config) {
        return {
            categories: config.categories,
            games: [],
            loading: false,
            q: '',
            categoryFilter: '',
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
                this.fetchGames();
            },

            emptyForm() {
                return {
                    game_category_id: '',
                    games: '',
                    slug: '',
                    code: '',
                    provider: '',
                    publisher: '',
                    image: '',
                    banner: '',
                    description: '',
                    target: 'default',
                    is_popular: 'N',
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
                    this.fetchGames();
                }, 400);
            },

            async fetchGames() {
                this.loading = true;
                const params = new URLSearchParams({
                    q: this.q,
                    status: this.statusFilter,
                    per_page: this.perPage,
                    page: this.page
                });
                if (this.categoryFilter) params.set('game_category_id', this.categoryFilter);

                try {
                    const res = await fetch(`${config.listUrl}?${params}`);
                    const json = await res.json();
                    this.games = (json.data && json.data.items) || [];
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
                this.fetchGames();
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
                    const g = json.data;
                    this.editingId = id;
                    this.form = {
                        game_category_id: g.game_category_id ?? '',
                        games: g.games ?? '',
                        slug: g.slug ?? '',
                        code: g.code ?? '',
                        provider: g.provider ?? '',
                        publisher: g.publisher ?? '',
                        image: g.image ?? '',
                        banner: g.banner ?? '',
                        description: g.description ?? '',
                        target: g.target ?? 'default',
                        is_popular: g.is_popular ?? 'N',
                        sort: g.sort ?? 0,
                        status: g.status ?? 'On',
                    };
                    this.modalOpen = true;
                } catch (e) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal memuat game'
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
                    this.fetchGames();
                } catch (e) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi kesalahan'
                    });
                } finally {
                    this.saving = false;
                }
            },

            async remove(game) {
                const confirmed = await Swal.fire({
                    icon: 'warning',
                    title: `Hapus "${game.games}"?`,
                    text: 'Data yang dihapus tidak bisa dikembalikan.',
                    showCancelButton: true,
                    confirmButtonText: 'Hapus',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#d92d20',
                });
                if (!confirmed.isConfirmed) return;

                try {
                    const res = await fetch(`${config.resourceUrl}/${game.id}`, {
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
                    this.fetchGames();
                } catch (e) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi kesalahan'
                    });
                }
            },

            async toggleStatus(game) {
                try {
                    const res = await fetch(`${config.resourceUrl}/${game.id}/toggle-status`, {
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
                    this.fetchGames();
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