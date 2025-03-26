{{-- Marketing popup --}}
<div >
    <!-- Drawer Overlay -->
    <div x-show="marketingPopup" x-transition.opacity class="fixed inset-0 bg-black/50 z-40" @click="marketingPopup = false"></div>

    <!-- Cart Drawer Content -->
    <div
    x-show="marketingPopup"
    x-cloak
    x-transition:enter="transition duration-300 ease-out"
    x-transition:enter-start="opacity-0 scale-90"
    x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="transition duration-200 ease-in"
    x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-90"
    class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[90%] max-w-[500px] lg:max-w-3xl h-max bg-white text-black z-50 flex flex-col shadow-lg rounded-lg"
  >
        <!-- Content -->
        <div class="w-full relative">
            <!-- Close Button -->
            <button @click="marketingPopup = false" class="absolute top-4 right-4 text-2xl text-gray-800">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class=" px-2 py-2 flex items-center justify-center bg-white rounded-full w-10 h-10 shadow-lg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>

            <div class="flex flex-col lg:flex-row">
                <div class="order-1 lg:order-2 overflow-hidden">
                    <img class="h-[300px] w-full object-cover object-top lg:h-full" src="{{ asset('images/marketing-img.png') }}" alt="Sign Up and Save 10% + Free Shipping on Your First Order!">
                </div>
    
                <div class="order-2 lg:order-1 py-10 px-5">
                    <div class="font-cubao text-3xl text-left text-primary px-6">
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
    </div>
</div>