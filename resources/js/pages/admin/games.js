document.addEventListener('alpine:init', () => {
    Alpine.data('gamesPage', (config) => ({
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
            total: 0,
        },
        modalOpen: false,
        saving: false,
        editingId: null,
        searchTimer: null,
        form: {},
        media: {
            image: { blobUrl: null, broken: false },
            banner: { blobUrl: null, broken: false },
        },
        customInputRows: [],

        init() {
            this.form = this.emptyForm();
            this.fetchGames();

            // ponytail: 1 watcher titik. Cuma seed baris wajib kalau kosong —
            // gak nimpa row yang udah diisi admin pas bolak-balik toggle target.
            this.$watch('form.target', (value) => {
                if (value === 'custom') {
                    this.ensureCustomInputRows();
                }
            });
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
                input_custom: '',
                is_popular: 'N',
                sort: 0,
                status: 'On',
            };
        },

        emptyCustomInputRow() {
            return { label: '', type: 'text', placeholder: '', options: [] };
        },

        // Baris pertama = customer_id, dipaksa backend (GameAccountInputService::inputFromArray),
        // wajib ada & gak bisa dihapus dari UI.
        ensureCustomInputRows() {
            if (this.customInputRows.length === 0) {
                this.customInputRows.push({ label: 'User ID / Player ID', type: 'text', placeholder: 'Masukkan ID akunmu', options: [] });
            }
        },

        addCustomInputRow() {
            if (this.customInputRows.length >= 6) return;
            this.customInputRows.push(this.emptyCustomInputRow());
        },

        removeCustomInputRow(index) {
            if (index === 0) return; // baris customer_id, wajib
            this.customInputRows.splice(index, 1);
        },

        // Dipanggil dari @change select tipe — auto-seed 1 pilihan kosong
        // pas pertama kali pindah ke Dropdown, biar gak langsung fallback
        // ke Text gara-gara options masih kosong.
        onRowTypeChange(row) {
            if (row.type === 'select' && (!row.options || row.options.length === 0)) {
                row.options = [{ value: '', label: '' }];
            }
        },

        addRowOption(row) {
            row.options.push({ value: '', label: '' });
        },

        removeRowOption(row, optIndex) {
            row.options.splice(optIndex, 1);
        },

        parseCustomInputRows(inputCustom) {
            const validTypes = ['text', 'number', 'hidden', 'select'];

            try {
                const decoded = typeof inputCustom === 'string' ? JSON.parse(inputCustom) : inputCustom;
                const list = Array.isArray(decoded?.inputs) ? decoded.inputs : (Array.isArray(decoded) ? decoded : []);

                return list.slice(0, 6).map((row) => ({
                    label: row?.label ?? '',
                    type: validTypes.includes(row?.type) ? row.type : 'text',
                    placeholder: row?.placeholder ?? '',
                    options: Array.isArray(row?.options)
                        ? row.options.map((opt) => ({ value: opt?.value ?? '', label: opt?.label ?? '' }))
                        : [],
                }));
            } catch (e) {
                return [];
            }
        },

        get imageBaseUrls() {
            return { image: config.imageBaseUrl, banner: config.bannerBaseUrl };
        },

        mediaPreviewUrl(field) {
            const state = this.media[field];
            if (state.blobUrl) return state.blobUrl;

            const filename = this.form[field];
            return filename ? `${this.imageBaseUrls[field]}${filename}` : '';
        },

        onMediaFileChange(field, event) {
            const file = event.target.files?.[0];
            if (!file) return;

            const state = this.media[field];
            if (state.blobUrl) {
                URL.revokeObjectURL(state.blobUrl);
            }
            state.blobUrl = URL.createObjectURL(file);
            state.broken = false; // src baru, kasih kesempatan render dari awal
            this.form[field] = file.name;
        },

        resetMediaPreview(field) {
            const state = this.media[field];
            if (state.blobUrl) {
                URL.revokeObjectURL(state.blobUrl);
            }
            state.blobUrl = null;
            state.broken = false;

            const ref = field === 'banner' ? 'bannerFile' : 'imageFile';
            if (this.$refs[ref]) {
                this.$refs[ref].value = '';
            }
        },

        resetAllMediaPreviews() {
            this.resetMediaPreview('image');
            this.resetMediaPreview('banner');
        },

        clearMedia(field) {
            this.resetMediaPreview(field);
            this.form[field] = '';
        },

        notifyError(message) {
            Swal.fire({ icon: 'error', title: message ?? 'Terjadi kesalahan' });
        },

        notifySuccess(message) {
            Swal.fire({ icon: 'success', title: message, timer: 1500, showConfirmButton: false });
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

            try {
                const { data } = await axios.get(config.listUrl, {
                    params: {
                        q: this.q,
                        status: this.statusFilter,
                        per_page: this.perPage,
                        page: this.page,
                        game_category_id: this.categoryFilter || undefined,
                    },
                });
                this.games = data.data?.items ?? [];
                this.pager = data.data?.pager ?? this.pager;
            } catch (e) {
                this.notifyError('Gagal memuat data');
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
            this.customInputRows = [];
            this.resetAllMediaPreviews();
            this.modalOpen = true;
        },

        async openEdit(id) {
            try {
                const { data } = await axios.get(`${config.resourceUrl}/${id}`);
                const g = data.data;
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
                    input_custom: '',
                    is_popular: g.is_popular ?? 'N',
                    sort: g.sort ?? 0,
                    status: g.status ?? 'On',
                };
                this.customInputRows = this.parseCustomInputRows(g.input_custom);
                if (this.form.target === 'custom') {
                    this.ensureCustomInputRows();
                }
                this.resetAllMediaPreviews();
                this.modalOpen = true;
            } catch (e) {
                this.notifyError('Gagal memuat game');
            }
        },

        closeModal() {
            this.modalOpen = false;
        },

        async save() {
            this.saving = true;

            if (this.form.target === 'custom') {
                this.ensureCustomInputRows();
                this.form.input_custom = JSON.stringify({
                    inputs: this.customInputRows.map((row) => ({
                        label: row.label,
                        type: row.type,
                        placeholder: row.placeholder,
                        options: row.type === 'select'
                            ? (row.options ?? []).filter((opt) => opt.value.trim() !== '' && opt.label.trim() !== '')
                            : [],
                    })),
                });
            } else {
                this.form.input_custom = '';
            }

            const url = this.editingId ? `${config.resourceUrl}/${this.editingId}` : config.resourceUrl;
            const method = this.editingId ? 'put' : 'post';

            try {
                const { data } = await axios[method](url, this.form);
                if (!data.success) {
                    this.notifyError(data.message);
                    return;
                }
                this.notifySuccess(data.message);
                this.modalOpen = false;
                this.fetchGames();
            } catch (e) {
                this.notifyError(e.response?.data?.message);
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
                const { data } = await axios.delete(`${config.resourceUrl}/${game.id}`);
                if (!data.success) {
                    this.notifyError(data.message);
                    return;
                }
                this.notifySuccess(data.message);
                this.fetchGames();
            } catch (e) {
                this.notifyError(e.response?.data?.message);
            }
        },

        async toggleStatus(game) {
            try {
                const { data } = await axios.post(`${config.resourceUrl}/${game.id}/toggle-status`);
                if (!data.success) {
                    this.notifyError(data.message);
                    return;
                }
                this.fetchGames();
            } catch (e) {
                this.notifyError(e.response?.data?.message);
            }
        },

        imageUrl(image) {
            return image ? `${config.imageBaseUrl}${image}` : '';
        },
    }));
});
