@extends('layouts.guest')

@section('content')

    @if ($albums && $albums->banners->isNotEmpty())
    @php
        $banner = $albums->banners->first();
    @endphp
    <div class="pb-16 flex flex-col w-full items-center text-center relative h-screen overflow-hidden" style="background-image: url('{{ $banner->image_path }}'); background-size: cover; background-position: center; background-repeat: no-repeat; background-color: rgba(0, 0, 0, 0.5);">
        <div class="relative w-full h-full">
            <div class="container absolute flex self-center text-gray-200 px-3 font-cubao z-20 pt-10" style="position-area: center; align-self: anchor-center;">
                <h1 class="text-8xl lg:text-9xl font-light text-center mx-auto w-full lg:w-[80%] drop-shadow-[0_0_10px_green]">
                    {{ $banner->title }}
                </h1>
            </div>
            {{-- <img src="{{ asset('images/lechon-image3.jpg') }}" alt="Lydias Lechon" class="w-full h-screen object-cover object-right hidden lg:block"> 
            <img src="{{ asset('images/portrait-hero.jpg') }}" alt="Lydias Lechon" class="w-full h-screen object-cover object-right lg:hidden block">  --}}
        </div>
    </div>
    @endif

    @php 
    
    $blocks = [
        [
            'title' => 'METRO MANILA DELIVERY',
            'description' => 'Fresh lechon delivered to your doorstep.',
            'icon' => 'block1.svg'
        ],
        [
            'title' => 'STORE PICKUP',
            'description' => 'Conventient and easy pick up from our store.',
            'icon' => 'block2.svg'
        ],
        [
            'title' => '60 YEARS OF TRADITION',
            'description' => 'Mastering lechon roasting for decades.',
            'icon' => 'block3.svg'
        ],
        [
            'title' => 'PREMIUM INGREDIENTS',
            'description' => 'Fresh pork and quality herbs, every time.',
            'icon' => 'block4.svg'
        ],
    ];

    @endphp

    <div class="w-full mx-auto bg-tertiary ">
        <div x-data="{ scrollPosition: 0, maxScroll: 0 }" x-init="maxScroll = $refs.slider.scrollWidth - $refs.slider.clientWidth" class="relative w-full">
            <!-- Scrollable Container -->
            <div class="container overflow-x-scroll lg:overflow-hidden scrollbar-hide scroll-smooth snap-x flex items-center space-x-6 p-6" 
                x-ref="slider"
                @scroll="scrollPosition = $refs.slider.scrollLeft">
                
                @foreach ($blocks as $block)
                <div class="min-w-[250px] text-white text-left snap-normal mx-auto">
                    <div class="flex gap-3">
                        <img src="{{ asset('images/' . $block['icon']) }}" alt="{{ $block['title'] }}" class="w-8 h-8">
                        <div class="text-white">
                            <span>{{ $block['title'] }}</span>
                            <div>{{ $block['description'] }}</div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <x-products-component :categories="$categories" />

    <x-our-story-component />

    {{-- Find Lydia's Lechon at your nearest store --}}
    <div class="container">
        <div class="mx-4 mt-16 flex flex-col lg:flex-row">
            <img src="{{ asset('images/lydia-store-img2.png') }}" alt="Find Lydia's Lechon at your nearest store" 
                class="rounded-t-lg lg:rounded-lg order-1 lg:order-2 lg:w-3/5 w-full">
            <div class="flex flex-col items-start py-6 px-4 text-white gap-4 order-2 lg:order-1 lg:w-2/5 bg-tertiary lg:my-10 lg:py-12 lg:px-12 lg:rounded-bl-lg lg:rounded-tl-lg">
                <h2 class="text-4xl font-cubao font-medium text-left lg:text-5xl">Find a Lydia's Lechon Near You</h2>
                <p class="text-left">With over 25 stores across Manila, Lydia’s Lechon is always close by to bring the taste of tradition to your table. Visit us today and experience the joy of Filipino flavors!</p>
                <button class="custom-btn btn-primary-dark text-white bg-primary rounded-md px-6 py-3 mt-3 text-sm font-medium">Our Stores</button>
            </div>
        </div>
    </div>

    <x-digital-partners-component />

    <x-faq-component />

    <x-blogs-component :blogs="$blogs"/>

    <x-as-seen-in-component />

    <x-newsletter-component />

    <x-footer-component />
    
@endsection

