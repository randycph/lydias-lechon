<div class="w-full rounded-lg border bg-white border-[#DFDFDF] shadow-md">

    {{-- HEADER --}}
    <div class="px-4 py-3 border-b border-[#DFDFDF]">
        <h2 class="text-lg lg:text-2xl font-semibold">
            Contact Information
        </h2>
    </div>

    <div class="px-4 py-5 space-y-6">

        {{-- NAME --}}
        <div>
            <label class="block mb-2 text-sm font-bold">
                Name <span class="text-red-600">*</span>
            </label>

            <input
                type="text"
                name="name"
                x-model="contact.name"
                class="bg-gray-50 border border-gray-300 text-sm rounded-md block w-full p-2.5"
                :class="{'border-red-500': errors.name}"
            >

            <template x-if="errors.name">
                <p class="text-red-500 text-xs mt-1"
                   x-text="errors.name"></p>
            </template>
        </div>


        {{-- MOBILE --}}
        <div>
            <label class="block mb-2 text-sm font-bold">
                Mobile Number <span class="text-red-600">*</span>
            </label>

            <input
                type="tel"
                name="mobile"
                x-model="contact.mobile"
                placeholder="e.g. 09171234567"
                class="bg-gray-50 border border-gray-300 text-sm rounded-md block w-full p-2.5"
                :class="{'border-red-500': errors.mobile}"
            >

            <template x-if="errors.mobile">
                <p class="text-red-500 text-xs mt-1"
                   x-text="errors.mobile"></p>
            </template>
        </div>


        {{-- EMAIL --}}
        <div>
            <label class="block mb-2 text-sm font-bold">
                Email <span class="text-red-600">*</span>
            </label>

            <input
                type="email"
                name="email"
                x-model="contact.email"
                class="bg-gray-50 border border-gray-300 text-sm rounded-md block w-full p-2.5"
                :class="{'border-red-500': errors.email}"
            >

            <template x-if="errors.email">
                <p class="text-red-500 text-xs mt-1"
                   x-text="errors.email"></p>
            </template>
        </div>


        {{-- AGENT CODE --}}
        <div>
            <label class="block mb-2 text-sm font-bold">
                Agent Code
            </label>

            <input
                type="text"
                name="agent"
                x-model="contact.agent"
                class="bg-gray-50 border border-gray-300 text-sm rounded-md block w-full p-2.5"
            >
        </div>


        {{-- SINGLE DELIVERY DATE + TIME (ONLY IF NOT MULTIPLE) --}}
        <template x-if="!allowMultiple">

            <div class="space-y-4">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    {{-- DATE --}}
                    <div>
                        <label class="block mb-2 text-sm font-bold">
                            Select Date <span class="text-red-600">*</span>
                        </label>

                        <input
                            type="text"
                            x-model="need_date"
                            readonly
                            class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-2.5"
                            :class="{'border-red-500': errors.need_date}"
                        >

                        <template x-if="errors.need_date">
                            <p class="text-red-500 text-xs mt-1"
                               x-text="errors.need_date"></p>
                        </template>
                    </div>


                    {{-- TIME --}}
                    <div>
                        <label class="block mb-2 text-sm font-bold">
                            Select Time <span class="text-red-600">*</span>
                        </label>

                        <select
                            x-model="need_time"
                            class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-2.5"
                            :class="{'border-red-500': errors.need_time}"
                        >
                            <option value="">Select Hour</option>

                            <template x-for="hour in allHours" :key="hour">
                                <option
                                    :value="formatHourValue(hour)"
                                    x-text="formatAMPM(hour)">
                                </option>
                            </template>
                        </select>

                        <template x-if="errors.need_time">
                            <p class="text-red-500 text-xs mt-1"
                               x-text="errors.need_time"></p>
                        </template>
                    </div>

                </div>


                {{-- NOTE --}}
                <div>
                    <label class="block mb-2 text-sm font-bold">
                        Note
                    </label>

                    <textarea
                        name="instruction"
                        x-model="note"
                        rows="3"
                        class="bg-gray-50 border border-gray-300 text-sm rounded-md block w-full p-2.5"
                        placeholder="Add instructions or notes..."
                    ></textarea>
                </div>

            </div>

        </template>


        {{-- PRIVACY AGREEMENT --}}
        @if(auth()->guest())
        <div class="flex items-center gap-2">

            <input
                type="checkbox"
                x-model="privacy"
                class="w-4 h-4"
            >

            <span class="text-sm">
                I agree to the
                <button
                    type="button"
                    @click="openPrivacyModal"
                    class="text-primary underline"
                >
                    Privacy Protection Policy
                </button>
            </span>

        </div>

        <template x-if="errors.privacy">
            <p class="text-red-500 text-xs"
               x-text="errors.privacy"></p>
        </template>
        @endif


        {{-- GLOBAL WARNING --}}
        <template x-if="warningMessage">
            <div class="text-red-700 bg-red-100 border-l-4 border-red-500 p-3 rounded"
                 x-html="warningMessage">
            </div>
        </template>


        {{-- SUBMIT --}}
        <button
            type="submit"
            :disabled="isSubmitting"
            class="bg-primary text-white px-6 py-4 w-full rounded-md disabled:opacity-50"
        >
            <span x-show="!isSubmitting">Place Order</span>

            <span x-show="isSubmitting">
                Processing...
            </span>
        </button>

    </div>

</div>
