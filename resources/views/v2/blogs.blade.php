@extends('layouts.guest')

@section('content')

    <div class="pt-20 pb-10 px-4 container">
        <p class="text-center text-base lg:text-3xl uppercase mt-10 font-semibold">NAVIGATING THE WORLD OF LECHON</p>
        <h1 class="text-4xl lg:text-7xl font-cubao font-medium text-primary text-center ">Roast to perfection</h1>
    </div>

    <div class="relative mx-auto pb-12">

        <div class="bg-tertiary container">
            <div class="flex flex-col lg:flex-row">
                <img class="w-full lg:w-1/2" src="{{ $featuredArticle?->image_url ?? $featuredArticle?->thumbnail_url }}" alt="Lydiandary the story of how a little girl’s idea became the world famous Lydia’s Lechon">
                <div class="w-full lg:w-1/2 p-2 lg:p-10">
                    <div class="p-6 text-white">
                        <div class="font-light text-base lg:text-xl">{{ $featuredArticle?->created_at->format('F d, Y') }}</div>
                        <h2 class="text-3xl lg:text-5xl font-semibold mt-5">{{ $featuredArticle->name }}</h2>
                    </div>
                    <div class="px-6 pb-6 lg:pt-10">
                        <div class="rounded-md border border-white py-3 px-10 w-max custom-btn btn-tertiary">
                            <a href="{{ route('article', ['category' => $featuredArticle?->category->slug, 'slug' => $featuredArticle?->slug]) }}" class="text-center text-base lg:text-xl  text-white flex justify-center relative">Read Article</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <h2 class="font-cubao text-3xl lg:text-6xl text-center text-primary mt-12">savor the flavor</h2>

            
            <div class=" px-4 py-10 mx-auto w-full flex justify-center">
                <div class="relative">
                    <div class="swiper swiper-menus relative">
                        <div class="swiper-wrapper lg:gap-10">
                            @foreach ($categories as $category)
                            <a href="{{ route('blogs-category', $category->slug) }}" class="swiper-slide !flex items-center justify-center p-4 flex-col !w-[140px] lg:!w-[175px] h-[140px] lg:h-[175px]">
                                <div class="bg-white border-secondary border-2 p-2 rounded-lg items-center w-[140px] lg:w-[175px] h-[140px] lg:h-[175px] flex flex-col justify-center overflow-hidden">
                                    <img src="{{ asset('images/news/' . $category->image) }}" alt="{{ $category->name }}" class="rounded-lg hover:scale-125 transition-transform duration-300">
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

            <div class="px-4"
                    x-data="{
                    page: 1,
                    loaded: false,
                    hasMore: true,
                    async init() {
                        if (this.loaded) return; // 🛡️ prevent double run
                        this.loaded = true;
                        console.log('[Alpine] init triggered once');
                        await this.loadArticles();
                    },
                    async loadArticles() {
                        await this.fetchArticles();
                    },
                    async loadMore() {
                        this.page++;
                        this.loading = true;
                        await this.fetchArticles();
                        this.loading = false;
                    },
                    async fetchArticles() {
                        try {
                            const response = await fetch(`{{ route('articles.load-more') }}?page=${this.page}`, {
                                method: 'GET',
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            });

                            if (!response.ok) throw new Error('Network response was not ok');

                            const data = await response.json();

                            document.getElementById('blogs').insertAdjacentHTML('beforeend', data.html);
                            this.hasMore = data.hasMore;
                        } catch (error) {
                            console.error('Fetch error:', error);
                        }
                    }
                }"
                x-init="$nextTick(() => init())"
            >
       
                <h2 class="font-cubao text-3xl lg:text-5xl text-center text-primary mt-12">latest blogs</h2>
                <div id="blogs" class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-4 mt-6">
                    
                </div>
                <div class="flex justify-center mt-6" x-show="hasMore">
                    <button
                        @click="loadMore"
                        :disabled="loading"
                        class="custom-btn btn-primary border-primary border text-base lg:text-lg text-primary px-6 py-3 rounded-md flex items-center gap-2"
                    >
                        <svg x-show="loading" class="animate-spin h-5 w-5 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                        </svg>
                        <span x-show="!loading">Load More</span>
                        <span x-show="loading">Loading...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <x-newsletter-component />

    <x-footer-component />
    
@endsection

