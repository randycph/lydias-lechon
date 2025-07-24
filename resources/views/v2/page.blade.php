@extends('layouts.guest')

@section('title', $page->meta_title ?? $page->label ?? 'Careers at Lydia\'s Lechon')
@section('meta_description', $page->meta_description ?? 'Join the Lydia\'s Lechon family! Explore exciting career opportunities and be part of our mission to bring joy and delicious lechon to every Filipino home. Apply now!')
@section('image', $page->image_url ?? null)

@section('content')
    {{-- if route name is home --}}
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
                <img src="{{ $banner->image_path }}" alt="Lydias Lechon" class="w-full h-screen object-cover object-right hidden lg:block"> 
                {{-- <img src="{{ asset('images/portrait-hero.jpg') }}" alt="Lydias Lechon" class="w-full h-screen object-cover object-right lg:hidden block">  --}}
            </div>
        </div>
        @endif
    @endif
    

    <div>
        @if ($page->contents)
            @php
                $cleanContent = stripslashes($page->contents);
            @endphp

            {!! parse_shortcodes($cleanContent) !!}
        @endif
    </div>

    <x-footer-component />
    
@endsection

