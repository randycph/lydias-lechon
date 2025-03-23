@extends('layouts.guest')

@section('content')

    <div class="pt-20 pb-10 px-4">
        <p class="text-center uppercase mt-10 font-semibold">Find the perfect lechon for your budget</p>
        <h1 class="text-4xl font-cubao font-medium text-primary text-center ">lechon pricelist</h1>
    </div>

    <div class="relative mx-auto pb-12">

        <div class="bg-tertiary">
            <img src="{{ asset('images/featured-blog.png') }}" alt="Lydiandary the story of how a little girl’s idea became the world famous Lydia’s Lechon">
            <div class="p-6 text-white">
                <div class="font-light">SEPTEMBER 25, 2024</div>
                <h2 class="text-3xl font-semibold mt-3">“Lydiandary” the story of how a little girl’s idea became the world famous Lydia’s Lechon</h2>
            </div>
            <div class="p-6 ">
                <div class="w-full rounded-md border border-white py-3 px-4 ">
                    <a href="#" class="text-center text-white flex justify-center relative">Read Article</a>
                </div>
            </div>
        </div>

        <div>
            <h2 class="font-cubao text-3xl text-center text-primary mt-12">savor the flavor</h2>

            @php
                $products = [
                    [
                        'name' => 'Lechon in Modern Cuisine',
                        'image' => 'article1.png',
                    ],
                    [   
                        'name' => 'Health and Nutrition',
                        'image' => 'article2.png',
                    ],
                    [
                        'name' => 'Lechon Stories and Traditions',
                        'image' => 'article3.png',
                    ],
                    [   
                        'name' => 'Health and Nutrition',
                        'image' => 'article2.png',
                    ],
                    [
                        'name' => 'Lechon in Modern Cuisine',
                        'image' => 'article1.png',
                    ],
            
                ];
            @endphp

            <div class="relative px-4">
                <div class="swiper swiper-menus relative">
                    <div class="swiper-wrapper">
                        @foreach ($products as $product)
                        <div class="swiper-slide !flex items-center justify-center p-4 flex-col !w-[140px] h-[140px]">
                            <div class="bg-white border-secondary border-2 p-2 rounded-lg items-center w-[140px] h-[140px] flex flex-col justify-center overflow-hidden">
                                <img src="{{ asset('images/' . $product['image']) }}" alt="Anniversary Give-Back Promo" class="rounded-lg">
                            </div>
                            <div class="font-semibold text-center mt-2">{{ $product['name'] }}</div>
                        </div>
                        @endforeach
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

            @php
                $blogs = [
                    [
                        'title' => "Lydia’s Lechon: A Filipino Passion for Food",
                        'image' => 'blog1.png',
                    ],
                    [
                        'title' => "Lydia’s Lechon: Celebrating 55 years of bringing happiness",
                        'image' => 'blog2.png',
                    ],
                    [
                        'title' => "digital expansion: foodpanda and grabfood",
                        'image' => 'blog3.png',
                    ],
                    [
                        'title' => "Lydia’s Lechon: from a street into a popular dining chain",
                        'image' => 'blog4.png',
                    ],
                    [
                        'title' => "lechon-in-a-box",
                        'image' => 'blog5.png',
                    ],
                    [
                        'title' => "Lydia’s Lechon: A staple in filipino celebration",
                        'image' => 'blog6.png',
                    ],
                    [
                        'title' => "Lydia’s Lechon: A culinary legacy",
                        'image' => 'blog6.png',
                    ],
                    [
                        'title' => "the secret to lydia’s lechon’s success",
                        'image' => 'blog6.png',
                    ],
                ];
            @endphp

            <div class="px-4">
                <h2 class="font-cubao text-3xl text-center text-primary mt-12">latest blogs</h2>
                <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-6">
                    @foreach ($blogs as $blog)
                    <div class="rounded-lg">
                        <img src="{{ asset('images/' . $blog['image']) }}" alt="{{ $blog['title'] }}" class="w-full h-[188px] object-cover rounded-t-lg">
                        <div class="uppercase font-semibold py-2 px-1">{{ $blog['title'] }}</div>
                    </div>
                    @endforeach
                </div>

                <div class="flex justify-center mt-6">
                    <button class="border-primary border text-primary px-4 py-2 rounded-md">Load More</button>
                </div>
            </div>
        </div>
    </div>

    <x-newsletter-component />

    <x-footer-component />
    
@endsection

