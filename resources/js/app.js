import Alpine from 'alpinejs';
import axios from 'axios';
import Swal from 'sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

import layoutApp from './alpine/layout';

window.Alpine = Alpine;
window.axios = axios;
window.Swal = Swal;

/*
|--------------------------------------------------------------------------
| Axios
|--------------------------------------------------------------------------
*/
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
const csrfToken = document.querySelector('meta[name="csrf-token"]');
const csrfHeader = document.querySelector('meta[name="csrf-header"]');
if (csrfToken && csrfHeader) {
    axios.defaults.headers.common[csrfHeader.content] = csrfToken.content;
}
window.updateCsrf = (hash) => {
    if (!hash || !csrfToken || !csrfHeader) return;
    csrfToken.content = hash;
    axios.defaults.headers.common[csrfHeader.content] = hash;
};
axios.interceptors.response.use(
    (response) => {
        if (response.data?.csrf_hash) {
            window.updateCsrf(response.data.csrf_hash);
        }
        return response;
    },
    (error) => Promise.reject(error)
);

/*
|--------------------------------------------------------------------------
| Alpine Components
|--------------------------------------------------------------------------
*/
document.addEventListener('alpine:init', () => {
    Alpine.data('layoutApp', layoutApp);
});

/*
|--------------------------------------------------------------------------
| Global Alert
|--------------------------------------------------------------------------
*/
window.addEventListener('DOMContentLoaded', () => {
    if (!window.__APP_ALERT__) return;
    Swal.fire({
        icon: window.__APP_ALERT__.type ?? 'info',
        title: window.__APP_ALERT__.title ?? '',
        text: window.__APP_ALERT__.message ?? '',
        confirmButtonText: 'Oke',
    });
});