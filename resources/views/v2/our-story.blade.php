@extends('layouts.guest')

@section('content')

    {{-- Hero Section --}}
    <div class="pt-20 pb-16 flex flex-col items-center text-center relative h-full max-h-[80%] overflow-hidden bg-cover bg-no-repeat" style="background-image: url('{{ asset('/images/lydia-store-img.png') }}');  background-position: -150px">
        <div class="relative w-full h-[550px]">
            <div class="bg-white p-5 rounded-md text-left w-[70%] absolute left-5 bottom-0 shadow-md">
                <h3 class="font-semibold uppercase">
                    Our story
                </h3>
                <h1 class="text-3xl font-cubao font-medium text-primary leading-7 mt-2">
                    Celebrating Tradition and Everyday Joy
                </h1>
            </div>
        </div>
    </div>

    <div class="flex flex-col gap-5 px-6 py-12">
        <p>
            Over 50 years ago, Lydia’s Lechon began as a small stall in Baclaran, serving what would become the most iconic roast lechon in Manila. From humble beginnings to over 20 stores today, our mission has stayed true: <strong>to bring joy, tradition, and the best-tasting lechon to every Filipino home.</strong>
        </p>
        <p>
            Our lechon is more than just food—it’s a symbol of celebration. Whether it’s a birthday, fiesta, or simple family dinner, Lydia’s Lechon has been the centerpiece of countless special moments for generations.
        </p>
    </div>

    <div class="px-4">
        <img src="{{ asset('images/our-story-lechon-delivery.png') }}" alt="Lechon delivery at your doorstep" class="w-full rounded-md h-[250px] object-cover">
        <h2 class="text-4xl font-cubao font-medium text-primary text-left my-4">our mission</h2>
        <p>
            We are the preferred lechon house and restaurant in Luzon bringing Everyday Happiness to our customers and stakeholders as we enhance the lives of our employees and communities.
        </p>
    </div>

    <div class="px-4">
        <img src="{{ asset('images/lechon-roasting.jfif') }}" alt="Lechon delivery at your doorstep" class="mt-10 w-full rounded-md h-[250px] object-cover">
        <h2 class="text-4xl font-cubao font-medium text-primary text-left my-4">our vision</h2>
        <p>
            To be the country’s leading envoy of Filipino festive and diverse cuisine adhering solidly to our core values of Integrity, Ethics, Customers First and Excellence, progressively evolving consistent with its objective of providing sustainable quality products through efficient productivity process.
        </p>
    </div>

    <div class="relative my-10" style="background-image: url('{{ asset('images/our-story-bg.png') }}'); background-size: cover; background-position: center;">
        <div class="absolute inset-0 bg-primary opacity-80"></div>
        
        <div class="relative flex flex-col px-4 py-8">
            <ul class="text-white font-bold">
                <li>#CelebrateSmallWins</li>
                <li>#LydiasLechon</li>
                <li>#EverydayLechonMoment</li>
            </ul>
        </div>
    </div>

    <div class="px-4">
        <div class="relative">
            
            <div class="relative container py-16 ">
                <div style="background-image: url('{{ asset('images/lydia-store-img.png') }}'); background-size: 214% 196%;ba;background-position: center center;background-repeat: no-repeat;" class="relative w-full h-[250px] rounded-lg shadow-lg clip-bottom">
                    <div class="absolute inset-0 bg-primary-dark opacity-50 "></div>
                    <img src="{{ asset('images/lydia-portrait.png') }}" alt="Lydia Our story" class="absolute -bottom-50 right-12 h-[320px] object-contain scale-125">
                </div>
                <div class="flex flex-col items-start text-left text-white bg-tertiary p-5">
                    <p class="font-medium">"I learned early on that life wouldn’t hand me anything. Growing up in a broken home and starting as a sidewalk vendor taught me that I had to fight for my dreams. I remember selling lechon at a young age, making every peso count. I decided to take control of my life, saying yes to opportunities, even when they seemed impossible. It’s that spirit of resilience and determination that has brought me here today."</p>
                    <div class="font-bold text-lg mt-5">Lydia De Roca</div>
                    <div class="font-medium">Founder of Lydia’s Lechon</div>
                </div>
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

