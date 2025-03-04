<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
    <header>
        <!--include main-header class for the landing page -->
        <!--exclude main-header class for the other pages -->
        <div class="main-header fixed top-0 left-0 right-0 z-20 overflow-hidden" style="margin-bottom:113px">
            <div class="container">
                <!--include main-nav class for the landing page -->
                <!--exclude main-nav class for the other pages -->
                <nav class="main-nav ltr flex flex-wrap justify-between items-center px-6 py-6">
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
        <section>
            <form id="deliveryoption_form" method="post">
                <div class="start-screen bg-very-dark-cyan relative flex justify-center items-center z-10">
                    <div class="container px-6">
                        <h1 class="text-white font-semibold text-2xl text-center mb-16 md:mb-10">Delivery or Pickup</h1>
                        <div class="w-full max-w-md mx-auto grid grid-cols-2 gap-4 md:gap-8 px-4">
                            <a class="bg-white border-4 border-solid border-pure-orange pt-2 pb-4 px-2 transform hover:scale-95 rounded-3xl transition-all duration-150 ease-in-out" href="javascript:;" onclick="set_delivery_option('pick-up');">
                                <img class="w-full" src="{{ asset('theme/lydias/kiosk/images/pickup.jpg') }}" alt="pickup">
                                <div class="font-bold text-sm text-center">Pick-up</div>
                            </a>
                            <a class="bg-white border-4 border-solid border-pure-orange pt-2 pb-4 px-2 transform hover:scale-95 rounded-3xl transition-all duration-150 ease-in-out" href="javascript:;" onclick="set_delivery_option('delivery');">
                                <img class="w-full" src="{{ asset('theme/lydias/kiosk/images/delivery.jpg') }}" alt="delivery">
                                <div class="font-bold text-sm text-center">Delivery</div>
                            </a>

                            <input type="hidden" name="delivery_option" id="delivery_option" value="">
                        </div>
                    </div>
                </div>
            </form>
        </section>
    </main>
    <!-- END CONTENT -->

    <!-- START FOOTER -->
    <footer>
        <!--include main-footer class for the landing page -->
        <!--exclude main-footer class for the other pages -->
        <div class="main-footer relative overflow-hidden">
            <div class="container">
                <div class="ltr px-6 py-6">
                    <p style="opacity:.4;" class="text-white text-center text-xs mb-1">All information, pictures and images on this site are copyrighted material and owned by their respective creators or owners.</p>
                    <p style="opacity:.4;" class="text-white text-center text-xs mb-6">Copyright © 2021 | Lydia’s Lechon</p>
                    <hr style="opacity:.4;" class="mb-6" />
                    <p class="text-white text-center text-xs">Powered by <a class="underline hover:no-underline" href="http://www.webfocus.ph" target="_blank">WebFocus Solutions, Inc.</a></p>
                </div>
            </div>
        </div>
    </footer>
    <!-- END FOOTER -->

    <script src="{{ asset('theme/lydias/kiosk/js/jquery.min.js') }}"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function set_delivery_option(x){        
        
            $('#delivery_option').val(x);
            
            $.ajax({
                type: "POST",
                url: "{{route('set-delivery-option')}}",
                data: $('#deliveryoption_form').serialize(),
                success: function(){
                    window.location.href = "{{route('kiosk.menu')}}";                
                }
            });

           return false;
        }
    </script>
</body>
</html>