{{-- Blog --}}
<div class="flex flex-col gap-3 px-4 container">
    <h2 class="text-4xl lg:text-6xl font-cubao font-medium text-center mt-16 text-primary">BITE-SIZED MOMENTS: LYDIa's blog</h2>
    <div class="font-medium text-center mt-4 text-base lg:text-2xl w-full max-w-3xl mx-auto">Discover tasty tips, exciting updates, and everything you need to know about celebrating with Lydia's Lechon.</div>
    <a href="#" class="text-white bg-primary rounded-md px-6 py-3 mt-4 font-medium mx-auto">View All Blogs</a> 
</div>

<div class="relative container">
    <div class="swiper swiper-blogs relative mt-10">
        <!-- Additional required wrapper -->
        <div class="swiper-wrapper">
            <!-- Slides -->
            <div class="swiper-slide flex flex-col items-center gap-2 p-4">
                <img src="{{ asset('images/blog-img1.png') }}" alt="Anniversary Give-Back Promo" class="w-full rounded-lg">
                <div class="text-gray-600 text-sm lg:text-base mt-2">Nov 04, 2021</div>
                <div class="font-semibold text-base lg:text-lg">Anniversary Give-Back Promo</div>
            </div>
            <div class="swiper-slide flex flex-col items-center gap-2 p-4">
                <img src="{{ asset('images/blog-img2.png') }}" alt="Anniversary Give-Back Promo" class="w-full rounded-lg">
                <div class="text-gray-600 text-sm lg:text-base mt-2">Nov 04, 2021</div>
                <div class="font-semibold text-base lg:text-lg">Food Trip This Pandemic</div>
            </div>
            <div class="swiper-slide flex flex-col items-center gap-2 p-4">
                <img src="{{ asset('images/blog-img1.png') }}" alt="Anniversary Give-Back Promo" class="w-full rounded-lg">
                <div class="text-gray-600 text-sm lg:text-base mt-2">Nov 04, 2021</div>
                <div class="font-semibold text-base lg:text-lg">Anniversary Give-Back Promo</div>
            </div>
            <div class="swiper-slide flex flex-col items-center gap-2 p-4">
                <img src="{{ asset('images/blog-img1.png') }}" alt="Anniversary Give-Back Promo" class="w-full rounded-lg">
                <div class="text-gray-600 text-sm lg:text-base mt-2">Nov 04, 2021</div>
                <div class="font-semibold text-base lg:text-lg">Anniversary Give-Back Promo</div>
            </div>
            <div class="swiper-slide flex flex-col items-center gap-2 p-4">
                <img src="{{ asset('images/blog-img1.png') }}" alt="Anniversary Give-Back Promo" class="w-full rounded-lg">
                <div class="text-gray-600 text-sm lg:text-base mt-2">Nov 04, 2021</div>
                <div class="font-semibold text-base lg:text-lg">Anniversary Give-Back Promo</div>
            </div>
        </div>
    </div>
    <button class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-white text-black p-3 rounded-full z-10 swiper-button-prev-custom">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
        </svg>
    </button>
    <button class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-white text-black p-3 rounded-full z-10 swiper-button-next-custom">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
        </svg>
    </button>
</div>