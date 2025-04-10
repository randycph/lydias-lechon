<div >
    <!-- Drawer Overlay -->
    <div x-show="open" x-transition.opacity class="fixed inset-0 bg-black/50 z-40" @click="open = false"></div>

    <!-- Drawer Content -->
    <div x-show="open" x-transition:enter="transition-transform transform duration-300"
        x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
        x-transition:leave="transition-transform transform duration-300"
        x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
        class="fixed inset-0 bg-white text-black w-full h-full z-50 py-6 flex flex-col overflow-y-auto">

        <!-- Close Button -->
        <button @click="open = false" class="self-start text-2xl text-gray-800 px-6">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-7">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>
        </button>

        <!-- Drawer Content -->
        <div class="mt-10 space-y-4 text-left">

            <a href="#" class="block text-2xl font-medium font-cubao uppercase border-[#DFDFDF] text-primary pl-10">Hi, juan!</a>
            <ul class="flex flex-col gap-4 pl-10">
                <li class="uppercase font-light"><a href="{{ route('my-account') }}" class="link-tertiary">Manage account</a></li>
                <li class="uppercase font-light"><a href="{{ route('change-password') }}" class="link-tertiary">Change password</a></li>
                <li class="uppercase font-light"><a href="{{ route('order-history') }}" class="link-tertiary">Order History</a></li>
                <li class="uppercase font-light"><a href="{{ route('login') }}" class="link-tertiary">Sign out</a></li>
            </ul>

            <a href="{{ route('our-story') }}" class="hover:bg-tertiary hover:text-white block text-2xl font-medium font-cubao uppercase py-5 border-y border-[#DFDFDF] pl-10">Our Story</a>
            <a href="{{ route('our-stores') }}" class="hover:bg-tertiary hover:text-white block text-2xl font-medium font-cubao uppercase py-5 border-b border-[#DFDFDF] pl-10">Our Stores</a>
            <a href="{{ route('lechon-menu') }}" class="hover:bg-tertiary hover:text-white block text-2xl font-medium font-cubao uppercase py-5 border-b border-[#DFDFDF] pl-10">Menu</a>
            <a href="{{ route('lechon-pricelist') }}" class="hover:bg-tertiary hover:text-white block text-2xl font-medium font-cubao uppercase py-5 border-b border-[#DFDFDF] pl-10">Lechon Pricelist</a>
            <button @click="open = false; openHotline = true; " class="hover:bg-tertiary hover:text-white w-full text-left block text-2xl font-medium font-cubao uppercase py-5 border-b border-[#DFDFDF] pl-10">Hotline</button>
            <a href="{{ route('careers.v2') }}" class="hover:bg-tertiary hover:text-white block text-2xl font-medium font-cubao uppercase py-5 border-b border-[#DFDFDF] pl-10">Careers</a>
        </div>
    </div>
</div>