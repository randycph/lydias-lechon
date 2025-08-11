@if ($page) 
{!! $page->contents ?? '' !!}
@endif

{{-- Footer --}}
<footer class="relative hidden text-white pt-10 pb-16 px-4" style="background-image: url('{{ asset('images/our-story-bg.png') }}'); background-size: cover; background-position: center;">
    <!-- Green Overlay -->
    <div class="absolute inset-0 bg-primary-dark opacity-80"></div>
    <div class="relative container flex flex-wrap flex-col lg:flex-row items-start justify-between gap-4">
        <div class="flex justify-start items-start">
            <img src="{{ asset('images/lydias-logo-footer.png') }}" alt="Lydia's Logo" class="w-[180px] lg:w-[230px]">
        </div>
        <div class="mt-3 lg:mt-0">
            <h3 class="font-bold text-lg md:text-xl uppercase">About Us</h3>
            <ul class="mt-2 flex flex-col gap-2">
                <li><a href="{{ route('our-story') }}" class="link-underline-light">Our Story</a></li>
                <li><a href="{{ route('our-stores') }}" class="link-underline-light">Our Stores</a></li>
                <li><a href="{{ route('blogs') }}" class="link-underline-light">Blog</a></li>
            </ul>
        </div>
        <div class="mt-3 lg:mt-0">
            <h3 class="font-bold text-lg md:text-xl uppercase ">SHOP</h3>
            <ul class="mt-2 flex flex-col gap-2">
                <li><a href="{{ route('lechon-menu') }}" class="link-underline-light">Menu</a></li>
                <li><a href="{{ route('lechon-pricelist') }}" class="link-underline-light">Lechon Pricelist</a></li>
            </ul>
        </div>
        <div class="mt-3 lg:mt-0">
            <h3 class="font-bold text-lg md:text-xl uppercase ">Contact</h3>
            <ul class="mt-2 flex flex-col gap-2">
                <li>
                    <button @click="openContactUs = true" class="link-underline-light">Contact Us</button>
                </li>
                <li>
                    <button @click="openHotline = true" class="link-underline-light">Hotline</button>
                </li>
                <li><a href="{{ route('careers.v2') }}" class="link-underline-light">Careers</a></li>
            </ul>
            <div class="mt-3">Or get in touch with us via email:</div>
            <div><a href="mailto:orders@lydias-lechon.com" class="font-semibold link-underline-light">orders@lydias-lechon.com</a></div>
        </div>
        <div class="mt-3 lg:mt-0">
            <h3 class="font-bold text-lg md:text-xl uppercase">Follow Us</h3>
            <div class="mt-2 flex">
                <a href="https://www.facebook.com/lydiaslechonrestaurant/" target="_blank" class="fill-gray-300 hover:fill-gray-500">
                    <span class="sr-only">Facebook</span>
                    <svg class="h-8 w-8 " viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M0 24C0 10.7452 10.7452 0 24 0C37.2548 0 48 10.7452 48 24C48 37.2548 37.2548 48 24 48C10.7452 48 0 37.2548 0 24ZM26.5016 38.1115V25.0542H30.1059L30.5836 20.5546H26.5016L26.5077 18.3025C26.5077 17.1289 26.6192 16.5001 28.3048 16.5001H30.5581V12H26.9532C22.6231 12 21.0991 14.1828 21.0991 17.8536V20.5551H18.4V25.0547H21.0991V38.1115H26.5016Z"/>
                    </svg>
                </a>
                <a href="https://www.instagram.com/lydiaslechon" target="_blank" class="ml-3 fill-gray-300 hover:fill-gray-500">
                    <span class="sr-only">Instagram</span>
                    <svg class="h-8 w-8 " viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M0 24C0 10.7452 10.7452 0 24 0C37.2548 0 48 10.7452 48 24C48 37.2548 37.2548 48 24 48C10.7452 48 0 37.2548 0 24ZM24.0012 11.2C20.5249 11.2 20.0886 11.2152 18.7233 11.2773C17.3606 11.3397 16.4305 11.5555 15.6166 11.872C14.7747 12.1989 14.0606 12.6363 13.3491 13.348C12.6371 14.0595 12.1997 14.7736 11.8717 15.6152C11.5544 16.4294 11.3384 17.3598 11.2771 18.7219C11.216 20.0873 11.2 20.5238 11.2 24.0001C11.2 27.4764 11.2155 27.9114 11.2773 29.2767C11.34 30.6394 11.5557 31.5695 11.872 32.3834C12.1992 33.2253 12.6365 33.9394 13.3483 34.6509C14.0595 35.3629 14.7736 35.8013 15.615 36.1283C16.4294 36.4448 17.3598 36.6605 18.7222 36.7229C20.0876 36.7851 20.5236 36.8003 23.9996 36.8003C27.4762 36.8003 27.9111 36.7851 29.2765 36.7229C30.6391 36.6605 31.5703 36.4448 32.3848 36.1283C33.2264 35.8013 33.9394 35.3629 34.6506 34.6509C35.3626 33.9394 35.8 33.2253 36.128 32.3837C36.4427 31.5695 36.6587 30.6391 36.7227 29.277C36.784 27.9116 36.8 27.4764 36.8 24.0001C36.8 20.5238 36.784 20.0876 36.7227 18.7222C36.6587 17.3595 36.4427 16.4294 36.128 15.6155C35.8 14.7736 35.3626 14.0595 34.6506 13.348C33.9386 12.636 33.2266 12.1987 32.384 11.872C31.5679 11.5555 30.6373 11.3397 29.2746 11.2773C27.9092 11.2152 27.4746 11.2 23.9972 11.2H24.0012Z" />
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M22.8529 13.5067C23.1937 13.5062 23.574 13.5067 24.0012 13.5067C27.4188 13.5067 27.8239 13.519 29.1735 13.5803C30.4215 13.6374 31.0989 13.8459 31.5501 14.0211C32.1474 14.2531 32.5733 14.5304 33.021 14.9784C33.469 15.4264 33.7464 15.8531 33.9789 16.4505C34.1541 16.9011 34.3629 17.5785 34.4197 18.8265C34.481 20.1758 34.4944 20.5812 34.4944 23.9972C34.4944 27.4132 34.481 27.8186 34.4197 29.1679C34.3626 30.4159 34.1541 31.0933 33.9789 31.5439C33.7469 32.1413 33.469 32.5666 33.021 33.0144C32.573 33.4624 32.1477 33.7397 31.5501 33.9717C31.0994 34.1477 30.4215 34.3557 29.1735 34.4128C27.8242 34.4741 27.4188 34.4874 24.0012 34.4874C20.5833 34.4874 20.1782 34.4741 18.8289 34.4128C17.5809 34.3552 16.9035 34.1466 16.4521 33.9714C15.8547 33.7394 15.428 33.4621 14.98 33.0141C14.532 32.5661 14.2547 32.1405 14.0222 31.5429C13.847 31.0922 13.6382 30.4149 13.5814 29.1669C13.52 27.8175 13.5078 27.4122 13.5078 23.994C13.5078 20.5758 13.52 20.1726 13.5814 18.8233C13.6384 17.5753 13.847 16.8979 14.0222 16.4467C14.2542 15.8494 14.532 15.4227 14.98 14.9747C15.428 14.5267 15.8547 14.2494 16.4521 14.0168C16.9033 13.8408 17.5809 13.6328 18.8289 13.5755C20.0097 13.5222 20.4673 13.5062 22.8529 13.5035V13.5067ZM30.8338 15.632C29.9858 15.632 29.2978 16.3193 29.2978 17.1675C29.2978 18.0155 29.9858 18.7035 30.8338 18.7035C31.6818 18.7035 32.3698 18.0155 32.3698 17.1675C32.3698 16.3195 31.6818 15.632 30.8338 15.632ZM24.0012 17.4267C20.371 17.4267 17.4278 20.37 17.4278 24.0001C17.4278 27.6303 20.371 30.5722 24.0012 30.5722C27.6314 30.5722 30.5735 27.6303 30.5735 24.0001C30.5735 20.37 27.6314 17.4267 24.0012 17.4267Z" />
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M24.0012 19.7334C26.3575 19.7334 28.2679 21.6436 28.2679 24.0001C28.2679 26.3564 26.3575 28.2668 24.0012 28.2668C21.6446 28.2668 19.7345 26.3564 19.7345 24.0001C19.7345 21.6436 21.6446 19.7334 24.0012 19.7334Z" />
                    </svg>
                </a>
                <a href="https://www.youtube.com/@lydias_lechon" target="_blank" class="ml-3 fill-gray-300 hover:fill-gray-500">
                    <span class="sr-only">Youtube</span>
                    <svg class="h-8 w-8 " role="img" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg"><title>YouTube</title><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                </a>

                <a href="https://share.google/6Du4hxNHkWY4Sh64A" target="_blank" class="ml-3 fill-gray-300 hover:fill-gray-500">
                    <span class="sr-only">Google</span>
                    <svg class="h-8 w-8 " role="img" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg"><title>Google</title><path d="M12.48 10.92v3.28h7.84c-.24 1.84-.853 3.187-1.787 4.133-1.147 1.147-2.933 2.4-6.053 2.4-4.827 0-8.6-3.893-8.6-8.72s3.773-8.72 8.6-8.72c2.6 0 4.507 1.027 5.907 2.347l2.307-2.307C18.747 1.44 16.133 0 12.48 0 5.867 0 .307 5.387.307 12s5.56 12 12.173 12c3.573 0 6.267-1.173 8.373-3.36 2.16-2.16 2.84-5.213 2.84-7.667 0-.76-.053-1.467-.173-2.053H12.48z"/></svg>
                </a>

                <a href="https://linkedin.com/company/lydias-lechon" class="ml-3 fill-gray-300 hover:fill-gray-500">
                    <svg class="h-8 w-8 " role="img" viewBox="0 0 48 48" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                        <title>linkedin</title>
                        <defs></defs>
                        <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <g id="Dribbble-Light-Preview" transform="translate(-180.000000, -7479.000000)"  class="fill-gray-300 hover:fill-gray-500">
                                <g id="icons" transform="translate(56.000000, 160.000000)">
                                    <path d="M144,7339 L140,7339 L140,7332.001 C140,7330.081 139.153,7329.01 137.634,7329.01 C135.981,7329.01 135,7330.126 135,7332.001 L135,7339 L131,7339 L131,7326 L135,7326 L135,7327.462 C135,7327.462 136.255,7325.26 139.083,7325.26 C141.912,7325.26 144,7326.986 144,7330.558 L144,7339 L144,7339 Z M126.442,7323.921 C125.093,7323.921 124,7322.819 124,7321.46 C124,7320.102 125.093,7319 126.442,7319 C127.79,7319 128.883,7320.102 128.883,7321.46 C128.884,7322.819 127.79,7323.921 126.442,7323.921 L126.442,7323.921 Z M124,7339 L129,7339 L129,7326 L124,7326 L124,7339 Z" id="linkedin-[#161]">

                                    </path>
                                </g>
                            </g>
                        </g>
                    </svg>
                </a>
                <a href="https://www.tiktok.com/@lydiaslechon" class="ml-3 fill-gray-300 hover:fill-gray-500">
                    <span class="sr-only">Tiktok</span>
                    <svg class="h-8 w-8 " viewBox="0 0 48 48"  xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M14.4706 37.3231C15.4211 37.8246 16.4797 38.0864 17.5543 38.086C21.1338 38.086 24.0559 35.2387 24.1878 31.69L24.201 0H32.1076C32.1083 0.673085 32.1709 1.34468 32.2945 2.00632H26.5053V2.00742H34.4129C34.4118 4.65963 35.3731 7.22216 37.1185 9.21918L37.1207 9.22173C38.9011 10.3848 40.9821 11.0032 43.1088 11.0012V12.7622C43.8531 12.9216 44.6227 13.0065 45.4142 13.0065V20.9152C41.4667 20.9198 37.6179 19.6821 34.413 17.3775V33.4468C34.413 41.4709 27.8839 48 19.8586 48C17.8601 48.0005 15.883 47.5882 14.0512 46.7889C12.2207 45.9903 10.5749 44.8224 9.21653 43.3584L9.21355 43.3563C5.46035 40.7212 3 36.3633 3 31.4404C3 23.4151 9.52906 16.885 17.5543 16.885C18.2106 16.8881 18.8658 16.9359 19.5156 17.0279V18.9001C19.5613 18.8992 19.6067 18.8976 19.6523 18.8961C19.7207 18.8937 19.7893 18.8913 19.8586 18.8913C20.5148 18.8944 21.1701 18.9422 21.8198 19.0342V27.1068C21.1998 26.9122 20.5435 26.7989 19.8586 26.7989C18.0962 26.801 16.4066 27.5021 15.1605 28.7484C13.9144 29.9947 13.2136 31.6844 13.2119 33.4467C13.212 34.8387 13.6519 36.1951 14.4689 37.3222L14.4706 37.3231ZM6.26428 38.6397C6.93975 40.3996 7.9478 41.9961 9.21034 43.3529C7.9221 41.9743 6.92735 40.371 6.26428 38.6397Z" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
    
    <div class="relative">
        <hr class="w-full border-[#46B57C] mt-5">
        <div class="text-center w-full mt-5 text-gray-300">
            All information, pictures and images on this site are copyrighted material and owned by their respective creators or owners.
        </div>
        <div class="text-center w-full mt-2 text-gray-300">
            Copyright © 2020 - {{ now()->year }} | <a href="{{ config('app.url') }}" class="link-underline-light">Lydia’s Lechon</a>
        </div>
    </div>
</footer>