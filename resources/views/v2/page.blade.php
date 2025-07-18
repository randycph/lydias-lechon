@extends('layouts.guest')

@section('title', $page->meta_title ?? $page->label ?? 'Careers at Lydia\'s Lechon')
@section('meta_description', $page->meta_description ?? 'Join the Lydia\'s Lechon family! Explore exciting career opportunities and be part of our mission to bring joy and delicious lechon to every Filipino home. Apply now!')
@section('image', $page->image_url ?? null)

@section('content')
    {{-- if route name is home --}}
    @if (request()->routeIs('home'))
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
                <img src="{{ asset('images/lechon-image3.jpg') }}" alt="Lydias Lechon" class="w-full h-screen object-cover object-right hidden lg:block"> 
                <img src="{{ asset('images/portrait-hero.jpg') }}" alt="Lydias Lechon" class="w-full h-screen object-cover object-right lg:hidden block"> 
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

