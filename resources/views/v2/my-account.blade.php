@extends('layouts.guest', ['page' => $page])

@section('content')

<div x-data="{ step: 1, accountType: 'individual' }" class="bg-cream">
    <div class="py-20 px-4 container">
        <div class="flex gap-6 lg:flex-row flex-col mt-10">
            <div class="w-full lg:w-1/4">
                <x-account-menu-component />
            </div>

            <div class="w-full lg:w-3/4">
                <div class="rounded-lg border bg-white border-[#DFDFDF] shadow-md">
                    <div class="px-6 py-4 border-b border-[#DFDFDF]">
                        <h2 class="font-semibold text-tertiary text-left uppercase">Personal Information</h2>
                    </div>
                    <form method="POST" action="{{ route('save-personal-information') }}" class="flex items-start font-bold flex-col gap-2  py-5 border-b border-[#DFDFDF]">
                        @csrf
                        <div class="px-6 w-full text-sm">
                            <div class="mb-5">
                                <label for="firstname" class="block mb-2 font-bold text-gray-900">First Name <span class="text-red-800">*</span> </label>
                                <input type="text" id="firstname" name="firstname" value="{{ auth()->user()->firstname }}"
                                    class="bg-gray-50 border border-gray-300 text-gray-900  rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                    placeholder="" required />
                                
                                @error('firstname')
                                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                @enderror

                            </div>
                            <div class="mb-5">
                                <label for="lastname" class="block mb-2 font-bold text-gray-900">Last Name <span class="text-red-800">*</span> </label>
                                <input type="text" id="lastname" name="lastname" value="{{ auth()->user()->lastname }}"
                                    class="bg-gray-50 border border-gray-300 text-gray-900  rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                    placeholder="" required />

                                @error('lastname')
                                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                @enderror

                            </div>
                            <div class="mb-5">
                                <label for="email" class="block mb-2 font-bold text-gray-900">Email address <span class="text-red-800">*</span> </label>
                                <input type="email" id="email" name="email" value="{{ auth()->user()->email }}"
                                    class="bg-gray-50 border border-gray-300 text-gray-900  rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                    placeholder="" required />

                                @error('email')
                                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                @enderror

                            </div>
                            <div class="w-full flex flex-col lg:flex-row gap-5 mb-5">
                                <div class="w-full lg:w-1/2">
                                    <label for="date" class="block mb-2 text-sm font-bold text-gray-900">Birth Date <span class="text-red-700">*</span></label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                                        </svg>
                                        </div>
                                        <input name="birthday" value="{{ auth()->user()->birthday }}"
                                            id="default-datepicker" type="date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-3" placeholder="Select date">
                                    </div>

                                    @error('birthday')
                                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="w-full lg:w-1/2">
                                    <label for="contact" class="block mb-2 font-bold text-gray-900">Contact Number <span class="text-red-800">*</span> </label>
                                    <input type="text" id="contact_mobile" name="contact_mobile" value="{{ auth()->user()->contact_mobile }}"
                                        class="bg-gray-50 border border-gray-300 text-gray-900  rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                        placeholder="" required />

                                    @error('contact_mobile')
                                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
        
                        <div class="w-full px-6">
                            <button type="submit"
                                class="text-white  bg-primary custom-btn btn-primary-dark font-medium rounded-md w-full sm:w-auto px-5 py-3.5 text-center">
                                Save
                            </button>
                        </div>
                        
                    </form>
                </div>
        
                <div class="rounded-lg border bg-white border-[#DFDFDF] shadow-md mt-5">
                    <div class="px-6 py-4 border-b border-[#DFDFDF]">
                        <h2 class="font-semibold text-tertiary text-left uppercase">Delivery Address</h2>
                    </div>
                    <form method="POST" action="{{ route('save-delivery-address') }}" class="flex items-start font-bold flex-col gap-2  py-5 border-b border-[#DFDFDF]">
                        @csrf
                        <div class="px-6 w-full text-sm">
                            <div class="mb-5">
                                <label for="address_street" class="block mb-2 font-bold text-gray-900">Address <span class="text-red-800">*</span> </label>
                                <input type="text" id="address_street" name="address_street" value="{{ auth()->user()->address_street }}"
                                    class="bg-gray-50 border border-gray-300 text-gray-900  rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                    placeholder="" required />
                            </div>
                            <div 
                                x-init="loadData()"
                                x-data="addressSelector()"
                                class="space-y-4">

                                <!-- Region -->
                                <div>
                                    <label class="block mb-2 font-bold text-gray-900">Region</label>
                                    <select name="address_region" x-model="regionCode" @change="filterProvinces"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                        <option value="">Select Region</option>
                                        <template x-for="region in regions" :key="region.region_code + '-' + region.region_name">
                                            <option :value="region.region_name" x-text="region.region_name"></option>
                                        </template>
                                    </select>
                            
                                    @error('address_region')
                                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            
                                <!-- Province -->
                                <div>
                                    <label class="block mb-2 font-bold text-gray-900">Province</label>
                                    <select name="address_municipality" x-model="provinceCode" @change="filterCities" class="bg-gray-50 border border-gray-300 text-gray-900  rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                        <option value="">Select Province</option>
                                        <template x-for="province in provincesFiltered" :key="province.province_code + '-'  + province.province_name">
                                            <option :value="province.province_name" x-text="province.province_name"></option>
                                        </template>
                                    </select>

                                    @error('address_municipality')
                                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            
                                <!-- City / Municipality -->
                                <div>
                                    <label class="block mb-2 font-bold text-gray-900">City / Municipality</label>
                                    <select name="address_city" x-model="cityCode" @change="filterBarangays" class="bg-gray-50 border border-gray-300 text-gray-900  rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                        <option value="">Select City</option>
                                        <template x-for="city in citiesFiltered" :key="city.city_code + '-'  + city.city_name">
                                            <option :value="city.city_name" x-text="city.city_name"></option>
                                        </template>
                                    </select>

                                    @error('address_city')
                                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            
                                <!-- Barangay -->
                                <div>
                                    <label class="block mb-2 font-bold text-gray-900">Barangay</label>
                                    <select name="address_brgy" x-model="barangayCode" class="bg-gray-50 border border-gray-300 text-gray-900  rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                        <option value="">Select Barangay</option>
                                        <template x-for="barangay in barangaysFiltered" :key="barangay.brgy_code + '-'  + barangay.brgy_name">
                                            <option :value="barangay.brgy_name" x-text="barangay.brgy_name"></option>
                                        </template>
                                    </select>

                                    @error('address_brgy')
                                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
        
                        <div class="w-full px-6 mt-4">
                            <button type="submit"
                                class="text-white bg-primary custom-btn btn-primary-dark font-medium rounded-md w-full sm:w-auto px-5 py-3.5 text-center">
                                Save
                            </button>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
    
