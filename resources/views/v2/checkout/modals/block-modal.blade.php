<div
    x-cloak
    x-show="blockModal"
    x-transition.opacity
    class="fixed inset-0 z-50"
>

    {{-- BACKDROP --}}
    <div
        class="fixed inset-0 bg-black/50"
        @click="closeBlockModal"
    ></div>

    {{-- MODAL CONTAINER --}}
    <div class="fixed inset-0 flex items-center justify-center p-4">

        <div
            x-show="blockModal"
            x-transition
            class="w-full max-w-lg"
        >

            <div class="relative bg-white rounded-2xl shadow-2xl overflow-hidden">

                {{-- HEADER --}}
                <div class="px-6 py-4 border-b flex justify-between items-center">
                    <h2 class="text-xl font-semibold text-red-600">
                        Schedule Unavailable
                    </h2>

                    <button
                        type="button"
                        @click="closeBlockModal"
                        class="text-gray-500 hover:text-gray-700"
                    >
                        ✕
                    </button>
                </div>


                {{-- BODY --}}
                <div class="px-6 py-6 space-y-5">

                    {{-- MAIN MESSAGE --}}
                    <div class="text-sm text-gray-700 leading-relaxed">
                        <p>
                            We’re sorry, but your selected schedule cannot proceed due to blocked dates or unavailable delivery/pickup options.
                        </p>
                    </div>

                    {{-- DATE INFO --}}
                    <div x-show="blockedDetails.date">
                        <span class="font-semibold text-sm">Selected Date:</span>
                        <span class="text-sm" x-text="blockedDetails.date"></span>
                    </div>

                    {{-- REASON LIST --}}
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                            <template x-for="reason in blockedDetails.reasons" :key="reason">
                                <li x-text="reason"></li>
                            </template>
                        </ul>
                    </div>

                    {{-- INSTRUCTION --}}
                    <div class="text-sm text-gray-600">
                        Please choose another date, change your delivery method, or remove unavailable items from your cart to continue.
                    </div>

                </div>

                {{-- FOOTER --}}
                <div class="px-6 py-4 border-t flex justify-end">

                    <button
                        type="button"
                        @click="closeBlockModal"
                        class="px-5 py-2 bg-primary text-white rounded-md text-sm"
                    >
                        Adjust Selection
                    </button>

                </div>

            </div>
        </div>
    </div>
</div>
