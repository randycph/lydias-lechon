@extends('layouts.guest')

@section('content')

    {{-- Hero Section --}}
    <div class="pt-20 pb-16 flex flex-col items-center text-center relative lg:bg-center bg-[-150px] w-full h-full max-h-[80%] overflow-hidden bg-cover bg-no-repeat" style="background-image: url('{{ asset('/images/lydia-store-img.png') }}');  ">
        <div class="relative w-full h-[550px] container">
            <div class="bg-white p-5 rounded-md text-left w-[70%] lg:w-max absolute left-5 bottom-0 shadow-md ">
                <h3 class="font-semibold uppercase text-base lg:text-2xl">
                    Our story
                </h3>
                <h1 class="text-3xl lg:text-5xl max-w-sm font-cubao font-medium text-primary leading-7 mt-2">
                    Celebrating Tradition and Everyday Joy
                </h1>
            </div>
        </div>
    </div>

    <div class="flex flex-col gap-5 px-8 lg:px-10 py-12 lg:py-20 max-w-4xl mx-auto">
        <p>
            Over 50 years ago, Lydia’s Lechon began as a small stall in Baclaran, serving what would become the most iconic roast lechon in Manila. From humble beginnings to over 20 stores today, our mission has stayed true: <strong>to bring joy, tradition, and the best-tasting lechon to every Filipino home.</strong>
        </p>
        <p>
            Our lechon is more than just food—it’s a symbol of celebration. Whether it’s a birthday, fiesta, or simple family dinner, Lydia’s Lechon has been the centerpiece of countless special moments for generations.
        </p>
    </div>

    <div class="px-4 flex flex-col gap-2 lg:flex-row lg:gap-20 container items-center">
        <img src="{{ asset('images/our-story-lechon-delivery.png') }}" alt="Lechon delivery at your doorstep" class="max-w-[650px] max-h-[500px] order-1 lg:order-2 lg:h-auto w-full rounded-md h-[250px] object-cover">
        <div class="order-2 lg:order-1">
            <h2 class="text-4xl lg:text-7xl font-cubao font-medium text-primary text-left my-4">our mission</h2>
            <p>
                We are the preferred lechon house and restaurant in Luzon bringing Everyday Happiness to our customers and stakeholders as we enhance the lives of our employees and communities.
            </p>
        </div>
    </div>

    <div class="px-4 flex flex-col gap-2 lg:flex-row lg:gap-20 container items-center mt-10 lg:mt-20">
        <img src="{{ asset('images/lechon-roasting.jfif') }}" alt="Lechon delivery at your doorstep" class="max-w-[650px] max-h-[500px] order-1 lg:h-auto mt-10 w-full rounded-md h-[250px] object-cover">
        <div class="order-2">
            <h2 class="text-4xl lg:text-7xl font-cubao font-medium text-primary text-left my-4">our vision</h2>
            <p>
                To be the country’s leading envoy of Filipino festive and diverse cuisine adhering solidly to our core values of Integrity, Ethics, Customers First and Excellence, progressively evolving consistent with its objective of providing sustainable quality products through efficient productivity process.
            </p>
        </div>
    </div>

    <div class="relative my-10 py-5 lg:py-16" style="background-image: url('{{ asset('images/our-story-bg.png') }}'); background-size: cover; background-position: center;">
        <div class="absolute inset-0 bg-primary opacity-80"></div>
        
        <div class="relative flex flex-col px-4 py-8 container">
            <ul class="text-white font-bold flex flex-col gap-2 lg:flex-row lg:gap-10 text-base lg:text-4xl items-start lg:items-center justify-start lg:justify-center">
                <li><a href="#" class="hover:text-tertiary hover:underline">#CelebrateSmallWins</a></li>
                <li><a href="#" class="hover:text-tertiary hover:underline">#LydiasLechon</a></li>
                <li><a href="#" class="hover:text-tertiary hover:underline">#EverydayLechonMoment</a></li>
            </ul>
        </div>
    </div>


    <div class="container">
        <div class="mx-4 mt-16 flex flex-col lg:flex-row justify-center items-center">
            <img src="{{ asset('images/our-story-img2.png') }}" alt="Find Lydia's Lechon at your nearest store" 
                class="rounded-t-lg lg:rounded-lg order-1 lg:order-2 lg:w-1/2 w-full relative lg:top-[-25px] top-4 z-10">
            <div class="flex flex-col lg:h-max items-start py-6 px-4 text-white order-2 lg:order-1 lg:w-1/2 bg-tertiary z-20 lg:py-12 lg:px-12 lg:rounded-bl-lg lg:rounded-tl-lg">
                <p class="font-medium text-base lg:text-2xl">"I learned early on that life wouldn’t hand me anything. Growing up in a broken home and starting as a sidewalk vendor taught me that I had to fight for my dreams. I remember selling lechon at a young age, making every peso count. I decided to take control of my life, saying yes to opportunities, even when they seemed impossible. It’s that spirit of resilience and determination that has brought me here today."</p>
                <div class="font-bold text-lg lg:text-3xl mt-5">Lydia De Roca</div>
                <div class="font-medium text-base lg:text-xl">Founder of Lydia’s Lechon</div>
            </div>
        </div>
    </div>

    <x-timeline-component />

    <div class="px-4 py-10 flex flex-col gap-10">
        <div>
            <img src="{{ asset('images/story1.png') }}" alt="Lechon delivery at your doorstep" class="w-full rounded-md h-[250px] object-cover">
            <h2 class="text-3xl font-cubao font-light text-primary text-left my-2">More than just lechon</h2>
            <p class="font-medium">
                While our lechon is often the star of big celebrations, we believe <strong>great food can make any day special</strong>. Lydia’s Lechon is perfect for any meal, whether you’re gathering with friends or enjoying a quiet family dinner. Over the years, we’ve expanded our menu with traditional Filipino dishes that pair perfectly with our famous lechon, offering something for every occasion.
            </p>
        </div>
        <div>
            <img src="{{ asset('images/story2.png') }}" alt="Lechon delivery at your doorstep" class="w-full rounded-md h-[250px] object-cover">
            <h2 class="text-3xl font-cubao font-light text-primary text-left my-2">Rooted in Family and Togetherness</h2>
            <p class="font-medium">
                At Lydia’s Lechon, our values are simple: <strong>food brings people together</strong>. We’re proud to have been part of countless family traditions, celebrations, and milestones over the decades. Every meal with Lydia’s is an opportunity to connect, share, and create memories.
            </p>
        </div>
        <div>
            <img src="{{ asset('images/story3.png') }}" alt="Lechon delivery at your doorstep" class="w-full rounded-md h-[250px] object-cover">
            <div class="text-lg font-bold text-secondary text-left my-2">
                #SmallWinsMatter
            </div>
            <h2 class="text-3xl font-cubao font-light text-primary text-left my-2">Celebrating Everyday Moments</h2>
            <p class="font-medium">
                At Lydia’s Lechon, we believe that life’s <strong>small victories deserve to be celebrated</strong>. Whether it’s gathering with loved ones for a simple meal or surprising someone with a thoughtful gesture, these everyday moments bring joy and connection. Lydia’s Lechon is proud to be part of these occasions, making even the smallest wins feel special.
            </p>
        </div>
    </div>

    <x-footer-component />
    
@endsection

