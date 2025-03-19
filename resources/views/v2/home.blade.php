@extends('layouts.guest')

@section('content')
    {{-- Marketing popup --}}
    <div >
        <!-- Drawer Overlay -->
        <div x-show="marketingPopup" x-transition.opacity class="fixed inset-0 bg-black/50 z-40" @click="marketingPopup = false"></div>

        <!-- Cart Drawer Content -->
        <div x-show="marketingPopup" x-transition:enter="transition-transform transform duration-300"
            x-transition:enter-start="-translate-y-full" x-transition:enter-end="translate-y-0"
            x-transition:leave="transition-transform transform duration-300"
            x-transition:leave-start="translate-y-0" x-transition:leave-end="-translate-y-full"
            class="fixed top-[2%] right-[5%] w-[90%] max-w-[500px] pb-10 h-max bg-white text-black z-50 flex flex-col shadow-lg rounded-lg overflow-x-scroll scrollbar-hide scroll-smooth snap-y">

            <!-- Content -->
            <div class="w-full relative">
                <div style="background-image: url('{{ asset('images/marketing-popup-hero.jfif') }}'); background-size: cover;" class="relative w-full h-[300px] rounded-lg overflow-hidden bg-bottom">

                </div>

                <!-- Close Button -->
                <button @click="marketingPopup = false" class="absolute top-4 right-4 text-2xl text-gray-800">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class=" px-2 py-2 flex items-center justify-center bg-white rounded-full w-10 h-10 shadow-lg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>

                <div class="font-cubao text-3xl text-left text-primary px-6 mt-5">
                    Sign Up and Save 10% + Free Shipping on Your First Order!
                </div>

                <div class="font-medium px-6 text-left mt-5">Join us today and enjoy an exclusive 10% discount on your first purchase, plus free shipping! Sign up now to unlock these great perks and start shopping with savings.</div>

                <div class="w-full px-6 mt-5">
                    <button class="bg-primary text-white rounded-md  w-full px-6 py-4 mt-3 font-medium uppercase">Sign Up Now</button>
                    <button class="bg-white border border-primary w-full text-primary rounded-md px-6 py-4 mt-3 font-medium uppercase">No, Thanks</button>
                </div>
            </div>
        </div>
    </div>
    {{-- Hero Section --}}
    <div class="pt-20 pb-16 flex flex-col items-center text-center relative h-[calc(100vh-15%)] overflow-hidden" style="background-image: url('{{ asset('/images/hero-bg.png') }}'); background-size: cover; background-position: center;">
        <div class="relative w-full h-full">
            <div class="inset-0 flex justify-center items-center text-green-800 font-bold px-10 font-cubao z-20 pt-20">
                <h1 class="text-[55px] leading-[55px]">EVERYDAY<br>LECHON HAPPINESS</h1>
            </div>
            <img @click="marketingPopup = true" src="{{ asset('/images/lechon-chopped.png') }}" alt="Lechon" class="w-full h-[400px] object-cover absolute bottom-0 left-0 z-10 scale-150">
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
    {{-- Product Section --}}
    <div class="px-4">
        <div class="flex flex-col items-center text-center py-10 gap-6">
            <h2 class="text-4xl font-cubao font-medium text-primary">You've Never Had Lechon Like Lydia's</h2>
            <p>For 60 years, Lydia’s Lechon has perfected the art of roasting—whether it’s a grand feast or a simple craving, we’ve got the best lechon for every occasion.</p>
            <button class="text-white bg-primary mx-auto rounded-md px-6 py-3 mt-3 font-medium">View Menu</button>
        </div>
    </div>
    <div class="products">
        <div class="product pb-20 px-4">
            <div class="relative bg-orange-500 rounded-md w-full h-[210px] clip-bottom p-10 flex justify-center items-end">
                <img src="{{ asset('images/product1.png') }}" alt="Shop Whole Lechon" class="object-cover scale-125">
            </div>
            <div class="flex justify-between mt-4">
                <div class="font-bold">Shop Whole Lechon</div>
                <button class="bg-primary text-white rounded-md px-4 py-2 flex items-center justify-center">Shop Now</button>
            </div>
        </div>
        <div class="product pb-20 px-4">
            <div class="relative bg-orange-500 rounded-md w-full h-[210px] clip-bottom p-10 flex justify-center items-end">
                <img src="{{ asset('images/product2.png') }}" alt="Shop Whole Lechon" class="scale-125 absolute top-0 left-0 px-10">
            </div>
            <div class="flex justify-between mt-4">
                <div class="font-bold">Shop Lechon-In-A-Box</div>
                <button class="bg-primary text-white rounded-md px-4 py-2 flex items-center justify-center">Shop Now</button>
            </div>
        </div>
        <div class="product pb-20 px-4">
            <div class="relative bg-orange-500 rounded-md w-full h-[210px] clip-bottom p-10 flex justify-center items-end">
                <img src="{{ asset('images/product3.png') }}" alt="Shop Whole Lechon" class="scale-125 absolute top-0 left-0 px-10">
            </div>
            <div class="flex justify-between mt-4">
                <div class="font-bold">Shop Lydia’s Family Box</div>
                <button class="bg-primary text-white rounded-md px-4 py-2 flex items-center justify-center">Shop Now</button>
            </div>
        </div>
        <div class="product pb-20 px-4">
            <div class="relative bg-orange-500 rounded-md w-full h-[210px] clip-bottom p-10 flex justify-center items-end">
                <img src="{{ asset('images/product4.png') }}" alt="Shop Whole Lechon" class="scale-125 absolute top-0 left-0 px-10">
            </div>
            <div class="flex justify-between mt-4">
                <div class="font-bold">Shop Party Trays</div>
                <button class="bg-primary text-white rounded-md px-4 py-2 flex items-center justify-center">Shop Now</button>
            </div>
        </div>
        <div class="product pb-20 px-4">
            <div class="relative bg-orange-500 rounded-md w-full h-[210px] clip-bottom p-10 flex justify-center items-end">
                <img src="{{ asset('images/product5.png') }}" alt="Shop Whole Lechon" class="scale-125 absolute top-0 left-0 px-10">
            </div>
            <div class="flex justify-between mt-4">
                <div class="font-bold">Shop Lechon Espesyal</div>
                <button class="bg-primary text-white rounded-md px-4 py-2 flex items-center justify-center">Shop Now</button>
            </div>
        </div>
        <div class="product pb-20 px-4">
            <div class="relative bg-orange-500 rounded-md w-full h-[210px] clip-bottom p-10 flex justify-center items-end">
                <img src="{{ asset('images/product6.png') }}" alt="Shop Whole Lechon" class="scale-125 absolute top-0 left-0 px-10">
            </div>
            <div class="flex justify-between mt-4">
                <div class="font-bold">Shop Bento Box</div>
                <button class="bg-primary text-white rounded-md px-4 py-2 flex items-center justify-center">Shop Now</button>
            </div>
        </div>
        <div class="product pb-20 px-4">
            <div class="relative bg-orange-500 rounded-md w-full h-[210px] clip-bottom p-10 flex justify-center items-end">
                <img src="{{ asset('images/product7.png') }}" alt="Shop Whole Lechon" class="scale-125 absolute top-0 left-0 px-10">
            </div>
            <div class="flex justify-between mt-4">
                <div class="font-bold">Shop Pampagana</div>
                <button class="bg-primary text-white rounded-md px-4 py-2 flex items-center justify-center">Shop Now</button>
            </div>
        </div>
    </div>

    {{-- Our story --}}
    <div class="relative" style="background-image: url('{{ asset('images/our-story-bg.png') }}'); background-size: cover; background-position: center;">
        <!-- Green Overlay -->
        <div class="absolute inset-0 bg-primary-dark opacity-80"></div>
        
        <div class="relative container py-16 px-4">
            <div style="background-image: url('{{ asset('images/lydia-store-img.png') }}'); background-size: cover; background-position: center;" class="relative w-full h-[300px] rounded-lg overflow-hidden shadow-lg">
                <div class="absolute inset-0 bg-primary-dark opacity-50"></div>
                <img src="{{ asset('images/lydia-portrait.png') }}" alt="Lydia Our story" class="absolute -bottom-20 -right-8 w-[320px] h-[320px] object-contain scale-125">
            </div>
            <div class="flex flex-col items-start text-left text-white gap-5">
                <h3 class="font-medium">Our Story</h3>
                <h2 class="text-4xl font-cubao font-medium">60 Years of Bringing Filipino Flavors to Life!</h2>
                <p class="font-light">Lydia De Roca and her husband Benigno started Lydia’s Lechon in Baclaran in the 1960s with a dream to serve delicious, flavorful lechon to every Filipino home. Their iconic boneless lechon stuffed with seafood paella became an instant favorite, creating a brand that now has over 25 stores. Through their dedication, Lydia and Benigno created more than just a dish—they brought joy and tradition to every Filipino table, one delicious bite at a time. Today, Lydia’s Lechon continues to honor their legacy, bringing the joy of Filipino cooking to every meal, whether for grand celebrations or simple everyday feasts. With each bite, we celebrate 60 years of passion, tradition, and happiness.</p>
                <button class="text-white bg-secondary rounded-md px-6 py-3 mt-3 font-medium">Read More</button>
            </div>
        </div>
    </div>

    {{-- Find Lydia's Lechon at your nearest store --}}
    <div class="bg-tertiary rounded-lg mx-4 mt-16">
        <img src="{{ asset('images/lydia-store-img2.png') }}" alt="Find Lydia's Lechon at your nearest store" class="rounded-t-lg">
        <div class="flex flex-col items-start py-6 px-4 text-white gap-4">
            <h2 class="text-4xl font-cubao font-medium  text-left">Find a Lydia's Lechon Near You</h2>
            <p class="text-left">With over 25 stores across Manila, Lydia’s Lechon is always close by to bring the taste of tradition to your table. Visit us today and experience the joy of Filipino flavors!</p>
            <button class="text-white bg-primary rounded-md px-6 py-3 mt-3 font-medium">Our Stores</button>
        </div>
    </div>

    {{-- Digital partner --}}
    <div class="flex flex-col gap-5 px-4">
        <h2 class="text-4xl font-cubao font-medium text-center mt-16 text-primary">Our digital partners</h2>
        <div class="font-medium text-center">
            Foodpanda and GrabFood orders are accepted from 10AM to 7PM only.
        </div>
        <div class="flex flex-wrap gap-5 justify-center items-center">
            <img src="{{ asset('images/foodpanda.png') }}" alt="Foodpanda" class="w-auto h-7">
            <img src="{{ asset('images/grab.png') }}" alt="grab" class="w-auto h-7">
            <img src="{{ asset('images/maya.png') }}" alt="maya" class="w-auto h-7">
            <img src="{{ asset('images/gcash.png') }}" alt="gcash" class="w-[100px]">
        </div>
    </div>

    {{-- FAQ --}}
    <div class="bg-primary px-4 py-10 text-white">
        <h2 class="text-4xl font-cubao font-medium text-center">Frequently Asked Questions</h2>
        <div class="font-medium text-center mt-4">Everything you need to know about our delivery areas, order times, and policies. We make sure your Lydia's Lechon arrives fresh and on time!</div>
        <div class="faqs rounded-lg border-dashed border-[#46B57C] border-2 mt-10">
            <div class="faq border-b-2 border-[#46B57C] pb-4 border-dashed p-4">
                <div class="question font-bold">
                    Where do you deliver?
                </div>
                <div class="answer text-sm mt-2">
                    We deliver within Metro Manila. For deliveries outside Metro Manila, please call our hotline at 8939-1221 or 8851-2987.
                </div>
            </div>
            <div class="faq border-b-2 border-[#46B57C] pb-4 border-dashed p-4">
                <div class="question font-bold">
                    How early should I place an order for a whole lechon?
                </div>
                <div class="answer text-sm mt-2">
                    Whole Lechon orders must be placed at least 1 day or 24 hours before the delivery date.
                </div>
            </div>
            <div class="faq border-b-2 border-[#46B57C] pb-4 border-dashed p-4">
                <div class="question font-bold">
                    How do I ensure my order is confirmed?
                </div>
                <div class="answer text-sm mt-2">
                    Your order will be confirmed once payment is validated. For any concerns, please reach out to our hotline for assistance.
                </div>
            </div>
            <div class="faq border-b-2 border-[#46B57C] pb-4 border-dashed p-4">
                <div class="question font-bold">
                    What is the recommended payment method?
                </div>
                <div class="answer text-sm mt-2">
                    We recommend using online payment methods for quick and easy validation.
                </div>
            </div>
            <div class="faq border-b-2 border-[#46B57C] pb-4 border-dashed p-4">
                <div class="question font-bold">
                    Can I cancel or change my order?
                </div>
                <div class="answer text-sm mt-2">
                    Yes, changes or cancellations can be made up to 30 hours before the delivery date. Please call our hotline at 8939-1221. Cancellation is subject to review by Lydia’s Lechon Management.
                </div>
            </div>
            <div class="faq pb-4 border-dashed p-4">
                <div class="question font-bold">
                    How early should I place an international order?
                </div>
                <div class="answer text-sm mt-2">
                    For international orders, please place your order at least 3 days or 72 hours prior to the delivery date.
                </div>
            </div>
        </div>
    </div>

    {{-- Blog --}}
    <div class="flex flex-col gap-3 px-4">
        <h2 class="text-4xl font-cubao font-medium text-center mt-16 text-primary">BITE-SIZED MOMENTS: LYDIa's blog</h2>
        <div class="font-medium text-center mt-4">Discover tasty tips, exciting updates, and everything you need to know about celebrating with Lydia's Lechon.</div>
        <a href="#" class="text-white bg-primary rounded-md px-6 py-3 mt-4 font-medium mx-auto">View All Blogs</a> 
    </div>

    <div class="relative">
        <div class="swiper swiper-blogs relative">
            <!-- Additional required wrapper -->
            <div class="swiper-wrapper">
              <!-- Slides -->
                <div class="swiper-slide flex flex-col items-center gap-2 p-4">
                    <img src="{{ asset('images/blog-img1.png') }}" alt="Anniversary Give-Back Promo" class="w-[300px]">
                    <div class="text-gray-600 text-sm mt-2">Nov 04, 2021</div>
                    <div class="font-semibold">Anniversary Give-Back Promo</div>
                </div>
                <div class="swiper-slide flex flex-col items-center gap-2 p-4">
                    <img src="{{ asset('images/blog-img2.png') }}" alt="Anniversary Give-Back Promo" class="w-[300px]">
                    <div class="text-gray-600 text-sm mt-2">Nov 04, 2021</div>
                    <div class="font-semibold">Food Trip This Pandemic</div>
                </div>
            </div>
        </div>
        <button class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-white text-black p-3 rounded-full z-10 swiper-button-prev-custom">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </svg>
        </button>
        <button class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-white text-black p-3 rounded-full z-10 swiper-button-next-custom">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
            </svg>
        </button>
    </div>

    {{-- As seen in --}}
    <div>
        <h2 class="font-bold text-center mt-16 text-primary uppercase">As Seen In</h2>
        <div class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4 px-4 justify-center items-center">
            <img src="{{ asset('images/philstar.png') }}" alt="philstar">
            <img src="{{ asset('images/esquire.png') }}" alt="esquire">
            <img src="{{ asset('images/spot.png') }}" alt="spot">
            <img src="{{ asset('images/rappler.png') }}" alt="rappler">
            <img src="{{ asset('images/sunstar.png') }}" alt="sunstar">
            <img src="{{ asset('images/tatler.png') }}" alt="tatler">
        </div>
    </div>

    {{-- Newsletter --}}
    <div class="bg-tertiary py-8 px-6 text-white">
        <h2 class="text-4xl font-cubao font-medium text-left">Join our Newsletter</h2>
        <div class="mt-4 text-left">Join our subscriber's list to get the latest updates and articles delivered straight to your inbox.</div>
        <div class="mt-4">
            <label for="hs-trailing-button-add-on-with-icon-and-button" class="sr-only">Label</label>
            <div class="relative flex rounded-lg">
                <input placeholder="Enter your email" type="text" id="hs-trailing-button-add-on-with-icon-and-button" name="hs-trailing-button-add-on-with-icon-and-button" class="py-2.5 sm:py-3 px-4 ps-11 block w-full border-gray-200 rounded-s-lg sm:text-sm focus:z-10 focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600">
                <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-4 text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="shrink-0  text-primary dark:text-neutral-500 size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                    </svg>
                    <span class="ps-4"></span>
                </div>
                <button type="button" class="py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-e-md border border-transparent bg-primary text-white hover:bg-primary-dark focus:outline-hidden focus:bg-primary-dark disabled:opacity-50 disabled:pointer-events-none">Search</button>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <footer class="relative bg-primary-dark text-white pt-10 pb-16 px-4" style="background-image: url('{{ asset('images/our-story-bg.png') }}'); background-size: cover; background-position: center;">
        <!-- Green Overlay -->
        <div class="absolute inset-0 bg-primary-dark opacity-80"></div>
        <div class="relative grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <div>
                <img src="{{ asset('images/lydia-logo.png') }}" alt="Lydia's Logo" class="">
            </div>
            <div class="mt-6">
                <h3 class="font-bold text-lg md:text-xl uppercase">About Us</h3>
                <ul class="mt-2 flex flex-col gap-2">
                    <li>Our Story</li>
                    <li>Our Stores</li>
                    <li>Blog</li>
                </ul>
            </div>
            <div>
                <h3 class="font-bold text-lg md:text-xl uppercase mt-3">SHOP</h3>
                <ul class="mt-2 flex flex-col gap-2">
                    <li>Menu</li>
                    <li>Lechon Pricelist</li>
                </ul>
            </div>
            <div>
                <h3 class="font-bold text-lg md:text-xl uppercase mt-3">Contact</h3>
                <ul class="mt-2 flex flex-col gap-2">
                    <li>Contact Us</li>
                    <li>Hotline</li>
                    <li>Careers</li>
                </ul>
                <div class="mt-3">Or get in touch with us via email:</div>
                <div><a href="mailto:orders@lydias-lechon.com" class="font-semibold">orders@lydias-lechon.com</a></div>
            </div>
            <div>
                <h3 class="font-bold text-lg md:text-xl uppercase mt-3">Follow Us</h3>
                <div class="mt-2 flex">
                    <a href="#" class="text-gray-400 hover:text-gray-500">
                      <span class="sr-only">Facebook</span>
                      <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" />
                      </svg>
                    </a>
                    <a href="#" class="ml-3 text-gray-400 hover:text-gray-500">
                      <span class="sr-only">Instagram</span>
                      <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd" />
                      </svg>
                    </a>
                    <a href="#" class="ml-3 text-gray-400 hover:text-gray-500">
                      <span class="sr-only">Twitter</span>
                      <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" />
                      </svg>
                    </a>
                </div>
            </div>
            <hr class="w-full border-[#46B57C] mt-5">
            <div class="text-xs text-center w-full mt-5 text-white">
                All information, pictures and images on this site are copyrighted material and owned by their respective creators or owners.
            </div>
            <div class="text-center text-sm w-full mt-4">
                Copyright © 2020 - {{ now()->year }} | Lydia’s Lechon
            </div>
        </footer>
    </footer>
     <!-- Mobile menu -->
     <div class="md:hidden flex justify-center mt-3 w-full">
        <div id="mobile-menu" class="mobile-menu absolute top-23 w-full"> <!-- add hidden here later -->
          <ul class="bg-gray-100 shadow-lg leading-9 font-bold h-screen">
            <li class="border-b-2 border-white hover:bg-red-400 hover:text-white pl-4"><a href="https://google.com" class="block pl-7">Home</a></li>
            <li class="border-b-2 border-white hover:bg-red-400 hover:text-white pl-4"><a href="#" class="block pl-7">News</a></li>
            <li class="border-b-2 border-white hover:bg-red-400 hover:text-white">
              <a href="#" class="block pl-11">Services <i class="fa-solid fa-chevron-down fa-2xs pt-4"></i></a> 
              
              <!-- Submenu starts -->
              <ul class="bg-white text-gray-800 w-full">
                <li class="text-sm leading-8 font-normal hover:bg-slate-200"><a class="block pl-16" href="#">Webdesign</a></li>
                <li class="text-sm leading-8 font-normal hover:bg-slate-200"><a class="block pl-16" href="#">Digital marketing</a></li>
                <li class="text-sm leading-8 font-normal hover:bg-slate-200"><a class="block pl-16" href="#">SEO</a></li>
                <li class="text-sm leading-8 font-normal hover:bg-slate-200"><a class="block pl-16" href="#">Ad campaigns</a></li>
                <li class="text-sm leading-8 font-normal hover:bg-slate-200"><a class="block pl-16" href="#">UX Design</a></li>
              </ul>
              <!-- Submenu ends -->
            </li>
            <li class="border-b-2 border-white hover:bg-red-400 hover:text-white pl-4"><a href="#" class="block pl-7">About</a></li>
            <li class="border-b-2 border-white hover:bg-red-400 hover:text-white pl-4"><a href="#" class="block pl-7">Contact</a></li>
          </ul>
          </div>
      </div>
@endsection

