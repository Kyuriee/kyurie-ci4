import '../css/app.css';

import Alpine from 'alpinejs';
import axios from 'axios';
import Swal from 'sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

window.Alpine = Alpine;
window.axios = axios;
window.Swal = Swal;

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const csrfMeta = document.querySelector('meta[name="csrf-token"]');
const csrfHeaderMeta = document.querySelector('meta[name="csrf-header"]');

if (csrfMeta && csrfHeaderMeta) {
    axios.defaults.headers.common[csrfHeaderMeta.content] = csrfMeta.content;
}

window.updateCsrf = function (hash) {
    if (!hash || !csrfMeta || !csrfHeaderMeta) return;

    csrfMeta.setAttribute('content', hash);
    axios.defaults.headers.common[csrfHeaderMeta.content] = hash;
};

axios.interceptors.response.use(
    function (response) {
        if (response.data?.csrf_hash) {
            window.updateCsrf(response.data.csrf_hash);
        }

        return response;
    },
    function (error) {
        return Promise.reject(error);
    }
);

document.addEventListener('alpine:init', () => {
    Alpine.data('layoutApp', () => ({
        mobileMenu: false,
        searchKeyword: '',

        submitSearch() {
            const keyword = this.searchKeyword.trim();

            if (keyword.length < 2) {
                Swal.fire({
                    icon: 'info',
                    title: 'Keyword terlalu pendek',
                    text: 'Minimal masukkan 2 karakter.',
                    confirmButtonText: 'Oke',
                });

                return;
            }

            window.location.href = `/search?keyword=${encodeURIComponent(keyword)}`;
        },
    }));
});

Alpine.start();

window.addEventListener('DOMContentLoaded', () => {
    if (!window.__APP_ALERT__) return;

    Swal.fire({
        icon: window.__APP_ALERT__.type || 'info',
        title: window.__APP_ALERT__.title || '',
        text: window.__APP_ALERT__.message || '',
        confirmButtonText: 'Oke',
    });
});