@extends('layouts.guest')

@section('content')

    <div class="pt-20 pb-10 px-4">
        <a href="{{ route('blogs') }}" class="text-tertiary flex items-center gap-1 cursor-pointer mt-5 font-semibold">  
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                <path fill-rule="evenodd" d="M7.72 12.53a.75.75 0 0 1 0-1.06l7.5-7.5a.75.75 0 1 1 1.06 1.06L9.31 12l6.97 6.97a.75.75 0 1 1-1.06 1.06l-7.5-7.5Z" clip-rule="evenodd" />
            </svg>
            <span>Go Back</span>
        </a>

        <div class="mt-10">
            <div class="text-center">
                <div class="font-light">PUBLISHED SEPTEMBER 25, 2024</div>
                <h1 class="text-4xl font-semibold my-5">
                    “Lydiandary” the story of how a little girl’s idea became the world famous Lydia’s Lechon
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
                <div class="text-left">
                    <img src="{{ asset('images/featured-blog.png') }}" alt="Lydiandary the story of how a little girl’s idea became the world famous Lydia’s Lechon"  class="mb-5">
                    <p class="mb-4">Whoever said eating too much lechon will make you sick and weak, obviously hasn’t met Lydia de Roca—the woman behind the lechon empire carrying her name.</p>
                    <p class="mb-4">We met Lydia last week over lunch at their newest branch in Marcos Highway and it was honestly the first time I saw her. Of course I know Lydia’s Lechon but I never actually googled her or saw any of her photos. And because Lydia’s Lechon just turned 55, I was actually expecting a frail old woman with several assistants backing her up.</p>
                    <p class="mb-4">Instead we saw a sprightly lady who didn’t look her age. A proud 72 year old, Lydia says she still makes regular rounds at her numerous restaurants.</p>
                    <p class="mb-4"><img src="{{ asset('images/featured-blog1.png')}}" alt="blog1"></p>
                    <p class="mb-4">And when our host Spanky Enriquez called her to chop the lechon, I was thinking of just a ceremonial photo. So you could just probably imagine my surprise as she raised the knife and chopped the lechon all the way through in just one motion (complete with small bits of lechon flying toward us)—and she didn’t just do it once or twice—but three times to split the whole lechon and showcase their famous seafood paella filling.</p>
                    <p class="mb-4">This woman meant business and as she told me later—it was a business that began way back when she was just seven years old.</p>
                    <p class="mb-4"><img src="{{ asset('images/featured-blog2.png')}}" alt="blog1"></p>
                    <p class="mb-4"><strong>DEVOTION MEETS PASSION</strong><br>The Redemptorist street near the National Shrine of or more commonly known as Baclaran Church, is known for a lot of things a refuge for devotees of Our Mother of Perpetual Help, the endless tiangge stores that sell clothes, vendors’ stalls, jeepneys, traffic jams and the landmark restaurant that is Lydia’s Lechon.</p>
                    <p class="mb-4"><strong>A CRISPY, CRUNCHY BREAK</strong> <br>It was during that time that they got their first significant break happened as a well-known hotel requested for a delivery of ten whole lechons every day. This presented a huge challenge for Lydia as they never encountered an order this huge before. Using the Php500 they received from the godparents of their second child as business capital they purchased 10 live pigs and sold them at Php80 per roasting.</p>
                </div>
            </div>
        </div>
    </div>

    <div>
        <div class="border-y border-tertiary p-4">
            <div class="font-light">Previous News</div>

            <div class="flex mt-3 gap-2 items-start">
                <img src="{{ asset('images/featured-blog1.png')}}" alt="Blog1" class="h-[130px] w-[114px] object-cover rounded-lg">
                <div>
                    <div class="font-bold">LYDIA’S LECHON: A FILIPINO PASSION FOR FOOD</div>
                    <div class="font-light text-sm mt-3">July 07, 2020</div>
                </div>
            </div>
        </div>

        <div class="border-b border-tertiary p-4">
            <div class="font-light text-right">Next News</div>

            <div class="flex mt-3 gap-2 items-start">
                <img src="{{ asset('images/featured-blog2.png')}}" alt="Blog1" class="h-[130px] w-[114px] object-cover rounded-lg">
                <div>
                    <div class="font-bold">DIGITAL EXPANSION: FOODPANDA AND GRABFOOD</div>
                    <div class="font-light text-sm mt-3">July 07, 2020</div>
                </div>
            </div>
        </div>

        <div class="px-4 pt-10 flex justify-between">
            <h2 class="text-2xl text-primary">Related News</h2>
            <div class="underline text-lg">
                View All
            </div>
        </div>

        <div>
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

            <div class="px-4 pb-10">
                <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-6">
                    @foreach ($blogs as $blog)
                    <div class="rounded-lg">
                        <img src="{{ asset('images/' . $blog['image']) }}" alt="{{ $blog['title'] }}" class="w-full h-[188px] object-cover rounded-t-lg">
                        <div class="uppercase font-semibold py-2 px-1">{{ $blog['title'] }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>


    <x-newsletter-component />

    <x-footer-component />
    
@endsection

