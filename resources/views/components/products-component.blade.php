<div class="relative container">
    {{-- Product Section --}}
    <div class="px-4">
        <div class="flex flex-col items-center text-center py-10 gap-6">
            <h2 class="text-4xl font-cubao font-medium text-primary">You've Never Had Lechon Like Lydia's</h2>
            <p>For 60 years, Lydia’s Lechon has perfected the art of roasting—whether it’s a grand feast or a simple craving, we’ve got the best lechon for every occasion.</p>
            <button class="custom-btn btn-primary-dark text-white bg-primary mx-auto rounded-md px-6 py-3 mt-3 font-medium">View Menu</button>
        </div>
    </div>
    @php
        $products = [
            [
                'title' => 'Shop Whole Lechon',
                'image' => 'product1.png',
            ],
            [
                'title' => 'Shop Lechon-In-A-Box',
                'image' => 'product2.png',
            ],
            [
                'title' => 'Shop Lydia’s Family Box',
                'image' => 'product3.png',
            ],
            [
                'title' => 'Shop Party Trays',
                'image' => 'product4.png',
            ],
            [
                'title' => 'Shop Lechon Espesyal',
                'image' => 'product5.png',
            ],
            [
                'title' => 'Shop Bento Box',
                'image' => 'product6.png',
            ],
            [
                'title' => 'Shop Pampagana',
                'image' => 'product7.png',
            ],
        ];
    @endphp
    <div class="products grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 px-4">
        @foreach ($products as $product)
        <div class="product pb-12 px-4">
            <a href="{{ route('lechon-menu') }}" class="cursor-pointer relative bg-orange-500 rounded-md w-full h-[300px] p-2 flex justify-center clip-bottom  items-center">
                <img src="{{ asset('images/'.$product['image']) }}" alt="Shop Whole Lechon" class="scale-110 md:scale-125 top-0 left-0 px-10 overflow-hidden hover:scale-150 transition-all duration-300 ease-in-out">
            </a>
            <div class="flex justify-between mt-4">
                <div class="font-bold">{{ $product['title']}}</div>
                <a href="{{ route('lechon-menu') }}" class="bg-primary custom-btn btn-primary-dark text-white rounded-md px-4 py-2 flex items-center justify-center text-sm">Shop Now</a>
            </div>
        </div>
        @endforeach
    </div>
</div>