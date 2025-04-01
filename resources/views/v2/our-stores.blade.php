@extends('layouts.guest')

@section('content')

<div 
x-data="{
    mapModal: false,
    mapAddress: '',
    embedMap: '',
    openMap(mapAddress, embedMap) {
        this.mapAddress = mapAddress;
        this.embedMap = embedMap;
        this.mapModal = true;
    }
}">

<div 
    class="bg-cream">
    <div class="py-20 px-4 container ">
        <h1 class="text-4xl lg:text-7xl font-cubao font-medium text-primary text-center my-10 max-w-3xl mx-auto">lechon
            goodness in every neighborhood</h1>
        <p class="text-center text-base lg:text-2xl max-w-3xl mx-auto">With over <strong>20 stores</strong> across Metro
            Manila, Lydia’s Lechon brings our famous roast lechon closer to you. Find your nearest branch and enjoy a
            taste of Filipino tradition, making every meal a celebration.</p>
    </div>

    <div class="relative mx-auto bg-cream">
        <div class="swiper-stores overflow-hidden px-4">
            <div class="swiper-wrapper">
                @foreach (['store1.png', 'store2.jfif', 'store3.png', 'store2.jfif','store1.png'] as $img)
                <div class="swiper-slide w-[75%] md:w-[70%] lg:!w-[85%] lg:!mx-16 !mx-5">
                    <img src="{{ asset('images/' . $img) }}" alt="Lydia's Lechon Store"
                        class="w-full h-[300px] max-h-[650px] lg:h-auto object-cover rounded-md">
                </div>
                @endforeach
            </div>
        </div>

        <div>
            <button
                class="absolute left-10 top-1/2 transform -translate-y-1/2 bg-white text-black p-3 rounded-full z-10 swiper-button-prev-custom">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                </svg>
            </button>
            <button
                class="absolute right-10 top-1/2 transform -translate-y-1/2 bg-white text-black p-3 rounded-full z-10 swiper-button-next-custom">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                </svg>
            </button>
        </div>
    </div>
</div>

