<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lydias Lechon | Kiosk</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('theme/lydias/kiosk/style.css') }}">
</head>
<body>
    <!-- START HEADER -->
    <header style="margin-bottom:113px">
        <div class="fixed top-0 left-0 right-0 z-30 overflow-hidden">
            <div class="container">
                <nav class="ltr flex flex-wrap justify-between items-center" style="padding: 15px;">
                    <div class="w-28">
                        <a href="{{ route('kiosk.home') }}">
                            <img src="{{ asset('theme/lydias/kiosk/images/logo.png') }}" alt="logo">
                        </a>
                    </div>
                    <div class="flex">
                    </div>
                </nav>
            </div>
        </div>
    </header>
    <!-- END HEADER -->

    <!-- START CONTENT -->
    <main>

        <!-- START H1 HEADING SECTION -->
        <section>
            <div class="bg-white relative">
                <div class="container">
                    <div class="px-6 md:px-7 py-7">
                        <div class="swal-icon swal-icon--success">
                            <span class="swal-icon--success__line swal-icon--success__line--long"></span>
                            <span class="swal-icon--success__line swal-icon--success__line--tip"></span>

                            <div class="swal-icon--success__ring"></div>
                            <div class="swal-icon--success__hide-corners"></div>
                        </div>
                        <h1 class="text-very-dark-cyan text-2xl md:text-3xl text-center font-bold pt-3 mb-4">We have received your order.</h1>
                        <h2 class="text-pure-orange text-base md:text-lg text-center font-semibold mt-10">Your order number is</h2>
                        <h1 class="text-very-dark-cyan text-2xl md:text-3xl text-center font-bold mb-4">{{ $sales->order_number }}</h1>
                        <h2 class="text-pure-orange text-base md:text-lg text-center font-semibold mt-5 mb-10">You may now proceed to the cashier for your payment.</h2>
                        <div class="flex justify-center">
                            <a class="bg-very-dark-cyan hover:bg-very-dark-cyan-hover inline-block text-white font-semibold px-5 py-3 rounded-xl transition-all duration-150 ease-in-out" href="{{ route('kiosk.home') }}">
                                New Order
                            </a>&nbsp;&nbsp;
                            <a class="bg-very-dark-cyan hover:bg-very-dark-cyan-hover inline-block text-white font-semibold px-5 py-3 rounded-xl transition-all duration-150 ease-in-out" target="_blank" href="{{ route('sales.print',$sales->HashOrderNumber) }}">
                                Print Receipt
                            </a>
                        </div>
                        
                    </div>
                </div>
            </div>
        </section>
        <!-- END H1 HEADING SECTION -->
    </main>
    <!-- END CONTENT -->

    <!-- START FOOTER -->
    <footer>
        <div class="relative overflow-hidden">
            <div class="container">
                <div class="ltr px-6 md:px-7 py-6">
                    <p class="text-white opacity-50 text-center text-xs mb-1 mt-10">All information, pictures and images on this site are copyrighted material and owned by their respective creators or owners.</p>
                    <p class="text-white opacity-50 text-center text-xs mb-6">Copyright © 2021 | Lydia’s Lechon</p>
                    <hr class="opacity-50 mb-6" />
                    <p class="text-white text-center text-xs">Powered by <a class="underline hover:no-underline" href="http://www.webfocus.ph" target="_blank">WebFocus Solutions, Inc.</a></p>
                </div>
            </div>
        </div>
    </footer>
    <!-- END FOOTER -->

    <script src="{{ asset('theme/lydias/kiosk/js/jquery.min.js') }}"></script>
    <script src="{{ asset('theme/lydias/kiosk/js/sweetalert.min.js') }}"></script>
</body>
</html>