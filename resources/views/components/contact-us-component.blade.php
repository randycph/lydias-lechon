<div>
    <!-- Drawer Overlay -->
    <div x-show="openContactUs" x-transition.opacity class="fixed inset-0 bg-black/50 z-40" @click="openContactUs = false"></div>
   
    <!-- Hotline Content -->
    <div x-show="openContactUs" x-transition:enter="transition-transform transform duration-300"
        x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
        x-transition:leave="transition-transform transform duration-300"
        x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
        class="fixed top-[2%] right-[5%] w-[90%] h-[90%] bg-white text-black z-50 py-3 flex flex-col max-w-lg shadow-lg rounded-lg overflow-x-scroll scrollbar-hide scroll-smooth snap-y">

        <!-- Cart Content -->
        <div class="" x-cloak>
            <div class="flex justify-between items-center px-3">
                <div class="flex gap-2 items-center">
                    <div class="text-2xl font-bold">Have some questions?</div>
                </div>
                <button @click="openContactUs = false" class="self-end text-2xl text-gray-800">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-7">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="text-gray-600 font-medium px-4 mt-4">
                For more inquiries, please send us a message by filling in the form below and we’ll get back to you.
            </div>

            <div class="text-gray-600 font-medium px-4 mt-4">
                If you prefer to speak with us directly, feel free to call our hotline. Want to visit us in person? Check out our store locations.
            </div>
            

            <div class="mt-5 px-4">
                <form class="mx-auto">
                    <div class="mb-5">
                      <label for="firstname" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">First name</label>
                      <input type="firstname" id="firstname" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Randy" required />
                    </div>
                    <div class="mb-5">
                      <label for="lastname" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Last name</label>
                      <input type="lastname" id="lastname" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Corpuz" required />
                    </div>
                    <div class="mb-5">
                      <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                      <input type="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="email@email.com" required />
                    </div>
                    <div class="mb-5">
                      <label for="phone" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Contact number</label>
                      <input type="tel" id="phone" pattern="[0-9]{3}-[0-9]{2}-[0-9]{3}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required />
                    </div>
                    <div class="mb-5">
                      <label for="message" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Message</label>
                      <textarea id="message" rows="4" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Your message here" required></textarea>
                    </div>
                    <button type="submit" class="text-white bg-primary hover:bg-primary-dark focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg w-full sm:w-auto px-5 py-3 text-center ">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>