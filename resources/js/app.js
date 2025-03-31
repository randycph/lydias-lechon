// Import Swiper and required modules
import Swiper from 'swiper';
import { Navigation, Pagination, EffectCoverflow } from 'swiper/modules';

// Import Swiper and modules styles
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';

// import 'flowbite';

// Import Swiper styles
document.addEventListener("DOMContentLoaded", function () {
    setTimeout(() => {
        const swiper = new Swiper('.swiper-blogs', {
            modules: [Navigation, Pagination], // ✅ Register Swiper modules
            loop: true,
            slidesPerView: 1.2,

            breakpoints: {
                768: {
                    slidesPerView: 2,
                },
                1024: {
                    slidesPerView: 3.2,
                },
            },

            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },

            navigation: {
                nextEl: '.swiper-button-next-custom',
                prevEl: '.swiper-button-prev-custom',
            },
        });
    }, 100);

    setTimeout(() => {
        const swiper = new Swiper('.swiper-stores', {
            modules: [Navigation, Pagination, EffectCoverflow], 
            effect: "coverflow",
            grabCursor: true,
            centeredSlides: true,
            slidesPerView: "auto",
            loop: true,

            coverflowEffect: {
                rotate: 0,
                stretch: 0,
                depth: 200,
                modifier: 1,
                slideShadows: true,
            },

            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },

            navigation: {
                nextEl: '.swiper-button-next-custom',
                prevEl: '.swiper-button-prev-custom',
            },
        });
    }, 100);

    setTimeout(() => {
        const swiper = new Swiper('.swiper-addons', {
            modules: [Navigation, Pagination], // ✅ Register Swiper modules
            loop: false,
            slidesPerView: 2,
            centeredSlides: false,

            breakpoints: {
                768: {
                    slidesPerView: 4,
                },
                1024: {
                    slidesPerView: 5,
                },
            },

            spaceBetween: 15,

            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },

            navigation: {
                nextEl: '.swiper-button-next-custom',
                prevEl: '.swiper-button-prev-custom',
            },
        });
    }, 100);

    setTimeout(() => {
        const swiper = new Swiper('.swiper-menus', {
            modules: [Navigation, Pagination], // ✅ Register Swiper modules
            loop: false,
            slidesPerView: 2,
            centeredSlides: false,

            spaceBetween: 15,
            
            breakpoints: {
                768: {
                    slidesPerView: 3,
                },
            },

            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },

            navigation: {
                nextEl: '.swiper-button-next-custom',
                prevEl: '.swiper-button-prev-custom',
            },
        });
    }, 100);
});
