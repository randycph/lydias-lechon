<div class="px-6 my-5 bg-cream py-16 mt-10 lg:mt-20">
    <div class="container">
        <div class="my-10">
            <h3 class="uppercase text-lg lg:text-4xl font-bold text-center">Our Journey</h3>
            <h2 class="text-4xl lg:text-8xl font-cubao font-medium text-primary text-center my-4">From Humble Roots to
                Filipino Heart</h2>
            <div class="font-medium text-center text-base lg:text-2xl">
                From a small stall beside the Our Lady of Sorrows Church, Lydia’s Lechon has grown into a beloved icon
                across Metro Manila. Founded by Ms. Lydia and Benigno de Roca, our story is rooted in dedication and
                faith, making every bite a cherished Filipino tradition.
            </div>
        </div>

        @php

        $timeline = [
            [
                'year' => '1965',
                'title' => 'A Humble Beginning',
                'description' => 'With just P500, Aling Lydia opened her very first stall outside Our Lady of Sorrows Church on F.B. Harrison St., Pasay City, marking the birth of Lydia’s Lechon.',
                'image' => 'timeline1.jfif'
            ],
            [
                'year' => '1986',
                'title' => 'Expanding Horizons',
                'description' => 'Lydia’s Lechon opened its first full-service restaurant along Roxas Blvd. in Baclaran, Paranaque City, elevating the lechon experience for its loyal customers.',
                'image' => 'timeline2.jfif'
            ],
            [
                'year' => '1989',
                'title' => 'going beyond borders',
                'description' => 'The first branch of Lydia’s Lechon in Quezon City opened its doors, spreading the love for lechon to a wider audience and solidifying its place in Filipino cuisine.',
                'image' => 'timeline3.jfif'
            ],
            [
                'year' => '1990',
                'title' => 'A New Taste in the City',
                'description' => 'Lydia’s ventured into the food court scene with its inaugural outlet in SM Sta. Mesa, bringing delicious lechon to mall-goers.',
                'image' => 'timeline4.jfif'
            ],
            [
                'year' => '2012',
                'title' => 'A New Taste in the City',
                'description' => 'Lydia’s Lechon celebrated the opening of its 28th branch, a testament to its growing popularity and the love of Filipinos for its iconic dish.',
                'image' => 'timeline5.jfif'
            ],
            [
                'year' => '2014',
                'title' => 'Elevating Flavor',
                'description' => 'The introduction of its own line of condiments allowed Lydia’s to enhance the dining experience, offering customers the perfect accompaniments to their lechon.',
                'image' => 'timeline6.png'
            ],
            [
                'year' => '2015',
                'title' => 'Golden Anniversary',
                'description' => 'Lydia’s proudly celebrated 50 years of serving lechon goodness, honoring the legacy of flavor and tradition built over five decades.',
                'image' => 'timeline7.png'
            ],
            [   
                'year' => '2017',
                'title' => 'EMBRACING TECHNOLOGY',
                'description' => 'The launch of the Online Lechon Delivery website and app marked a new era for Lydia’s, making it easier than ever for customers to enjoy their favorite lechon from the comfort of their homes.',
                'image' => 'timeline8.png'
            ],
            [
                'year' => '2020',
                'title' => 'STRONG AT 55',
                'description' => 'Lydia’s celebrated its 55th anniversary, reflecting on a legacy of resilience and commitment to quality in every dish.',
                'image' => 'timeline9.png'
            ],
            [
                'year' => '2020-2021',
                'title' => 'Overcoming adversity',
                'description' => 'In response to the pandemic, Lydia’s Lechon strengthened safety measures, embraced online orders, and introduced food trucks and kiosks. Our community support efforts kept us moving forward during these challenging times.',
                'image' => 'timeline10.png'
            ],
        ];

        @endphp

        <ol class="relative border-s-2 border-black border-dashed lg:hidden block">
            @foreach ($timeline as $index => $event)
                <li class="mb-10 ms-8">
                    <div class="relative h-[212px] w-full flex items-center justify-center my-5"
                        style="background-image: url('{{ asset('images/our-story-bg.png') }}'); background-size: cover; background-position: center;">

                        <div class="absolute inset-0 bg-primary-dark opacity-80"></div>

                        <img src="{{ asset('images/' . $event['image']) }}" alt="A Humble Beginning"
                            class="relative h-[212px] w-full object-cover">
                    </div>
                    <div class="absolute w-8 h-8 bg-secondary rounded-full -start-4 border border-black"></div>
                    <time class="mb-1 font-medium font-cubao leading-none text-3xl text-tertiary">{{ $event['year'] }}</time>
                    <h3 class="text-lg font-semibold uppercase">{{ $event['title'] }}</h3>
                    <p class="mb-4 mt-1 text-base font-medium ">{{ $event['description'] }}</p>
                </li>
            @endforeach
        </ol>

        <div class="relative hidden lg:block mt-20">
            <!-- Vertical center timeline line -->
            <div class="absolute left-1/2 top-0 bottom-0 w-px -translate-x-1/2 border-l-2 border-dashed border-black z-0"></div>
        
            <ol class="lg:grid lg:grid-cols-9 lg:gap-4">
                @foreach ($timeline as $index => $event)
                    @php
                        $isEven = $index % 2 === 0;
                    @endphp
        
                    <li class="lg:col-span-9 lg:grid lg:grid-cols-9 lg:items-center lg:mb-20 relative">
                        {{-- Left side (Text or Image) --}}
                        @if ($isEven)
                            {{-- Text Left --}}
                            <div class="lg:col-span-4 text-end px-4 hidden lg:block">
                                <div class="text-6xl font-cubao text-tertiary">{{ $event['year'] }}</div>
                                <h3 class="text-3xl font-semibold uppercase mt-3">{{ $event['title'] }}</h3>
                                <p class="text-xl font-semibold mt-3">{{ $event['description'] }}</p>
                            </div>
                        @else
                            {{-- Image Left --}}
                            <div class="lg:col-span-4 px-4">
                                <div class="relative h-[500px] w-full bg-cover bg-center rounded-lg overflow-hidden"
                                     style="background-image: url('{{ asset('images/' . $event['image']) }}')">
                                    <div class="absolute inset-0"></div>
                                </div>
                            </div>
                        @endif
        
                        {{-- Circle in center --}}
                        <div class="col-span-1 flex justify-center z-10 relative">
                            <div class="w-10 h-10 bg-secondary rounded-full border border-black"></div>
                        </div>
        
                        {{-- Right side (Text or Image) --}}
                        @if ($isEven)
                            {{-- Image Right --}}
                            <div class="lg:col-span-4 px-4">
                                <div class="relative h-[500px] w-full bg-cover bg-center rounded-lg overflow-hidden"
                                     style="background-image: url('{{ asset('images/' . $event['image']) }}')">
                                    <div class="absolute inset-0"></div>
                                </div>
                            </div>
                        @else
                            {{-- Text Right --}}
                            <div class="lg:col-span-4 px-4 hidden lg:block">
                                <div class="text-6xl font-cubao text-tertiary">{{ $event['year'] }}</div>
                                <h3 class="text-3xl font-semibold uppercase mt-3">{{ $event['title'] }}</h3>
                                <p class="text-xl font-semibold mt-3">{{ $event['description'] }}</p>
                            </div>
                        @endif
                    </li>
                @endforeach
            </ol>
        </div>
    </div>
</div>