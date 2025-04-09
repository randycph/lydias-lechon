<nav 
x-data="{

    goback() {
        window.history.back();
    },

}"  class="fixed max-w-8xl top-5 left-1/2 transform -translate-x-[50%] w-[90%] bg-green-700 px-4 flex justify-between items-center z-40 rounded-full">
    @if (isset($page) && $page == 'checkout')
        <div class="text-white font-bold text-lg py-3">
            <a href="{{ route('index') }}">
                <img src="{{ asset('images/lydia-logo.png') }}" alt="Lydia's Logo" class="w-[60px]">
            </a>
        </div>
        <button @click="goback()" class="py-3">
            <div class="relative flex items-center text-white gap-2 text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 font-light">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 15.75 3 12m0 0 3.75-3.75M3 12h18" />
                </svg>
                <span class="uppercase font-light">Go back</span>
            </div>
        </button>
    @else
        <button @click="open = true" class="md:hidden py-3">
            <svg  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6 text-white cursor-pointer">
                <path fill-rule="evenodd" d="M3 6.75A.75.75 0 0 1 3.75 6h16.5a.75.75 0 0 1 0 1.5H3.75A.75.75 0 0 1 3 6.75ZM3 12a.75.75 0 0 1 .75-.75h16.5a.75.75 0 0 1 0 1.5H3.75A.75.75 0 0 1 3 12Zm0 5.25a.75.75 0 0 1 .75-.75h16.5a.75.75 0 0 1 0 1.5H3.75a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd" />
            </svg>
        </button>
        <div class="text-white font-bold text-lg py-3">
            <a href="{{ route('index') }}">
                <img src="{{ asset('images/lydia-logo.png') }}" alt="Lydia's Logo" class="w-[60px]">
            </a>
        </div>
        <div class="hidden md:flex text-white text-lg gap-4">
            <a href="{{ route('lechon-menu') }}" class="uppercase hover:bg-primary-dark py-4 px-2 transition-all duration-100">
                Menu
            </a>
            <a href="{{ route('lechon-pricelist') }}" class="uppercase hover:bg-primary-dark py-4 px-2 transition-all duration-100">
                Lechon Pricelist
            </a>
            <a href="{{ route('our-stores') }}" class="uppercase hover:bg-primary-dark py-4 px-2 transition-all duration-100">
                Stores
            </a>
            <a href="{{ route('blogs') }}" class="uppercase hover:bg-primary-dark py-4 px-2 transition-all duration-100">
                Blogs
            </a>
            <button @click="openHotline = true" class="uppercase hover:bg-primary-dark py-4 px-2 transition-all duration-100">
                Hotline
            </button>
        </div>
        <div class="flex">
            <button @click="toggleSearch()" aria-label="Search Lydia's lechon website" class="text-white" title="Search Lydia's lechon website">
                <div class="relative">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-10 text-white cursor-pointer hover:bg-primary-dark p-2 hover:rounded-full">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                </div>
            </button>
            <a href="{{ route('my-account') }}" class="text-white" aria-label="My Account" title="My Account">
                <div class="relative">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-10 text-white cursor-pointer hover:bg-primary-dark p-2 hover:rounded-full">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                </div>
            </a>
            <button @click="openCart = true" class="text-white" aria-label="Cart" title="Cart">
                <div class="relative">
                    <span class="absolute -top-1 -right-1 bg-secondary text-black text-xs px-2 py-2 w-4 h-4 rounded-full flex items-center justify-center">3</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-10 text-white cursor-pointer hover:bg-primary-dark p-2 hover:rounded-full">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                    </svg>
                </div>
            </button>
        </div>
    @endif

</nav>