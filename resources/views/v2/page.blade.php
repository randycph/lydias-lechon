@extends('layouts.guest')

@section('title', $page->meta_title ?? $page->label ?? 'Careers at Lydia\'s Lechon')
@section('meta_description', $page->meta_description ?? 'Join the Lydia\'s Lechon family! Explore exciting career opportunities and be part of our mission to bring joy and delicious lechon to every Filipino home. Apply now!')
@section('image', $page->image_url ?? null)

@section('content')
    @if ($page->image_url != null)
        @if (request()->routeIs('home') || $page->slug == 'home')
            @if ($albums && $albums->banners->isNotEmpty())
                @php
                    $banner = $albums->banners->first();
                @endphp
                <div class="flex flex-col w-full items-center text-center relative overflow-hidden" >
                    <div class="relative w-full ">
                        <div 
                            class="
                                absolute flex-col flex w-full text-gray-200 px-3 z-20
                                bottom-[4%] 
                                left-1/2 -translate-x-1/2
                                lg:top-1/2 lg:bottom-auto lg:-translate-y-1/2 lg:left-1/2
                                place-items-center place-content-center
                            " style="">
                            <h1 class="text-4xl md:text-7xl font-light font-cubao  text-center mx-auto w-full drop-shadow-[0_0_10px_green]" style="place-content: center">
                                {{ $banner->title }}
                            </h1>
                            <p class="text-lg">{{ $banner->description }}</p>
                        </div>
                        <img src="{{ asset('images/lydias-hero-banner-mobile.png') }}" alt="Lydias Lechon" class="w-full lg:hidden block"> 
                        <img src="{{ $page->image_url }}" alt="Lydias Lechon" class="w-full h-screen object-cover object-right hidden lg:block"> 
                        {{-- <img src="{{ asset('images/portrait-hero.jpg') }}" alt="Lydias Lechon" class="w-full h-screen object-cover object-right lg:hidden block">  --}}
                    </div>
                </div>
            @else
                <div class="relative w-full ">
                    <div 
                        class="
                            absolute flex-col flex w-full text-gray-200 px-3 z-20
                            bottom-[4%] 
                            left-1/2 -translate-x-1/2
                            lg:top-1/2 lg:bottom-auto lg:-translate-y-1/2 lg:left-1/2
                            place-items-center place-content-center
                        " style="">
                        <h1 class="text-4xl md:text-7xl font-light font-cubao  text-center mx-auto w-full drop-shadow-[0_0_10px_green]" style="place-content: center">
                            {{ $banner->title }}
                        </h1>
                        <p class="text-lg">{{ $banner->description }}</p>
                    </div>
                    <img src="{{ asset('images/lydias-hero-banner-mobile.png') }}" alt="Lydias Lechon" class="w-full lg:hidden block"> 
                    <img src="{{ $page->image_url }}" alt="Lydias Lechon" class="w-full h-screen object-cover object-right hidden lg:block"> 
                    {{-- <img src="{{ asset('images/portrait-hero.jpg') }}" alt="Lydias Lechon" class="w-full h-screen object-cover object-right lg:hidden block">  --}}
                </div>
            @endif
        @else
            <div class="flex flex-col w-full items-center text-center relative overflow-hidden" >
                <div class="relative w-full ">
                    <div 
                        class="
                            absolute flex-col flex w-full text-gray-200 px-3 z-20
                            bottom-[4%] 
                            left-1/2 -translate-x-1/2
                            lg:top-1/2 lg:bottom-auto lg:-translate-y-1/2 lg:left-1/2
                            place-items-center place-content-center
                        " style="">
                    </div>
                    <img src="{{ $page->image_url }}" alt="Lydias Lechon" class="w-full h-auto object-cover"> 
                    {{-- <img src="{{ asset('images/portrait-hero.jpg') }}" alt="Lydias Lechon" class="w-full h-screen object-cover object-right lg:hidden block">  --}}
                </div>
            </div>
        @endif
    @else
        @if ($page->album && $page->album->banners->isNotEmpty())
        <div class="relative">
            <div class="swiper page-sliders relative" 
                data-effect="{{ strtolower($page->album->effect ?? 'slide') }}"
                data-speed="2">
                <div class="swiper-wrapper">
                    @foreach ($page->album->banners as $banner)
                    <div class="swiper-slide !flex items-center justify-center flex-col ">
                        <div class="bg-white rounded-lg items-center  flex flex-col justify-center overflow-hidden">
                            <img onerror="this.onerror=null;this.src='{{ asset('images/no-image.jpg') }}'" src="{{ $banner->image_path }}" alt="{{ $banner->title }}" class="w-full">
                        </div>
                        {{-- <div class="font-semibold text-base lg:text-lg text-center mt-2">{{ $banner->title }}</div> --}}
                    </div>
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
        @endif
    @endif


    {{-- if route name is home --}}
    <div class="">
        @if ($page->contents)
            @php
                $cleanContent = stripslashes($page->contents);
            @endphp

            {!! parse_shortcodes($cleanContent) !!}
        @endif
    </div>

    <x-footer-component />
    
@endsection

