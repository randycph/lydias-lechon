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
                @blur="validateSingleDeliveryField('address')"
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

            {{-- PROVINCE --}}
            <div>
                <label class="block font-bold mb-2">
                    Province <span class="text-red-600">*</span>
                </label>

                <select
                    x-model="province"
                    @change="onProvinceChange"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                    :class="{'border-red-500': singleDeliveryErrors.province}"
                >
                    <option value="">Choose a province</option>

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

            {{-- CITY --}}
            <div>
                <label class="block font-bold mb-2">
                    City / Municipality <span class="text-red-600">*</span>
                </label>

                <select
                    x-model="city"
                    @change="onCityChange"
                    :disabled="!province"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 disabled:bg-gray-100"
                    :class="{'border-red-500': singleDeliveryErrors.city}"
                >
                    <option value="">Choose a city</option>

                    <template x-for="(c, i) in filteredCities" :key="i">
                        <option :value="c.city" x-text="c.city"></option>
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
                Barangay
            </label>

            <select
                x-model="location"
                @change="getDeliveryFee"
                :disabled="!city"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 disabled:bg-gray-100"
            >
                <option value="">Choose a barangay</option>

                <template x-for="(b, i) in filteredBarangay()" :key="i">
                    <option :value="b.barangay"
                            x-text="b.barangay">
                    </option>
                </template>
            </select>
        </div>


        {{-- DATE + TIME --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            {{-- DATE --}}
            <div>
                <label class="block font-bold mb-2">
                    Select Date <span class="text-red-600">*</span>
                </label>

                <input
                    type="text"
                    x-model="need_date"
                    readonly
                    @change="validateSingleDeliveryField('date')"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                    :class="{'border-red-500': singleDeliveryErrors.date}"
                >

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

                <select
                    x-model="need_time"
                    @change="validateSingleDeliveryField('time')"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                    :class="{'border-red-500': singleDeliveryErrors.time}"
                >
                    <option value="">Select Hour</option>

                    <template x-for="hour in allHours" :key="hour">
                        <template x-if="!isTimeDisabled(hour)">
                            <option :value="formatHourValue(hour)"
                                    x-text="formatAMPM(hour)">
                            </option>
                        </template>
                    </template>
                </select>

                <template x-if="singleDeliveryErrors.time">
                    <p class="text-red-500 text-xs mt-1"
                       x-text="singleDeliveryErrors.time"></p>
                </template>
            </div>
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
