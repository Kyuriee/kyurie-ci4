import axios from 'axios';

export default () => ({
    mobileMenu: false,
    mobileSearch: false,
    profileMenu: false,

    searchKeyword: '',
    searchResults: [],
    searchLoading: false,
    searchOpen: false,
    searchTimer: null,

    init() {
        this.$watch('searchKeyword', (value) => {
            clearTimeout(this.searchTimer);

            const keyword = value.trim();

            if (keyword.length < 2) {
                this.searchResults = [];
                this.searchOpen = false;
                return;
            }

            this.searchTimer = setTimeout(() => this.fetchSearch(keyword), 350);
        });
    },

    async fetchSearch(keyword) {
        this.searchLoading = true;
        this.searchOpen = true;

        try {
            const { data } = await axios.get('/search/games', {
                params: { keyword },
            });

            this.searchResults = data.data ?? [];
        } catch (error) {
            this.searchResults = [];
        } finally {
            this.searchLoading = false;
        }
    },

    submitSearch() {
        const keyword = this.searchKeyword.trim();

        if (keyword.length < 2) {
            Swal.fire({
                icon: 'info',
                title: 'Keyword terlalu pendek',
                text: 'Minimal 2 karakter.',
            });

            return;
        }

        if (this.searchResults.length > 0) {
            window.location.href = this.searchResults[0].url;
            return;
        }

        Swal.fire({
            icon: 'info',
            title: 'Gak ketemu',
            text: 'Gak ada game yang cocok sama kata kunci itu.',
        });
    },

    closeSearch() {
        this.searchOpen = false;
    },

    openMobileMenu() {
        this.mobileMenu = true;
    },

    closeMobileMenu() {
        this.mobileMenu = false;
    },

    toggleProfile() {
        this.profileMenu = !this.profileMenu;
    },
});