<x-footer-component />

<script>
function addressSelector() {
    return {
        regions: [],
        provinces: [],
        cities: [],
        barangays: [],
        provincesFiltered: [],
        citiesFiltered: [],
        barangaysFiltered: [],

        regionCode: '',
        provinceCode: '',
        cityCode: '',
        barangayCode: '',
        isReady: false,

        async loadData() {
            const [regions, provinces, cities, barangays] = await Promise.all([
                fetch('{{asset("addresses/region.json")}}').then(res => res.json()),
                fetch('{{asset("addresses/province.json")}}').then(res => res.json()),
                fetch('{{asset("addresses/city.json")}}').then(res => res.json()),
                fetch('{{asset("addresses/barangay.json")}}').then(res => res.json()),
            ]);

            this.regions = regions;
            this.provinces = provinces;
            this.cities = cities;
            this.barangays = barangays;

            this.isReady = true;

            this.$nextTick(() => {
                this.regionCode = @json(old('address_region', auth()->user()->address_region));
                this.filterProvinces();

                this.$nextTick(() => {
                    this.provinceCode = @json(old('address_municipality', auth()->user()->address_municipality));
                    this.filterCities();

                    this.$nextTick(() => {
                        this.cityCode = @json(old('address_city', auth()->user()->address_city));
                        this.filterBarangays();

                        this.$nextTick(() => {
                            this.barangayCode = @json(old('address_brgy', auth()->user()->address_brgy));
                        });
                    });
                });
            });
        },

        filterProvinces() {
            const region = this.regions.find(r => r.region_name === this.regionCode);
            const regionCode = region?.region_code;
            this.provincesFiltered = this.provinces.filter(p => p.region_code === regionCode);
            this.citiesFiltered = [];
            this.barangaysFiltered = [];
        },

        filterCities() {
            const province = this.provinces.find(p => p.province_name === this.provinceCode);
            const provinceCode = province?.province_code;
            this.citiesFiltered = this.cities.filter(c => c.province_code === provinceCode);
            this.barangaysFiltered = [];
        },

        filterBarangays() {
            const city = this.cities.find(c => c.city_name === this.cityCode);
            const cityCode = city?.city_code;
            this.barangaysFiltered = this.barangays.filter(b => b.city_code === cityCode);
        }
    };
}

</script>
     

@endsection