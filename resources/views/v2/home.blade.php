@extends('layouts.guest')

@section('content')

    <x-marketing-popup-component />

    {{-- Hero Section --}}
    <div class="pt-20 pb-16 flex flex-col items-center text-center relative h-screen overflow-hidden" style="background-image: url('{{ asset('/images/hero-bg.png') }}'); background-size: cover; background-position: center;">
        <div class="relative w-full h-full">
            <div class="flex justify-start items-start text-primary px-3 font-cubao z-20 pt-10">
                <h1 class="text-8xl font-light text-left">EVERYDAY LECHON HAPPINESS</h1>
            </div>
            <img @click="marketingPopup = true" src="{{ asset('/images/lechon-chopped.png') }}" alt="Lechon" class="w-full h-full object-cover absolute -bottom-30 left-0 z-10 ">
        </div>
    </div>
    {{-- Delivery option --}}
    <div x-data="{ scrollPosition: 0, maxScroll: 0 }" x-init="maxScroll = $refs.slider.scrollWidth - $refs.slider.clientWidth" class="relative w-full">
        <!-- Scrollable Container -->
        <div class="overflow-x-scroll scrollbar-hide scroll-smooth snap-x flex items-center space-x-6 bg-tertiary p-6" 
            x-ref="slider"
            @scroll="scrollPosition = $refs.slider.scrollLeft">
            
            <!-- Slide 1 -->
            <div class="min-w-[250px] text-white text-left snap-normal">
                <div class="flex flex-col gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-8 text-white">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                    </svg>
                    <div class="text-white">
                        <span>METRO MANILA DELIVERY</span>
                        <div>Fresh lechon delivered to your doorstep.</div>
                    </div>
                </div>
            </div>
    
            <!-- Slide 2 -->
            <div class="min-w-[250px] text-white text-left snap-normal">
                <div class="flex flex-col gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-8 text-white">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z" />
                    </svg>
                    <div class="text-white">
                        <span>STORE PICKUP</span>
                        <div>Conventient and easy pick up from our store.</div>
                    </div>
                </div>
            </div>
    
            <!-- Add more slides as needed -->
        </div>
    </div>

    <x-products-component />

    <x-our-story-component />

    {{-- Find Lydia's Lechon at your nearest store --}}
    <div class="bg-tertiary rounded-lg mx-4 mt-16">
        <img src="{{ asset('images/lydia-store-img2.png') }}" alt="Find Lydia's Lechon at your nearest store" class="rounded-t-lg">
        <div class="flex flex-col items-start py-6 px-4 text-white gap-4">
            <h2 class="text-4xl font-cubao font-medium  text-left">Find a Lydia's Lechon Near You</h2>
            <p class="text-left">With over 25 stores across Manila, Lydia’s Lechon is always close by to bring the taste of tradition to your table. Visit us today and experience the joy of Filipino flavors!</p>
            <button class="text-white bg-primary rounded-md px-6 py-3 mt-3 font-medium">Our Stores</button>
        </div>
    </div>

    <x-digital-partners-component />

    <x-faq-component />

    <x-blogs-component />

    <x-as-seen-in-component />

    <x-newsletter-component />

    <x-footer-component />
    
@endsection

