import Alpine from 'alpinejs';
import persist from '@alpinejs/persist';
import Swal from 'sweetalert2';

window.Alpine = Alpine;
window.Swal = Swal;

Alpine.plugin(persist);
