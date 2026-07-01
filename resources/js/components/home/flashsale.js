import Alpine from 'alpinejs';
import Swiper from 'swiper';
import { Pagination, Autoplay } from 'swiper/modules';

Alpine.data('flashsaleCountdown', (seconds = 0) => ({
    remaining: Number(seconds),

    days: '00',
    hours: '00',
    minutes: '00',
    seconds: '00',

    interval: null,

    init() {
        this.update();

        this.interval = setInterval(() => {
            if (this.remaining <= 0) {
                clearInterval(this.interval);
                return;
            }

            this.remaining--;
            this.update();
        }, 1000);
    },

    update() {
        this.days = String(Math.floor(this.remaining / 86400)).padStart(2, '0');
        this.hours = String(Math.floor((this.remaining % 86400) / 3600)).padStart(2, '0');
        this.minutes = String(Math.floor((this.remaining % 3600) / 60)).padStart(2, '0');
        this.seconds = String(this.remaining % 60).padStart(2, '0');
    },

    destroy() {
        clearInterval(this.interval);
    },
}));

document.addEventListener('DOMContentLoaded', () => {
    const slider = document.querySelector('.flashsale-swiper');

    if (!slider) return;

    new Swiper('.flashsale-swiper', {
        modules: [Pagination, Autoplay],

        slidesPerView: 2.4,
        spaceBetween: 10,

        pagination: {
            el: '.flashsale-pagination',
            clickable: true,
            dynamicBullets: true,
            dynamicMainBullets: 3,
        },

        autoplay: {
            delay: 4000,
            disableOnInteraction: false,
        },

        breakpoints: {
            640: {
                slidesPerView: 3,
                spaceBetween: 16,
            },
            768: {
                slidesPerView: 4,
            },
            1024: {
                slidesPerView: 5,
            },
            1280: {
                slidesPerView: 6,
            }
        }
    });
});