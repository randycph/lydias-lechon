<div class="w-full rounded-lg border bg-white border-[#DFDFDF] shadow-md">

    {{-- HEADER --}}
    <div class="px-4 py-3 border-b border-[#DFDFDF]">
        <h2 class="text-lg lg:text-2xl font-semibold">
            Delivery Information
        </h2>
    </div>

    <div class="px-4 py-5 space-y-6">

        {{-- ADDRESS --}}
        <div>
            <label class="block font-bold mb-2">
                Address <small class="font-normal">Street, Building, House No.</small>
                <span class="text-red-600">*</span>
            </label>

            <textarea
                x-model="delivery_address"
                @focus="startEditingAddress"
                @input="onAddressInput"
                @blur="validateSingleDeliveryField('address'); finishEditingAddress()"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-md block w-full p-2.5"
                :class="{'border-red-500': singleDeliveryErrors.address}"
                rows="3"
            ></textarea>

            <template x-if="singleDeliveryErrors.address">
                <p class="text-red-500 text-xs mt-1"
                   x-text="singleDeliveryErrors.address"></p>
            </template>
        </div>


        {{-- PROVINCE + CITY --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            <div>
                <label class="block font-bold mb-2 text-sm">
                    Province <span class="text-red-600">*</span>
                </label>

                <select
                    x-model="province"
                    @change="onProvinceChange()"
                    @input="singleDeliveryErrors.province = ''"
                    class="bg-white border border-gray-300 text-sm rounded-lg block w-full p-2.5"
                    :class="{'border-red-500': singleDeliveryErrors.province}"
                >
                    <option value="">Choose province</option>

                    @foreach ($provinces as $province)
                        <option value="{{ $province }}">
                            {{ $province }}
                        </option>
                    @endforeach
                </select>

                <template x-if="singleDeliveryErrors.province">
                    <p class="text-red-500 text-xs mt-1"
                       x-text="singleDeliveryErrors.province"></p>
                </template>
            </div>

            <div>
                <label class="block font-bold mb-2 text-sm">
                    City <span class="text-red-600">*</span>
                </label>

                <select
                    x-model="city"
                    @change="onCityChange()"
                    @input="singleDeliveryErrors.city = ''"
                    :disabled="!province"
                    class="bg-white border border-gray-300 text-sm rounded-lg block w-full p-2.5"
                    :class="{'border-red-500': singleDeliveryErrors.city}"
                >
                    <option value="">Choose city</option>

                    <template x-for="c in filteredCities" :key="`${province}-${c}`">
                        <option :value="c" x-text="c"></option>
                    </template>
                </select>

                <template x-if="singleDeliveryErrors.city">
                    <p class="text-red-500 text-xs mt-1"
                       x-text="singleDeliveryErrors.city"></p>
                </template>
            </div>

        </div>


        {{-- BARANGAY --}}
        <div>
            <label class="block font-bold mb-2">
                Barangay <span class="text-red-600">*</span>
            </label>

            <select
                x-model="location"
                @change="onBarangayChange()"
                :disabled="!city"
                class="bg-white border border-gray-300 text-sm rounded-lg block w-full p-2.5"
                :class="{'border-red-500': singleDeliveryErrors.location}"
            >
                <option value="">Choose barangay</option>

                <template x-for="b in filteredBarangay()" :key="`${city}-${b}`">
                    <option :value="b" x-text="b"></option>
                </template>
            </select>

            <template x-if="singleDeliveryErrors.location">
                <p class="text-red-500 text-xs mt-1"
                   x-text="singleDeliveryErrors.location"></p>
            </template>
        </div>


        {{-- DATE + TIME --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            {{-- DATE --}}
            <div>
                <label class="block font-bold mb-2">
                    Select Date <span class="text-red-600">*</span>
                </label>

                <div class="relative">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500"
                             xmlns="http://www.w3.org/2000/svg"
                             fill="currentColor"
                             viewBox="0 0 20 20">
                            <path
                                d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Z" />
                        </svg>
                    </div>

                    <input
                        x-ref="deliveryDate"
                        type="text"
                        x-model="need_date"
                        readonly
                        x-init="initSingleDeliveryDatepicker($el)"
                        @change="validateSingleDeliveryField('date')"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 ps-10"
                        :class="{'border-red-500': singleDeliveryErrors.date}"
                    >
                </div>

                <template x-if="singleDeliveryErrors.date">
                    <p class="text-red-500 text-xs mt-1"
                       x-text="singleDeliveryErrors.date"></p>
                </template>
            </div>

            {{-- TIME --}}
            <div>
                <label class="block font-bold mb-2">
                    Select Time <span class="text-red-600">*</span>
                </label>

                <div class="relative">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 text-gray-500">
                            <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25ZM12.75 6a.75.75 0 0 0-1.5 0v6c0 .414.336.75.75.75h4.5a.75.75 0 0 0 0-1.5h-3.75V6Z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <select
                        x-model="need_time"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full ps-10 p-2.5"
                        :class="{'border-red-500': singleDeliveryErrors.time}"
                        @change="validateSingleDeliveryField('time')"
                    >
                        <option value="">Select Hour</option>

                        <template x-for="hour in availableDeliveryHours" :key="hour">
                            <option :value="formatHourValue(hour)"
                                    x-text="formatAMPM(hour)">
                            </option>
                        </template>
                    </select>
                </div>

                <template x-if="singleDeliveryErrors.time">
                    <p class="text-red-500 text-xs mt-1"
                       x-text="singleDeliveryErrors.time"></p>
                </template>
            </div>
        </div>

        <div class="text-yellow-700 bg-yellow-100 border-l-4 border-yellow-500 p-3 mt-3 rounded">
            <div>We've pre-selected the earliest available time for your order. You may adjust the date and time to your preference. For bookings earlier that our pre-selected schedule, please contact our <a href="/contact-us" target="_blank" class="underline">Hotline</a> directly.</div>
        </div>

        {{-- NOTE --}}
        <div>
            <label class="block font-bold mb-2">
                Note
            </label>

            <textarea
                x-model="instruction"
                rows="4"
                class="w-full border border-gray-300 p-2 rounded-md"
                placeholder="Add delivery instructions..."
            ></textarea>
        </div>

    </div>

</div>
