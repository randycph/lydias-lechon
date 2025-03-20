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

    <div class="pt-20 pb-5 px-4">
        <h3 class="font-medium uppercase text-center mt-10">Find the perfect lechon for your budget</hh3>
        <h1 class="text-4xl font-cubao font-medium text-primary text-center">lechon pricelist</h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 px-4 lechons pb-10">
        @foreach ($lists as $list)
        <div class="bg-white shadow-md rounded-lg  border-primary border-2 lechon">
            <img src="{{ asset('images/' .  $list['image']) }}" alt="{{ $list['name'] }}" class="px-4">
            <div class="mt-4 px-4">
                <h2 class="text-2xl font-cubao font-medium text-primary text-left">{{ $list['name'] }}</h2>
                <div class="text-tertiary text-2xl font-semibold mt-2">{{ $list['price'] }}</div>
            </div>
            <hr class="text-primary my-4">
            <div class="mt-4 flex flex-col gap-2 p-4">
                @if (isset($list['description']))
                <div class="flex items-center text-primary gap-2">
                    <svg width="14" height="16" viewBox="0 0 14 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_73_19609)">
                        <path d="M9.5 4C9.5 3.33696 9.23661 2.70107 8.76777 2.23223C8.29893 1.76339 7.66304 1.5 7 1.5C6.33696 1.5 5.70107 1.76339 5.23223 2.23223C4.76339 2.70107 4.5 3.33696 4.5 4C4.5 4.66304 4.76339 5.29893 5.23223 5.76777C5.70107 6.23661 6.33696 6.5 7 6.5C7.66304 6.5 8.29893 6.23661 8.76777 5.76777C9.23661 5.29893 9.5 4.66304 9.5 4ZM3 4C3 2.93913 3.42143 1.92172 4.17157 1.17157C4.92172 0.421427 5.93913 0 7 0C8.06087 0 9.07828 0.421427 9.82843 1.17157C10.5786 1.92172 11 2.93913 11 4C11 5.06087 10.5786 6.07828 9.82843 6.82843C9.07828 7.57857 8.06087 8 7 8C5.93913 8 4.92172 7.57857 4.17157 6.82843C3.42143 6.07828 3 5.06087 3 4ZM1.54062 14.5H12.4625C12.1844 12.5219 10.4844 11 8.43125 11H5.575C3.52187 11 1.82188 12.5219 1.54375 14.5H1.54062ZM0 15.0719C0 11.9937 2.49375 9.5 5.57188 9.5H8.42813C11.5063 9.5 14 11.9937 14 15.0719C14 15.5844 13.5844 16 13.0719 16H0.928125C0.415625 16 0 15.5844 0 15.0719Z" fill="#018441"/>
                        </g>
                        <defs>
                        <clipPath id="clip0_73_19609">
                        <rect width="14" height="16" fill="white"/>
                        </clipPath>
                        </defs>
                    </svg>
                    <span class=" font-semibold">{{ $list['description'] }}</span>
                </div>
                @endif
                @if (isset($list['weight']))
                <div class="flex items-center text-primary gap-2">
                    <svg width="18" height="20" viewBox="0 0 18 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_73_19612)">
                        <path d="M7.31424 3.73388C7.31424 3.28066 7.49203 2.846 7.8085 2.52552C8.12497 2.20504 8.5542 2.025 9.00176 2.025C9.44932 2.025 9.87855 2.20504 10.195 2.52552C10.5115 2.846 10.6893 3.28066 10.6893 3.73388C10.6893 4.18711 10.5115 4.62177 10.195 4.94224C9.87855 5.26272 9.44932 5.44277 9.00176 5.44277C8.5542 5.44277 8.12497 5.26272 7.8085 4.94224C7.49203 4.62177 7.31424 4.18711 7.31424 3.73388ZM11.2518 5.44277C11.6034 4.9657 11.8143 4.37471 11.8143 3.73388C11.8143 2.16028 10.5557 0.885742 9.00176 0.885742C7.44783 0.885742 6.18922 2.16028 6.18922 3.73388C6.18922 4.37471 6.40016 4.9657 6.75173 5.44277H4.22044C3.447 5.44277 2.77199 5.97679 2.58214 6.73867L0.0508563 16.992C-0.0757079 17.5011 0.0367936 18.0422 0.35672 18.4588C0.676646 18.8753 1.16884 19.1138 1.68916 19.1138H16.3144C16.8347 19.1138 17.3234 18.8717 17.6433 18.4552C17.9632 18.0387 18.0757 17.5011 17.9491 16.9884L15.4179 6.73511C15.2315 5.97679 14.5565 5.44277 13.7831 5.44277H11.2518ZM9.00176 6.58202H13.7831C14.0397 6.58202 14.2647 6.76003 14.328 7.0128L16.8593 17.2661C16.9015 17.437 16.8628 17.615 16.7573 17.7538C16.6519 17.8927 16.4866 17.9746 16.3144 17.9746H1.68916C1.51689 17.9746 1.35166 17.8927 1.24618 17.7538C1.14071 17.615 1.10204 17.437 1.14423 17.2661L3.67551 7.0128C3.7388 6.76003 3.9638 6.58202 4.22044 6.58202H9.00176Z" fill="#018441"/>
                        </g>
                        <defs>
                        <clipPath id="clip0_73_19612">
                        <rect width="18" height="18.2281" fill="white" transform="translate(0 0.885742)"/>
                        </clipPath>
                        </defs>
                    </svg>
                    <span class=" font-semibold">{{ $list['weight'] }}</span>
                </div>
                @endif
                @if (isset($list['serving']))
                <div class="flex items-center text-primary gap-2">
                    <svg width="15" height="18" viewBox="0 0 15 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_73_19615)">
                        <path d="M1.42634 0.491584C1.71763 0.541468 1.91518 0.814167 1.8683 1.10349L1.09487 5.71941C1.08147 5.80588 1.07143 5.89234 1.07143 5.98214V6.33797C1.07143 7.21926 1.79129 7.93426 2.67857 7.93426H3.75H4.82143C5.7087 7.93426 6.42857 7.21926 6.42857 6.33797V5.98214C6.42857 5.89567 6.42188 5.80588 6.40513 5.71941L5.6317 1.10349C5.58147 0.814167 5.77902 0.538142 6.07366 0.491584C6.3683 0.445026 6.64286 0.63791 6.68973 0.930562L7.46317 5.54648C7.48661 5.68948 7.5 5.83581 7.5 5.98546V6.3413C7.5 7.81121 6.30134 9.00177 4.82143 9.00177H4.28571V16.9832C4.28571 17.2758 4.04464 17.5153 3.75 17.5153C3.45536 17.5153 3.21429 17.2758 3.21429 16.9832V9.00177H2.67857C1.19866 9.00177 0 7.81121 0 6.3413V5.98546C0 5.83913 0.0133929 5.69281 0.0368304 5.54648L0.810268 0.930562C0.860491 0.641236 1.13504 0.445026 1.42634 0.491584ZM2.97321 0.484933C3.26786 0.498235 3.49554 0.75098 3.48214 1.04363L3.21429 6.36458C3.20089 6.65723 2.94643 6.88337 2.65179 6.87007C2.35714 6.85677 2.12946 6.60402 2.14286 6.31137L2.41071 0.990423C2.42746 0.697771 2.67857 0.471631 2.97321 0.484933ZM4.52679 0.484933C4.82143 0.471631 5.07254 0.697771 5.08929 0.990423L5.35714 6.31137C5.37054 6.60402 5.14286 6.85344 4.84821 6.87007C4.55357 6.8867 4.30246 6.65723 4.28571 6.36458L4.01786 1.04363C4.00446 0.75098 4.23214 0.501561 4.52679 0.484933ZM10.7143 6.33797V10.0626C10.7143 10.6513 11.1931 11.1268 11.7857 11.1268H13.9286V1.55577L13.9219 1.5591C13.865 1.5724 13.6975 1.60898 13.4431 1.71208C13.1049 1.84843 12.6897 2.07457 12.2879 2.42043C11.5112 3.08887 10.7143 4.25948 10.7143 6.33797ZM13.9286 0.484933C14.5212 0.484933 15 0.960493 15 1.54912V11.1268V11.6589V12.191V16.9799C15 17.2725 14.7589 17.512 14.4643 17.512C14.1696 17.512 13.9286 17.2725 13.9286 16.9799V12.191H11.7857C10.6038 12.191 9.64286 11.2366 9.64286 10.0626V6.33797C9.64286 1.54912 13.3929 0.484933 13.9286 0.484933Z" fill="#018441"/>
                        </g>
                        <defs>
                        <clipPath id="clip0_73_19615">
                        <rect width="15" height="17.0309" fill="white" transform="translate(0 0.484375)"/>
                        </clipPath>
                        </defs>
                    </svg>
                    <span class="font-semibold">{{ $list['serving'] }}</span>
                </div>
                @endif
                @if (isset($list['free']))
                <div class="flex items-center text-primary gap-2">
                    <svg width="15" height="10" viewBox="0 0 15 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_73_19618)">
                        <path d="M14.764 0.225889C15.0787 0.527075 15.0787 1.0141 14.764 1.31208L5.92669 9.77411C5.61203 10.0753 5.10321 10.0753 4.7919 9.77411L0.235996 5.41653C-0.0786655 5.11535 -0.0786655 4.62832 0.235996 4.33034C0.550658 4.03236 1.05947 4.02916 1.37079 4.33034L5.35427 8.14322L13.6259 0.225889C13.9405 -0.0752964 14.4493 -0.0752964 14.7607 0.225889H14.764Z" fill="#018441"/>
                        </g>
                        <defs>
                        <clipPath id="clip0_73_19618">
                        <rect width="15" height="10" fill="white"/>
                        </clipPath>
                        </defs>
                    </svg>
                        
                    <span class=" font-semibold">{{ $list['free'] }}</span>
                </div>
                @endif
                @if (isset($list['add']))
                <div class="text-sm text-tertiary mt-2">
                    {{ $list['add'] }}
                </div>
                @endif
            </div>
            <div class="mt-4 border-t-2 border-primary ">
                <button class="text-primary px-4 py-3 w-full hover:bg-primary hover:text-white">Add to Cart</button>
            </div>
        </div>
        @endforeach
    </div>

    <x-faq-component />

    <div class="py-10 px-4">
        <h2 class="text-2xl font-cubao font-medium text-tertiary text-center">your #everydaylechonhappiness</h2>
    </div>

    <x-footer-component />
    
@endsection

