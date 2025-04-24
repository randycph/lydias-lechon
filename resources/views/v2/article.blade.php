@extends('layouts.guest')

@section('content')

    <div class="pt-20 pb-10 px-4 container lg:pt-32">
        <div class="w-full max-w-4xl mx-auto">
            <a href="{{ route('blogs') }}" class="text-tertiary flex items-center gap-1 cursor-pointer mt-5 font-semibold">  
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                    <path fill-rule="evenodd" d="M7.72 12.53a.75.75 0 0 1 0-1.06l7.5-7.5a.75.75 0 1 1 1.06 1.06L9.31 12l6.97 6.97a.75.75 0 1 1-1.06 1.06l-7.5-7.5Z" clip-rule="evenodd" />
                </svg>
                <span>Go Back</span>
            </a>
    
            <div class="flex flex-col justify-center mt-10 items-center w-full">
                <div class="font-light">PUBLISHED {{ $article?->created_at->format('F d, Y') }}</div>
                <h1 class="text-4xl lg:text-6xl font-semibold my-5">
                    {{ $article?->name }}
                </h1>
                <div class="flex gap-2 justify-center my-5 items-center">
                    <span class="font-light text-sm">Share via</span>
                    <div class="flex gap-4">
                        <a href="">
                                <svg class="size-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                                <!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc. -->
                                <path
                                    d="M80 299.3V512H196V299.3h86.5l18-97.8H196V166.9c0-51.7 20.3-71.5 72.7-71.5c16.3 0 29.4 .4 37 1.2V7.9C291.4 4 256.4 0 236.2 0C129.3 0 80 50.5 80 159.4v42.1H14v97.8H80z" />
                                </svg>
                        </a>
                        <a href="">
                            <svg
                            class="size-6"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="currentColor"
                            viewBox="0 0 512 512">
                            <!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc. -->
                            <path
                              d="M256.6 8C116.5 8 8 110.3 8 248.6c0 72.3 29.7 134.8 78.1 177.9 8.4 7.5 6.6 11.9 8.1 58.2A19.9 19.9 0 0 0 122 502.3c52.9-23.3 53.6-25.1 62.6-22.7C337.9 521.8 504 423.7 504 248.6 504 110.3 396.6 8 256.6 8zm149.2 185.1l-73 115.6a37.4 37.4 0 0 1 -53.9 9.9l-58.1-43.5a15 15 0 0 0 -18 0l-78.4 59.4c-10.5 7.9-24.2-4.6-17.1-15.7l73-115.6a37.4 37.4 0 0 1 53.9-9.9l58.1 43.5a15 15 0 0 0 18 0l78.4-59.4c10.4-8 24.1 4.5 17.1 15.6z" />
                          </svg>
                        </a>
                        <a href="">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                                <path d="M1.5 8.67v8.58a3 3 0 0 0 3 3h15a3 3 0 0 0 3-3V8.67l-8.928 5.493a3 3 0 0 1-3.144 0L1.5 8.67Z" />
                                <path d="M22.5 6.908V6.75a3 3 0 0 0-3-3h-15a3 3 0 0 0-3 3v.158l9.714 5.978a1.5 1.5 0 0 0 1.572 0L22.5 6.908Z" />
                            </svg>
                              
                        </a>
                        <a href="">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                                <path fill-rule="evenodd" d="M19.902 4.098a3.75 3.75 0 0 0-5.304 0l-4.5 4.5a3.75 3.75 0 0 0 1.035 6.037.75.75 0 0 1-.646 1.353 5.25 5.25 0 0 1-1.449-8.45l4.5-4.5a5.25 5.25 0 1 1 7.424 7.424l-1.757 1.757a.75.75 0 1 1-1.06-1.06l1.757-1.757a3.75 3.75 0 0 0 0-5.304Zm-7.389 4.267a.75.75 0 0 1 1-.353 5.25 5.25 0 0 1 1.449 8.45l-4.5 4.5a5.25 5.25 0 1 1-7.424-7.424l1.757-1.757a.75.75 0 1 1 1.06 1.06l-1.757 1.757a3.75 3.75 0 1 0 5.304 5.304l4.5-4.5a3.75 3.75 0 0 0-1.035-6.037.75.75 0 0 1-.354-1Z" clip-rule="evenodd" />
                            </svg>
                        </a>
                          
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-10">
            <div class="text-center">

                <div class="text-left">
                    @php
                        $image = !empty($article?->image_url) ? $article?->image_url : $article?->thumbnail_url;
                    @endphp
                    <img src="{{ $image }}" alt="Lydiandary the story of how a little girl’s idea became the world famous Lydia’s Lechon"  class="mb-5 rounded-lg mx-auto">

                    <div class="w-full max-w-5xl mx-auto mt-10">
                        {!! $article?->contents !!}

                        <div class="flex flex-col gap-4 lg:flex-row mt-10">
                            @if ($previous)
                            <div class="w-full lg:w-1/2 py-4 px-6 rounded-lg lg:rounded-tl-lg flex flex-col lg:rounded-bl-lg border border-tertiary">
                                <div class="flex justify-start">
                                    <a href="{{ route('article', ['category' => $previous->category->slug, 'slug' => $previous->slug]) }}" class="uppercase text-xs">Previous news</a>
                                </div>
                                <a href="{{ route('article', ['category' => $previous->category->slug, 'slug' => $previous->slug]) }}" class="flex mt-3 gap-2 items-start">
                                    <img src="{{ $previous->thumbnail_url }}" alt="Blog1" class="h-[114px] w-[114px] object-cover rounded-lg hover:scale-110 transition-transform duration-300">
                                    <div>
                                        <div class="font-bold text-base lg:text-xl w-full max-w-xs">{{ $previous->name }}</div>
                                        <div class="font-light text-sm mt-3">{{ $previous->created_at->format('F d, Y') }}</div>
                                    </div>
                                </a>
                            </div>
                            @endif
                            @if ($next)
                            <div class="w-full lg:w-1/2 py-4 px-6 rounded-lg lg:rounded-tr-lg flex flex-col lg:rounded-br-lg border border-tertiary">
                                <div class="flex justify-end">
                                    <a href="{{ route('article', ['category' => $next->category->slug, 'slug' => $next->slug]) }}" class="uppercase text-xs">Next news</a>
                                </div>
                                <a href="{{ route('article', ['category' => $next->category->slug, 'slug' => $next->slug]) }}" class="flex mt-3 gap-2 items-start">
                                    <img src="{{ $next->thumbnail_url }}" alt="Blog1" class="h-[114px] w-[114px] object-cover rounded-lg hover:scale-110 transition-transform duration-300">
                                    <div>
                                        <div class="font-bold text-base lg:text-xl w-full max-w-xs">{{ $next->name }}</div>
                                        <div class="font-light text-sm mt-3">{{ $next->created_at->format('F d, Y') }}</div>
                                    </div>
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="pt-20 pb-10 px-4 container">
        <div class="px-4 pt-10 flex justify-between">
            <h2 class="text-2xl lg:text-4xl text-primary">Related News</h2>
            <a href="{{ route('blogs-category', $article->category->slug) }}" class="underline text-lg">
                View All
            </a>
        </div>

        <div>
            <div class="px-4 pb-10">
                <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-4 mt-6">
                    @foreach ($relatedNews as $related)
                    <a href="{{ route('article', ['category' => $related->category->slug, 'slug' => $related->slug]) }}" class="rounded-lg group">
                        <img src="{{ $related->thumbnail_url }}" alt="{{ $related->name }}" class="w-full h-[188px] object-cover rounded-t-lg group-hover:scale-110 transition-transform duration-300">
                        <div class="uppercase font-semibold py-2 px-1 ">{{ $related->name }}</div>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <x-newsletter-component />

    <x-footer-component />
    
@endsection

