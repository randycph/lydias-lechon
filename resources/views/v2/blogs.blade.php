@extends('layouts.guest')

@section('content')

    <div class="pt-20 pb-10 px-4 container">
        <p class="text-center text-base lg:text-3xl uppercase mt-10 font-semibold">Find the perfect lechon for your budget</p>
        <h1 class="text-4xl lg:text-7xl font-cubao font-medium text-primary text-center ">lechon pricelist</h1>
    </div>

    <div class="relative mx-auto pb-12">

        <div class="bg-tertiary container">
            <div class="flex flex-col lg:flex-row">
                <img class="w-full lg:w-1/2" src="{{ asset('images/featured-blog.png') }}" alt="Lydiandary the story of how a little girl’s idea became the world famous Lydia’s Lechon">
                <div class="w-full lg:w-1/2 p-2 lg:p-10">
                    <div class="p-6 text-white">
                        <div class="font-light text-base lg:text-xl">SEPTEMBER 25, 2024</div>
                        <h2 class="text-3xl lg:text-5xl font-semibold mt-5">“Lydiandary” the story of how a little girl’s idea became the world famous Lydia’s Lechon</h2>
                    </div>
                    <div class="px-6 pb-6 lg:pt-10">
                        <div class="rounded-md border border-white py-3 px-10 w-max">
                            <a href="{{ route('article') }}" class="text-center text-base lg:text-xl  text-white flex justify-center relative">Read Article</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <h2 class="font-cubao text-3xl lg:text-6xl text-center text-primary mt-12">savor the flavor</h2>

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
                    ]
            
                ];
            @endphp

            <div class=" px-4 py-10 mx-auto w-full flex justify-center">
                <div class="relative">
                    <div class="swiper swiper-menus relative">
                        <div class="swiper-wrapper lg:gap-10">
                            @foreach ($products as $product)
                            <a href="{{ route('article') }}" class="swiper-slide !flex items-center justify-center p-4 flex-col !w-[140px] lg:!w-[175px] h-[140px] lg:h-[175px]">
                                <div class="bg-white border-secondary border-2 p-2 rounded-lg items-center w-[140px] lg:w-[175px] h-[140px] lg:h-[175px] flex flex-col justify-center overflow-hidden">
                                    <img src="{{ asset('images/' . $product['image']) }}" alt="Anniversary Give-Back Promo" class="rounded-lg">
                                </div>
                                <div class="font-semibold text-base lg:text-lg text-center mt-2">{{ $product['name'] }}</div>
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
                <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-4 mt-6">
                    @foreach ($blogs as $blog)
                    <a href="{{ route('article') }}" class="rounded-lg">
                        <img src="{{ asset('images/' . $blog['image']) }}" alt="{{ $blog['title'] }}" class="w-full h-[188px] lg:h-[362px] object-cover rounded-t-lg">
                        <div class="uppercase text-base lg:text-lg font-semibold py-2 px-1">{{ $blog['title'] }}</div>
                    </a>
                    @endforeach
                </div>

                <div class="flex justify-center mt-6">
                    <button class="border-primary border text-base lg:text-lg text-primary px-6 py-3 rounded-md">Load More</button>
                </div>
            </div>
        </div>
    </div>

    <x-newsletter-component />

    <x-footer-component />
    
@endsection

