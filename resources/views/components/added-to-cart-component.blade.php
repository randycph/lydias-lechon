<div 
    x-transition:enter="transition duration-300 ease-out"
    x-transition:enter-start="opacity-0 scale-90"
    x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="transition duration-200 ease-in"
    x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-90"
    x-show="addedToCart"
    x-cloak 
    class="fixed top-20 lg:right-28 right-4 my-4 z-[60]">
    <div class="rounded-full bg-white shadow-lg px-10 py-4 flex justify-center gap-2 items-center">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-8 p-2 bg-[#CFEDD6] rounded-full text-[#28A745]">
            <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" />
        </svg>
          
        <div class="font-semibold">
            Product has been added to cart
        </div>
    </div>
</div>