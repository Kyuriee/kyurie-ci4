import Alpine from 'alpinejs';
import persist from '@alpinejs/persist';
import axios from 'axios';
import Swal from 'sweetalert2';

window.Alpine = Alpine;
window.axios = axios;
window.Swal = Swal;

Alpine.plugin(persist);

/*
|--------------------------------------------------------------------------
| Axios + CSRF sync (samain sama resources/js/app.js storefront)
|--------------------------------------------------------------------------
| X-Requested-With dipakai AdminAuthFilter/BaseController buat bedain
| request AJAX vs page load biasa (lihat wantsJson() di Admin\BaseController).
*/
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
const csrfToken     = document.querySelector('meta[name="csrf-token"]');
const csrfHeader    = document.querySelector('meta[name="csrf-header"]');
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
        if (error.response?.data?.csrf_hash) {
            window.updateCsrf(error.response.data.csrf_hash);
        }
        return Promise.reject(error);
    }
);

/*
|--------------------------------------------------------------------------
| Global Toast (Flash Alert dari Admin\BaseController::redirectWithAlert)
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
