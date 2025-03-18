import Vue from 'vue'; // Ensure Vue is imported properly
import ExampleComponent from './components/ExampleComponent.vue';

// Import Swiper and required modules
import Swiper from 'swiper';
import { Navigation, Pagination } from 'swiper/modules';

// Import Swiper and modules styles
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';

document.addEventListener("DOMContentLoaded", function () {
    setTimeout(() => {
        const swiper = new Swiper('.swiper-blogs', {
            modules: [Navigation, Pagination], // ✅ Register Swiper modules
            loop: true,
            slidesPerView: 1,
            centeredSlides: false,

            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },

            navigation: {
                nextEl: '.swiper-button-next-custom',
                prevEl: '.swiper-button-prev-custom',
            },
        });
    }, 100); // Small delay to ensure elements are loaded
});
