import Alpine from 'alpinejs';

import homePage from '../alpine/home';
document.addEventListener('alpine:init', () => {
    Alpine.data('homePage', homePage);
});
import '../components/home/banner';
import '../components/home/flashsale';

