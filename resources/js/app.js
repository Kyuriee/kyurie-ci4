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
const csrfTokenName = document.querySelector('meta[name="csrf-token-name"]');

if (csrfToken && csrfHeader) {
    axios.defaults.headers.common[csrfHeader.content] = csrfToken.content;
}

window.updateCsrf = (hash) => {
    if (!hash) return;

    if (csrfToken) {
        csrfToken.content = hash;
    }

    if (csrfHeader) {
        axios.defaults.headers.common[csrfHeader.content] = hash;
    }

    // Config\Security::$regenerate = true bikin hash rotate tiap request yang
    // lolos CSRF check. Meta tag & axios header doang gak cukup — form asli
    // (misal form "Buat Pesanan" di halaman detail game) submit native pake
    // hidden input yang di-render sekali pas page load. Kalau input itu gak
    // ikut disinkronin, dia bakal expired begitu ada request lain (misal
    // preview harga) yang duluan regenerate token-nya.
    if (csrfTokenName?.content) {
        document
            .querySelectorAll(`input[name="${csrfTokenName.content}"]`)
            .forEach((input) => {
                input.value = hash;
            });
    }
};

axios.interceptors.response.use(
    (response) => {
        if (response.data?.csrf_hash) {
            window.updateCsrf(response.data.csrf_hash);
        }
        return response;
    },
    (error) => {
        // Body error (misal validasi gagal, bukan CSRF) tetep bisa bawa
        // csrf_hash terbaru kalau lewat responseJson() — jangan dilewatin.
        if (error.response?.data?.csrf_hash) {
            window.updateCsrf(error.response.data.csrf_hash);
        }
        return Promise.reject(error);
    }
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
| Global Toast (Flash Alert dari BaseController)
|--------------------------------------------------------------------------
*/
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (el) => {
        el.onmouseenter = Swal.stopTimer;
        el.onmouseleave = Swal.resumeTimer;
    },
});
window.Toast = Toast;

window.addEventListener('DOMContentLoaded', () => {
    if (!window.__APP_ALERT__) return;

    Toast.fire({
        icon: window.__APP_ALERT__.type ?? 'info',
        title: window.__APP_ALERT__.message ?? window.__APP_ALERT__.title ?? '',
    });
});