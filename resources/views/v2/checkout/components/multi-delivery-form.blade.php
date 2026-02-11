<div class="w-full rounded-lg border bg-white border-[#DFDFDF] shadow-md">

    {{-- HEADER --}}
    <div class="px-4 py-3 border-b border-[#DFDFDF]">
        <h2 class="text-lg lg:text-2xl font-semibold">
            Multiple Delivery Addresses
        </h2>
    </div>

    <div class="px-4 py-5 space-y-6">

        {{-- DELIVERY CARDS --}}
        <template x-for="(delivery, index) in deliveries" :key="index">

            <div class="p-4 bg-gray-50 border rounded-md space-y-6">

                {{-- TITLE --}}
                <div class="flex justify-between items-center">
                    <h3 class="font-bold text-gray-800">
                        Delivery Address <span x-text="index + 1"></span>
                    </h3>

                    <button
                        type="button"
                        @click="removeDelivery(index)"
                        x-show="deliveries.length > 1"
                        class="text-red-600 text-sm underline"
                    >
                        Remove
                    </button>
                </div>


                {{-- ORDER ASSIGNMENT --}}
                <div>
                    <label class="block font-bold mb-2 text-sm">
                        Assign Orders
                    </label>

                    <div class="space-y-2">

                        <template x-for="order in getAvailableOrders()" :key="order.id">

                            <div class="flex justify-between items-center">

                                <div class="flex items-center gap-2">
                                    <input
                                        type="checkbox"
                                        :checked="isOrderChecked(delivery, order)"
                                        @change="onOrderCheckToggle(delivery, order, $event.target.checked)"
                                    >

                                    <span x-text="order.product.name"></span>

                                    <span
                                        x-show="order.is_free_product"
                                        class="text-green-600 text-xs font-semibold"
                                    >
                                        (Free)
                                    </span>
                                </div>

                                <select
                                    class="border rounded px-2 py-1"
                                    :disabled="!isOrderChecked(delivery, order)"
                                    :value="getSelectedQty(delivery, order)"
                                    @change="updateSelectedQty(delivery, order, $event.target.value)"
                                >
                                    <template x-for="i in getAvailableQtyForDropdown(delivery, order)">
                                        <option :value="i" x-text="i"></option>
                                    </template>
                                </select>

                            </div>

                        </template>

                    </div>
                </div>


                {{-- DATE + TIME --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    {{-- DATE --}}
                    <div>
                        <label class="block font-bold mb-2 text-sm">
                            Select Date <span class="text-red-600">*</span>
                        </label>

                        <input
                            type="text"
                            x-model="delivery.need_date"
                            readonly
                            @change="validateDelivery(index, 'date')"
                            class="bg-white border border-gray-300 text-sm rounded-lg block w-full p-2.5"
                            :class="{'border-red-500': errors[index]?.need_date}"
                        >

                        <template x-if="errors[index]?.need_date">
                            <p class="text-red-500 text-xs mt-1"
                               x-text="errors[index]?.need_date"></p>
                        </template>
                    </div>


                    {{-- TIME --}}
                    <div>
                        <label class="block font-bold mb-2 text-sm">
                            Select Time <span class="text-red-600">*</span>
                        </label>

                        <select
                            x-model="delivery.need_time"
                            @change="validateDelivery(index, 'time')"
                            class="bg-white border border-gray-300 text-sm rounded-lg block w-full p-2.5"
                            :class="{'border-red-500': errors[index]?.need_time}"
                        >
                            <option value="">Select Hour</option>

                            <template x-for="hour in getAvailableHours(delivery)" :key="hour">
                                <option
                                    :value="formatHourValue(hour)"
                                    x-text="formatAMPM(hour)">
                                </option>
                            </template>
                        </select>

                        <template x-if="errors[index]?.need_time">
                            <p class="text-red-500 text-xs mt-1"
                               x-text="errors[index]?.need_time"></p>
                        </template>
                    </div>

                </div>


                {{-- ADDRESS --}}
                <div>
                    <label class="block font-bold mb-2 text-sm">
                        Address <span class="text-red-600">*</span>
                    </label>

                    <textarea
                        x-model="delivery.address"
                        @blur="validateDelivery(index, 'address')"
                        rows="2"
                        class="bg-white border border-gray-300 text-sm rounded-md block w-full p-2.5"
                        :class="{'border-red-500': errors[index]?.address}"
                    ></textarea>

                    <template x-if="errors[index]?.address">
                        <p class="text-red-500 text-xs mt-1"
                           x-text="errors[index]?.address"></p>
                    </template>
                </div>


                {{-- PROVINCE + CITY --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <div>
                        <label class="block font-bold mb-2 text-sm">
                            Province <span class="text-red-600">*</span>
                        </label>

                        <select
                            x-model="delivery.province"
                            @change="onMultiProvinceChange(index)"
                            class="bg-white border border-gray-300 text-sm rounded-lg block w-full p-2.5"
                        >
                            <option value="">Choose province</option>

                            @foreach ($provinces as $province)
                                <option value="{{ $province }}">
                                    {{ $province }}
                                </option>
                            @endforeach
                        </select>
                    </div>


                    <div>
                        <label class="block font-bold mb-2 text-sm">
                            City <span class="text-red-600">*</span>
                        </label>

                        <select
                            x-model="delivery.city"
                            @change="getDeliveryFeeForMultiple(index)"
                            :disabled="!delivery.province"
                            class="bg-white border border-gray-300 text-sm rounded-lg block w-full p-2.5"
                        >
                            <option value="">Choose city</option>

                            <template x-for="c in multipleFilteredCities(index)">
                                <option :value="c.city" x-text="c.city"></option>
                            </template>
                        </select>
                    </div>

                </div>


                {{-- BARANGAY --}}
                <div>
                    <label class="block font-bold mb-2 text-sm">
                        Barangay
                    </label>

                    <select
                        x-model="delivery.location"
                        @change="getDeliveryFeeForMultiple(index)"
                        :disabled="!delivery.city"
                        class="bg-white border border-gray-300 text-sm rounded-lg block w-full p-2.5"
                    >
                        <option value="">Choose barangay</option>

                        <template x-for="b in filteredMultipleBarangay(index)">
                            <option :value="b.barangay"
                                    x-text="b.barangay"></option>
                        </template>
                    </select>
                </div>


                {{-- CONTACT --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <div>
                        <label class="block font-bold mb-2 text-sm">
                            Contact Person <span class="text-red-600">*</span>
                        </label>

                        <input
                            type="text"
                            x-model="delivery.name"
                            @blur="validateDelivery(index, 'name')"
                            class="bg-white border border-gray-300 text-sm rounded-md block w-full p-2.5"
                        >
                    </div>

                    <div>
                        <label class="block font-bold mb-2 text-sm">
                            Contact Number <span class="text-red-600">*</span>
                        </label>

                        <input
                            type="tel"
                            x-model="delivery.phone"
                            @blur="validateDelivery(index, 'phone')"
                            class="bg-white border border-gray-300 text-sm rounded-md block w-full p-2.5"
                        >
                    </div>

                </div>


                {{-- NOTE --}}
                <div>
                    <label class="block font-bold mb-2 text-sm">
                        Note
                    </label>

                    <textarea
                        x-model="delivery.note"
                        rows="2"
                        class="bg-white border border-gray-300 text-sm rounded-md block w-full p-2.5"
                    ></textarea>
                </div>

            </div>

        </template>


        {{-- ADD DELIVERY BUTTON --}}
        <div>
            <button
                type="button"
                @click="addDelivery()"
                class="bg-green-700 text-white px-4 py-2 rounded-md text-sm"
            >
                Add Another Delivery
            </button>
        </div>

    </div>
</div>
