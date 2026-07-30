document.addEventListener('alpine:init', () => {
    Alpine.data('gameCategoriesPage', (config) => ({
        categories: [],
        loading: false,
        q: '',
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
        },

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
                this.fetchCategories();
            }, 400);
        },

        async fetchCategories() {
            this.loading = true;

            try {
                const { data } = await axios.get(config.listUrl, {
                    params: {
                        q: this.q,
                        status: this.statusFilter,
                        per_page: this.perPage,
                        page: this.page,
                    },
                });
                this.categories = data.data?.items ?? [];
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
            this.fetchCategories();
        },

        // --- Media picker (icon), pola sama kayak games.js — cuma 1 field ---
        get imageBaseUrls() {
            return { image: config.imageBaseUrl };
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
            state.broken = false;
            this.form[field] = file.name;
        },

        resetMediaPreview(field) {
            const state = this.media[field];
            if (state.blobUrl) {
                URL.revokeObjectURL(state.blobUrl);
            }
            state.blobUrl = null;
            state.broken = false;

            if (this.$refs.imageFile) {
                this.$refs.imageFile.value = '';
            }
        },

        clearMedia(field) {
            this.resetMediaPreview(field);
            this.form[field] = '';
        },

        openCreate() {
            this.editingId = null;
            this.form = this.emptyForm();
            this.resetMediaPreview('image');
            this.modalOpen = true;
        },

        async openEdit(id) {
            try {
                const { data } = await axios.get(`${config.resourceUrl}/${id}`);
                const c = data.data;
                this.editingId = id;
                this.form = {
                    category: c.category ?? '',
                    slug: c.slug ?? '',
                    image: c.image ?? '',
                    sort: c.sort ?? 0,
                    status: c.status ?? 'On',
                };
                this.resetMediaPreview('image');
                this.modalOpen = true;
            } catch (e) {
                this.notifyError('Gagal memuat kategori');
            }
        },

        closeModal() {
            this.modalOpen = false;
        },

        async save() {
            this.saving = true;
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
                this.fetchCategories();
            } catch (e) {
                this.notifyError(e.response?.data?.message);
            } finally {
                this.saving = false;
            }
        },

        async remove(category) {
            const confirmed = await Swal.fire({
                icon: 'warning',
                title: `Hapus "${category.category}"?`,
                text: 'Data yang dihapus tidak bisa dikembalikan.',
                showCancelButton: true,
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#d92d20',
            });
            if (!confirmed.isConfirmed) return;

            try {
                const { data } = await axios.delete(`${config.resourceUrl}/${category.id}`);
                if (!data.success) {
                    this.notifyError(data.message);
                    return;
                }
                this.notifySuccess(data.message);
                this.fetchCategories();
            } catch (e) {
                this.notifyError(e.response?.data?.message);
            }
        },

        async toggleStatus(category) {
            try {
                const { data } = await axios.post(`${config.resourceUrl}/${category.id}/toggle-status`);
                if (!data.success) {
                    this.notifyError(data.message);
                    return;
                }
                this.fetchCategories();
            } catch (e) {
                this.notifyError(e.response?.data?.message);
            }
        },

        imageUrl(image) {
            return image ? `${config.imageBaseUrl}${image}` : '';
        },
    }));
});
