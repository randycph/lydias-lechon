<div class="relative container">
    <div class="products grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 px-4">
        @if(!empty($categories))
        @foreach ($categories as $category)
            <div class="product pb-12 px-4">
                <a href="menu?s={{ $category->slug }}" class="cursor-pointer relative bg-orange-500 rounded-md w-full h-[300px] p-2 flex justify-center clip-bottom items-center">
                    <img onerror="this.onerror=null;this.src='{{ asset('images/no-image.jpg') }}'" src="{{ asset('images/category/'.$category['image']) }}" alt="Shop {{ $category->name }}" class="scale-110 md:scale-125 top-0 left-0 px-10 overflow-hidden hover:scale-150 transition-all duration-300 ease-in-out">
                </a>
                <div class="flex justify-between mt-4">
                    <div class="font-bold">{{ $category->name }}</div>
                    <a href="menu?s={{ $category->slug }}" class="bg-primary custom-btn btn-primary-dark text-white rounded-md px-4 py-2 flex items-center justify-center text-sm">Shop Now</a>
                </div>
            </div>
        @endforeach
    @endif
    </div>
</div>