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

                        <template x-for="(order, index2) in orders" :key="index2">
                            <template x-if="getRemainingQty(order) > 0 || isOrderChecked(delivery, order)">

                                <div class="flex justify-between items-center">

                                    <div class="flex items-center gap-2">
                                        <input
                                            :id="'order-' + order.id + '-' + index + '-' + index2 + '-' + (order.paella_price > 0 ? 'paella' : 'nopaella')"
                                            type="checkbox"
                                            :checked="isOrderChecked(delivery, order)"
                                            @input="clearDeliveryFieldError(delivery, 'orders')"
                                            @change="onOrderCheckToggle(index, delivery, order, $event.target.checked)"
                                        >

                                        <label 
                                            :for="'order-' + order.id + '-' + index + '-' + index2 + '-' + (order.paella_price > 0 ? 'paella' : 'nopaella')"
                                            x-html="order.product.name + (order.paella_price > 0 ? ' <strong>Boneless with Paella</strong>' : '') + (getRemainingQty(order) <= 0 && !isOrderChecked(delivery, order) ? ' (Fully Assigned)' : '')">
                                        </label>

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

                        </template>

                        <template x-if="delivery.errors?.orders">
                            <div>
                                <p class="text-red-500 text-xs mt-2" x-text="delivery.errors.orders"></p>
                                <div class="border-red-500"></div>
                            </div>
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
                        @focus="startMultiEditing(index)"
                        @input="onMultiAddressInput(index);clearDeliveryFieldError(delivery, 'address')"
                        @blur="validateDelivery(index, 'address'); finishMultiEditing(index)"
                        rows="2"
                        class="bg-white border border-gray-300 text-sm rounded-md block w-full p-2.5"
                        :class="{'border-red-500': delivery.errors?.address}"
                    ></textarea>

                    <template x-if="delivery.errors?.address">
                        <p class="text-red-500 text-xs mt-1"
                        x-text="delivery.errors.address"></p>
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
                            :class="{'border-red-500': delivery.errors?.province}"
                            @input="clearDeliveryFieldError(delivery, 'province')"
                        >
                            <option value="">Choose province</option>

                            @foreach ($provinces as $province)
                                <option value="{{ $province }}">
                                    {{ $province }}
                                </option>
                            @endforeach
                        </select>

                        <template x-if="delivery.errors?.province">
                            <p class="text-red-500 text-xs mt-1"
                            x-text="delivery.errors.province"></p>
                        </template>
                    </div>


                    <div>
                        <label class="block font-bold mb-2 text-sm">
                            City <span class="text-red-600">*</span>
                        </label>

                        <select
                            x-model="delivery.city"
                            @change="getDeliveryFeeForMultiple(index); onMultiCityChange(index)"
                            :disabled="!delivery.province"
                            class="bg-white border border-gray-300 text-sm rounded-lg block w-full p-2.5"
                            :class="{'border-red-500': delivery.errors?.city}"
                            @input="clearDeliveryFieldError(delivery, 'city')"
                        >
                            <option value="">Choose city</option>

                            <template x-for="c in multipleFilteredCities(index)">
                                <option :value="c" x-text="c"></option>
                            </template>
                        </select>

                        <template x-if="delivery.errors?.city">
                            <p class="text-red-500 text-xs mt-1"
                            x-text="delivery.errors.city"></p>
                        </template>

                    </div>

                </div>


                {{-- BARANGAY --}}
                <div>
                    <label class="block font-bold mb-2 text-sm">
                        Barangay
                    </label>

                    <select
                        x-model="delivery.location"
                        @change="onMultiBarangayChange(index)"
                        :disabled="!delivery.city"
                        class="bg-white border border-gray-300 text-sm rounded-lg block w-full p-2.5"
                        :class="{'border-red-500': delivery.errors?.location}"
                        @input="clearDeliveryFieldError(delivery, 'location')"
                    >
                        <option value="">Choose barangay</option>

                        <template x-for="b in filteredMultipleBarangay(index)">
                            <option :value="b"
                                    x-text="b"></option>
                        </template>
                    </select>

                    <template x-if="delivery.errors?.location">
                        <p class="text-red-500 text-xs mt-1"
                        x-text="delivery.errors.location"></p>
                    </template>
                </div>

                {{-- DATE + TIME --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    {{-- DATE --}}
                    <div>
                        <label class="block font-bold mb-2 text-sm">
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
                                type="text"
                                x-model="delivery.need_date"
                                readonly
                                placeholder="Select Date"
                                :disabled="!delivery.orders.length"
                                :x-ref="'deliveryDate' + index"
                                x-init="initMultiDeliveryDatepicker($el, index)"
                                @change="validateDelivery(index, 'date')"
                                class="bg-white border border-gray-300 text-sm rounded-lg block w-full p-2.5 ps-10"
                                :class="{
                                    'bg-gray-100 text-gray-400 cursor-not-allowed': !delivery.orders.length
                                }"
                            >
                        </div>
                    </div>

                    {{-- TIME --}}
                    <div>
                        <label class="block font-bold mb-2 text-sm">
                            Select Time <span class="text-red-600">*</span>
                        </label>

                        <div class="relative">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 text-gray-500">
                                <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25ZM12.75 6a.75.75 0 0 0-1.5 0v6c0 .414.336.75.75.75h4.5a.75.75 0 0 0 0-1.5h-3.75V6Z" clip-rule="evenodd" />
                                </svg>
                            </div>

                            <select 
                                :disabled="!delivery.orders.length"
                                x-model="delivery.need_time" 
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full ps-10 p-2.5"
                                :class="{'border-red-500': delivery.errors?.need_time}"
                                @input="clearDeliveryFieldError(delivery, 'need_time')"
                            >
                                <option value="">Select Hour</option>

                                <template x-for="hour in delivery.availableHours" :key="hour">
                                    <option :value="formatHourValue(hour)"
                                            x-text="formatAMPM(hour)">
                                    </option>
                                </template>
                            </select>
                        </div>

                        <template x-if="delivery.errors?.need_time">
                            <p class="text-red-500 text-xs mt-1"
                               x-text="delivery.errors.need_time"></p>
                        </template>
                    </div>
                </div>

                <template x-if="delivery.cochinillo_warning">
                    <div
                        class="text-red-700 bg-red-100 border-l-4 border-red-500 p-3 mt-3 rounded">
                        Our Cochinillo is not available on December 24. Please select another size.
                    </div>
                </template>

                <div
                    class="text-yellow-700 bg-yellow-100 border-l-4 border-yellow-500 p-3 mt-3 rounded">
                    <div>We've pre-selected the earliest available time for your order. You may adjust the date and time to your preference. For bookings earlier that our pre-selected schedule, please contact our <a href="/contact-us" target="_blank" class="underline">Hotline</a> directly.</div>
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
                            :class="{'border-red-500': delivery.errors?.name}"
                            @input="clearDeliveryFieldError(delivery, 'name')"
                        >

                        <template x-if="delivery.errors?.name">
                            <p class="text-red-500 text-xs mt-1"
                            x-text="delivery.errors.name"></p>
                        </template>
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
                            :class="{'border-red-500': delivery.errors?.phone}"
                            @input="clearDeliveryFieldError(delivery, 'phone')"
                        >

                        <template x-if="delivery.errors?.phone">
                            <p class="text-red-500 text-xs mt-1"
                            x-text="delivery.errors.phone"></p>
                        </template>
                    </div>

                    <div class="w-full flex gap-2">
                        <input :id="'sms-' + index" type="checkbox"
                            x-model="delivery.sms" class="border border-gray-300 p-2" />
                        <label 
                            class="block text-sm mb-1" 
                            :for="'sms-' + index">
                            Notify recipient through SMS?
                        </label>
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
                        placeholder="Add instructions or notes about your delivery."
                        class="bg-white border border-gray-300 text-sm rounded-md block w-full p-2.5"
                    ></textarea>
                </div>

            </div>

        </template>


        {{-- ADD DELIVERY BUTTON --}}
        <div x-show="hasRemainingOrders()">
            <button
                type="button"
                @click="validateBeforeAddDelivery"
                class="bg-green-700 text-white px-4 py-2 rounded-md text-sm"
            >
                Add Another Delivery
            </button>
        </div>

        <div x-show="!hasRemainingOrders()"
            class="text-sm text-gray-500 italic">
            All items have been assigned to deliveries.
        </div>

        <template x-if="errors?.unused">
            <div>
                <p class="text-red-500 text-xs mt-2" x-text="errors.unused"></p>
                <div class="border-red-500"></div>
            </div>
        </template>

    </div>
</div>