<div class="bg-cream">
    <div class="px-4 pt-16 container ">
        <h2 class="text-3xl lg:text-7xl font-cubao font-medium text-primary text-center">Head Office</h2>

        <div class="mt-10">
            <div class="font-bold text-xl">North Head Office, Quezon City</div>
            
            <div class="flex items-center gap-2 py-2 border-b border-gray-200">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                    <path fill-rule="evenodd"
                        d="m3.855 7.286 1.067-.534a1 1 0 0 0 .542-1.046l-.44-2.858A1 1 0 0 0 4.036 2H3a1 1 0 0 0-1 1v2c0 .709.082 1.4.238 2.062a9.012 9.012 0 0 0 6.7 6.7A9.024 9.024 0 0 0 11 14h2a1 1 0 0 0 1-1v-1.036a1 1 0 0 0-.848-.988l-2.858-.44a1 1 0 0 0-1.046.542l-.534 1.067a7.52 7.52 0 0 1-4.86-4.859Z"
                        clip-rule="evenodd" />
                </svg>
                <div class="text-base">Tel No: (+632) 8939 4665</div>
            </div>
            <div class="flex items-center gap-2 py-2 border-b border-gray-200">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                    <path fill-rule="evenodd"
                        d="m3.855 7.286 1.067-.534a1 1 0 0 0 .542-1.046l-.44-2.858A1 1 0 0 0 4.036 2H3a1 1 0 0 0-1 1v2c0 .709.082 1.4.238 2.062a9.012 9.012 0 0 0 6.7 6.7A9.024 9.024 0 0 0 11 14h2a1 1 0 0 0 1-1v-1.036a1 1 0 0 0-.848-.988l-2.858-.44a1 1 0 0 0-1.046.542l-.534 1.067a7.52 7.52 0 0 1-4.86-4.859Z"
                        clip-rule="evenodd" />
                </svg>
                <div class="text-base">Tel No: (+632) 8939 1221</div>
            </div>
            <div class="flex items-center gap-2 py-2 border-b border-gray-200">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                    <path fill-rule="evenodd"
                        d="m3.855 7.286 1.067-.534a1 1 0 0 0 .542-1.046l-.44-2.858A1 1 0 0 0 4.036 2H3a1 1 0 0 0-1 1v2c0 .709.082 1.4.238 2.062a9.012 9.012 0 0 0 6.7 6.7A9.024 9.024 0 0 0 11 14h2a1 1 0 0 0 1-1v-1.036a1 1 0 0 0-.848-.988l-2.858-.44a1 1 0 0 0-1.046.542l-.534 1.067a7.52 7.52 0 0 1-4.86-4.859Z"
                        clip-rule="evenodd" />
                </svg>
                <div class="text-base">Fax no. (+632) 8935 9165</div>
            </div>
            <div class="flex items-center gap-2 py-2 border-b border-gray-200">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                    <path d="M7.25 11.5a.75.75 0 0 0 0 1.5h1.5a.75.75 0 0 0 0-1.5h-1.5Z" />
                    <path fill-rule="evenodd"
                        d="M6 1a2.5 2.5 0 0 0-2.5 2.5v9A2.5 2.5 0 0 0 6 15h4a2.5 2.5 0 0 0 2.5-2.5v-9A2.5 2.5 0 0 0 10 1H6Zm4 1.5h-.5V3a.5.5 0 0 1-.5.5H7a.5.5 0 0 1-.5-.5v-.5H6a1 1 0 0 0-1 1v9a1 1 0 0 0 1 1h4a1 1 0 0 0 1-1v-9a1 1 0 0 0-1-1Z"
                        clip-rule="evenodd" />
                </svg>
                <div class="text-base">Mobile: 0917-5380304 (Globe)</div>
            </div>
            <div class="flex items-center gap-2 py-2 border-b border-gray-200">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                    <path d="M7.25 11.5a.75.75 0 0 0 0 1.5h1.5a.75.75 0 0 0 0-1.5h-1.5Z" />
                    <path fill-rule="evenodd"
                        d="M6 1a2.5 2.5 0 0 0-2.5 2.5v9A2.5 2.5 0 0 0 6 15h4a2.5 2.5 0 0 0 2.5-2.5v-9A2.5 2.5 0 0 0 10 1H6Zm4 1.5h-.5V3a.5.5 0 0 1-.5.5H7a.5.5 0 0 1-.5-.5v-.5H6a1 1 0 0 0-1 1v9a1 1 0 0 0 1 1h4a1 1 0 0 0 1-1v-9a1 1 0 0 0-1-1Z"
                        clip-rule="evenodd" />
                </svg>
                <div class="text-base">Mobile: 0918-9675213 (Smart)</div>
            </div>
        </div>

        <div class="flex gap-4 mt-5">
            <div @click="openMap(
                    'Block 4 Lot 4 - Carol Street, San Miguel Subdivision, Quezon City, Metro Manila', 
                    'https://www.google.com/maps/embed?pb=!1m16!1m12!1m3!1d3859.556058229893!2d121.04215022428801!3d14.681117335814788!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!2m1!1sBlock%204%20Lot%204%20-%20Carol%20Street%2C%20San%20Miguel%20Subdivision%2C%20Quezon%20City%2C%20Metro%20Manila!5e0!3m2!1sen!2sph!4v1743410127825!5m2!1sen!2sph'
                )" 
                class="flex text-tertiary gap-1 cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                </svg>
                Get Directions
            </div>
            <div @click="openMap(
                    'Block 4 Lot 4 - Carol Street, San Miguel Subdivision, Quezon City, Metro Manila', 
                    'https://www.google.com/maps/embed?pb=!1m16!1m12!1m3!1d3859.556058229893!2d121.04215022428801!3d14.681117335814788!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!2m1!1sBlock%204%20Lot%204%20-%20Carol%20Street%2C%20San%20Miguel%20Subdivision%2C%20Quezon%20City%2C%20Metro%20Manila!5e0!3m2!1sen!2sph!4v1743410127825!5m2!1sen!2sph'
                )" class="flex text-tertiary gap-1 cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 6.75V15m6-6v8.25m.503 3.498 4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 0 0-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0Z" />
                </svg>
                View Full Map
            </div>
        </div>

        <div class="mt-5">
            <div class="flex items-center gap-2 py-2 border-b border-gray-200">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                    <path fill-rule="evenodd"
                        d="m3.855 7.286 1.067-.534a1 1 0 0 0 .542-1.046l-.44-2.858A1 1 0 0 0 4.036 2H3a1 1 0 0 0-1 1v2c0 .709.082 1.4.238 2.062a9.012 9.012 0 0 0 6.7 6.7A9.024 9.024 0 0 0 11 14h2a1 1 0 0 0 1-1v-1.036a1 1 0 0 0-.848-.988l-2.858-.44a1 1 0 0 0-1.046.542l-.534 1.067a7.52 7.52 0 0 1-4.86-4.859Z"
                        clip-rule="evenodd" />
                </svg>
                <div class="text-base">Hotline: 8939-1221 (Quezon City)</div>
            </div>
            <div class="flex items-center gap-2 py-2 border-b border-gray-200">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                    <path fill-rule="evenodd"
                        d="m3.855 7.286 1.067-.534a1 1 0 0 0 .542-1.046l-.44-2.858A1 1 0 0 0 4.036 2H3a1 1 0 0 0-1 1v2c0 .709.082 1.4.238 2.062a9.012 9.012 0 0 0 6.7 6.7A9.024 9.024 0 0 0 11 14h2a1 1 0 0 0 1-1v-1.036a1 1 0 0 0-.848-.988l-2.858-.44a1 1 0 0 0-1.046.542l-.534 1.067a7.52 7.52 0 0 1-4.86-4.859Z"
                        clip-rule="evenodd" />
                </svg>
                <div class="text-base">Hotline: 8404-0178 (Quezon City)</div>
            </div>
            <div class="flex items-center gap-2 py-2 border-b border-gray-200">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                    <path fill-rule="evenodd"
                        d="m3.855 7.286 1.067-.534a1 1 0 0 0 .542-1.046l-.44-2.858A1 1 0 0 0 4.036 2H3a1 1 0 0 0-1 1v2c0 .709.082 1.4.238 2.062a9.012 9.012 0 0 0 6.7 6.7A9.024 9.024 0 0 0 11 14h2a1 1 0 0 0 1-1v-1.036a1 1 0 0 0-.848-.988l-2.858-.44a1 1 0 0 0-1.046.542l-.534 1.067a7.52 7.52 0 0 1-4.86-4.859Z"
                        clip-rule="evenodd" />
                </svg>
                <div class="text-base">Hotline: 8851-2987 to 89 (Baclaran)</div>
            </div>
            <div class="flex items-center gap-2 py-2 border-b border-gray-200">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                    <path fill-rule="evenodd"
                        d="m3.855 7.286 1.067-.534a1 1 0 0 0 .542-1.046l-.44-2.858A1 1 0 0 0 4.036 2H3a1 1 0 0 0-1 1v2c0 .709.082 1.4.238 2.062a9.012 9.012 0 0 0 6.7 6.7A9.024 9.024 0 0 0 11 14h2a1 1 0 0 0 1-1v-1.036a1 1 0 0 0-.848-.988l-2.858-.44a1 1 0 0 0-1.046.542l-.534 1.067a7.52 7.52 0 0 1-4.86-4.859Z"
                        clip-rule="evenodd" />
                </svg>
                <div class="text-base">Tel No: (+632) 8939 1221</div>
            </div>
            <div class="flex items-center gap-2 py-2 border-b border-gray-200">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                    <path fill-rule="evenodd"
                        d="m3.855 7.286 1.067-.534a1 1 0 0 0 .542-1.046l-.44-2.858A1 1 0 0 0 4.036 2H3a1 1 0 0 0-1 1v2c0 .709.082 1.4.238 2.062a9.012 9.012 0 0 0 6.7 6.7A9.024 9.024 0 0 0 11 14h2a1 1 0 0 0 1-1v-1.036a1 1 0 0 0-.848-.988l-2.858-.44a1 1 0 0 0-1.046.542l-.534 1.067a7.52 7.52 0 0 1-4.86-4.859Z"
                        clip-rule="evenodd" />
                </svg>
                <div class="text-base">Fax no. (+632) 8935 9165</div>
            </div>
            <div class="flex items-center gap-2 py-2 border-b border-gray-200">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                    <path d="M7.25 11.5a.75.75 0 0 0 0 1.5h1.5a.75.75 0 0 0 0-1.5h-1.5Z" />
                    <path fill-rule="evenodd"
                        d="M6 1a2.5 2.5 0 0 0-2.5 2.5v9A2.5 2.5 0 0 0 6 15h4a2.5 2.5 0 0 0 2.5-2.5v-9A2.5 2.5 0 0 0 10 1H6Zm4 1.5h-.5V3a.5.5 0 0 1-.5.5H7a.5.5 0 0 1-.5-.5v-.5H6a1 1 0 0 0-1 1v9a1 1 0 0 0 1 1h4a1 1 0 0 0 1-1v-9a1 1 0 0 0-1-1Z"
                        clip-rule="evenodd" />
                </svg>
                <div class="text-base">Mobile: 0917-5380304 (Globe)</div>
            </div>
            <div class="flex items-center gap-2 py-2 border-b border-gray-200">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                    <path d="M7.25 11.5a.75.75 0 0 0 0 1.5h1.5a.75.75 0 0 0 0-1.5h-1.5Z" />
                    <path fill-rule="evenodd"
                        d="M6 1a2.5 2.5 0 0 0-2.5 2.5v9A2.5 2.5 0 0 0 6 15h4a2.5 2.5 0 0 0 2.5-2.5v-9A2.5 2.5 0 0 0 10 1H6Zm4 1.5h-.5V3a.5.5 0 0 1-.5.5H7a.5.5 0 0 1-.5-.5v-.5H6a1 1 0 0 0-1 1v9a1 1 0 0 0 1 1h4a1 1 0 0 0 1-1v-9a1 1 0 0 0-1-1Z"
                        clip-rule="evenodd" />
                </svg>
                <div class="text-base">Mobile: 0918-9675213 (Smart)</div>
            </div>
        </div>

        <h2 class="text-3xl lg:text-7xl font-cubao font-medium text-primary text-center mt-10">our stores and outlets
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-10">
            <div class="mt-10">
                <div class="font-bold text-xl">Roces Ave. Q.C. Branch</div>
                <div class="flex gap-2">
                    <span>49-A Roces Avenue, Corner Scout Reyes, Q.C. Metro Manila</span>
                </div>
                <div class="flex items-center gap-2 py-2 border-b border-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                        <path fill-rule="evenodd"
                            d="m3.855 7.286 1.067-.534a1 1 0 0 0 .542-1.046l-.44-2.858A1 1 0 0 0 4.036 2H3a1 1 0 0 0-1 1v2c0 .709.082 1.4.238 2.062a9.012 9.012 0 0 0 6.7 6.7A9.024 9.024 0 0 0 11 14h2a1 1 0 0 0 1-1v-1.036a1 1 0 0 0-.848-.988l-2.858-.44a1 1 0 0 0-1.046.542l-.534 1.067a7.52 7.52 0 0 1-4.86-4.859Z"
                            clip-rule="evenodd" />
                    </svg>
                    <div class="text-base">Tel No: (+632) 8376-1818</div>
                </div>
                <div class="flex items-center gap-2 py-2 border-b border-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                        <path fill-rule="evenodd"
                            d="m3.855 7.286 1.067-.534a1 1 0 0 0 .542-1.046l-.44-2.858A1 1 0 0 0 4.036 2H3a1 1 0 0 0-1 1v2c0 .709.082 1.4.238 2.062a9.012 9.012 0 0 0 6.7 6.7A9.024 9.024 0 0 0 11 14h2a1 1 0 0 0 1-1v-1.036a1 1 0 0 0-.848-.988l-2.858-.44a1 1 0 0 0-1.046.542l-.534 1.067a7.52 7.52 0 0 1-4.86-4.859Z"
                            clip-rule="evenodd" />
                    </svg>
                    <div class="text-base">Tel No: (+632) 8851-1028</div>
                </div>
                <div class="flex items-center gap-2 py-2 border-b border-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                        <path fill-rule="evenodd"
                            d="m3.855 7.286 1.067-.534a1 1 0 0 0 .542-1.046l-.44-2.858A1 1 0 0 0 4.036 2H3a1 1 0 0 0-1 1v2c0 .709.082 1.4.238 2.062a9.012 9.012 0 0 0 6.7 6.7A9.024 9.024 0 0 0 11 14h2a1 1 0 0 0 1-1v-1.036a1 1 0 0 0-.848-.988l-2.858-.44a1 1 0 0 0-1.046.542l-.534 1.067a7.52 7.52 0 0 1-4.86-4.859Z"
                            clip-rule="evenodd" />
                    </svg>
                    <div class="text-base">Tel No: (+632) 8736-9664</div>
                </div>
                <div class="flex items-center gap-2 py-2 border-b border-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                        <path d="M7.25 11.5a.75.75 0 0 0 0 1.5h1.5a.75.75 0 0 0 0-1.5h-1.5Z" />
                        <path fill-rule="evenodd"
                            d="M6 1a2.5 2.5 0 0 0-2.5 2.5v9A2.5 2.5 0 0 0 6 15h4a2.5 2.5 0 0 0 2.5-2.5v-9A2.5 2.5 0 0 0 10 1H6Zm4 1.5h-.5V3a.5.5 0 0 1-.5.5H7a.5.5 0 0 1-.5-.5v-.5H6a1 1 0 0 0-1 1v9a1 1 0 0 0 1 1h4a1 1 0 0 0 1-1v-9a1 1 0 0 0-1-1Z"
                            clip-rule="evenodd" />
                    </svg>
                    <div class="text-base">Mobile: 0995-0692273</div>
                </div>
    
                <div class="flex gap-4 mt-5">
                    <div @click="openMap(
                            '49-A Roces Avenue, Corner Scout Reyes, Q.C. Metro Manila', 
                            'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3860.4439847546587!2d121.02269977428699!3d14.630720585859256!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397b64ed0aad229%3A0xd185169a6af9b07d!2s49%20Don%20A.%20Roces%20Ave%2C%20Diliman%2C%20Quezon%20City%2C%201103%20Metro%20Manila!5e0!3m2!1sen!2sph!4v1743410983735!5m2!1sen!2sph'
                        )" class="flex text-tertiary gap-1 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                        </svg>
                        Get Directions
                    </div>
                    <div @click="openMap(
                            '49-A Roces Avenue, Corner Scout Reyes, Q.C. Metro Manila', 
                            'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3860.4439847546587!2d121.02269977428699!3d14.630720585859256!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397b64ed0aad229%3A0xd185169a6af9b07d!2s49%20Don%20A.%20Roces%20Ave%2C%20Diliman%2C%20Quezon%20City%2C%201103%20Metro%20Manila!5e0!3m2!1sen!2sph!4v1743410983735!5m2!1sen!2sph'
                        )" class="flex text-tertiary gap-1 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 6.75V15m6-6v8.25m.503 3.498 4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 0 0-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0Z" />
                        </svg>
                        View Full Map
                    </div>
                </div>
            </div>
    
            <div class="mt-10">
                <div class="font-bold text-xl">Tandang Sora, Quezon City Branch (Cloud Kitchen)</div>
                <div class="flex gap-2">
                    <span>Lot 4 Blk 4 Carol St., San Miguel Subd., Tandang Sora, Quezon City, Metro Manila,</span>
                </div>
                <div class="flex items-center gap-2 py-2 border-b border-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                        <path fill-rule="evenodd"
                            d="m3.855 7.286 1.067-.534a1 1 0 0 0 .542-1.046l-.44-2.858A1 1 0 0 0 4.036 2H3a1 1 0 0 0-1 1v2c0 .709.082 1.4.238 2.062a9.012 9.012 0 0 0 6.7 6.7A9.024 9.024 0 0 0 11 14h2a1 1 0 0 0 1-1v-1.036a1 1 0 0 0-.848-.988l-2.858-.44a1 1 0 0 0-1.046.542l-.534 1.067a7.52 7.52 0 0 1-4.86-4.859Z"
                            clip-rule="evenodd" />
                    </svg>
                    <div class="text-base">Tel No: (+632) 8939-1221</div>
                </div>
                <div class="flex items-center gap-2 py-2 border-b border-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                        <path fill-rule="evenodd"
                            d="m3.855 7.286 1.067-.534a1 1 0 0 0 .542-1.046l-.44-2.858A1 1 0 0 0 4.036 2H3a1 1 0 0 0-1 1v2c0 .709.082 1.4.238 2.062a9.012 9.012 0 0 0 6.7 6.7A9.024 9.024 0 0 0 11 14h2a1 1 0 0 0 1-1v-1.036a1 1 0 0 0-.848-.988l-2.858-.44a1 1 0 0 0-1.046.542l-.534 1.067a7.52 7.52 0 0 1-4.86-4.859Z"
                            clip-rule="evenodd" />
                    </svg>
                    <div class="text-base">Tel No: (+632) 8404-0178</div>
                </div>
                <div class="flex items-center gap-2 py-2 border-b border-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                        <path d="M7.25 11.5a.75.75 0 0 0 0 1.5h1.5a.75.75 0 0 0 0-1.5h-1.5Z" />
                        <path fill-rule="evenodd"
                            d="M6 1a2.5 2.5 0 0 0-2.5 2.5v9A2.5 2.5 0 0 0 6 15h4a2.5 2.5 0 0 0 2.5-2.5v-9A2.5 2.5 0 0 0 10 1H6Zm4 1.5h-.5V3a.5.5 0 0 1-.5.5H7a.5.5 0 0 1-.5-.5v-.5H6a1 1 0 0 0-1 1v9a1 1 0 0 0 1 1h4a1 1 0 0 0 1-1v-9a1 1 0 0 0-1-1Z"
                            clip-rule="evenodd" />
                    </svg>
                    <div class="text-base">Mobile: 0967-3345188</div>
                </div>
                <div class="flex items-center gap-2 py-2 border-b border-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                        <path d="M7.25 11.5a.75.75 0 0 0 0 1.5h1.5a.75.75 0 0 0 0-1.5h-1.5Z" />
                        <path fill-rule="evenodd"
                            d="M6 1a2.5 2.5 0 0 0-2.5 2.5v9A2.5 2.5 0 0 0 6 15h4a2.5 2.5 0 0 0 2.5-2.5v-9A2.5 2.5 0 0 0 10 1H6Zm4 1.5h-.5V3a.5.5 0 0 1-.5.5H7a.5.5 0 0 1-.5-.5v-.5H6a1 1 0 0 0-1 1v9a1 1 0 0 0 1 1h4a1 1 0 0 0 1-1v-9a1 1 0 0 0-1-1Z"
                            clip-rule="evenodd" />
                    </svg>
                    <div class="text-base">Mobile: 0998-3022088</div>
                </div>
    
                <div class="flex gap-4 mt-5">
                    <div @click="openMap(
                            'Lot 4 Blk 4 Carol St., San Miguel Subd., Tandang Sora, Quezon City, Metro Manila', 
                            'https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d3859.5567418523683!2d121.0419057!3d14.6810786!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397b72d160be693%3A0x1e03a638f7bc1808!2sSan%20Gregorio%20All%20Ventures!5e0!3m2!1sen!2sph!4v1743411080555!5m2!1sen!2sph'
                        )" class="flex text-tertiary gap-1 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                        </svg>
                        Get Directions
                    </div>
                    <div @click="openMap(
                            'Lot 4 Blk 4 Carol St., San Miguel Subd., Tandang Sora, Quezon City, Metro Manila', 
                            'https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d3859.5567418523683!2d121.0419057!3d14.6810786!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397b72d160be693%3A0x1e03a638f7bc1808!2sSan%20Gregorio%20All%20Ventures!5e0!3m2!1sen!2sph!4v1743411080555!5m2!1sen!2sph'
                        )" class="flex text-tertiary gap-1 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 6.75V15m6-6v8.25m.503 3.498 4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 0 0-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0Z" />
                        </svg>
                        View Full Map
                    </div>
                </div>
            </div>
    
            <div class="mt-10">
                <div class="font-bold text-xl">Marcos Highway, Marikina Branch</div>
                <div class="flex gap-2">
                    <span>24 Marcos Hiway Corner Pambuli Street, Marikina City, Metro Manila</span>
                </div>
                <div class="flex items-center gap-2 py-2 border-b border-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                        <path fill-rule="evenodd"
                            d="m3.855 7.286 1.067-.534a1 1 0 0 0 .542-1.046l-.44-2.858A1 1 0 0 0 4.036 2H3a1 1 0 0 0-1 1v2c0 .709.082 1.4.238 2.062a9.012 9.012 0 0 0 6.7 6.7A9.024 9.024 0 0 0 11 14h2a1 1 0 0 0 1-1v-1.036a1 1 0 0 0-.848-.988l-2.858-.44a1 1 0 0 0-1.046.542l-.534 1.067a7.52 7.52 0 0 1-4.86-4.859Z"
                            clip-rule="evenodd" />
                    </svg>
                    <div class="text-base">Tel No: (+632) 8646-0871</div>
                </div>
                <div class="flex items-center gap-2 py-2 border-b border-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                        <path fill-rule="evenodd"
                            d="m3.855 7.286 1.067-.534a1 1 0 0 0 .542-1.046l-.44-2.858A1 1 0 0 0 4.036 2H3a1 1 0 0 0-1 1v2c0 .709.082 1.4.238 2.062a9.012 9.012 0 0 0 6.7 6.7A9.024 9.024 0 0 0 11 14h2a1 1 0 0 0 1-1v-1.036a1 1 0 0 0-.848-.988l-2.858-.44a1 1 0 0 0-1.046.542l-.534 1.067a7.52 7.52 0 0 1-4.86-4.859Z"
                            clip-rule="evenodd" />
                    </svg>
                    <div class="text-base">Tel No: (+632) 8401-9791</div>
                </div>
                <div class="flex items-center gap-2 py-2 border-b border-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                        <path d="M7.25 11.5a.75.75 0 0 0 0 1.5h1.5a.75.75 0 0 0 0-1.5h-1.5Z" />
                        <path fill-rule="evenodd"
                            d="M6 1a2.5 2.5 0 0 0-2.5 2.5v9A2.5 2.5 0 0 0 6 15h4a2.5 2.5 0 0 0 2.5-2.5v-9A2.5 2.5 0 0 0 10 1H6Zm4 1.5h-.5V3a.5.5 0 0 1-.5.5H7a.5.5 0 0 1-.5-.5v-.5H6a1 1 0 0 0-1 1v9a1 1 0 0 0 1 1h4a1 1 0 0 0 1-1v-9a1 1 0 0 0-1-1Z"
                            clip-rule="evenodd" />
                    </svg>
                    <div class="text-base">Mobile: 0995-0692281</div>
                </div>
    
                <div class="flex gap-4 mt-5">
                    <div @click="openMap(
                            '77 Fairview Avenue, Fairview Subdivision, Quezon City, Metro Manila', 
                            'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3859.1274267692284!2d121.07274207140898!3d14.705385054395673!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397b09dd94cb529%3A0x63684a9f27d06f78!2sFairview%20Subdivision%2C%2077%20Fairview%20Avenue%2C%20Quezon%20City%2C%201121%20Metro%20Manila!5e0!3m2!1sen!2sph!4v1743411202705!5m2!1sen!2sph'
                        )" class="flex text-tertiary gap-1 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                        </svg>
                        Get Directions
                    </div>
                    <div @click="openMap(
                            '77 Fairview Avenue, Fairview Subdivision, Quezon City, Metro Manila', 
                            'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3859.1274267692284!2d121.07274207140898!3d14.705385054395673!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397b09dd94cb529%3A0x63684a9f27d06f78!2sFairview%20Subdivision%2C%2077%20Fairview%20Avenue%2C%20Quezon%20City%2C%201121%20Metro%20Manila!5e0!3m2!1sen!2sph!4v1743411202705!5m2!1sen!2sph'
                        )" class="flex text-tertiary gap-1 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 6.75V15m6-6v8.25m.503 3.498 4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 0 0-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0Z" />
                        </svg>
                        View Full Map
                    </div>
                </div>
            </div>
    
            <div class="mt-10">
                <div class="font-bold text-xl">C5 Ugong, Pasig Branch</div>
                <div class="flex gap-2">
                    <span>E. Rodriguez Jr. Avenue Corner C.J. Caparas St., Brgy. Ugong, Pasig City, Metro Manila</span>
                </div>
                <div class="flex items-center gap-2 py-2 border-b border-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                        <path fill-rule="evenodd"
                            d="m3.855 7.286 1.067-.534a1 1 0 0 0 .542-1.046l-.44-2.858A1 1 0 0 0 4.036 2H3a1 1 0 0 0-1 1v2c0 .709.082 1.4.238 2.062a9.012 9.012 0 0 0 6.7 6.7A9.024 9.024 0 0 0 11 14h2a1 1 0 0 0 1-1v-1.036a1 1 0 0 0-.848-.988l-2.858-.44a1 1 0 0 0-1.046.542l-.534 1.067a7.52 7.52 0 0 1-4.86-4.859Z"
                            clip-rule="evenodd" />
                    </svg>
                    <div class="text-base">Tel No: (+632) 8671 9053</div>
                </div>
                <div class="flex items-center gap-2 py-2 border-b border-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                        <path fill-rule="evenodd"
                            d="m3.855 7.286 1.067-.534a1 1 0 0 0 .542-1.046l-.44-2.858A1 1 0 0 0 4.036 2H3a1 1 0 0 0-1 1v2c0 .709.082 1.4.238 2.062a9.012 9.012 0 0 0 6.7 6.7A9.024 9.024 0 0 0 11 14h2a1 1 0 0 0 1-1v-1.036a1 1 0 0 0-.848-.988l-2.858-.44a1 1 0 0 0-1.046.542l-.534 1.067a7.52 7.52 0 0 1-4.86-4.859Z"
                            clip-rule="evenodd" />
                    </svg>
                    <div class="text-base">Tel No: (+632) 7933-2961</div>
                </div>
                <div class="flex items-center gap-2 py-2 border-b border-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                        <path d="M7.25 11.5a.75.75 0 0 0 0 1.5h1.5a.75.75 0 0 0 0-1.5h-1.5Z" />
                        <path fill-rule="evenodd"
                            d="M6 1a2.5 2.5 0 0 0-2.5 2.5v9A2.5 2.5 0 0 0 6 15h4a2.5 2.5 0 0 0 2.5-2.5v-9A2.5 2.5 0 0 0 10 1H6Zm4 1.5h-.5V3a.5.5 0 0 1-.5.5H7a.5.5 0 0 1-.5-.5v-.5H6a1 1 0 0 0-1 1v9a1 1 0 0 0 1 1h4a1 1 0 0 0 1-1v-9a1 1 0 0 0-1-1Z"
                            clip-rule="evenodd" />
                    </svg>
                    <div class="text-base">Mobile: 0995-0692239</div>
                </div>
    
                <div class="flex gap-4 mt-5">
                    <div @click="openMap(
                            '551 Service Road, Roxas Blvd. Baclaran, Parañaque Metro Manila', 
                            'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3862.26811281623!2d120.9911556742844!3d14.526650585950987!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397c9535a160a57%3A0xbc305274dfc17007!2s551%20Roxas%20Blvd%2C%20Para%C3%B1aque%2C%20Metro%20Manila!5e0!3m2!1sen!2sph!4v1743411256958!5m2!1sen!2sph'
                        )" class="flex text-tertiary gap-1 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                        </svg>
                        Get Directions
                    </div>
                    <div @click="openMap(
                            '551 Service Road, Roxas Blvd. Baclaran, Parañaque Metro Manila', 
                            'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3862.26811281623!2d120.9911556742844!3d14.526650585950987!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397c9535a160a57%3A0xbc305274dfc17007!2s551%20Roxas%20Blvd%2C%20Para%C3%B1aque%2C%20Metro%20Manila!5e0!3m2!1sen!2sph!4v1743411256958!5m2!1sen!2sph'
                        )" class="flex text-tertiary gap-1 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 6.75V15m6-6v8.25m.503 3.498 4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 0 0-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0Z" />
                        </svg>
                        View Full Map
                    </div>
                </div>
            </div>
    
            <div class="mt-10">
                <div class="font-bold text-xl">Commonwealth Ave. Q.C. Branch</div>
                <div class="flex gap-2">
                    <span>77 Fairview Avenue, Fairview Subdivision, Quezon City, Metro Manila</span>
                </div>
                <div class="flex items-center gap-2 py-2 border-b border-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                        <path fill-rule="evenodd"
                            d="m3.855 7.286 1.067-.534a1 1 0 0 0 .542-1.046l-.44-2.858A1 1 0 0 0 4.036 2H3a1 1 0 0 0-1 1v2c0 .709.082 1.4.238 2.062a9.012 9.012 0 0 0 6.7 6.7A9.024 9.024 0 0 0 11 14h2a1 1 0 0 0 1-1v-1.036a1 1 0 0 0-.848-.988l-2.858-.44a1 1 0 0 0-1.046.542l-.534 1.067a7.52 7.52 0 0 1-4.86-4.859Z"
                            clip-rule="evenodd" />
                    </svg>
                    <div class="text-base">Tel No: (+632) 8935-5095</div>
                </div>
                <div class="flex items-center gap-2 py-2 border-b border-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                        <path fill-rule="evenodd"
                            d="m3.855 7.286 1.067-.534a1 1 0 0 0 .542-1.046l-.44-2.858A1 1 0 0 0 4.036 2H3a1 1 0 0 0-1 1v2c0 .709.082 1.4.238 2.062a9.012 9.012 0 0 0 6.7 6.7A9.024 9.024 0 0 0 11 14h2a1 1 0 0 0 1-1v-1.036a1 1 0 0 0-.848-.988l-2.858-.44a1 1 0 0 0-1.046.542l-.534 1.067a7.52 7.52 0 0 1-4.86-4.859Z"
                            clip-rule="evenodd" />
                    </svg>
                    <div class="text-base">Tel No: (+632) 8401-9867</div>
                </div>
                <div class="flex items-center gap-2 py-2 border-b border-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                        <path fill-rule="evenodd"
                            d="m3.855 7.286 1.067-.534a1 1 0 0 0 .542-1.046l-.44-2.858A1 1 0 0 0 4.036 2H3a1 1 0 0 0-1 1v2c0 .709.082 1.4.238 2.062a9.012 9.012 0 0 0 6.7 6.7A9.024 9.024 0 0 0 11 14h2a1 1 0 0 0 1-1v-1.036a1 1 0 0 0-.848-.988l-2.858-.44a1 1 0 0 0-1.046.542l-.534 1.067a7.52 7.52 0 0 1-4.86-4.859Z"
                            clip-rule="evenodd" />
                    </svg>
                    <div class="text-base">Tel No: (+632) 7091-4175</div>
                </div>
                <div class="flex items-center gap-2 py-2 border-b border-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                        <path d="M7.25 11.5a.75.75 0 0 0 0 1.5h1.5a.75.75 0 0 0 0-1.5h-1.5Z" />
                        <path fill-rule="evenodd"
                            d="M6 1a2.5 2.5 0 0 0-2.5 2.5v9A2.5 2.5 0 0 0 6 15h4a2.5 2.5 0 0 0 2.5-2.5v-9A2.5 2.5 0 0 0 10 1H6Zm4 1.5h-.5V3a.5.5 0 0 1-.5.5H7a.5.5 0 0 1-.5-.5v-.5H6a1 1 0 0 0-1 1v9a1 1 0 0 0 1 1h4a1 1 0 0 0 1-1v-9a1 1 0 0 0-1-1Z"
                            clip-rule="evenodd" />
                    </svg>
                    <div class="text-base">Mobile: 0995-0692235</div>
                </div>
    
                <div class="flex gap-4 mt-5">
                    <div @click="openMap(
                            '77 Fairview Avenue, Fairview Subdivision, Quezon City, Metro Manila', 
                            'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3859.1273344940173!2d121.07478057577477!3d14.705390274501147!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397b09dd94cb529%3A0x63684a9f27d06f78!2sFairview%20Subdivision%2C%2077%20Fairview%20Avenue%2C%20Quezon%20City%2C%201121%20Metro%20Manila!5e0!3m2!1sen!2sph!4v1743466707724!5m2!1sen!2sph'
                        )"  class="flex text-tertiary gap-1 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                        </svg>
                        Get Directions
                    </div>
                    <div @click="openMap(
                            '77 Fairview Avenue, Fairview Subdivision, Quezon City, Metro Manila', 
                            'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3859.1273344940173!2d121.07478057577477!3d14.705390274501147!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397b09dd94cb529%3A0x63684a9f27d06f78!2sFairview%20Subdivision%2C%2077%20Fairview%20Avenue%2C%20Quezon%20City%2C%201121%20Metro%20Manila!5e0!3m2!1sen!2sph!4v1743466707724!5m2!1sen!2sph'
                        )" class="flex text-tertiary gap-1 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 6.75V15m6-6v8.25m.503 3.498 4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 0 0-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0Z" />
                        </svg>
                        View Full Map
                    </div>
                </div>
            </div>
    
            <div class="mt-10">
                <div class="font-bold text-xl">Baclaran, Paranaque City Branch</div>
                <div class="flex gap-2">
                    <span>551 Service Road, Roxas Blvd. Baclaran, Parañaque Metro Manila</span>
                </div>
                <div class="flex items-center gap-2 py-2 border-b border-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                        <path fill-rule="evenodd"
                            d="m3.855 7.286 1.067-.534a1 1 0 0 0 .542-1.046l-.44-2.858A1 1 0 0 0 4.036 2H3a1 1 0 0 0-1 1v2c0 .709.082 1.4.238 2.062a9.012 9.012 0 0 0 6.7 6.7A9.024 9.024 0 0 0 11 14h2a1 1 0 0 0 1-1v-1.036a1 1 0 0 0-.848-.988l-2.858-.44a1 1 0 0 0-1.046.542l-.534 1.067a7.52 7.52 0 0 1-4.86-4.859Z"
                            clip-rule="evenodd" />
                    </svg>
                    <div class="text-base">Tel No: (+632) 8851-2987</div>
                </div>
                <div class="flex items-center gap-2 py-2 border-b border-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                        <path fill-rule="evenodd"
                            d="m3.855 7.286 1.067-.534a1 1 0 0 0 .542-1.046l-.44-2.858A1 1 0 0 0 4.036 2H3a1 1 0 0 0-1 1v2c0 .709.082 1.4.238 2.062a9.012 9.012 0 0 0 6.7 6.7A9.024 9.024 0 0 0 11 14h2a1 1 0 0 0 1-1v-1.036a1 1 0 0 0-.848-.988l-2.858-.44a1 1 0 0 0-1.046.542l-.534 1.067a7.52 7.52 0 0 1-4.86-4.859Z"
                            clip-rule="evenodd" />
                    </svg>
                    <div class="text-base">Tel No: (+632) 8851-2988</div>
                </div>
                <div class="flex items-center gap-2 py-2 border-b border-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                        <path fill-rule="evenodd"
                            d="m3.855 7.286 1.067-.534a1 1 0 0 0 .542-1.046l-.44-2.858A1 1 0 0 0 4.036 2H3a1 1 0 0 0-1 1v2c0 .709.082 1.4.238 2.062a9.012 9.012 0 0 0 6.7 6.7A9.024 9.024 0 0 0 11 14h2a1 1 0 0 0 1-1v-1.036a1 1 0 0 0-.848-.988l-2.858-.44a1 1 0 0 0-1.046.542l-.534 1.067a7.52 7.52 0 0 1-4.86-4.859Z"
                            clip-rule="evenodd" />
                    </svg>
                    <div class="text-base">Tel No: (+632) 8851-1028</div>
                </div>
                <div class="flex items-center gap-2 py-2 border-b border-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                        <path d="M7.25 11.5a.75.75 0 0 0 0 1.5h1.5a.75.75 0 0 0 0-1.5h-1.5Z" />
                        <path fill-rule="evenodd"
                            d="M6 1a2.5 2.5 0 0 0-2.5 2.5v9A2.5 2.5 0 0 0 6 15h4a2.5 2.5 0 0 0 2.5-2.5v-9A2.5 2.5 0 0 0 10 1H6Zm4 1.5h-.5V3a.5.5 0 0 1-.5.5H7a.5.5 0 0 1-.5-.5v-.5H6a1 1 0 0 0-1 1v9a1 1 0 0 0 1 1h4a1 1 0 0 0 1-1v-9a1 1 0 0 0-1-1Z"
                            clip-rule="evenodd" />
                    </svg>
                    <div class="text-base">Mobile: 0917-1620731</div>
                </div>
                <div class="flex items-center gap-2 py-2 border-b border-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                        <path d="M7.25 11.5a.75.75 0 0 0 0 1.5h1.5a.75.75 0 0 0 0-1.5h-1.5Z" />
                        <path fill-rule="evenodd"
                            d="M6 1a2.5 2.5 0 0 0-2.5 2.5v9A2.5 2.5 0 0 0 6 15h4a2.5 2.5 0 0 0 2.5-2.5v-9A2.5 2.5 0 0 0 10 1H6Zm4 1.5h-.5V3a.5.5 0 0 1-.5.5H7a.5.5 0 0 1-.5-.5v-.5H6a1 1 0 0 0-1 1v9a1 1 0 0 0 1 1h4a1 1 0 0 0 1-1v-9a1 1 0 0 0-1-1Z"
                            clip-rule="evenodd" />
                    </svg>
                    <div class="text-base">Mobile: 0917-8202989</div>
                </div>
    
                <div class="flex gap-4 mt-5">
                    <div @click="openMap(
                            '551 Service Road, Roxas Blvd. Baclaran, Parañaque Metro Manila', 
                            'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3862.268022112934!2d120.99115567577265!3d14.526655778881405!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397c9535a160a57%3A0xbc305274dfc17007!2s551%20Roxas%20Blvd%2C%20Para%C3%B1aque%2C%20Metro%20Manila!5e0!3m2!1sen!2sph!4v1743466764659!5m2!1sen!2sph'
                        )" class="flex text-tertiary gap-1 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                        </svg>
                        Get Directions
                    </div>
                    <div @click="openMap(
                            '551 Service Road, Roxas Blvd. Baclaran, Parañaque Metro Manila', 
                            'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3862.268022112934!2d120.99115567577265!3d14.526655778881405!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397c9535a160a57%3A0xbc305274dfc17007!2s551%20Roxas%20Blvd%2C%20Para%C3%B1aque%2C%20Metro%20Manila!5e0!3m2!1sen!2sph!4v1743466764659!5m2!1sen!2sph'
                        )" class="flex text-tertiary gap-1 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 6.75V15m6-6v8.25m.503 3.498 4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 0 0-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0Z" />
                        </svg>
                        View Full Map
                    </div>
                </div>
            </div>
    
            <div class="mt-10">
                <div class="font-bold text-xl">BF Homes Aguirre, Paranaque Branch</div>
                <div class="flex gap-2">
                    <span>312 Aguirre Avenue, Phase 3 BF Homes, Paranaque City Metro Manila</span>
                </div>
                <div class="flex items-center gap-2 py-2 border-b border-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                        <path fill-rule="evenodd"
                            d="m3.855 7.286 1.067-.534a1 1 0 0 0 .542-1.046l-.44-2.858A1 1 0 0 0 4.036 2H3a1 1 0 0 0-1 1v2c0 .709.082 1.4.238 2.062a9.012 9.012 0 0 0 6.7 6.7A9.024 9.024 0 0 0 11 14h2a1 1 0 0 0 1-1v-1.036a1 1 0 0 0-.848-.988l-2.858-.44a1 1 0 0 0-1.046.542l-.534 1.067a7.52 7.52 0 0 1-4.86-4.859Z"
                            clip-rule="evenodd" />
                    </svg>
                    <div class="text-base">Tel No: (+632) 8556-6741</div>
                </div>
                <div class="flex items-center gap-2 py-2 border-b border-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                        <path fill-rule="evenodd"
                            d="m3.855 7.286 1.067-.534a1 1 0 0 0 .542-1.046l-.44-2.858A1 1 0 0 0 4.036 2H3a1 1 0 0 0-1 1v2c0 .709.082 1.4.238 2.062a9.012 9.012 0 0 0 6.7 6.7A9.024 9.024 0 0 0 11 14h2a1 1 0 0 0 1-1v-1.036a1 1 0 0 0-.848-.988l-2.858-.44a1 1 0 0 0-1.046.542l-.534 1.067a7.52 7.52 0 0 1-4.86-4.859Z"
                            clip-rule="evenodd" />
                    </svg>
                    <div class="text-base">Fax No: (+632) 8861-0608</div>
                </div>
                <div class="flex items-center gap-2 py-2 border-b border-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                        <path d="M7.25 11.5a.75.75 0 0 0 0 1.5h1.5a.75.75 0 0 0 0-1.5h-1.5Z" />
                        <path fill-rule="evenodd"
                            d="M6 1a2.5 2.5 0 0 0-2.5 2.5v9A2.5 2.5 0 0 0 6 15h4a2.5 2.5 0 0 0 2.5-2.5v-9A2.5 2.5 0 0 0 10 1H6Zm4 1.5h-.5V3a.5.5 0 0 1-.5.5H7a.5.5 0 0 1-.5-.5v-.5H6a1 1 0 0 0-1 1v9a1 1 0 0 0 1 1h4a1 1 0 0 0 1-1v-9a1 1 0 0 0-1-1Z"
                            clip-rule="evenodd" />
                    </svg>
                    <div class="text-base">Mobile: 0917-1685485</div>
                </div>
    
                <div class="flex gap-4 mt-5">
                    <div @click="openMap(
                        '312 Aguirre Avenue, Phase 3 BF Homes, Paranaque City Metro Manila', 
                        'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3863.6170247513223!2d121.01437018452965!3d14.449220944733906!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397ce25a66d3b27%3A0x4e34203183e67797!2sAdela%20Bldg.%2C%203%20Aguirre%20Ave%2C%20Para%C3%B1aque%2C%201720%20Metro%20Manila!5e0!3m2!1sen!2sph!4v1743466855112!5m2!1sen!2sph'
                    )" class="flex text-tertiary gap-1 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                        </svg>
                        Get Directions
                    </div>
                    <div @click="openMap(
                        '312 Aguirre Avenue, Phase 3 BF Homes, Paranaque City Metro Manila', 
                        'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3863.6170247513223!2d121.01437018452965!3d14.449220944733906!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397ce25a66d3b27%3A0x4e34203183e67797!2sAdela%20Bldg.%2C%203%20Aguirre%20Ave%2C%20Para%C3%B1aque%2C%201720%20Metro%20Manila!5e0!3m2!1sen!2sph!4v1743466855112!5m2!1sen!2sph'
                    )" class="flex text-tertiary gap-1 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 6.75V15m6-6v8.25m.503 3.498 4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 0 0-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0Z" />
                        </svg>
                        View Full Map
                    </div>
                </div>
            </div>
    
            <div class="mt-10">
                <div class="font-bold text-xl">Timog Ave. Q.C. Branch</div>
                <div class="flex gap-2">
                    <span>116 Timog Avenue, Cor. 11th Jamboree St. Quezon City, Metro Manila</span>
                </div>
                <div class="flex items-center gap-2 py-2 border-b border-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                        <path fill-rule="evenodd"
                            d="m3.855 7.286 1.067-.534a1 1 0 0 0 .542-1.046l-.44-2.858A1 1 0 0 0 4.036 2H3a1 1 0 0 0-1 1v2c0 .709.082 1.4.238 2.062a9.012 9.012 0 0 0 6.7 6.7A9.024 9.024 0 0 0 11 14h2a1 1 0 0 0 1-1v-1.036a1 1 0 0 0-.848-.988l-2.858-.44a1 1 0 0 0-1.046.542l-.534 1.067a7.52 7.52 0 0 1-4.86-4.859Z"
                            clip-rule="evenodd" />
                    </svg>
                    <div class="text-base">Tel No: (+632) 8921 1221</div>
                </div>
                <div class="flex items-center gap-2 py-2 border-b border-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                        <path fill-rule="evenodd"
                            d="m3.855 7.286 1.067-.534a1 1 0 0 0 .542-1.046l-.44-2.858A1 1 0 0 0 4.036 2H3a1 1 0 0 0-1 1v2c0 .709.082 1.4.238 2.062a9.012 9.012 0 0 0 6.7 6.7A9.024 9.024 0 0 0 11 14h2a1 1 0 0 0 1-1v-1.036a1 1 0 0 0-.848-.988l-2.858-.44a1 1 0 0 0-1.046.542l-.534 1.067a7.52 7.52 0 0 1-4.86-4.859Z"
                            clip-rule="evenodd" />
                    </svg>
                    <div class="text-base">Tel No: (+632) 8355 9149</div>
                </div>
                <div class="flex items-center gap-2 py-2 border-b border-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                        <path d="M7.25 11.5a.75.75 0 0 0 0 1.5h1.5a.75.75 0 0 0 0-1.5h-1.5Z" />
                        <path fill-rule="evenodd"
                            d="M6 1a2.5 2.5 0 0 0-2.5 2.5v9A2.5 2.5 0 0 0 6 15h4a2.5 2.5 0 0 0 2.5-2.5v-9A2.5 2.5 0 0 0 10 1H6Zm4 1.5h-.5V3a.5.5 0 0 1-.5.5H7a.5.5 0 0 1-.5-.5v-.5H6a1 1 0 0 0-1 1v9a1 1 0 0 0 1 1h4a1 1 0 0 0 1-1v-9a1 1 0 0 0-1-1Z"
                            clip-rule="evenodd" />
                    </svg>
                    <div class="text-base">Mobile: 0995-0692238</div>
                </div>
    
                <div class="flex gap-4 mt-5">
                    <div @click="openMap(
                        '116 Timog Avenue, Cor. 11th Jamboree St. Quezon City, Metro Manila', 
                        'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3860.397973102159!2d121.03864787577389!3d14.633336276273031!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397b7ae25ad7b53%3A0x77777c3f265821a!2s116%20Timog%20Ave%2C%20Diliman%2C%20Quezon%20City%2C%201103%20Metro%20Manila!5e0!3m2!1sen!2sph!4v1743466923388!5m2!1sen!2sph'
                    )" class="flex text-tertiary gap-1 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                        </svg>
                        Get Directions
                    </div>
                    <div @click="openMap(
                        '116 Timog Avenue, Cor. 11th Jamboree St. Quezon City, Metro Manila', 
                        'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3860.397973102159!2d121.03864787577389!3d14.633336276273031!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397b7ae25ad7b53%3A0x77777c3f265821a!2s116%20Timog%20Ave%2C%20Diliman%2C%20Quezon%20City%2C%201103%20Metro%20Manila!5e0!3m2!1sen!2sph!4v1743466923388!5m2!1sen!2sph'
                    )" class="flex text-tertiary gap-1 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 6.75V15m6-6v8.25m.503 3.498 4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 0 0-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0Z" />
                        </svg>
                        View Full Map
                    </div>
                </div>
            </div>
    
            <div class="mt-10">
                <div class="font-bold text-xl">General Trias, Cavite Branch</div>
                <div class="flex gap-2">
                    <span>9002 Governor's Drive, Manggahan General Trias Cavite, 4107</span>
                </div>
                <div class="flex items-center gap-2 py-2 border-b border-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                        <path d="M7.25 11.5a.75.75 0 0 0 0 1.5h1.5a.75.75 0 0 0 0-1.5h-1.5Z" />
                        <path fill-rule="evenodd"
                            d="M6 1a2.5 2.5 0 0 0-2.5 2.5v9A2.5 2.5 0 0 0 6 15h4a2.5 2.5 0 0 0 2.5-2.5v-9A2.5 2.5 0 0 0 10 1H6Zm4 1.5h-.5V3a.5.5 0 0 1-.5.5H7a.5.5 0 0 1-.5-.5v-.5H6a1 1 0 0 0-1 1v9a1 1 0 0 0 1 1h4a1 1 0 0 0 1-1v-9a1 1 0 0 0-1-1Z"
                            clip-rule="evenodd" />
                    </svg>
                    <div class="text-base">Mobile: 09190041731 (Smart)</div>
                </div>
                <div class="flex items-center gap-2 py-2 border-b border-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                        <path d="M7.25 11.5a.75.75 0 0 0 0 1.5h1.5a.75.75 0 0 0 0-1.5h-1.5Z" />
                        <path fill-rule="evenodd"
                            d="M6 1a2.5 2.5 0 0 0-2.5 2.5v9A2.5 2.5 0 0 0 6 15h4a2.5 2.5 0 0 0 2.5-2.5v-9A2.5 2.5 0 0 0 10 1H6Zm4 1.5h-.5V3a.5.5 0 0 1-.5.5H7a.5.5 0 0 1-.5-.5v-.5H6a1 1 0 0 0-1 1v9a1 1 0 0 0 1 1h4a1 1 0 0 0 1-1v-9a1 1 0 0 0-1-1Z"
                            clip-rule="evenodd" />
                    </svg>
                    <div class="text-base">Mobile: 09178872168 (Globe)</div>
                </div>
    
                <div class="flex gap-4 mt-5">
                    <div @click="openMap(
                        '9002 Governors Drive, Manggahan General Trias Cavite, 4107', 
                        'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3866.3306506431663!2d120.90579407576992!3d14.292211084551184!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397d55830aaffff%3A0xec6886d1bd92ecfb!2s9002%20Governor\'s%20Dr%2C%20General%20Trias%2C%204107%20Cavite!5e0!3m2!1sen!2sph!4v1743466972465!5m2!1sen!2sph'
                    )" class="flex text-tertiary gap-1 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                        </svg>
                        Get Directions
                    </div>
                    <div @click="openMap(
                        '9002 Governors Drive, Manggahan General Trias Cavite, 4107', 
                        'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3866.3306506431663!2d120.90579407576992!3d14.292211084551184!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397d55830aaffff%3A0xec6886d1bd92ecfb!2s9002%20Governor\'s%20Dr%2C%20General%20Trias%2C%204107%20Cavite!5e0!3m2!1sen!2sph!4v1743466972465!5m2!1sen!2sph'
                    )" class="flex text-tertiary gap-1 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 6.75V15m6-6v8.25m.503 3.498 4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 0 0-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0Z" />
                        </svg>
                        View Full Map
                    </div>
                </div>
            </div>
    
            <div class="mt-10">
                <div class="font-bold text-xl">Manila Doctors Hospital, Ermita Manila Branch</div>
                <div class="flex gap-2">
                    <span>667 United Nations Ave., Ermita Manila</span>
                </div>
                <div class="flex items-center gap-2 py-2 border-b border-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                        <path fill-rule="evenodd"
                            d="m3.855 7.286 1.067-.534a1 1 0 0 0 .542-1.046l-.44-2.858A1 1 0 0 0 4.036 2H3a1 1 0 0 0-1 1v2c0 .709.082 1.4.238 2.062a9.012 9.012 0 0 0 6.7 6.7A9.024 9.024 0 0 0 11 14h2a1 1 0 0 0 1-1v-1.036a1 1 0 0 0-.848-.988l-2.858-.44a1 1 0 0 0-1.046.542l-.534 1.067a7.52 7.52 0 0 1-4.86-4.859Z"
                            clip-rule="evenodd" />
                    </svg>
                    <div class="text-base">Tel No: (+632) 8536 7873</div>
                </div>
                <div class="flex items-center gap-2 py-2 border-b border-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                        <path d="M7.25 11.5a.75.75 0 0 0 0 1.5h1.5a.75.75 0 0 0 0-1.5h-1.5Z" />
                        <path fill-rule="evenodd"
                            d="M6 1a2.5 2.5 0 0 0-2.5 2.5v9A2.5 2.5 0 0 0 6 15h4a2.5 2.5 0 0 0 2.5-2.5v-9A2.5 2.5 0 0 0 10 1H6Zm4 1.5h-.5V3a.5.5 0 0 1-.5.5H7a.5.5 0 0 1-.5-.5v-.5H6a1 1 0 0 0-1 1v9a1 1 0 0 0 1 1h4a1 1 0 0 0 1-1v-9a1 1 0 0 0-1-1Z"
                            clip-rule="evenodd" />
                    </svg>
                    <div class="text-base">Mobile: 09171620634 (Globe)</div>
                </div>
    
                <div class="flex gap-4 mt-5">
                    <div @click="openMap(
                        '667 United Nations Ave., Ermita Manila', 
                        'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3861.2964723387736!2d120.98008307577338!3d14.582174977526213!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397ca2685f8cfa1%3A0x66cb857209e513ca!2s667%20United%20Nations%20Ave%2C%20Ermita%2C%20Manila%2C%201000%20Metro%20Manila!5e0!3m2!1sen!2sph!4v1743467028109!5m2!1sen!2sph'
                    )" class="flex text-tertiary gap-1 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                        </svg>
                        Get Directions
                    </div>
                    <div @click="openMap(
                        '667 United Nations Ave., Ermita Manila', 
                        'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3861.2964723387736!2d120.98008307577338!3d14.582174977526213!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397ca2685f8cfa1%3A0x66cb857209e513ca!2s667%20United%20Nations%20Ave%2C%20Ermita%2C%20Manila%2C%201000%20Metro%20Manila!5e0!3m2!1sen!2sph!4v1743467028109!5m2!1sen!2sph'
                    )" class="flex text-tertiary gap-1 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 6.75V15m6-6v8.25m.503 3.498 4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 0 0-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0Z" />
                        </svg>
                        View Full Map
                    </div>
                </div>
            </div>
    
            <div class="mt-10">
                <div class="font-bold text-xl">Bucal - Calamba Kiosk, Laguna Branch</div>
                <div class="flex gap-2">
                    <span>Bucal Bypass Rd, Calamba, 4027 Laguna</span>
                </div>
                <div class="flex items-center gap-2 py-2 border-b border-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                        <path fill-rule="evenodd"
                            d="m3.855 7.286 1.067-.534a1 1 0 0 0 .542-1.046l-.44-2.858A1 1 0 0 0 4.036 2H3a1 1 0 0 0-1 1v2c0 .709.082 1.4.238 2.062a9.012 9.012 0 0 0 6.7 6.7A9.024 9.024 0 0 0 11 14h2a1 1 0 0 0 1-1v-1.036a1 1 0 0 0-.848-.988l-2.858-.44a1 1 0 0 0-1.046.542l-.534 1.067a7.52 7.52 0 0 1-4.86-4.859Z"
                            clip-rule="evenodd" />
                    </svg>
                    <div class="text-base">Tel No: 049-3003968</div>
                </div>
                <div class="flex items-center gap-2 py-2 border-b border-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                        <path d="M7.25 11.5a.75.75 0 0 0 0 1.5h1.5a.75.75 0 0 0 0-1.5h-1.5Z" />
                        <path fill-rule="evenodd"
                            d="M6 1a2.5 2.5 0 0 0-2.5 2.5v9A2.5 2.5 0 0 0 6 15h4a2.5 2.5 0 0 0 2.5-2.5v-9A2.5 2.5 0 0 0 10 1H6Zm4 1.5h-.5V3a.5.5 0 0 1-.5.5H7a.5.5 0 0 1-.5-.5v-.5H6a1 1 0 0 0-1 1v9a1 1 0 0 0 1 1h4a1 1 0 0 0 1-1v-9a1 1 0 0 0-1-1Z"
                            clip-rule="evenodd" />
                    </svg>
                    <div class="text-base">Mobile: 0960-2008633</div>
                </div>
                <div class="flex items-center gap-2 py-2 border-b border-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                        <path d="M7.25 11.5a.75.75 0 0 0 0 1.5h1.5a.75.75 0 0 0 0-1.5h-1.5Z" />
                        <path fill-rule="evenodd"
                            d="M6 1a2.5 2.5 0 0 0-2.5 2.5v9A2.5 2.5 0 0 0 6 15h4a2.5 2.5 0 0 0 2.5-2.5v-9A2.5 2.5 0 0 0 10 1H6Zm4 1.5h-.5V3a.5.5 0 0 1-.5.5H7a.5.5 0 0 1-.5-.5v-.5H6a1 1 0 0 0-1 1v9a1 1 0 0 0 1 1h4a1 1 0 0 0 1-1v-9a1 1 0 0 0-1-1Z"
                            clip-rule="evenodd" />
                    </svg>
                    <div class="text-base">Mobile: 0977-7955018</div>
                </div>
    
                <div class="flex gap-4 mt-5">
                    <div @click="openMap(
                        'Bucal Bypass Rd, Calamba, 4027 Laguna', 
                        'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3868.278456947958!2d121.15251067576864!3d14.178462087271072!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33bd63f187a47f83%3A0x2ffe83460cdc293b!2sBucal%20Bypass%20Rd%2C%20Calamba%2C%204027%20Laguna!5e0!3m2!1sen!2sph!4v1743467074919!5m2!1sen!2sph'
                    )" class="flex text-tertiary gap-1 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                        </svg>
                        Get Directions
                    </div>
                    <div @click="openMap(
                        'Bucal Bypass Rd, Calamba, 4027 Laguna', 
                        'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3868.278456947958!2d121.15251067576864!3d14.178462087271072!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33bd63f187a47f83%3A0x2ffe83460cdc293b!2sBucal%20Bypass%20Rd%2C%20Calamba%2C%204027%20Laguna!5e0!3m2!1sen!2sph!4v1743467074919!5m2!1sen!2sph'
                    )" class="flex text-tertiary gap-1 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 6.75V15m6-6v8.25m.503 3.498 4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 0 0-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0Z" />
                        </svg>
                        View Full Map
                    </div>
                </div>
            </div>
    
            <div class="mt-10">
                <div class="font-bold text-xl">Tagaytay Kiosk, Cavite Branch</div>
                <div class="flex gap-2">
                    <span>Santa Rosa - Tagaytay Rd, Tagaytay, Cavite</span>
                </div>
                <div class="flex items-center gap-2 py-2 border-b border-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                        <path d="M7.25 11.5a.75.75 0 0 0 0 1.5h1.5a.75.75 0 0 0 0-1.5h-1.5Z" />
                        <path fill-rule="evenodd"
                            d="M6 1a2.5 2.5 0 0 0-2.5 2.5v9A2.5 2.5 0 0 0 6 15h4a2.5 2.5 0 0 0 2.5-2.5v-9A2.5 2.5 0 0 0 10 1H6Zm4 1.5h-.5V3a.5.5 0 0 1-.5.5H7a.5.5 0 0 1-.5-.5v-.5H6a1 1 0 0 0-1 1v9a1 1 0 0 0 1 1h4a1 1 0 0 0 1-1v-9a1 1 0 0 0-1-1Z"
                            clip-rule="evenodd" />
                    </svg>
                    <div class="text-base">Mobile: 0917-1858772</div>
                </div>
                <div class="flex items-center gap-2 py-2 border-b border-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                        <path d="M7.25 11.5a.75.75 0 0 0 0 1.5h1.5a.75.75 0 0 0 0-1.5h-1.5Z" />
                        <path fill-rule="evenodd"
                            d="M6 1a2.5 2.5 0 0 0-2.5 2.5v9A2.5 2.5 0 0 0 6 15h4a2.5 2.5 0 0 0 2.5-2.5v-9A2.5 2.5 0 0 0 10 1H6Zm4 1.5h-.5V3a.5.5 0 0 1-.5.5H7a.5.5 0 0 1-.5-.5v-.5H6a1 1 0 0 0-1 1v9a1 1 0 0 0 1 1h4a1 1 0 0 0 1-1v-9a1 1 0 0 0-1-1Z"
                            clip-rule="evenodd" />
                    </svg>
                    <div class="text-base">Mobile: 0919-0052731</div>
                </div>
    
                <div class="flex gap-4 mt-5">
                    <div @click="openMap(
                        'Santa Rosa - Tagaytay Rd, Tagaytay, Cavite', 
                        'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3867.5888761378365!2d121.03314807576912!3d14.218834486308035!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33bd7d4402a5a155%3A0x318a6c53ddae598a!2sSanta%20Rosa%20-%20Tagaytay%20Rd!5e0!3m2!1sen!2sph!4v1743467290801!5m2!1sen!2sph'
                    )" class="flex text-tertiary gap-1 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                        </svg>
                        Get Directions
                    </div>
                    <div @click="openMap(
                        'Santa Rosa - Tagaytay Rd, Tagaytay, Cavite', 
                        'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3867.5888761378365!2d121.03314807576912!3d14.218834486308035!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33bd7d4402a5a155%3A0x318a6c53ddae598a!2sSanta%20Rosa%20-%20Tagaytay%20Rd!5e0!3m2!1sen!2sph!4v1743467290801!5m2!1sen!2sph'
                    )" class="flex text-tertiary gap-1 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 6.75V15m6-6v8.25m.503 3.498 4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 0 0-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0Z" />
                        </svg>
                        View Full Map
                    </div>
                </div>
            </div>
    
            <div class="mt-10">
                <div class="font-bold text-xl">Molino - Bacoor Kiosk, Cavite Branch</div>
                <div class="flex gap-2">
                    <span>Stall 69, Terminal Section, Bayanan Bacoor Cavite, 4102</span>
                </div>
                <div class="flex items-center gap-2 py-2 border-b border-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                        <path d="M7.25 11.5a.75.75 0 0 0 0 1.5h1.5a.75.75 0 0 0 0-1.5h-1.5Z" />
                        <path fill-rule="evenodd"
                            d="M6 1a2.5 2.5 0 0 0-2.5 2.5v9A2.5 2.5 0 0 0 6 15h4a2.5 2.5 0 0 0 2.5-2.5v-9A2.5 2.5 0 0 0 10 1H6Zm4 1.5h-.5V3a.5.5 0 0 1-.5.5H7a.5.5 0 0 1-.5-.5v-.5H6a1 1 0 0 0-1 1v9a1 1 0 0 0 1 1h4a1 1 0 0 0 1-1v-9a1 1 0 0 0-1-1Z"
                            clip-rule="evenodd" />
                    </svg>
                    <div class="text-base">Mobile: 09778428855</div>
                </div>
    
                <div class="flex gap-4 mt-5">
                    <div @click="openMap(
                        'Stall 69, Terminal Section, Bayanan Bacoor Cavite, 4102', 
                        'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15456.275788223656!2d120.95015205104683!3d14.423188932624603!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397d23e8f4611c7%3A0x2b2746d354b077af!2sBayanan%2C%20Bacoor%2C%20Cavite!5e0!3m2!1sen!2sph!4v1743467358566!5m2!1sen!2sph'
                    )" class="flex text-tertiary gap-1 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                        </svg>
                        Get Directions
                    </div>
                    <div @click="openMap(
                        'Stall 69, Terminal Section, Bayanan Bacoor Cavite, 4102', 
                        'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15456.275788223656!2d120.95015205104683!3d14.423188932624603!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397d23e8f4611c7%3A0x2b2746d354b077af!2sBayanan%2C%20Bacoor%2C%20Cavite!5e0!3m2!1sen!2sph!4v1743467358566!5m2!1sen!2sph'
                    )" class="flex text-tertiary gap-1 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 6.75V15m6-6v8.25m.503 3.498 4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 0 0-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0Z" />
                        </svg>
                        View Full Map
                    </div>
                </div>
            </div>
    
            <div class="mt-10">
                <div class="font-bold text-xl">Cash & Carry Makati, Kiosk Branch</div>
                <div class="flex gap-2">
                    <span>20 Filmore St., Palanan, Makati City, Metro Manila</span>
                </div>
                <div class="flex items-center gap-2 py-2 border-b border-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                        <path fill-rule="evenodd"
                            d="m3.855 7.286 1.067-.534a1 1 0 0 0 .542-1.046l-.44-2.858A1 1 0 0 0 4.036 2H3a1 1 0 0 0-1 1v2c0 .709.082 1.4.238 2.062a9.012 9.012 0 0 0 6.7 6.7A9.024 9.024 0 0 0 11 14h2a1 1 0 0 0 1-1v-1.036a1 1 0 0 0-.848-.988l-2.858-.44a1 1 0 0 0-1.046.542l-.534 1.067a7.52 7.52 0 0 1-4.86-4.859Z"
                            clip-rule="evenodd" />
                    </svg>
                    <div class="text-base">Fax No: (+632) 84757558</div>
                </div>
                <div class="flex items-center gap-2 py-2 border-b border-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                        <path d="M7.25 11.5a.75.75 0 0 0 0 1.5h1.5a.75.75 0 0 0 0-1.5h-1.5Z" />
                        <path fill-rule="evenodd"
                            d="M6 1a2.5 2.5 0 0 0-2.5 2.5v9A2.5 2.5 0 0 0 6 15h4a2.5 2.5 0 0 0 2.5-2.5v-9A2.5 2.5 0 0 0 10 1H6Zm4 1.5h-.5V3a.5.5 0 0 1-.5.5H7a.5.5 0 0 1-.5-.5v-.5H6a1 1 0 0 0-1 1v9a1 1 0 0 0 1 1h4a1 1 0 0 0 1-1v-9a1 1 0 0 0-1-1Z"
                            clip-rule="evenodd" />
                    </svg>
                    <div class="text-base">Mobile: 0917-1685485</div>
                </div>
                <div class="flex items-center gap-2 py-2 border-b border-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                        <path d="M7.25 11.5a.75.75 0 0 0 0 1.5h1.5a.75.75 0 0 0 0-1.5h-1.5Z" />
                        <path fill-rule="evenodd"
                            d="M6 1a2.5 2.5 0 0 0-2.5 2.5v9A2.5 2.5 0 0 0 6 15h4a2.5 2.5 0 0 0 2.5-2.5v-9A2.5 2.5 0 0 0 10 1H6Zm4 1.5h-.5V3a.5.5 0 0 1-.5.5H7a.5.5 0 0 1-.5-.5v-.5H6a1 1 0 0 0-1 1v9a1 1 0 0 0 1 1h4a1 1 0 0 0 1-1v-9a1 1 0 0 0-1-1Z"
                            clip-rule="evenodd" />
                    </svg>
                    <div class="text-base">Mobile: 0917-1685569</div>
                </div>
    
                <div class="flex gap-4 mt-5">
                    <div @click="openMap(
                        '20 Filmore St., Palanan, Makati City, Metro Manila', 
                        'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3861.70709056556!2d121.00315897577303!3d14.558735478098901!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397c973c4f8a031%3A0xbd50617da4c17ebc!2s20%20Filmore%20St%2C%20Makati%2C%20Metro%20Manila!5e0!3m2!1sen!2sph!4v1743467401036!5m2!1sen!2sph'
                    )" class="flex text-tertiary gap-1 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                        </svg>
                        Get Directions
                    </div>
                    <div @click="openMap(
                        '20 Filmore St., Palanan, Makati City, Metro Manila', 
                        'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3861.70709056556!2d121.00315897577303!3d14.558735478098901!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397c973c4f8a031%3A0xbd50617da4c17ebc!2s20%20Filmore%20St%2C%20Makati%2C%20Metro%20Manila!5e0!3m2!1sen!2sph!4v1743467401036!5m2!1sen!2sph'
                    )" class="flex text-tertiary gap-1 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 6.75V15m6-6v8.25m.503 3.498 4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 0 0-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0Z" />
                        </svg>
                        View Full Map
                    </div>
                </div>
            </div>
    
            <div class="mt-10">
                <div class="font-bold text-xl">Los Baños, Laguna Branch</div>
                <div class="flex gap-2">
                    <span>Unit A2, RLC Bldg, Manila S Rd, Los Baños, 4030 Laguna</span>
                </div>
                <div class="flex items-center gap-2 py-2 border-b border-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                        <path d="M7.25 11.5a.75.75 0 0 0 0 1.5h1.5a.75.75 0 0 0 0-1.5h-1.5Z" />
                        <path fill-rule="evenodd"
                            d="M6 1a2.5 2.5 0 0 0-2.5 2.5v9A2.5 2.5 0 0 0 6 15h4a2.5 2.5 0 0 0 2.5-2.5v-9A2.5 2.5 0 0 0 10 1H6Zm4 1.5h-.5V3a.5.5 0 0 1-.5.5H7a.5.5 0 0 1-.5-.5v-.5H6a1 1 0 0 0-1 1v9a1 1 0 0 0 1 1h4a1 1 0 0 0 1-1v-9a1 1 0 0 0-1-1Z"
                            clip-rule="evenodd" />
                    </svg>
                    <div class="text-base">Mobile: 0917-5380304</div>
                </div>
                <div class="flex items-center gap-2 py-2 border-b border-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                        <path d="M7.25 11.5a.75.75 0 0 0 0 1.5h1.5a.75.75 0 0 0 0-1.5h-1.5Z" />
                        <path fill-rule="evenodd"
                            d="M6 1a2.5 2.5 0 0 0-2.5 2.5v9A2.5 2.5 0 0 0 6 15h4a2.5 2.5 0 0 0 2.5-2.5v-9A2.5 2.5 0 0 0 10 1H6Zm4 1.5h-.5V3a.5.5 0 0 1-.5.5H7a.5.5 0 0 1-.5-.5v-.5H6a1 1 0 0 0-1 1v9a1 1 0 0 0 1 1h4a1 1 0 0 0 1-1v-9a1 1 0 0 0-1-1Z"
                            clip-rule="evenodd" />
                    </svg>
                    <div class="text-base">Mobile: 0917-8202989</div>
                </div>
    
                <div class="flex gap-4 mt-5">
                    <div @click="openMap(
                        'Unit A2, RLC Bldg, Manila S Rd, Los Baños, 4030 Laguna', 
                        'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3868.397138638728!2d121.19836638450244!3d14.171502352041937!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33bd61c5b370e6bf%3A0x4b5ad6a1a347ffbf!2sLydias%20Lechon%20Los%20Ba%C3%B1os%20-%20The%20Best%20Lechon%20in%20Manila!5e0!3m2!1sen!2sph!4v1743467533757!5m2!1sen!2sph'
                    )" class="flex text-tertiary gap-1 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                        </svg>
                        Get Directions
                    </div>
                    <div @click="openMap(
                        'Unit A2, RLC Bldg, Manila S Rd, Los Baños, 4030 Laguna', 
                        'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3868.397138638728!2d121.19836638450244!3d14.171502352041937!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33bd61c5b370e6bf%3A0x4b5ad6a1a347ffbf!2sLydias%20Lechon%20Los%20Ba%C3%B1os%20-%20The%20Best%20Lechon%20in%20Manila!5e0!3m2!1sen!2sph!4v1743467533757!5m2!1sen!2sph'
                    )" class="flex text-tertiary gap-1 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 6.75V15m6-6v8.25m.503 3.498 4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 0 0-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0Z" />
                        </svg>
                        View Full Map
                    </div>
                </div>
            </div>
        </div>

        <h2 class="text-3xl lg:text-7xl font-cubao font-medium text-primary text-center mt-10">sm food court outlets
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-10">
            <div class="mt-10">
                <div class="font-bold text-xl">Los Baños, Laguna Branch</div>
                <div class="flex gap-2">
                    <span>Unit A2, RLC Bldg, Manila S Rd, Los Baños, 4030 Laguna</span>
                </div>
                <div class="flex items-center gap-2 py-2 border-b border-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                        <path d="M7.25 11.5a.75.75 0 0 0 0 1.5h1.5a.75.75 0 0 0 0-1.5h-1.5Z" />
                        <path fill-rule="evenodd"
                            d="M6 1a2.5 2.5 0 0 0-2.5 2.5v9A2.5 2.5 0 0 0 6 15h4a2.5 2.5 0 0 0 2.5-2.5v-9A2.5 2.5 0 0 0 10 1H6Zm4 1.5h-.5V3a.5.5 0 0 1-.5.5H7a.5.5 0 0 1-.5-.5v-.5H6a1 1 0 0 0-1 1v9a1 1 0 0 0 1 1h4a1 1 0 0 0 1-1v-9a1 1 0 0 0-1-1Z"
                            clip-rule="evenodd" />
                    </svg>
                    <div class="text-base">Mobile: 0917-5380304</div>
                </div>
                <div class="flex items-center gap-2 py-2 border-b border-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                        <path d="M7.25 11.5a.75.75 0 0 0 0 1.5h1.5a.75.75 0 0 0 0-1.5h-1.5Z" />
                        <path fill-rule="evenodd"
                            d="M6 1a2.5 2.5 0 0 0-2.5 2.5v9A2.5 2.5 0 0 0 6 15h4a2.5 2.5 0 0 0 2.5-2.5v-9A2.5 2.5 0 0 0 10 1H6Zm4 1.5h-.5V3a.5.5 0 0 1-.5.5H7a.5.5 0 0 1-.5-.5v-.5H6a1 1 0 0 0-1 1v9a1 1 0 0 0 1 1h4a1 1 0 0 0 1-1v-9a1 1 0 0 0-1-1Z"
                            clip-rule="evenodd" />
                    </svg>
                    <div class="text-base">Mobile: 0917-8202989</div>
                </div>
    
                <div class="flex gap-4 mt-5">
                    <div @click="openMap(
                        'Unit A2, RLC Bldg, Manila S Rd, Los Baños, 4030 Laguna', 
                        'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3868.397138638728!2d121.19836638450244!3d14.171502352041937!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33bd61c5b370e6bf%3A0x4b5ad6a1a347ffbf!2sLydias%20Lechon%20Los%20Ba%C3%B1os%20-%20The%20Best%20Lechon%20in%20Manila!5e0!3m2!1sen!2sph!4v1743467533757!5m2!1sen!2sph'
                    )" class="flex text-tertiary gap-1 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                        </svg>
                        Get Directions
                    </div>
                    <div @click="openMap(
                        'Unit A2, RLC Bldg, Manila S Rd, Los Baños, 4030 Laguna', 
                        'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3868.397138638728!2d121.19836638450244!3d14.171502352041937!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33bd61c5b370e6bf%3A0x4b5ad6a1a347ffbf!2sLydias%20Lechon%20Los%20Ba%C3%B1os%20-%20The%20Best%20Lechon%20in%20Manila!5e0!3m2!1sen!2sph!4v1743467533757!5m2!1sen!2sph'
                    )" class="flex text-tertiary gap-1 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 6.75V15m6-6v8.25m.503 3.498 4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 0 0-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0Z" />
                        </svg>
                        View Full Map
                    </div>
                </div>
            </div>
    
            <div class="mt-10">
                <div class="font-bold text-xl">SM Sucat Branch</div>
                <div class="flex gap-2">
                    <span>SMFC, New Sucat Road, San Dionisio Sucat, Parañaque Metro Manila</span>
                </div>
                <div class="flex items-center gap-2 py-2 border-b border-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                        <path fill-rule="evenodd"
                            d="m3.855 7.286 1.067-.534a1 1 0 0 0 .542-1.046l-.44-2.858A1 1 0 0 0 4.036 2H3a1 1 0 0 0-1 1v2c0 .709.082 1.4.238 2.062a9.012 9.012 0 0 0 6.7 6.7A9.024 9.024 0 0 0 11 14h2a1 1 0 0 0 1-1v-1.036a1 1 0 0 0-.848-.988l-2.858-.44a1 1 0 0 0-1.046.542l-.534 1.067a7.52 7.52 0 0 1-4.86-4.859Z"
                            clip-rule="evenodd" />
                    </svg>
                    <div class="text-base">Fax No: (+632) 8829 4460</div>
                </div>
                <div class="flex gap-4 mt-5">
                    <div @click="openMap(
                        'SMFC, New Sucat Road, San Dionisio Sucat, Parañaque Metro Manila', 
                        'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3863.3099392982385!2d121.01285917577202!3d14.466883780335081!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397ce8559fc3823%3A0xe397efef85bd1e31!2sDr%20Arcadio%20Santos%20Ave%2C%20Metro%20Manila!5e0!3m2!1sen!2sph!4v1743467587477!5m2!1sen!2sph'
                    )" class="flex text-tertiary gap-1 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                        </svg>
                        Get Directions
                    </div>
                    <div @click="openMap(
                        'SMFC, New Sucat Road, San Dionisio Sucat, Parañaque Metro Manila', 
                        'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3863.3099392982385!2d121.01285917577202!3d14.466883780335081!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397ce8559fc3823%3A0xe397efef85bd1e31!2sDr%20Arcadio%20Santos%20Ave%2C%20Metro%20Manila!5e0!3m2!1sen!2sph!4v1743467587477!5m2!1sen!2sph'
                    )" class="flex text-tertiary gap-1 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 6.75V15m6-6v8.25m.503 3.498 4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 0 0-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0Z" />
                        </svg>
                        View Full Map
                    </div>
                </div>
            </div>
    
            <div class="mt-10">
                <div class="font-bold text-xl">SM Megamall Branch</div>
                <div class="flex gap-2">
                    <span>SMFC, Julia Vargas Ave., Corner EDSA, Mandaluyong City Metro Manila</span>
                </div>
                <div class="flex items-center gap-2 py-2 border-b border-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                        <path fill-rule="evenodd"
                            d="m3.855 7.286 1.067-.534a1 1 0 0 0 .542-1.046l-.44-2.858A1 1 0 0 0 4.036 2H3a1 1 0 0 0-1 1v2c0 .709.082 1.4.238 2.062a9.012 9.012 0 0 0 6.7 6.7A9.024 9.024 0 0 0 11 14h2a1 1 0 0 0 1-1v-1.036a1 1 0 0 0-.848-.988l-2.858-.44a1 1 0 0 0-1.046.542l-.534 1.067a7.52 7.52 0 0 1-4.86-4.859Z"
                            clip-rule="evenodd" />
                    </svg>
                    <div class="text-base">Fax No: (+632) 8633 4966</div>
                </div>
                <div class="flex gap-4 mt-5">
                    <div @click="openMap(
                        'SMFC, Julia Vargas Ave., Corner EDSA, Mandaluyong City Metro Manila', 
                        'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3861.265104589392!2d121.05125618454329!3d14.583964041145935!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397c83e0128a243%3A0xf64836253bea4489!2sEDSA%20Do%C3%B1a%20Julia%20Vargas%20Ave%2C%20Ortigas%20Center%2C%20Mandaluyong%2C%20Metro%20Manila!5e0!3m2!1sen!2sph!4v1743467637957!5m2!1sen!2sph'
                    )" class="flex text-tertiary gap-1 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                        </svg>
                        Get Directions
                    </div>
                    <div @click="openMap(
                        'SMFC, Julia Vargas Ave., Corner EDSA, Mandaluyong City Metro Manila', 
                        'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3861.265104589392!2d121.05125618454329!3d14.583964041145935!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397c83e0128a243%3A0xf64836253bea4489!2sEDSA%20Do%C3%B1a%20Julia%20Vargas%20Ave%2C%20Ortigas%20Center%2C%20Mandaluyong%2C%20Metro%20Manila!5e0!3m2!1sen!2sph!4v1743467637957!5m2!1sen!2sph'
                    )" class="flex text-tertiary gap-1 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 6.75V15m6-6v8.25m.503 3.498 4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 0 0-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0Z" />
                        </svg>
                        View Full Map
                    </div>
                </div>
            </div>
    
            <div class="mt-10">
                <div class="font-bold text-xl">SM San Lazaro Branch</div>
                <div class="flex gap-2">
                    <span>FS6, SM Food Court, Felix Huertas St., Corner A.H Lacson Ave., Brgy. 350 Zone 035, Sta. Cruz,
                        Manila</span>
                </div>
                <div class="flex items-center gap-2 py-2 border-b border-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                        <path fill-rule="evenodd"
                            d="m3.855 7.286 1.067-.534a1 1 0 0 0 .542-1.046l-.44-2.858A1 1 0 0 0 4.036 2H3a1 1 0 0 0-1 1v2c0 .709.082 1.4.238 2.062a9.012 9.012 0 0 0 6.7 6.7A9.024 9.024 0 0 0 11 14h2a1 1 0 0 0 1-1v-1.036a1 1 0 0 0-.848-.988l-2.858-.44a1 1 0 0 0-1.046.542l-.534 1.067a7.52 7.52 0 0 1-4.86-4.859Z"
                            clip-rule="evenodd" />
                    </svg>
                    <div class="text-base">Fax No: (+632) 8353 2637</div>
                </div>
                <div class="flex items-center gap-2 py-2 border-b border-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                        <path d="M7.25 11.5a.75.75 0 0 0 0 1.5h1.5a.75.75 0 0 0 0-1.5h-1.5Z" />
                        <path fill-rule="evenodd"
                            d="M6 1a2.5 2.5 0 0 0-2.5 2.5v9A2.5 2.5 0 0 0 6 15h4a2.5 2.5 0 0 0 2.5-2.5v-9A2.5 2.5 0 0 0 10 1H6Zm4 1.5h-.5V3a.5.5 0 0 1-.5.5H7a.5.5 0 0 1-.5-.5v-.5H6a1 1 0 0 0-1 1v9a1 1 0 0 0 1 1h4a1 1 0 0 0 1-1v-9a1 1 0 0 0-1-1Z"
                            clip-rule="evenodd" />
                    </svg>
                    <div class="text-base">Mobile: 0995-0692241</div>
                </div>
                <div class="flex gap-4 mt-5">
                    <div @click="openMap(
                        'FS6, SM Food Court, Felix Huertas St., Corner A.H Lacson Ave., Brgy. 350 Zone 035, Sta. Cruz, Manila', 
                        'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3860.6009963510114!2d120.98237047577389!3d14.621791276556115!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397b5e2dfcc456f%3A0x2ab2ffb42c1da5d!2sBRGY.%20350%20Zone%20350!5e0!3m2!1sen!2sph!4v1743467700201!5m2!1sen!2sph'
                    )" class="flex text-tertiary gap-1 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                        </svg>
                        Get Directions
                    </div>
                    <div @click="openMap(
                        'FS6, SM Food Court, Felix Huertas St., Corner A.H Lacson Ave., Brgy. 350 Zone 035, Sta. Cruz, Manila', 
                        'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3860.6009963510114!2d120.98237047577389!3d14.621791276556115!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397b5e2dfcc456f%3A0x2ab2ffb42c1da5d!2sBRGY.%20350%20Zone%20350!5e0!3m2!1sen!2sph!4v1743467700201!5m2!1sen!2sph'
                    )" class="flex text-tertiary gap-1 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 6.75V15m6-6v8.25m.503 3.498 4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 0 0-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0Z" />
                        </svg>
                        View Full Map
                    </div>
                </div>
            </div>
    
            <div class="mt-10">
                <div class="font-bold text-xl">Landmark, Makati Branch</div>
                <div class="flex gap-2">
                    <span>Stall No. 14 The Landmark Food Center, Ayala Center, San Lorenzo Makati City, Metro Manila</span>
                </div>
                <div class="flex items-center gap-2 py-2 border-b border-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                        <path d="M7.25 11.5a.75.75 0 0 0 0 1.5h1.5a.75.75 0 0 0 0-1.5h-1.5Z" />
                        <path fill-rule="evenodd"
                            d="M6 1a2.5 2.5 0 0 0-2.5 2.5v9A2.5 2.5 0 0 0 6 15h4a2.5 2.5 0 0 0 2.5-2.5v-9A2.5 2.5 0 0 0 10 1H6Zm4 1.5h-.5V3a.5.5 0 0 1-.5.5H7a.5.5 0 0 1-.5-.5v-.5H6a1 1 0 0 0-1 1v9a1 1 0 0 0 1 1h4a1 1 0 0 0 1-1v-9a1 1 0 0 0-1-1Z"
                            clip-rule="evenodd" />
                    </svg>
                    <div class="text-base">Mobile: 0917-1569616</div>
                </div>
                <div class="flex gap-4 mt-5">
                    <div @click="openMap(
                        'Stall No. 14 The Landmark Food Center, Ayala Center, San Lorenzo Makati City, Metro Manila', 
                        'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3861.846584506381!2d121.01660017577305!3d14.550764278293462!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397c9a9d9e1c98b%3A0x1818fb675e4f25e4!2sLydias%20Lechon%20Landmark%20Makati%20-%20The%20Best%20Lechon%20in%20Manila!5e0!3m2!1sen!2sph!4v1743467750319!5m2!1sen!2sph'
                    )" class="flex text-tertiary gap-1 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                        </svg>
                        Get Directions
                    </div>
                    <div @click="openMap(
                        'Stall No. 14 The Landmark Food Center, Ayala Center, San Lorenzo Makati City, Metro Manila', 
                        'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3861.846584506381!2d121.01660017577305!3d14.550764278293462!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397c9a9d9e1c98b%3A0x1818fb675e4f25e4!2sLydias%20Lechon%20Landmark%20Makati%20-%20The%20Best%20Lechon%20in%20Manila!5e0!3m2!1sen!2sph!4v1743467750319!5m2!1sen!2sph'
                    )" class="flex text-tertiary gap-1 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 6.75V15m6-6v8.25m.503 3.498 4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 0 0-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0Z" />
                        </svg>
                        View Full Map
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="py-20 px-4 bg-cream">
    <h2 class="text-2xl lg:text-7xl font-cubao font-medium text-tertiary text-center">your #everydaylechonhappiness</h2>
</div>

<x-footer-component />

<x-map-modal-component />

</div>



@endsection