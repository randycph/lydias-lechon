<div
    x-cloak
    x-show="paymentModal"
    x-transition.opacity
    class="fixed inset-0 z-50"
>

    {{-- BACKDROP --}}
    <div
        class="fixed inset-0 bg-black/50"
        @click="closePaymentModal"
    ></div>


    {{-- MODAL CONTAINER --}}
    <div class="fixed inset-0 flex items-center justify-center p-4">

        <div
            x-show="paymentModal"
            x-transition
            class="w-full max-w-lg"
        >

            <div class="relative bg-white rounded-2xl shadow-2xl overflow-hidden">

                {{-- HEADER --}}
                <div class="px-6 py-4 border-b flex justify-between items-center">
                    <h2 class="text-xl font-semibold">
                        Complete Your Payment
                    </h2>

                    <button
                        type="button"
                        @click="closePaymentModal"
                        class="text-gray-500 hover:text-gray-700"
                    >
                        ✕
                    </button>
                </div>


                {{-- BODY --}}
                <div class="px-6 py-6 space-y-6">

                    {{-- ORDER NUMBER --}}
                    <div class="text-sm text-gray-600">
                        Order #: 
                        <span class="font-semibold"
                              x-text="paymentDetails.order_number"></span>
                    </div>


                    {{-- AMOUNT --}}
                    <div>
                        <label class="block text-sm font-semibold mb-1">
                            Amount to Pay
                        </label>

                        <div class="flex">
                            <span
                                class="inline-flex items-center px-3 bg-gray-200 border border-r-0 rounded-l-md">
                                ₱
                            </span>

                            <input
                                type="text"
                                readonly
                                :value="paymentDetails.amount"
                                class="bg-gray-50 border border-gray-300 rounded-r-md w-full p-2.5"
                            >
                        </div>
                    </div>


                    {{-- PAYMENT METHOD --}}
                    <div>
                        <label class="block text-sm font-semibold mb-2">
                            Available Payment Options
                        </label>

                        {{-- Hidden field keeps paymentMode = PayMaya for submitPayment() --}}
                        <input type="hidden" x-model="paymentMode" value="PayMaya">

                        <div class="bg-gray-50 border border-gray-300 rounded-md p-3">
                            <p class="text-xs text-gray-500 mb-2">
                                You'll be redirected to PayMaya, where you can pay using any of the options below — a Maya account is not required.
                            </p>

                            <div class="flex flex-wrap items-center gap-3 justify-center">
                                <img src="{{ asset('payments/maya.webp') }}" alt="Maya" class="h-12">
                                <img src="{{ asset('payments/qrph.png') }}" alt="Gcash" class="h-4">
                                <img src="{{ asset('payments/gcash.webp') }}" alt="QR Ph" class="h-12">
                                <img src="{{ asset('payments/mastercard.svg') }}" alt="Mastercard" class="h-8">
                                <img src="{{ asset('payments/visa.svg') }}" alt="Visa" class="h-8">
                                <img src="{{ asset('payments/jcb.svg') }}" alt="JCB" class="h-8">
                            </div>
                        </div>
                    </div>

                </div>


                {{-- FOOTER --}}
                <div class="px-6 py-4 border-t flex justify-end gap-3">

                    <button
                        type="button"
                        @click="cancelPayment()"
                        class="px-4 py-2 border rounded-md text-sm"
                    >
                        Cancel
                    </button>

                    <button
                        type="button"
                        @click="submitPayment"
                        :disabled="isProcessingPayment"
                        class="px-6 py-2 bg-primary text-white rounded-md text-sm disabled:opacity-50"
                    >
                        <span x-show="!isProcessingPayment">
                            Pay Now
                        </span>

                        <span x-show="isProcessingPayment"
                              class="flex items-center gap-2">

                            <svg class="animate-spin h-4 w-4 text-white"
                                 xmlns="http://www.w3.org/2000/svg"
                                 fill="none"
                                 viewBox="0 0 24 24">
                                <circle class="opacity-25"
                                        cx="12"
                                        cy="12"
                                        r="10"
                                        stroke="currentColor"
                                        stroke-width="4"></circle>
                                <path class="opacity-75"
                                      fill="currentColor"
                                      d="M4 12a8 8 0 018-8v8z"></path>
                            </svg>

                            Processing...
                        </span>
                    </button>

                </div>

            </div>
        </div>
    </div>
</div>
