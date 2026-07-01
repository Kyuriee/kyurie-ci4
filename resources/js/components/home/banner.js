import Swiper from 'swiper';
import { Autoplay, Pagination } from 'swiper/modules';

const element = document.querySelector('.banner-swiper');

if (element) {
    new Swiper(element, {
        modules: [Autoplay, Pagination],
        loop: true,
        speed: 700,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,

            renderBullet(index, className) {
                return `<span class="${className} banner-bullet"></span>`;
            },
        },
    });
}