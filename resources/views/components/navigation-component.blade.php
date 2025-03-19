<nav  class="fixed top-5 left-1/2 transform -translate-x-1/2 w-[90%] bg-green-700 px-4 py-3 flex justify-between items-center z-50 rounded-full">
    <button @click="open = true">
        <svg  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6 text-white cursor-pointer">
            <path fill-rule="evenodd" d="M3 6.75A.75.75 0 0 1 3.75 6h16.5a.75.75 0 0 1 0 1.5H3.75A.75.75 0 0 1 3 6.75ZM3 12a.75.75 0 0 1 .75-.75h16.5a.75.75 0 0 1 0 1.5H3.75A.75.75 0 0 1 3 12Zm0 5.25a.75.75 0 0 1 .75-.75h16.5a.75.75 0 0 1 0 1.5H3.75a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd" />
        </svg>
    </button>
    <div class="text-white font-bold text-lg">
        <a href="{{ route('index') }}">
            <img src="{{ asset('images/lydia-logo.png') }}" alt="Lydia's Logo" class="w-[60px]">
        </a>
    </div>
    <button @click="openCart = true">
        <div class="relative">
            <span class="absolute -top-1 -right-1 bg-secondary text-black text-xs px-2 py-2 w-4 h-4 rounded-full flex items-center justify-center">3</span>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 text-white cursor-pointer">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
            </svg>
        </div>
    </button>
</nav>