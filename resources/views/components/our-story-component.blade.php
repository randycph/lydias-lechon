{{-- Our story --}}
<div class="relative" style="background-image: url('{{ asset('images/our-story-bg.png') }}'); background-size: cover; background-position: center;">
    <!-- Green Overlay -->
    <div class="absolute inset-0 bg-primary-dark opacity-80"></div>
    
    <div class="relative container flex flex-col lg:flex-row gap-2 lg:gap-10 py-16 px-4">
        <div style="background-image: url('{{ asset('images/lydia-store-img.png') }}'); background-size: cover; background-position: center;" 
            class="relative w-full lg:w-1/2 max-w-[560px] h-[300px] lg:h-[720px] rounded-lg overflow-hidden shadow-lg order-1 lg:order-2">
            <div class="absolute inset-0 bg-primary-dark opacity-50"></div>
            <img src="{{ asset('images/lydia-portrait.png') }}" alt="Lydia Our story" class="absolute -bottom-[12rem] lg:-bottom-40 -right-2 lg:right-0 w-[320px] lg:w-auto h-[500px] lg:h-auto object-cover ">
        </div>
        <div class="order-2 lg:order-1 flex flex-col items-start text-left text-white gap-5 lg:w-1/2 w-full">
            <h3 class="font-medium mt-8 text-base lg:text-3xl uppercase">Our Story</h3>
            <h2 class="text-4xl lg:text-6xl  font-cubao font-medium">60 Years of Bringing Filipino Flavors to Life!</h2>
            <p class="font-light">Lydia De Roca and her husband Benigno started Lydia’s Lechon in Baclaran in the 1960s with a dream to serve delicious, flavorful lechon to every Filipino home. Their iconic boneless lechon stuffed with seafood paella became an instant favorite, creating a brand that now has over 25 stores. Through their dedication, Lydia and Benigno created more than just a dish—they brought joy and tradition to every Filipino table, one delicious bite at a time. Today, Lydia’s Lechon continues to honor their legacy, bringing the joy of Filipino cooking to every meal, whether for grand celebrations or simple everyday feasts. With each bite, we celebrate 60 years of passion, tradition, and happiness.</p>
            <a href="{{ route('our-story') }}" class="text-white bg-secondary rounded-md px-6 py-3 mt-3 font-medium">Read More</a>
        </div>
    </div>
</div>