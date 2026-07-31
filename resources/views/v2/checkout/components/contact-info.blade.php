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
                :readonly="!isGuest && contact.name"
                type="text"
                name="name"
                x-model="contact.name"
                class="bg-gray-50 border border-gray-300 text-sm rounded-md block w-full p-2.5 read-only:bg-gray-100 read-only:cursor-not-allowed"
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
                :readonly="!isGuest && contact.mobile"
                type="tel"
                name="mobile"
                x-model="contact.mobile"
                class="bg-gray-50 border border-gray-300 text-sm rounded-md block w-full p-2.5 read-only:bg-gray-100 read-only:cursor-not-allowed"
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
                :readonly="!isGuest && contact.email"
                type="email"
                name="email"
                x-model="contact.email"
                class="bg-gray-50 border border-gray-300 text-sm rounded-md block w-full p-2.5 read-only:bg-gray-100 read-only:cursor-not-allowed"
                :class="{'border-red-500': errors.email}"
            >

            <template x-if="errors.email">
                <p class="text-red-500 text-xs mt-1"
                   x-text="errors.email"></p>
            </template>
        </div>


        {{-- AGENT CODE --}}
        {{-- <div>
            <label class="block mb-2 text-sm font-bold">
                Agent Code
            </label>

            <input
                type="text"
                name="agent"
                x-model="contact.agent"
                class="bg-gray-50 border border-gray-300 text-sm rounded-md block w-full p-2.5"
            >
        </div> --}}


        {{-- SINGLE DELIVERY DATE + TIME (ONLY IF NOT MULTIPLE) --}}
        <template x-show="!allowMultiple">

        </template>


        {{-- PRIVACY AGREEMENT --}}
        <div class="flex items-center gap-2">

            <input
                id="privacy"
                type="checkbox"
                x-model="privacy"
                class="w-4 h-4"
                :class="{'border-red-500': errors.privacy}"
                @input="errors.privacy = null"
            >

            <label for="privacy" class="text-sm">
                I agree to the
                <button
                    type="button"
                    @click="openPrivacyModal"
                    class="text-primary underline"
                >
                    Privacy Protection Policy
                </button>
            </label>

        </div>

        <template x-if="errors.privacy">
            <p class="text-red-500 text-xs"
               x-text="errors.privacy"></p>
        </template>


        {{-- GLOBAL WARNING --}}
        <template x-if="warningMessage">
            <div class="text-red-700 bg-red-100 border-l-4 border-red-500 p-3 rounded"
                 x-html="warningMessage">
            </div>
        </template>
    </div>
</div>
