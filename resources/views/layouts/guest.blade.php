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

    @yield('alpine.plugins')

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
        
        async add_to_cart(act,id, qty, addons){
            if (act && id) {
                await this.save_to_cart(act,id, qty, addons);
            } else {
                return false;
            }
        },

        async save_to_cart(act, id, qty, addons) {

            this.loading = true;

            const add_ons = addons.map((addon) => ({
                misc_id: addon.id,
                misc_qty: addon.qty,
            }));

            try {
                let response = await fetch('{{ route('cart.add') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        ac_item: id,
                        ac_qty: qty,
                        misc_cntr: add_ons
                    })
                }).then((response) => {
                    return response;
                }).catch((error) => {
                    
                });

                if (!response.ok) throw new Error('Network response was not ok');

                let data = await response.json();

                $dispatch('update-cart');

                this.addedToCart = true;

                setTimeout(() => {
                    this.addedToCart = false;
                }, 3000);

                this.lechonCart = false;
                this.added = false;

                this.loading = false;

            } catch (error) {
                console.error('There was a problem with the fetch operation:', error);
            }
        },

        loading: false,

        async updateCartQty(act, id, qty) {

            this.loading = true;

            console.log(act, id, qty);

            try {
                let response = await fetch('{{ route('cart.qty.update') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        ac_item: id,
                        ac_qty: qty,
                    })
                }).then((response) => {
                    return response;
                }).catch((error) => {
                    
                });

                if (!response.ok) throw new Error('Network response was not ok');

                let data = await response.json();

                let cartRes = await fetch('{{route('cart.get')}}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    }
                }).then((response) => {
                    return response;
                }).catch((error) => {
                    
                });

                const cartData = await cartRes.json();
                this.carts = cartData.cart;

                {{-- $dispatch('update-cart'); --}}
                
                this.loading = false;
            } catch (error) {
                console.error('There was a problem with the fetch operation:', error);
            }
        },
        handleQtyChange(productId, currentQty, diff) {
            const newQty = currentQty + diff;
            if (newQty < 1) return;
            this.updateCartQty('addcart', productId, newQty);
        },
        added: false,
        searchModal: false,
        toggleSearch() {
            this.searchModal = !this.searchModal;
        },
        search: '',
        results: [],
        isLoading: false,
        errorMessage: '',
        productLimit: 5,
        articleLimit: 5,
        async submitSearch() {
            if (!this.search.trim()) {
                this.errorMessage = 'Please enter a search term.';
                return;
            }
            this.errorMessage = '';
            this.isLoading = true;
            this.results = [];
            this.productLimit = 5;
            this.articleLimit = 5;

            try {
                let response = await fetch('{{ route('global.search') }}?searchTerm=' + encodeURIComponent(this.search));
                if (!response.ok) {
                    let error = await response.json();
                    this.errorMessage = error.error.searchTerm ? error.error.searchTerm[0] : 'Something went wrong.';
                    this.isLoading = false;
                    return;
                }
                let data = await response.json();
                this.results = data;
            } catch (error) {
                this.errorMessage = 'Something went wrong. Please try again.';
            } finally {
                this.isLoading = false;
            }
        },

        loadMoreProducts() {
            this.productLimit += 5;
        },

        loadMoreArticles() {
            this.articleLimit += 5;
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

    <x-marketing-popup-component />
    
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
                errorMessage: '',
        
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
                        console.log(event)
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
                        console.log(event.error)
                        this.errorMessage = `Speech recognition error: ${event.error}`;
                        this.isListening = false;
                    };
        
                    this.recognition.onend = () => {
                        // Keep it listening-like for just a moment
                        setTimeout(() => {
                            if (!this.query) {
                                this.isListening = false;
                            }
                        }, 500);
                    };
                },
        
                startListening() {
                    if (!this.recognition) return;
                    this.isListening = true;
                    this.recognition.start();
                },
            };
        }
    </script>

    @yield('scripts')

</body>
</html>
