// Import Swiper and required modules
import Swiper from 'swiper';

import { Navigation, Pagination, Autoplay,
  EffectFade, EffectCube, EffectCoverflow, EffectFlip, EffectCards, EffectCreative } from 'swiper/modules';

// Import Swiper and modules styles
import 'swiper/css';
import 'swiper/css/effect-fade';
import 'swiper/css/effect-cube';
import 'swiper/css/effect-coverflow';
import 'swiper/css/effect-flip';
import 'swiper/css/effect-cards';
import 'swiper/css/navigation';
import 'swiper/css/pagination';

// import 'flowbite';

// Import Swiper styles
document.addEventListener("DOMContentLoaded", function () {
    setTimeout(() => {
        const swiper = new Swiper('.swiper-blogs', {
            modules: [Navigation, Pagination],
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
            modules: [Navigation, Pagination],
            loop: false,
            slidesPerView: 2,
            centeredSlides: false,

            breakpoints: {
                768: {
                    slidesPerView: 3,
                },
                1024: {
                    slidesPerView: 3,
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
            modules: [Navigation, Pagination],
            loop: false,
            slidesPerView: 2,
            centeredSlides: false,

            spaceBetween: 30,
            watchOverflow: true,
            
            breakpoints: {
                480: {
                    slidesPerView: 3,
                },
                768: {
                    slidesPerView: 6,
                },
                1024: {
                    slidesPerView: 7,
                },
                1280: {
                    slidesPerView: 8,
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
        const swiper = new Swiper('.swiper-cart-image', {
            modules: [Navigation, Pagination],
            loop: false,
            slidesPerView: 1,

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

 
const el = document.querySelector('.page-sliders');
if (el) {
    const effect = (el.dataset.effect || 'slide').toLowerCase();
    const speedS = 1; 
    const speedMs = Math.max(100, speedS * 1000);

    // base options
    const options = {
        modules: [Navigation, Pagination, Autoplay,
                EffectFade, EffectCube, EffectCoverflow, EffectFlip, EffectCards, EffectCreative],
        effect,
        slidesPerView: 1,
        loop: false,
        speed: speedMs,
        autoplay: { delay: speedMs, disableOnInteraction: false },
        navigation: {
        nextEl: '.swiper-button-next-custom',
        prevEl: '.swiper-button-prev-custom',
        },
        pagination: { el: '.swiper-pagination', clickable: true },
    };

    // effect-specific tuning
    switch (effect) {
        case 'fade':
        options.fadeEffect = { crossFade: true };
        break;
        case 'cube':
        options.cubeEffect = {
            shadow: true,
            slideShadows: true,
            shadowOffset: 20,
            shadowScale: 0.94
        };
        break;
        case 'coverflow':
        options.centeredSlides = true;
        options.coverflowEffect = {
            rotate: 0,      // subtle; bump if you want more tilt
            stretch: 0,
            depth: 120,
            modifier: 1,
            slideShadows: true
        };
        break;
        case 'flip':
        options.flipEffect = { slideShadows: true, limitRotation: true };
        break;
        case 'cards':
        options.grabCursor = true; // feels nicer for cards
        break;
        case 'creative':
        // mimic a classy zoom/opacity transition
        options.creativeEffect = {
            prev: { translate: ['-20%', 0, -1], opacity: 0.6, scale: 0.85 },
            next: { translate: ['20%', 0, -1],  opacity: 0.6, scale: 0.85 }
        };
        break;
        // 'slide' uses defaults
    }

    new Swiper(el, options);
}



});
