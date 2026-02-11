<div class="w-full rounded-lg border bg-white border-[#DFDFDF] shadow-md">

    {{-- HEADER --}}
    <div class="px-4 py-3 border-b border-[#DFDFDF]">
        <h2 class="text-lg lg:text-2xl font-semibold">
            Review & Place Order
        </h2>
    </div>

    <div class="px-4 py-5 space-y-5">

        {{-- ERROR MESSAGE --}}
        <template x-if="hasErrorMessage">
            <div class="text-red-700 bg-red-100 border-l-4 border-red-500 p-3 rounded">
                We are not able to accommodate your order based on your selected
                date and time. Please adjust your schedule or contact our hotline.
            </div>
        </template>


        {{-- WARNING MESSAGE --}}
        <template x-if="warningMessage">
            <div class="text-yellow-700 bg-yellow-100 border-l-4 border-yellow-500 p-3 rounded"
                 x-html="warningMessage">
            </div>
        </template>


        {{-- SUMMARY QUICK CHECK --}}
        <div class="text-sm text-gray-600 space-y-1">

            <div>
                <span class="font-medium">Method:</span>
                <span x-text="method === 'pickup' ? 'Pickup' : 'Delivery'"></span>
            </div>

            <template x-if="method === 'delivery' && !allowMultiple">
                <div>
                    <span class="font-medium">Deliver To:</span>
                    <span x-text="delivery_address"></span>
                </div>
            </template>

            <template x-if="method === 'delivery' && allowMultiple">
                <div>
                    <span class="font-medium">Delivery Addresses:</span>
                    <span x-text="deliveries.length + ' locations'"></span>
                </div>
            </template>

            <div>
                <span class="font-medium">Total:</span>
                <span x-text="computeTotal()"></span>
            </div>

        </div>


        {{-- PLACE ORDER BUTTON --}}
        <button
            type="submit"
            :disabled="isSubmitting"
            class="bg-primary hover:bg-primary-dark text-white px-6 py-4 w-full rounded-md disabled:opacity-50 disabled:bg-gray-400 transition"
        >
            <span x-show="!isSubmitting">
                Place Order
            </span>

            <span x-show="isSubmitting" class="flex items-center justify-center gap-2">

                <svg class="animate-spin h-5 w-5 text-white"
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
