@extends('layouts.guest')

@section('content')

@php
$lists = [
    [
        'image' => 'petite.png',
        'name' => 'Petite',
        'price' => '₱9,800',
        'description' => 'Good for 8-10 persons',
        'weight' => 'Cooked Weight Approx. 3-4Kgs',
        'serving' => 'Approximate Serving 1.5ft',
        'add' => 'Add ₱2,800 for Boneless Lechon Stuffed with Seafood Paella'
    ],
    [
        'image' => 'chochinillo.png',
        'name' => 'COCHINILLO',
        'price' => '₱10,800',
        'description' => 'Good for 8-10 persons',
        'weight' => 'Cooked Weight Approx. 3-4Kgs',
        'serving' => 'Approximate Serving 2ft',
        'free' => 'FREE Mexican Flavored Rice'
    ],
    [
        'image' => 'deleche.png',
        'name' => 'De leche',
        'price' => '₱12,800',
        'description' => 'Good for 12-15 persons',
        'weight' => 'Cooked Weight Approx. 5-6Kgs',
        'serving' => 'Approximate Serving 2.5ft',
        'add' => 'Add ₱2,800 for Boneless Lechon Stuffed with Seafood Paella'
    ],
    [
        'image' => 'small.png',
        'name' => 'Small',
        'price' => '₱9,800',
        'description' => 'Good for 20-25 persons',
        'weight' => 'Cooked Weight Approx. 8-11Kgs',
        'serving' => 'Approximate Serving 3ft',
        'add' => 'Add ₱4,800 for Boneless Lechon Stuffed with Seafood Paella'
    ],
    [
        'image' => 'medium.png',
        'name' => 'Medium',
        'price' => '₱17,800',
        'description' => 'Good for 30-45 persons',
        'weight' => 'Cooked Weight Approx. 12-15Kgs',
        'serving' => 'Approximate Serving 3.5ft',
        'add' => 'Add ₱5,800 for Boneless Lechon stuffed with Seafood Paella'
    ],
    [
        'image' => 'large.png',
        'name' => 'Large',
        'price' => '₱21,800',
        'description' => 'Good for 40-50 persons',
        'weight' => 'Cooked Weight Approx. 16-20Kgs',
        'serving' => 'Approximate Serving 3.5-4ft',
        'add' => 'Add ₱6,800 for Boneless Lechon stuffed with Seafood Paella'
    ],
    [
        'image' => 'xlarge.png',
        'name' => 'X-Large',
        'price' => '₱24,800',
        'description' => 'Good for 60-70 persons',
        'weight' => 'Cooked Weight Approx. 21-25Kgs',
        'serving' => 'Approximate Serving 4ft',
        'add' => 'Add ₱7,800 for Boneless Lechon stuffed with Seafood Paella'
    ],
    [
        'image' => 'jumbo.png',
        'name' => 'Jumbo',
        'price' => '₱30,800',
        'description' => 'Good for 100-120 persons',
        'weight' => 'Cooked Weight Approx. 26-30Kgs',
        'serving' => 'Approximate Serving 4ft-4.5ft',
        'add' => 'Add ₱8,800 for Boneless Lechon stuffed with Seafood Paella'
    ],
    [
        'image' => 'lechonbaka.png',
        'name' => 'Lechon Baka',
        'price' => '₱65,800',
        'description' => 'Good for 150-200 persons',
        'weight' => 'Cooked Weight Approx. 26-30Kgs',
        'serving' => 'Live Weight 100-120Kgs',
        'add' => 'Add ₱3,500 for Service Fee Of Delivery and Reheating'
    ]
];
@endphp

    <div x-data="{ lechonCart: false }" class="px-1">
        <div class="pt-20 pb-5 px-4">
            <h3 class="font-medium uppercase text-center mt-10">LECHON AND BEYOND</hh3>
            <h1 class="text-4xl font-cubao font-medium text-primary text-center">a menu made for every occassion</h1>
        </div>

        @php
            $products = [
                [
                    'name' => 'Pork BBQ',
                    'image' => 'pork-bbq.png',
                ],
                [   
                    'name' => 'Whole Lechon',
                    'image' => 'whole-lechon.png',
                ],
                [
                    'name' => 'Lechon Espesyal',
                    'image' => 'lechon1kg.png',
                ],
                [
                    'name' => 'Lydias Family Boxes',
                    'image' => 'family-box-2.png',
                ],
                [
                    'name' => 'Lechon-In-A-Box',
                    'image' => 'lechon-in-a-box.png',
                ],
                [
                    'name' => 'Party Trays',
                    'image' => 'party-trays.png',
                ],
                [
                    'name' => 'Lechon Quick Meals',
                    'image' => 'bopis.png',
                ],
                [
                    'name' => 'Lydias Bento Meals',
                    'image' => 'bento-b.png',
                ],
                [
                    'name' => 'Pampagana',
                    'image' => 'chicharon-bulaklak.png',
                ],
                [
                    'name' => 'Meaty Espesyal',
                    'image' => 'bopis.png',
                ],
                [
                    'name' => 'Lechon Humba',
                    'image' => 'lechon-humba.png',
                ],
                [
                    'name' => 'Gulay ATBP.',
                    'image' => 'chapsauey.png',
                ],
                [
                    'name' => 'Yamang Dagat',
                    'image' => 'sinigang-salmon.png',
                ],
                [
                    'name' => 'Meryeda',
                    'image' => 'pancit-con-lechon.png',
                ],
                [
                    'name' => 'Kanin',
                    'image' => 'rice-platter.png',
                ],
                [
                    'name' => 'Mga Inumin',
                    'image' => 'mango-cooler.png',
                ]
            ];
        @endphp

        <div class="relative px-4">
            <div class="swiper swiper-menus relative">
                <div class="swiper-wrapper">
                    @foreach ($products as $product)
                    <div class="swiper-slide !flex items-center justify-center p-4 flex-col !w-[140px] h-[140px]">
                        <div class="bg-secondary p-2 rounded-lg items-center w-[140px] h-[140px] flex flex-col justify-center overflow-hidden">
                            <img src="{{ asset('images/' . $product['image']) }}" alt="Anniversary Give-Back Promo" class="scale-125">
                        </div>
                        <div class="font-semibold text-center mt-2">{{ $product['name'] }}</div>
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

        <div class="mt-5 pb-5 font-cubao font-medium text-4xl text-primary px-4">
            whole lechon
        </div>
    
        <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-4 px-2 lechons pb-10">
            @foreach ($lists as $list)
            <div class="bg-white shadow-md rounded-lg border-primary border lechon">
                <div style="background-image: url('{{ asset('images/checkout-bg.png') }}')" class="object-cover overflow-hidden m-2 rounded-md bg-center">
                    <img src="{{ asset('images/' .  $list['image']) }}" alt="{{ $list['name'] }}" class="px-4 scale-125">
                </div>
                <div class="mt-4 px-2">
                    <div class="text-primary text-base font-bold mt-2">{{ $list['price'] }}</div>
                    <h2 class="text-left text-sm mt-1 uppercase">{{ $list['name'] }}</h2>
                </div>
                <div class="mt-4 border-t border-primary ">
                    <button @click="lechonCart = true" class="text-primary text-sm px-4 py-3 w-full hover:bg-primary hover:text-white">Add to Cart</button>
                </div>
            </div>
            @endforeach
        </div>

        @php
            $boxes = [
                [
                    'image' => 'box1.png',
                    'name' => 'lechon-in-a-box at pancit',
                    'price' => '₱2,300.00',
                ],
                [
                    'image' => 'box2.png',
                    'name' => 'lechon-in-a-box MINI (1.5kls)',
                    'price' => '₱2,400.00',
                ],
                [
                    'image' => 'box3.png',
                    'name' => 'lechon-in-a-box at fresh lumpia',
                    'price' => '₱2,500.00',
                ],
                [
                    'image' => 'box4.png',
                    'name' => 'lechon-in-a-box At seafood paella',
                    'price' => '₱2,520.00',
                ],
                [
                    'image' => 'box5.png',
                    'name' => 'lechon-in-a-box (2kls)',
                    'price' => '₱3,200.00',
                ]
            ];
        @endphp

        <div class="mt-5 pb-5 font-cubao font-medium text-4xl text-primary px-4">
            lechon-in-a-box
        </div>

        <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-4 px-2 lechons pb-10">
            @foreach ($boxes as $box)
            <div class="bg-white shadow-md rounded-lg border-primary border lechon">
                <div style="background-image: url('{{ asset('images/checkout-bg.png') }}')" class="object-cover overflow-hidden m-2 rounded-md bg-center">
                    <img src="{{ asset('images/' .  $box['image']) }}" alt="{{ $box['name'] }}" class="px-4 scale-125">
                </div>
                <div class="mt-4 px-2">
                    <div class="text-primary text-base font-bold mt-2">{{ $box['price'] }}</div>
                    <h2 class="text-left text-sm mt-1 uppercase">{{ $box['name'] }}</h2>
                </div>
                <div class="mt-4 border-t border-primary ">
                    <button @click="lechonCart = true" class="text-primary text-sm px-4 py-3 w-full hover:bg-primary hover:text-white">Add to Cart</button>
                </div>
            </div>
            @endforeach
        </div>

        @php
            $familyboxes = [
                [
                    'image' => 'family-box1.png',
                    'name' => 'Family box a',
                    'price' => '₱1,500.00',
                ],
                [
                    'image' => 'family-box2.png',
                    'name' => 'Family box b',
                    'price' => '₱1,650.00',
                ],
            ];
        @endphp

        <div class="mt-5 pb-5 font-cubao font-medium text-4xl text-primary px-4">
            Lydia’s family boxes
        </div>

        <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-4 px-2 lechons pb-10">
            @foreach ($familyboxes as $box)
            <div class="bg-white shadow-md rounded-lg border-primary border lechon">
                <div style="background-image: url('{{ asset('images/checkout-bg.png') }}')" class="object-cover overflow-hidden m-2 rounded-md bg-center">
                    <img src="{{ asset('images/' .  $box['image']) }}" alt="{{ $box['name'] }}" class="px-4 scale-125">
                </div>
                <div class="mt-4 px-2">
                    <div class="text-primary text-base font-bold mt-2">{{ $box['price'] }}</div>
                    <h2 class="text-left text-sm mt-1 uppercase">{{ $box['name'] }}</h2>
                </div>
                <div class="mt-4 border-t border-primary ">
                    <button @click="lechonCart = true" class="text-primary text-sm px-4 py-3 w-full hover:bg-primary hover:text-white">Add to Cart</button>
                </div>
            </div>
            @endforeach
        </div>
        
        @php
            $specials = [
                [
                    'image' => 'sepcial1.png',
                    'name' => 'lechon paksiw',
                    'price' => '₱395.00',
                ],
                [
                    'image' => 'special2.png',
                    'name' => 'Lechon sisig',
                    'price' => '₱395.00',
                ],
                [
                    'image' => 'special3.png',
                    'name' => '1/4 kilo lechon',
                    'price' => '₱400.00',
                ],
                [
                    'image' => 'special4.png',
                    'name' => 'lechon sinigang',
                    'price' => '₱650.00',
                ],
                [
                    'image' => 'special5.png',
                    'name' => '1/2 kilo lechon',
                    'price' => '₱800.00',
                ],
                [
                    'image' => 'special6.png',
                    'name' => '1 kilo lechon',
                    'price' => '₱1,600.00',
                ]
            ];
        @endphp

        <div class="mt-5 pb-5 font-cubao font-medium text-4xl text-primary px-4">
            lechon espesyal
        </div>

        <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-4 px-2 lechons pb-10">
            @foreach ($specials as $box)
            <div class="bg-white shadow-md rounded-lg border-primary border lechon">
                <div style="background-image: url('{{ asset('images/checkout-bg.png') }}')" class="object-cover overflow-hidden m-2 rounded-md bg-center">
                    <img src="{{ asset('images/' .  $box['image']) }}" alt="{{ $box['name'] }}" class="px-4 scale-125">
                </div>
                <div class="mt-4 px-2">
                    <div class="text-primary text-base font-bold mt-2">{{ $box['price'] }}</div>
                    <h2 class="text-left text-sm mt-1 uppercase">{{ $box['name'] }}</h2>
                </div>
                <div class="mt-4 border-t border-primary ">
                    <button @click="lechonCart = true" class="text-primary text-sm px-4 py-3 w-full hover:bg-primary hover:text-white">Add to Cart</button>
                </div>
            </div>
            @endforeach
        </div>
        
        @php
            $trays = [
                [
                    'image' => 'party1.png',
                    'name' => 'pork bbq tray',
                    'price' => '₱1,150.00',
                ],
                [
                    'image' => 'party2.png',
                    'name' => 'fresh lumpia tray',
                    'price' => '₱1,600.00',
                ],
                [
                    'image' => 'party3.png',
                    'name' => 'pancit con lechon tray a',
                    'price' => '₱2,050.00',
                ],
                [
                    'image' => 'party4.png',
                    'name' => 'pancit con lechon tray b',
                    'price' => '₱2,150.00',
                ],
                [
                    'image' => 'party5.png',
                    'name' => 'bopis tray',
                    'price' => '₱2,650.00',
                ],
                [
                    'image' => 'party6.png',
                    'name' => 'lechon paksiw tray',
                    'price' => '₱2,650.00',
                ],
                [
                    'image' => 'party7.png',
                    'name' => 'pork dinuguan tray',
                    'price' => '₱2,650.00',
                ],
                [
                    'image' => 'party8.png',
                    'name' => 'seafood paella tray',
                    'price' => '₱4,250.00',
                ]
            ];
        @endphp

        <div class="mt-5 pb-5 font-cubao font-medium text-4xl text-primary px-4">
            party trays
        </div>

        <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-4 px-2 lechons pb-10">
            @foreach ($trays as $tray)
            <div class="bg-white shadow-md rounded-lg border-primary border lechon">
                <div style="background-image: url('{{ asset('images/checkout-bg.png') }}')" class="object-cover overflow-hidden m-2 rounded-md bg-center">
                    <img src="{{ asset('images/' .  $tray['image']) }}" alt="{{ $tray['name'] }}" class="px-4 scale-125">
                </div>
                <div class="mt-4 px-2">
                    <div class="text-primary text-base font-bold mt-2">{{ $tray['price'] }}</div>
                    <h2 class="text-left text-sm mt-1 uppercase">{{ $tray['name'] }}</h2>
                </div>
                <div class="mt-4 border-t border-primary ">
                    <button @click="lechonCart = true" class="text-primary text-sm px-4 py-3 w-full hover:bg-primary hover:text-white">Add to Cart</button>
                </div>
            </div>
            @endforeach
        </div>
        
        @php
            $trays = [
                [
                    'image' => 'quick1.png',
                    'name' => 'lechon + pakbet',
                    'price' => '₱288.00',
                ],
                [
                    'image' => 'quick2.png',
                    'name' => 'lechon + chopseuy',
                    'price' => '₱288.00',
                ],
                [
                    'image' => 'quick3.png',
                    'name' => 'lechon + bopis',
                    'price' => '₱350.00',
                ],
                [
                    'image' => 'quick4.png',
                    'name' => 'lechon + lechon paksiw',
                    'price' => '₱350.00',
                ],
                [
                    'image' => 'quick5.png',
                    'name' => 'lechon + dinuguan',
                    'price' => '₱350.00',
                ],
                [
                    'image' => 'quick6.png',
                    'name' => 'lechon + kare-kare',
                    'price' => '₱350.00',
                ]
            ];
        @endphp

        <div class="mt-5 pb-5 font-cubao font-medium text-4xl text-primary px-4">
            lechon quick meals
        </div>

        <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-4 px-2 lechons pb-10">
            @foreach ($trays as $tray)
            <div class="bg-white shadow-md rounded-lg border-primary border lechon">
                <div style="background-image: url('{{ asset('images/checkout-bg.png') }}')" class="object-cover overflow-hidden m-2 rounded-md bg-center">
                    <img src="{{ asset('images/' .  $tray['image']) }}" alt="{{ $tray['name'] }}" class="px-4 scale-125">
                </div>
                <div class="mt-4 px-2">
                    <div class="text-primary text-base font-bold mt-2">{{ $tray['price'] }}</div>
                    <h2 class="text-left text-sm mt-1 uppercase">{{ $tray['name'] }}</h2>
                </div>
                <div class="mt-4 border-t border-primary ">
                    <button @click="lechonCart = true" class="text-primary text-sm px-4 py-3 w-full hover:bg-primary hover:text-white">Add to Cart</button>
                </div>
            </div>
            @endforeach
        </div>
        
        @php
            $bentos = [
                [
                    'image' => 'bento1.png',
                    'name' => 'BENTO BOX A',
                    'price' => '₱390.00',
                ],
                [
                    'image' => 'bento2.png',
                    'name' => 'BENTO BOX B',
                    'price' => '₱450.00',
                ],
                [
                    'image' => 'bento3.png',
                    'name' => 'BENTO BOX C',
                    'price' => '₱450.00',
                ]
            ];
        @endphp

        <div class="mt-5 pb-5 font-cubao font-medium text-4xl text-primary px-4">
            lydia’s bento meals
        </div>

        <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-4 px-2 lechons pb-10">
            @foreach ($bentos as $bento)
            <div class="bg-white shadow-md rounded-lg border-primary border lechon">
                <div style="background-image: url('{{ asset('images/checkout-bg.png') }}')" class="object-cover overflow-hidden m-2 rounded-md bg-center">
                    <img src="{{ asset('images/' .  $bento['image']) }}" alt="{{ $bento['name'] }}" class="px-4 scale-125">
                </div>
                <div class="mt-4 px-2">
                    <div class="text-primary text-base font-bold mt-2">{{ $bento['price'] }}</div>
                    <h2 class="text-left text-sm mt-1 uppercase">{{ $bento['name'] }}</h2>
                </div>
                <div class="mt-4 border-t border-primary ">
                    <button @click="lechonCart = true" class="text-primary text-sm px-4 py-3 w-full hover:bg-primary hover:text-white">Add to Cart</button>
                </div>
            </div>
            @endforeach
        </div>
        
        @php
            $pampaganas = [
                [
                    'image' => 'pampagana1.png',
                    'name' => 'chicharon bituka',
                    'price' => '₱210.00',
                ],
                [
                    'image' => 'pampagana2.png',
                    'name' => 'chicharon bulaklak',
                    'price' => '₱265.00',
                ]
            ];
        @endphp

        <div class="mt-5 pb-5 font-cubao font-medium text-4xl text-primary px-4">
            pampagana
        </div>

        <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-4 px-2 lechons pb-10">
            @foreach ($pampaganas as $pampagana)
            <div class="bg-white shadow-md rounded-lg border-primary border lechon">
                <div style="background-image: url('{{ asset('images/checkout-bg.png') }}')" class="object-cover overflow-hidden m-2 rounded-md bg-center">
                    <img src="{{ asset('images/' .  $pampagana['image']) }}" alt="{{ $pampagana['name'] }}" class="px-4 scale-125">
                </div>
                <div class="mt-4 px-2">
                    <div class="text-primary text-base font-bold mt-2">{{ $pampagana['price'] }}</div>
                    <h2 class="text-left text-sm mt-1 uppercase">{{ $pampagana['name'] }}</h2>
                </div>
                <div class="mt-4 border-t border-primary ">
                    <button @click="lechonCart = true" class="text-primary text-sm px-4 py-3 w-full hover:bg-primary hover:text-white">Add to Cart</button>
                </div>
            </div>
            @endforeach
        </div>
        
        @php
            $meaties = [
                [
                    'image' => 'meaty1.png',
                    'name' => 'pork bbq large (per pc.)',
                    'price' => '₱99.00',
                ],
                [
                    'image' => 'meaty2.png',
                    'name' => 'bopis',
                    'price' => '₱395.00',
                ],
                [
                    'image' => 'meaty3.png',
                    'name' => 'Dinuguan',
                    'price' => '₱395.00',
                ],
                [
                    'image' => 'meaty4.png',
                    'name' => 'papaitan',
                    'price' => '₱395.00',
                ],
                [
                    'image' => 'meaty5.png',
                    'name' => 'kare-kare',
                    'price' => '₱395.00',
                ]
            ];
        @endphp

        <div class="mt-5 pb-5 font-cubao font-medium text-4xl text-primary px-4">
            meaty espesyal
        </div>

        <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-4 px-2 lechons pb-10">
            @foreach ($meaties as $meaty)
            <div class="bg-white shadow-md rounded-lg border-primary border lechon">
                <div style="background-image: url('{{ asset('images/checkout-bg.png') }}')" class="object-cover overflow-hidden m-2 rounded-md bg-center">
                    <img src="{{ asset('images/' .  $meaty['image']) }}" alt="{{ $meaty['name'] }}" class="px-4 scale-125">
                </div>
                <div class="mt-4 px-2">
                    <div class="text-primary text-base font-bold mt-2">{{ $meaty['price'] }}</div>
                    <h2 class="text-left text-sm mt-1 uppercase">{{ $meaty['name'] }}</h2>
                </div>
                <div class="mt-4 border-t border-primary ">
                    <button @click="lechonCart = true" class="text-primary text-sm px-4 py-3 w-full hover:bg-primary hover:text-white">Add to Cart</button>
                </div>
            </div>
            @endforeach
        </div>
        
        @php
            $gulays = [
                [
                    'image' => 'gulay1.png',
                    'name' => 'fresh lumpia',
                    'price' => '₱150.00',
                ],
                [
                    'image' => 'gulay2.png',
                    'name' => 'chopsuey',
                    'price' => '₱295.00',
                ],
                [
                    'image' => 'gulay3.png',
                    'name' => 'Classic pinakbet',
                    'price' => '₱295.00',
                ],
            ];
        @endphp

        <div class="mt-5 pb-5 font-cubao font-medium text-4xl text-primary px-4">
            gulay atbp.
        </div>

        <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-4 px-2 lechons pb-10">
            @foreach ($gulays as $gulay)
            <div class="bg-white shadow-md rounded-lg border-primary border lechon">
                <div style="background-image: url('{{ asset('images/checkout-bg.png') }}')" class="object-cover overflow-hidden m-2 rounded-md bg-center">
                    <img src="{{ asset('images/' .  $gulay['image']) }}" alt="{{ $gulay['name'] }}" class="px-4 scale-125">
                </div>
                <div class="mt-4 px-2">
                    <div class="text-primary text-base font-bold mt-2">{{ $gulay['price'] }}</div>
                    <h2 class="text-left text-sm mt-1 uppercase">{{ $gulay['name'] }}</h2>
                </div>
                <div class="mt-4 border-t border-primary ">
                    <button @click="lechonCart = true" class="text-primary text-sm px-4 py-3 w-full hover:bg-primary hover:text-white">Add to Cart</button>
                </div>
            </div>
            @endforeach
        </div>
        
        @php
            $yamangdagats = [
                [
                    'image' => 'yamangdagat1.png',
                    'name' => 'crab relleno',
                    'price' => '₱320.00',
                ],
                [
                    'image' => 'yamangdagat2.png',
                    'name' => 'salmon head sinigang',
                    'price' => '₱425.00',
                ]
            ];
        @endphp

        <div class="mt-5 pb-5 font-cubao font-medium text-4xl text-primary px-4">
            yamang dagat
        </div>

        <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-4 px-2 lechons pb-10">
            @foreach ($yamangdagats as $yamangdagat)
            <div class="bg-white shadow-md rounded-lg border-primary border lechon">
                <div style="background-image: url('{{ asset('images/checkout-bg.png') }}')" class="object-cover overflow-hidden m-2 rounded-md bg-center">
                    <img src="{{ asset('images/' .  $yamangdagat['image']) }}" alt="{{ $yamangdagat['name'] }}" class="px-4 scale-125">
                </div>
                <div class="mt-4 px-2">
                    <div class="text-primary text-base font-bold mt-2">{{ $yamangdagat['price'] }}</div>
                    <h2 class="text-left text-sm mt-1 uppercase">{{ $yamangdagat['name'] }}</h2>
                </div>
                <div class="mt-4 border-t border-primary ">
                    <button @click="lechonCart = true" class="text-primary text-sm px-4 py-3 w-full hover:bg-primary hover:text-white">Add to Cart</button>
                </div>
            </div>
            @endforeach
        </div>
        
        @php
            $meryendas = [
                [
                    'image' => 'meryenda1.png',
                    'name' => 'pancit con lechon regular (150g)',
                    'price' => '₱275.00',
                ],
                [
                    'image' => 'meryenda2.png',
                    'name' => 'pancit con lechon medium (225g)',
                    'price' => '₱505.00',
                ]
            ];
        @endphp

        <div class="mt-5 pb-5 font-cubao font-medium text-4xl text-primary px-4">
            meryenda
        </div>

        <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-4 px-2 lechons pb-10">
            @foreach ($meryendas as $meryenda)
            <div class="bg-white shadow-md rounded-lg border-primary border lechon">
                <div style="background-image: url('{{ asset('images/checkout-bg.png') }}')" class="object-cover overflow-hidden m-2 rounded-md bg-center">
                    <img src="{{ asset('images/' .  $meryenda['image']) }}" alt="{{ $meryenda['name'] }}" class="px-4 scale-125">
                </div>
                <div class="mt-4 px-2">
                    <div class="text-primary text-base font-bold mt-2">{{ $meryenda['price'] }}</div>
                    <h2 class="text-left text-sm mt-1 uppercase">{{ $meryenda['name'] }}</h2>
                </div>
                <div class="mt-4 border-t border-primary ">
                    <button @click="lechonCart = true" class="text-primary text-sm px-4 py-3 w-full hover:bg-primary hover:text-white">Add to Cart</button>
                </div>
            </div>
            @endforeach
        </div>
        
        @php
            $kanins = [
                [
                    'image' => 'rice1.png',
                    'name' => 'plain rice',
                    'price' => '₱50.00',
                ]
            ];
        @endphp

        <div class="mt-5 pb-5 font-cubao font-medium text-4xl text-primary px-4">
            kanin
        </div>

        <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-4 px-2 lechons pb-10">
            @foreach ($kanins as $kanin)
            <div class="bg-white shadow-md rounded-lg border-primary border lechon">
                <div style="background-image: url('{{ asset('images/checkout-bg.png') }}')" class="object-cover overflow-hidden m-2 rounded-md bg-center">
                    <img src="{{ asset('images/' .  $kanin['image']) }}" alt="{{ $kanin['name'] }}" class="px-4 scale-125">
                </div>
                <div class="mt-4 px-2">
                    <div class="text-primary text-base font-bold mt-2">{{ $kanin['price'] }}</div>
                    <h2 class="text-left text-sm mt-1 uppercase">{{ $kanin['name'] }}</h2>
                </div>
                <div class="mt-4 border-t border-primary ">
                    <button @click="lechonCart = true" class="text-primary text-sm px-4 py-3 w-full hover:bg-primary hover:text-white">Add to Cart</button>
                </div>
            </div>
            @endforeach
        </div>
        
        @php
            $drinks = [
                [
                    'image' => 'inumin1.png',
                    'name' => 'bottled water',
                    'price' => '₱45.00',
                ],
                [
                    'image' => 'inumin2.png',
                    'name' => 'coke (can)',
                    'price' => '₱85.00',
                ],
                [
                    'image' => 'inumin3.png',
                    'name' => 'coke zero (can)',
                    'price' => '₱85.00',
                ],
                [
                    'image' => 'inumin4.png',
                    'name' => 'sprite (can)',
                    'price' => '₱85.00',
                ],
                [
                    'image' => 'inumin5.png',
                    'name' => 'Pineapple juice',
                    'price' => '₱85.00',
                ]
            ];
        @endphp

        <div class="mt-5 pb-5 font-cubao font-medium text-4xl text-primary px-4">
            mga inumin
        </div>

        <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-4 px-2 lechons pb-10">
            @foreach ($drinks as $drink)
            <div class="bg-white shadow-md rounded-lg border-primary border lechon">
                <div style="background-image: url('{{ asset('images/checkout-bg.png') }}')" class="object-cover overflow-hidden m-2 rounded-md bg-center">
                    <img src="{{ asset('images/' .  $drink['image']) }}" alt="{{ $drink['name'] }}" class="px-4 scale-125">
                </div>
                <div class="mt-4 px-2">
                    <div class="text-primary text-base font-bold mt-2">{{ $drink['price'] }}</div>
                    <h2 class="text-left text-sm mt-1 uppercase">{{ $drink['name'] }}</h2>
                </div>
                <div class="mt-4 border-t border-primary ">
                    <button @click="lechonCart = true" class="text-primary text-sm px-4 py-3 w-full hover:bg-primary hover:text-white">Add to Cart</button>
                </div>
            </div>
            @endforeach
        </div>
    
        <x-footer-component />
    
        <x-lechon-cart-component />
    </div>
    
@endsection

