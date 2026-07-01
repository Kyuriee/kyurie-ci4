export default () => ({

    mobileMenu: false,

    profileMenu: false,

    searchKeyword: '',

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

        window.location.href =
            `/search?keyword=${encodeURIComponent(keyword)}`;

    },

    openMobileMenu() {

        this.mobileMenu = true;

    },

    closeMobileMenu() {

        this.mobileMenu = false;

    },

    toggleProfile() {

        this.profileMenu = !this.profileMenu;

    }

});