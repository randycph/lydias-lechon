<div>

    <div x-show="mapModal"
        x-transition
        class="relative z-50"
        aria-labelledby="modal-title"
        role="dialog"
        aria-modal="true"
        style="display: none;">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-gray-500/75 transition-opacity" aria-hidden="true"></div>

        <!-- Modal content -->
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 w-full max-w-2xl pb-5">
                    <!-- Modal body -->
                    <div class="">
                        <div class="flex flex-col items-center px-3 pt-3">
                            <button @click="mapModal = false" class="self-end text-2xl text-gray-800">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-7">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                </svg>
                            </button>
                            <div class="flex gap-2 items-center mt-2">
                                <div class="text-xl font-bold" x-text="mapAddress"></div>
                            </div>
                        </div>
            
                        <div class="text-gray-600 font-medium px-4 mt-4">
                            <iframe :src="embedMap" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>