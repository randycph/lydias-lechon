{{-- Marketing popup --}}
<div >
    <!-- Drawer Overlay -->
    <div x-show="searchModal" x-transition.opacity class="fixed inset-0 bg-black/50 z-40" @click="searchModal = false"></div>

    <!-- Cart Drawer Content -->
    <div
    x-show="searchModal"
    x-cloak
    x-transition:enter="transition duration-300 ease-out"
    x-transition:enter-start="opacity-0 scale-90"
    x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="transition duration-200 ease-in"
    x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-90"
    class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[90%] max-w-[500px] lg:max-w-3xl h-max bg-white text-black z-50 flex flex-col shadow-lg rounded-lg"
  >
        <!-- Content -->
        <div class="w-full relative">
            <!-- Close Button -->
            <button @click="searchModal = false" class="absolute top-4 right-4 text-2xl text-gray-800">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class=" px-2 py-2 flex items-center justify-center bg-white rounded-full w-10 h-10 shadow-lg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>

            <div class="flex flex-col mt-5" x-data="voiceSearch()">
                
                <form class="flex items-center px-6 pt-16 w-full" >   
                    <label for="voice-search" class="sr-only">Search</label>
                    <div class="relative w-full">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <svg width="35" height="24" viewBox="0 0 35 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M26.0018 0C25.7655 0 25.5346 0.0375001 25.309 0.101786L22.3716 1.00714C21.4641 1.28571 20.7015 1.84286 20.1645 2.57143H12.8881C8.7317 2.57143 5.26265 5.51786 4.46788 9.42857H2.5454C2.08895 9.42857 1.71842 9.05893 1.71842 8.60357C1.71842 8.22322 1.97618 7.89107 2.34671 7.8L2.78706 7.69286C3.24888 7.58036 3.52812 7.11429 3.40998 6.65357C3.29184 6.19286 2.83002 5.91429 2.36819 6.03214L1.92785 6.13929C0.794768 6.41786 0 7.43571 0 8.60357C0 10.0071 1.13845 11.1429 2.5454 11.1429H4.29604V12C4.29604 14.8607 5.85873 17.3571 8.17322 18.6857L9.66609 22.8643C9.90774 23.5446 10.5575 24 11.2825 24H13.7473C14.6978 24 15.4657 23.2339 15.4657 22.2857V20.5714H17.1842H19.7618V22.2857C19.7618 23.2339 20.5297 24 21.4802 24H23.9451C24.67 24 25.3198 23.5446 25.5614 22.8643L26.5281 20.1696C27.9189 19.6875 29.1486 18.8036 30.0347 17.6143C30.0508 17.5929 30.0669 17.5821 30.0777 17.5768H30.083H30.0884H32.2257C33.4125 17.5768 34.3737 16.6179 34.3737 15.4339V11.1482C34.3737 9.96429 33.4125 9.00536 32.2257 9.00536H31.2215C30.9422 8.81786 30.6898 8.59286 30.4643 8.34643C31.8068 7.6125 32.6607 6.20357 32.6607 4.65536C32.6499 3.49821 31.7209 2.57143 30.5717 2.57143H28.3539V2.34643C28.3539 1.05 27.3013 0 26.0018 0ZM28.1981 4.28571H30.5663C30.765 4.28571 30.9261 4.44643 30.9261 4.64464C30.9261 5.64107 30.3354 6.53571 29.4225 6.93214C28.9392 6.31607 28.3968 5.75357 27.8008 5.26071C27.9672 4.95536 28.1015 4.62857 28.1928 4.28571H28.1981ZM25.8138 1.74107C25.8729 1.725 25.9373 1.71429 26.0018 1.71429C26.3508 1.71429 26.6355 1.99821 26.6355 2.34643V3.1125C26.6355 3.78214 26.3938 4.40357 25.991 4.89643C25.8407 5.07857 25.7709 5.31429 25.7977 5.55C25.8246 5.78571 25.9535 6 26.1468 6.13929C27.108 6.83571 27.9457 7.70893 28.6063 8.7375C29.0466 9.41786 29.6212 9.99643 30.2871 10.4464C30.5771 10.6393 30.8993 10.7196 31.2054 10.7196H32.2203C32.4566 10.7196 32.6499 10.9125 32.6499 11.1482V15.4339C32.6499 15.6696 32.4566 15.8625 32.2203 15.8625H30.083C29.487 15.8625 28.9714 16.1679 28.6546 16.5964C27.9028 17.6036 26.8395 18.3268 25.6313 18.6589C25.3628 18.7339 25.1479 18.9321 25.0513 19.1946L23.9451 22.2857H21.4802V19.7143C21.4802 19.2429 21.0936 18.8571 20.621 18.8571H17.1842H14.6065C14.134 18.8571 13.7473 19.2429 13.7473 19.7143V22.2857H11.2825L9.67683 17.8018C9.60165 17.5982 9.45666 17.4268 9.26334 17.3304C7.33012 16.3286 6.01446 14.3196 6.01446 12V11.1429C6.01446 7.35536 9.0915 4.28571 12.8881 4.28571H20.433C20.4921 4.28571 20.5512 4.28571 20.6156 4.28571C20.9325 4.29107 21.2278 4.125 21.3835 3.84643C21.7004 3.27857 22.2266 2.83929 22.8818 2.64107L25.8192 1.74107H25.8138ZM25.1318 12C25.4167 12 25.6899 11.8871 25.8913 11.6862C26.0927 11.4853 26.2058 11.2127 26.2058 10.9286C26.2058 10.6444 26.0927 10.3719 25.8913 10.171C25.6899 9.97003 25.4167 9.85714 25.1318 9.85714C24.847 9.85714 24.5738 9.97003 24.3724 10.171C24.171 10.3719 24.0578 10.6444 24.0578 10.9286C24.0578 11.2127 24.171 11.4853 24.3724 11.6862C24.5738 11.8871 24.847 12 25.1318 12Z" fill="gray"/>
                            </svg>
                        </div>
                        <input type="text" x-model="search" id="voice-search" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-16 px-2.5 py-3.5  " placeholder="Search Lechon, Deserts, Menus, Articles, etc..." required />
                        <button aria-label="Enable voice search" @click="startListening" type="button" class="absolute inset-y-0 end-0 flex items-center pe-3 hover:opacity-70">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 18.75a6 6 0 0 0 6-6v-1.5m-6 7.5a6 6 0 0 1-6-6v-1.5m6 7.5v3.75m-3.75 0h7.5M12 15.75a3 3 0 0 1-3-3V4.5a3 3 0 1 1 6 0v8.25a3 3 0 0 1-3 3Z" />
                            </svg>
                        </button>
                    </div>
                    <button type="submit" class="inline-flex items-center py-2.5 px-3 ms-2 text-sm font-medium text-white bg-primary rounded-lg border border-primary hover:bg-primary-dark focus:ring-4">
                        <svg class="w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                        </svg>Search
                    </button>
                </form>

                <div x-show="search" class="px-6 mt-5">
                    Your search for: <span x-text="search" class="font-semibold"></span>
                </div>

                @php

                $searches = [
                    [
                        'title' => 'Petite',
                        'price' => '₱9,800',
                        'image' => asset('images/petite.png'),
                        'description' => 'Good for 8-10 persons. Add ₱2,800 for Boneless Lechon Stuffed with Seafood Paella'
                    ],
                    [
                        'title' => 'COCHINILLO',
                        'price' => '₱10,800',
                        'image' => asset('images/chochinillo.png'),
                        'description' => 'Good for 8-10 persons. FREE Mexican Flavored Rice'
                    ],
                    [
                        'title' => 'De leche',
                        'price' => '₱12,800',
                        'image' => asset('images/deleche.png'),
                        'description' => 'Good for 12-15 persons. Add ₱2,800 for Boneless Lechon Stuffed with Seafood Paella'
                    ]
                ];

                @endphp

                <ul role="list" class="divide-y divide-gray-100 w-full px-6 py-5">
                    @foreach ($searches as $search)
                    <li class="flex justify-between gap-x-6 py-5 hover:bg-gray-100 cursor-pointer rounded-md">
                      <div class="flex min-w-0 gap-x-4">
                        <img class="size-20 flex-none rounded-full bg-gray-50" src="{{ $search['image'] }}" alt="Search 1">
                        <div class="min-w-0 flex-auto">
                          <p class="text-lg font-semibold text-gray-900">{{ $search['title'] }}</p>
                          <p class="truncate text-base/5 text-gray-500">{{ $search['price'] }}</p>
                          <p class="text-sm text-gray-500">{{ $search['description'] }}</p>
                        </div>
                      </div>
                    </li>
                    @endforeach
                  </ul>
                  
            </div>
        </div>
    </div>
</div>