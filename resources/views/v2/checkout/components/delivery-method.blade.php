<div class="w-full rounded-lg border bg-white border-[#DFDFDF] shadow-md">

    {{-- HEADER --}}
    <div class="px-4 py-3 border-b border-[#DFDFDF]">
        <h2 class="text-lg lg:text-3xl font-semibold">
            Delivery Information
        </h2>
    </div>

    {{-- METHOD SELECTOR --}}
    <div class="px-4 py-5 space-y-4">

        <div class="font-bold text-gray-800">
            Choose Pickup or Delivery
        </div>

        <div class="flex gap-4">
            {{-- PICKUP --}}
            <button
                type="button"
                @click="changeMethod('pickup')"
                class="w-full px-6 py-3 rounded-md border-2 transition"
                :class="method === 'pickup'
                    ? 'bg-green-700 border-green-700 text-white'
                    : 'bg-gray-100 border-gray-300 text-gray-700 hover:bg-gray-200'"
            >
                Pickup
            </button>

            {{-- DELIVERY --}}
            <button
                type="button"
                @click="changeMethod('delivery')"
                class="w-full px-6 py-3 rounded-md border-2 transition"
                :class="method === 'delivery'
                    ? 'bg-green-700 border-green-700 text-white'
                    : 'bg-gray-100 border-gray-300 text-gray-700 hover:bg-gray-200'"
            >
                Delivery
            </button>
        </div>

        {{-- MULTIPLE DELIVERY TOGGLE (OPTIONAL / FUTURE-PROOF) --}}
        <template x-if="method === 'delivery'">
            <div class="pt-2">

                {{-- You can uncomment when ready --}}
                
                <label class="flex items-center gap-2 cursor-pointer">
                    <input
                        type="checkbox"
                        x-model="allowMultiple"
                        @change="onChangeMultipleAddress()"
                        class="w-5 h-5 text-green-700 border-gray-300 rounded"
                    >
                    <span class="text-sm font-medium text-gray-700">
                        Allow multiple delivery addresses
                    </span>
                </label>
               

            </div>
        </template>

    </div>

</div>
