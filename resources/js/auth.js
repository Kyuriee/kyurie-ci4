import Alpine from 'alpinejs';
import Swal from 'sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

window.Alpine = Alpine;
window.Swal = Swal;

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