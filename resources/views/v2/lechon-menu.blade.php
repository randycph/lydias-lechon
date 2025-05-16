@extends('layouts.guest')

@section('content')

    <div class="px-1 container"
        x-data="{ 
            goToAnchor(anchor) {
                const element = document.getElementById(anchor);
                if (element) {
                    const yOffset = -160;
                    const y = element.getBoundingClientRect().top + window.pageYOffset + yOffset;
                    window.scrollTo({ top: y, behavior: 'smooth' });
                }
            },
            product: null,
            show(product) {
                this.product = product;
                this.lechonCart = true;
                this.quantity = 1,
                console.log(product);
            },
            close() {
                this.lechonCart = false;
                this.product = null;
            },
            quantity: 1,
            get baseTotal() {
                return this.product?.price * this.quantity || 0;
            },
            addons: [],
            updateAddons() {
                if (!this.product?.addon_products) return;

                this.addons = this.product.addon_products
                    .filter(a => a.selected)
                    .map(a => ({
                        id: a.id,
                        qty: 1
                    }));
            },
            get addonsTotal() {
                if (!this.product?.addon_products) return 0;

                return this.product.addon_products
                    .filter(a => a.selected)
                    .reduce((sum, a) => sum + (a.price * this.quantity), 0);
            },
            get grandTotal() {
                return this.baseTotal + this.addonsTotal;
            },
            toggleAddon(index) {
                this.addons[index].selected = !this.addons[index].selected;
            },
            format(value) {
                return new Intl.NumberFormat('en-PH', {
                    style: 'currency',
                    currency: 'PHP'
                }).format(value);
            },
            setProduct(prod) {
                this.product = prod;
                this.quantity = 1;
                this.addons.forEach(a => a.selected = false);
                this.open = true;
            }
            
        }" 
        x-init="
            (() => {
                const params = new URLSearchParams(window.location.search);
        
                // 🔍 Log product param if exists
                const query = params.get('product');
                if (query) {
                    console.log('query:', query);
                }
        
                const anchor = params.get('s');
                if (anchor) {
                    setTimeout(() => goToAnchor(anchor), 100);
        
                    params.delete('s');
                    const newUrl = `${window.location.pathname}${params.toString() ? '?' + params.toString() : ''}`;
                    history.replaceState(null, '', newUrl);
                }
            })()
        "
    
    >
        <div class="pt-20 pb-5 px-4">
            <h3 class="font-medium uppercase text-center mt-10 lg:text-2xl text-base">LECHON AND BEYOND</h3>
            <h1 class="text-4xl lg:text-7xl font-cubao font-medium text-primary text-center">a menu made for every occassion</h1>
        </div>

        <div class="relative px-4 py-5 lg:py-10">
            <div class="swiper swiper-menus relative">
                <div class="swiper-wrapper">
                    @foreach ($categories as $category)
                    <div @click="goToAnchor('{{ $category->slug }}')" class="swiper-slide !flex items-center justify-center p-4 flex-col !w-[140px] h-[140px]">
                        <div class="bg-secondary p-2 rounded-lg items-center w-[140px] h-[140px] flex flex-col justify-center overflow-hidden">
                            <img src="{{ asset('images/category/'.$category['image']) }}" alt="Shop {{ $category->name }}" class="cursor-pointer hover:scale-125 transition duration-300">
                        </div>
                        <div class="font-semibold text-center mt-2">{{ $category->name }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
            <button class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-white text-black p-3 rounded-full z-10 swiper-button-prev-custom">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                </svg>
            </button>
            <button class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-white text-black p-3 rounded-full z-10 swiper-button-next-custom">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                </svg>
            </button>
        </div>

        @foreach ($categories as $category)
            <div class="mt-5 pb-5 font-cubao font-medium text-4xl lg:text-5xl text-primary px-4" id="{{ $category->slug }}">
                {{ $category->name }}
            </div>
            {{-- style="background-image: url('{{ asset('images/checkout-bg.png') }}')" --}}
            <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-4 px-2 lechons pb-10">
                @foreach ($category->products as $list)

                <div class="bg-white shadow-md  rounded-lg border-primary border lechon flex flex-col justify-between" 
                    x-init="
                        const product_slug = '{{ $list->slug }}';
                        const params = new URLSearchParams(window.location.search);
                        const slug = params.get('product');
                        if (slug && slug == product_slug) {
                            show(@js($list));
                        }
                    ">
                    <div class="p-2 flex flex-col justify-between h-full">
                        <div  class="object-cover overflow-hidden m-2 rounded-md lg:rounded-lg bg-center">
                            @if ($list->photos->count() > 0)
                                    @foreach($list->photos as $photo)
                                        @if ($photo->path)
                                            <img onerror="this.onerror=null;this.src='{{ asset('images/no-image.jpg') }}';" src="{{ asset('storage/products/' . $photo->path) }}" alt="{{ $list->name }}" class="px-4 scale-110">
                                        @else
                                            <img src="{{ asset('images/no-image.jpg') }}" alt="{{ $list->name }}" class="px-4 scale-110">
                                        @endif
                                    @endforeach
                                @else
                                <img src="{{ asset('images/no-image.jpg') }}" alt="{{ $list->name }}" class="px-4 scale-110">
                            @endif
                        </div>
                        <div class="mt-4 px-2">
                            <div class="text-primary text-base lg:text-2xl font-bold mt-2">₱{{ number_format($list->price, 2) }}</div>
                            <h2 class="text-left text-sm lg:text-xl mt-1 uppercase">{{ $list->name }}</h2>
                        </div>
                    </div>
                    
                    <div class="mt-4 border-t border-primary ">
                        <button @click="show(@js($list));" class="text-primary px-4 py-3 lg:py-5 w-full custom-btn btn-primary text-base lg:text-xl">Add to Cart</button>
                    </div>
                </div>
                @endforeach
            </div>
        @endforeach
    
        <x-lechon-cart-component />

    </div>
    
    <x-footer-component />

@endsection
