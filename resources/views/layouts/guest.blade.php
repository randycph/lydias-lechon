<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @font-face {
            font-family: 'Cubao';
            src: url('{{ asset("fonts/Cubao_Free_Narrow.otf") }}') format('opentype');
            font-weight: 300;
            font-style: normal;
        }
    
        @font-face {
            font-family: 'Cubao';
            src: url('{{ asset("fonts/Cubao_Free_Regular.otf") }}') format('opentype');
            font-weight: 500;
            font-style: normal;
        }
    
        @font-face {
            font-family: 'Cubao';
            src: url('{{ asset("fonts/Cubao_Free_Wide.otf") }}') format('opentype');
            font-weight: 700;
            font-style: normal;
        }
    </style>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    {{-- <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" /> --}}

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

</head>
<body x-cloak 
    class="bg-gray-100 text-gray-900" 
    x-data="{ 
        open: false, 
        openCart: false, 
        marketingPopup: false, 
        openHotline: false,
        openContactUs: false,
    }">
    
    <x-navigation-component :page="$page ?? ''" />

    <x-cart-component />

    <x-drawer-component />
    
    <x-hotline-component />

    <x-contact-us-component />

    <main class="" x-cloak>
        @yield('content')
    </main>

    <x-fixed-footer-component />

    <x-mobile-menu-component />

    {{-- <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script> --}}
</body>
</html>
