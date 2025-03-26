<!-- Fixed Bottom Navigation -->
<footer class="fixed bottom-1 left-1/2 transform -translate-x-[50%] w-[90%] bg-tertiary text-white px-6 py-3 flex md:hidden justify-between items-center z-30 rounded-full">
    <a href="{{ route('lechon-menu') }}" class="text-center">MENU</a>
    <a href="{{ route('lechon-pricelist') }}" class="text-center">LECHON PRICELIST</a>
    <button @click="openHotline = true" class="text-center">HOTLINE</button>
</footer>