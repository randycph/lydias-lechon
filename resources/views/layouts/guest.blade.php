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
<body 
    x-cloak 
    class="bg-gray-100 text-gray-900 mx-auto" 
    x-data="{ 
        open: false, 
        openCart: false, 
        marketingPopup: false, 
        openHotline: false,
        openContactUs: false,
        addedToCart: false,
        lechonCart: false,
        addToCart() {
            this.addedToCart = true;
            setTimeout(() => {
                this.addedToCart = false;
            }, 3000);
        },
        searchModal: false,
        toggleSearch() {
            this.searchModal = !this.searchModal;
        }
    }"
    x-init="
        const lockScroll = () => {
            const scrollY = window.scrollY;
            document.body.style.position = 'fixed';
            document.body.style.top = `-${scrollY}px`;
            document.body.style.width = '100%';
            document.body.dataset.scrollY = scrollY;
        };

        const unlockScroll = () => {
            const scrollY = document.body.dataset.scrollY;
            document.body.style.position = '';
            document.body.style.top = '';
            document.body.style.width = '';
            window.scrollTo(0, parseInt(scrollY || '0'));
        };

        const lockBody = () => {
            if (open || openCart || marketingPopup || openHotline || openContactUs || lechonCart || searchModal) {
                lockScroll();
            } else {
                unlockScroll();
            }
        };
        $watch('open', lockBody);
        $watch('openCart', lockBody);
        $watch('marketingPopup', lockBody);
        $watch('openHotline', lockBody);
        $watch('openContactUs', lockBody);
        $watch('lechonCart', lockBody);
        $watch('searchModal', lockBody);
    "
    >
    
    <x-navigation-component :page="$page ?? ''" />

    <x-search-component />

    <div class="container relative">
        <x-added-to-cart-component />
    </div>

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

    <script>
        function voiceSearch() {
            return {
                query: '',
                recognition: null,
                isListening: false,
                search: '',
        
                init() {
                    // Check if browser supports SpeechRecognition
                    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
                    if (!SpeechRecognition) {
                        alert('Your browser does not support voice recognition.');
                        return;
                    }
        
                    this.recognition = new SpeechRecognition();
                    this.recognition.lang = 'en-US';
                    this.recognition.interimResults = false;
                    this.recognition.maxAlternatives = 1;
        
                    this.recognition.onresult = (event) => {
                        const transcript = event.results[0][0].transcript;
                        this.query = transcript;
                        this.isListening = false;
        
                        // OPTIONAL: Trigger search immediately
                        this.$nextTick(() => {
                            this.$dispatch('voice-search-finished', { query: this.query });
                        });

                        this.search = this.query;
                    };
        
                    this.recognition.onerror = (event) => {
                        console.error('Speech recognition error:', event.error);
                        this.isListening = false;
                    };
        
                    this.recognition.onend = () => {
                        this.isListening = false;
                    };
                },
        
                startListening() {
                    if (!this.recognition) return;
        
                    this.isListening = true;
                    this.recognition.start();
                }
            };
        }
        </script>

</body>
</html>
