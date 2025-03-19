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