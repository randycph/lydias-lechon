<div x-show="openHotline" x-transition.opacity class="fixed inset-0 bg-black/50 z-40 overflow-y-auto py-10 px-4"
    @click.self="openHotline = false">
    <div x-show="openHotline" 
        x-transition:enter="transition-transform transform duration-300"
        x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
        x-transition:leave="transition-transform transform duration-300"
        x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
        class="relative ml-auto bg-white text-black z-50 w-full max-w-lg rounded-lg shadow-lg">

        <div class="py-3">
            <div class="">
                <div class="flex justify-between items-center px-3">
                    <div class="flex gap-2 items-center">
                        <div class="text-2xl font-bold">Call Our Hotlines</div>
                    </div>
                    <button @click="openHotline = false" class="self-end text-2xl text-gray-800">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-7">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="text-gray-600 font-medium px-4 mt-4">
                    Reach out to us through our hotlines for quick and reliable assistance.
                </div>

                <div class="px-4 mt-5">
                    <div class="font-bold text-primary">Roces Branch</div>
                    <div>
                        <a href="tel:+6383761818" class="flex items-center gap-2 px-4 py-2 border-b border-gray-200">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                                class="size-4">
                                <path fill-rule="evenodd"
                                    d="m3.855 7.286 1.067-.534a1 1 0 0 0 .542-1.046l-.44-2.858A1 1 0 0 0 4.036 2H3a1 1 0 0 0-1 1v2c0 .709.082 1.4.238 2.062a9.012 9.012 0 0 0 6.7 6.7A9.024 9.024 0 0 0 11 14h2a1 1 0 0 0 1-1v-1.036a1 1 0 0 0-.848-.988l-2.858-.44a1 1 0 0 0-1.046.542l-.534 1.067a7.52 7.52 0 0 1-4.86-4.859Z"
                                    clip-rule="evenodd" />
                            </svg>
                            <div class="text-base">(+632) 8376-1818</div>
                        </a>
                        <a href="tel:+63287369664" class="flex items-center gap-2 px-4 py-2 border-b border-gray-200">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                                class="size-4">
                                <path fill-rule="evenodd"
                                    d="m3.855 7.286 1.067-.534a1 1 0 0 0 .542-1.046l-.44-2.858A1 1 0 0 0 4.036 2H3a1 1 0 0 0-1 1v2c0 .709.082 1.4.238 2.062a9.012 9.012 0 0 0 6.7 6.7A9.024 9.024 0 0 0 11 14h2a1 1 0 0 0 1-1v-1.036a1 1 0 0 0-.848-.988l-2.858-.44a1 1 0 0 0-1.046.542l-.534 1.067a7.52 7.52 0 0 1-4.86-4.859Z"
                                    clip-rule="evenodd" />
                            </svg>
                            <div class="text-base">(+632) 8736-9664</div>
                        </a>
                        <a href="tel:+637091-0395" class="flex items-center gap-2 px-4 py-2 border-b border-gray-200">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                                class="size-4">
                                <path fill-rule="evenodd"
                                    d="m3.855 7.286 1.067-.534a1 1 0 0 0 .542-1.046l-.44-2.858A1 1 0 0 0 4.036 2H3a1 1 0 0 0-1 1v2c0 .709.082 1.4.238 2.062a9.012 9.012 0 0 0 6.7 6.7A9.024 9.024 0 0 0 11 14h2a1 1 0 0 0 1-1v-1.036a1 1 0 0 0-.848-.988l-2.858-.44a1 1 0 0 0-1.046.542l-.534 1.067a7.52 7.52 0 0 1-4.86-4.859Z"
                                    clip-rule="evenodd" />
                            </svg>
                            <div class="text-base">Globe - 7091-0395</div>
                        </a>
                    </div>
                </div>

                <div class="px-4 mt-5">
                    <div class="font-bold text-primary">Commonwealth Branch</div>
                    <div>
                        <a href="tel:+6389355095" class="flex items-center gap-2 px-4 py-2 border-b border-gray-200">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                                class="size-4">
                                <path fill-rule="evenodd"
                                    d="m3.855 7.286 1.067-.534a1 1 0 0 0 .542-1.046l-.44-2.858A1 1 0 0 0 4.036 2H3a1 1 0 0 0-1 1v2c0 .709.082 1.4.238 2.062a9.012 9.012 0 0 0 6.7 6.7A9.024 9.024 0 0 0 11 14h2a1 1 0 0 0 1-1v-1.036a1 1 0 0 0-.848-.988l-2.858-.44a1 1 0 0 0-1.046.542l-.534 1.067a7.52 7.52 0 0 1-4.86-4.859Z"
                                    clip-rule="evenodd" />
                            </svg>
                            <div class="text-base">(+632) 8935 5095</div>
                        </a>
                        <a href="tel:+63284019867" class="flex items-center gap-2 px-4 py-2 border-b border-gray-200">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                                class="size-4">
                                <path fill-rule="evenodd"
                                    d="m3.855 7.286 1.067-.534a1 1 0 0 0 .542-1.046l-.44-2.858A1 1 0 0 0 4.036 2H3a1 1 0 0 0-1 1v2c0 .709.082 1.4.238 2.062a9.012 9.012 0 0 0 6.7 6.7A9.024 9.024 0 0 0 11 14h2a1 1 0 0 0 1-1v-1.036a1 1 0 0 0-.848-.988l-2.858-.44a1 1 0 0 0-1.046.542l-.534 1.067a7.52 7.52 0 0 1-4.86-4.859Z"
                                    clip-rule="evenodd" />
                            </svg>
                            <div class="text-base">(+632) 8401 9867</div>
                        </a>
                    </div>
                </div>

                <div class="px-4 mt-5">
                    <div class="font-bold text-primary">Marcos Highway Branch</div>
                    <div>
                        <a href="tel:+63286460871" class="flex items-center gap-2 px-4 py-2 border-b border-gray-200">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                                class="size-4">
                                <path fill-rule="evenodd"
                                    d="m3.855 7.286 1.067-.534a1 1 0 0 0 .542-1.046l-.44-2.858A1 1 0 0 0 4.036 2H3a1 1 0 0 0-1 1v2c0 .709.082 1.4.238 2.062a9.012 9.012 0 0 0 6.7 6.7A9.024 9.024 0 0 0 11 14h2a1 1 0 0 0 1-1v-1.036a1 1 0 0 0-.848-.988l-2.858-.44a1 1 0 0 0-1.046.542l-.534 1.067a7.52 7.52 0 0 1-4.86-4.859Z"
                                    clip-rule="evenodd" />
                            </svg>
                            <div class="text-base">(+632) 8646-0871</div>
                        </a>
                        <a href="tel:+63284019791" class="flex items-center gap-2 px-4 py-2 border-b border-gray-200">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                                class="size-4">
                                <path fill-rule="evenodd"
                                    d="m3.855 7.286 1.067-.534a1 1 0 0 0 .542-1.046l-.44-2.858A1 1 0 0 0 4.036 2H3a1 1 0 0 0-1 1v2c0 .709.082 1.4.238 2.062a9.012 9.012 0 0 0 6.7 6.7A9.024 9.024 0 0 0 11 14h2a1 1 0 0 0 1-1v-1.036a1 1 0 0 0-.848-.988l-2.858-.44a1 1 0 0 0-1.046.542l-.534 1.067a7.52 7.52 0 0 1-4.86-4.859Z"
                                    clip-rule="evenodd" />
                            </svg>
                            <div class="text-base">(+632) 8401 9791</div>
                        </a>
                    </div>
                </div>

                <div class="px-4 mt-5">
                    <div class="font-bold text-primary">Pasig Branch</div>
                    <div>
                        <a href="tel:+63286719053" class="flex items-center gap-2 px-4 py-2 border-b border-gray-200">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                                class="size-4">
                                <path fill-rule="evenodd"
                                    d="m3.855 7.286 1.067-.534a1 1 0 0 0 .542-1.046l-.44-2.858A1 1 0 0 0 4.036 2H3a1 1 0 0 0-1 1v2c0 .709.082 1.4.238 2.062a9.012 9.012 0 0 0 6.7 6.7A9.024 9.024 0 0 0 11 14h2a1 1 0 0 0 1-1v-1.036a1 1 0 0 0-.848-.988l-2.858-.44a1 1 0 0 0-1.046.542l-.534 1.067a7.52 7.52 0 0 1-4.86-4.859Z"
                                    clip-rule="evenodd" />
                            </svg>
                            <div class="text-base">(+632) 8671-9053</div>
                        </a>
                        <a href="tel:+63279332961" class="flex items-center gap-2 px-4 py-2 border-b border-gray-200">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                                class="size-4">
                                <path fill-rule="evenodd"
                                    d="m3.855 7.286 1.067-.534a1 1 0 0 0 .542-1.046l-.44-2.858A1 1 0 0 0 4.036 2H3a1 1 0 0 0-1 1v2c0 .709.082 1.4.238 2.062a9.012 9.012 0 0 0 6.7 6.7A9.024 9.024 0 0 0 11 14h2a1 1 0 0 0 1-1v-1.036a1 1 0 0 0-.848-.988l-2.858-.44a1 1 0 0 0-1.046.542l-.534 1.067a7.52 7.52 0 0 1-4.86-4.859Z"
                                    clip-rule="evenodd" />
                            </svg>
                            <div class="text-base">Globe - 7933-2961</div>
                        </a>
                    </div>
                </div>

                <div class="px-4 mt-5">
                    <div class="font-bold text-primary">BF Aguirre, Paranaque Branch</div>
                    <div>
                        <a href="tel:+63288610608" class="flex items-center gap-2 px-4 py-2 border-b border-gray-200">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                                class="size-4">
                                <path fill-rule="evenodd"
                                    d="m3.855 7.286 1.067-.534a1 1 0 0 0 .542-1.046l-.44-2.858A1 1 0 0 0 4.036 2H3a1 1 0 0 0-1 1v2c0 .709.082 1.4.238 2.062a9.012 9.012 0 0 0 6.7 6.7A9.024 9.024 0 0 0 11 14h2a1 1 0 0 0 1-1v-1.036a1 1 0 0 0-.848-.988l-2.858-.44a1 1 0 0 0-1.046.542l-.534 1.067a7.52 7.52 0 0 1-4.86-4.859Z"
                                    clip-rule="evenodd" />
                            </svg>
                            <div class="text-base">(+632) 8861-0608</div>
                        </a>
                        <a href="tel:+63285566741" class="flex items-center gap-2 px-4 py-2 border-b border-gray-200">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                                class="size-4">
                                <path fill-rule="evenodd"
                                    d="m3.855 7.286 1.067-.534a1 1 0 0 0 .542-1.046l-.44-2.858A1 1 0 0 0 4.036 2H3a1 1 0 0 0-1 1v2c0 .709.082 1.4.238 2.062a9.012 9.012 0 0 0 6.7 6.7A9.024 9.024 0 0 0 11 14h2a1 1 0 0 0 1-1v-1.036a1 1 0 0 0-.848-.988l-2.858-.44a1 1 0 0 0-1.046.542l-.534 1.067a7.52 7.52 0 0 1-4.86-4.859Z"
                                    clip-rule="evenodd" />
                            </svg>
                            <div class="text-base">(+632) 8556-6741</div>
                        </a>
                        <a href="tel:+639171685485" class="flex items-center gap-2 px-4 py-2 border-b border-gray-200">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                                class="size-4">
                                <path d="M7.25 11.5a.75.75 0 0 0 0 1.5h1.5a.75.75 0 0 0 0-1.5h-1.5Z" />
                                <path fill-rule="evenodd"
                                    d="M6 1a2.5 2.5 0 0 0-2.5 2.5v9A2.5 2.5 0 0 0 6 15h4a2.5 2.5 0 0 0 2.5-2.5v-9A2.5 2.5 0 0 0 10 1H6Zm4 1.5h-.5V3a.5.5 0 0 1-.5.5H7a.5.5 0 0 1-.5-.5v-.5H6a1 1 0 0 0-1 1v9a1 1 0 0 0 1 1h4a1 1 0 0 0 1-1v-9a1 1 0 0 0-1-1Z"
                                    clip-rule="evenodd" />
                            </svg>
                            <div class="text-base">Globe - 09171685485</div>
                        </a>
                    </div>
                </div>

                <div class="px-4 mt-5">
                    <div class="font-bold text-primary">Cash and Carry, Makati Branch</div>
                    <div>
                        <a href="tel:+63284747558" class="flex items-center gap-2 px-4 py-2 border-b border-gray-200">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                                class="size-4">
                                <path fill-rule="evenodd"
                                    d="m3.855 7.286 1.067-.534a1 1 0 0 0 .542-1.046l-.44-2.858A1 1 0 0 0 4.036 2H3a1 1 0 0 0-1 1v2c0 .709.082 1.4.238 2.062a9.012 9.012 0 0 0 6.7 6.7A9.024 9.024 0 0 0 11 14h2a1 1 0 0 0 1-1v-1.036a1 1 0 0 0-.848-.988l-2.858-.44a1 1 0 0 0-1.046.542l-.534 1.067a7.52 7.52 0 0 1-4.86-4.859Z"
                                    clip-rule="evenodd" />
                            </svg>
                            <div class="text-base">(+632) 8474-7558</div>
                        </a>
                        <a href="tel:+639171685485" class="flex items-center gap-2 px-4 py-2 border-b border-gray-200">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                                class="size-4">
                                <path d="M7.25 11.5a.75.75 0 0 0 0 1.5h1.5a.75.75 0 0 0 0-1.5h-1.5Z" />
                                <path fill-rule="evenodd"
                                    d="M6 1a2.5 2.5 0 0 0-2.5 2.5v9A2.5 2.5 0 0 0 6 15h4a2.5 2.5 0 0 0 2.5-2.5v-9A2.5 2.5 0 0 0 10 1H6Zm4 1.5h-.5V3a.5.5 0 0 1-.5.5H7a.5.5 0 0 1-.5-.5v-.5H6a1 1 0 0 0-1 1v9a1 1 0 0 0 1 1h4a1 1 0 0 0 1-1v-9a1 1 0 0 0-1-1Z"
                                    clip-rule="evenodd" />
                            </svg>
                            <div class="text-base">Globe - 0917-1685485</div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>