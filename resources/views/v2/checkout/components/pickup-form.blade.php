<div class="w-full rounded-lg border bg-white border-[#DFDFDF] shadow-md">

    {{-- HEADER --}}
    <div class="px-4 py-3 border-b border-[#DFDFDF]">
        <h2 class="text-lg lg:text-2xl font-semibold">
            Pickup Information
        </h2>
    </div>

    <div class="px-4 py-5 space-y-6">

        {{-- BRANCH --}}
        <div>
            <label class="block font-bold mb-2">
                Select Branch <span class="text-red-600">*</span>
            </label>

            <select
                name="delivery_branch"
                x-model="pickup_branch"
                @change="onPickupBranchChange"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                :class="{'border-red-500': pickupErrors.branch}"
                required
            >
                <option value="">Choose a branch</option>

                @foreach ($pickupBranches as $branch)
                    <option value="{{ $branch->name }}">
                        {{ $branch->name }}
                    </option>
                @endforeach
            </select>

            <template x-if="pickupErrors.branch">
                <p class="text-red-500 text-xs mt-1" x-text="pickupErrors.branch"></p>
            </template>
        </div>


        {{-- DATE + TIME --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

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
                        x-ref="pickupDate"
                        type="text"
                        x-model="pickup_date"
                        @change="validatePickupDateTime"
                        readonly
                        x-init="initPickupDatepicker($el)"
                        placeholder="Select date"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full ps-10 p-2.5"
                        :class="{'border-red-500': pickupErrors.date}"
                    >
                </div>

                <template x-if="pickupErrors.date">
                    <p class="text-red-500 text-xs mt-1" x-text="pickupErrors.date"></p>
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

                    <select x-model="pickup_time" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full ps-10 p-2.5">
                        <option value="">Select Hour</option>

                        <template x-for="hour in availablePickupHours" :key="hour">
                            <option :value="formatHourValue(hour)"
                                    x-text="formatAMPM(hour)">
                            </option>
                        </template>
                    </select>
                </div>

                <template x-if="pickupErrors.time">
                    <p class="text-red-500 text-xs mt-1" x-text="pickupErrors.time"></p>
                </template>
            </div>

        </div>

        <div
            class="text-yellow-700 bg-yellow-100 border-l-4 border-yellow-500 p-3 mt-3 rounded">
            <div>We've pre-selected the earliest available time for your order. You may adjust the date and time to your preference. For bookings earlier that our pre-selected schedule, please contact our <a href="/contact-us" target="_blank" class="underline">Hotline</a> directly.</div>
        </div>

        {{-- NOTE --}}
        <div>
            <label class="block font-bold mb-2">
                Note
            </label>

            <textarea
                name="instruction"
                x-model="pickup_note"
                rows="4"
                placeholder="Add instructions or notes about your pickup."
                class="w-full border border-gray-300 p-2 rounded-md"
            ></textarea>
        </div>


        {{-- WARNING MESSAGE --}}
        <template x-if="pickupWarning">
            <div class="text-yellow-700 bg-yellow-100 border-l-4 border-yellow-500 p-3 rounded">
                <span x-text="pickupWarning"></span>
            </div>
        </template>

    </div>

</div>
