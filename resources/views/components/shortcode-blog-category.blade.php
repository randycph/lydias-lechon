<div class="container">
    <div class=" px-4 py-10 mx-auto w-full flex justify-center">
        <div class="relative">
            <div class="swiper swiper-menus relative">
                <div class="swiper-wrapper lg:gap-10">
                    @foreach ($categories as $category)
                    <a href="{{ route('blogs-category', $category->slug) }}" class="swiper-slide !flex items-center justify-center p-4 flex-col !w-[140px] lg:!w-[175px] h-[140px] lg:h-[175px]">
                        <div class="bg-white border-secondary border-2 p-2 rounded-lg items-center w-[140px] lg:w-[175px] h-[140px] lg:h-[175px] flex flex-col justify-center overflow-hidden">
                            <img onerror="this.onerror=null;this.src='{{ asset('images/no-image.jpg') }}'" src="{{ asset('images/news/' . $category->image) }}" alt="{{ $category->name }}" class="rounded-lg hover:scale-125 transition-transform duration-300">
                        </div>
                        <div class="font-semibold text-base lg:text-lg text-center mt-2">{{ $category->name }}</div>
                    </a>
                    @endforeach
                </div>
            </div>
            <button class="lg:hidden absolute left-0 top-1/2 transform -translate-y-1/2 bg-white text-black p-3 rounded-full z-10 swiper-button-prev-custom">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                </svg>
            </button>
            <button class="lg:hidden absolute right-0 top-1/2 transform -translate-y-1/2 bg-white text-black p-3 rounded-full z-10 swiper-button-next-custom">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                </svg>
            </button>
        </div>
    </div>
</div>