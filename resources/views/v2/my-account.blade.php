@extends('layouts.guest', ['page' => $page])

@section('content')

<div x-data="{ step: 1, accountType: 'individual' }" class="bg-cream">
    <div class="py-20 px-4">
        <div class="rounded-lg border bg-white border-[#DFDFDF] shadow-md mt-10">
            <div class="px-6 py-4 border-b border-[#DFDFDF]">
                <h2 class="text-lg font-bold text-primary text-left uppercase">my account</h2>
            </div>
            <div class="flex items-start font-bold gap-4 flex-col px-6 py-5 border-b border-[#DFDFDF]">
                <div class="">Hi, Juan Dela Cruz!</div>
                <div class="text-tertiary  underline">Sign out</div>
            </div>
            <div class="flex items-start font-bold flex-col gap-2  py-5 border-b border-[#DFDFDF]">

                <div class="group relative items-center flex pl-6 hover:text-tertiary text-tertiary cursor-pointer">
                    <div class="absolute left-0 top-0 h-10 w-1 bg-orange-500 opacity-100 group-hover:opacity-100 transition duration-200"></div>
                    <div class="py-2">Manage Account</div>
                </div>
                
                <div class="group relative items-center flex pl-6 hover:text-tertiary cursor-pointer">
                    <div class="absolute left-0 top-0 h-10 w-1 bg-orange-500 opacity-0 group-hover:opacity-100 transition duration-200"></div>
                    <div class="py-2">Change Password</div>
                </div>
                
                <div class="group relative items-center flex pl-6 hover:text-tertiary cursor-pointer">
                    <div class="absolute left-0 top-0 h-10 w-1 bg-orange-500 opacity-0 group-hover:opacity-100 transition duration-200"></div>
                    <div class="py-2">Order History</div>
                </div>
            </div>
        </div>

        <div class="rounded-lg border bg-white border-[#DFDFDF] shadow-md mt-5">
            <div class="px-6 py-4 border-b border-[#DFDFDF]">
                <h2 class="font-semibold text-tertiary text-left uppercase">Personal Information</h2>
            </div>
            <div class="flex items-start font-bold flex-col gap-2  py-5 border-b border-[#DFDFDF]">
                <div class="px-6 w-full text-sm">
                    <div class="mb-5">
                        <label for="firstname" class="block mb-2 font-bold text-gray-900 dark:text-white">First Name <span class="text-red-800">*</span> </label>
                        <input type="text" id="firstname"
                            class="bg-gray-50 border border-gray-300 text-gray-900  rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Randy" required />
                    </div>
                    <div class="mb-5">
                        <label for="lastname" class="block mb-2 font-bold text-gray-900 dark:text-white">Last Name <span class="text-red-800">*</span> </label>
                        <input type="text" id="lastname"
                            class="bg-gray-50 border border-gray-300 text-gray-900  rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Corpuz" required />
                    </div>
                    <div class="mb-5">
                        <label for="date" class="block mb-2 text-sm font-bold text-gray-900 dark:text-white">Birth Date <span class="text-red-700">*</span></label>
                        <div class="relative max-w-sm">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                            </svg>
                            </div>
                            <input datepicker datepicker-autohide id="default-datepicker" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-3 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Select date">
                        </div>
                    </div>
                    <div class="mb-5">
                        <label for="contact" class="block mb-2 font-bold text-gray-900 dark:text-white">Contact Number <span class="text-red-800">*</span> </label>
                        <input type="text" id="contact"
                            class="bg-gray-50 border border-gray-300 text-gray-900  rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="" required />
                    </div>
                </div>

                <div class="w-full px-6">
                    <button type="button"
                        class="text-white bg-primary hover:bg-primary-dark font-medium rounded-lg w-full sm:w-auto px-5 py-3.5 text-center">
                        Save
                    </button>
                </div>
                
            </div>
        </div>

        <div class="rounded-lg border bg-white border-[#DFDFDF] shadow-md mt-5">
            <div class="px-6 py-4 border-b border-[#DFDFDF]">
                <h2 class="font-semibold text-tertiary text-left uppercase">Delivery Address</h2>
            </div>
            <div class="flex items-start font-bold flex-col gap-2  py-5 border-b border-[#DFDFDF]">
                <div class="px-6 w-full text-sm">
                    <div class="mb-5">
                        <label for="address" class="block mb-2 font-bold text-gray-900 dark:text-white">Address <span class="text-red-800">*</span> </label>
                        <input type="text" id="address"
                            class="bg-gray-50 border border-gray-300 text-gray-900  rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="" required />
                    </div>
                    <div class="mb-5">
                        <label for="city" class="block mb-2 text-sm font-bold text-gray-900 dark:text-white">City <span class="text-red-700">*</span></label>
                        <select id="city" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option selected>Select</option>
                            <option value="bdo">Quezon City</option>
                            <option value="un">Manila</option>
                            <option value="metrobank">Pasig</option>
                        </select>
                    </div>
                    <div class="mb-5">
                        <label for="municipality" class="block mb-2 text-sm font-bold text-gray-900 dark:text-white">Municipality <span class="text-red-700">*</span></label>
                        <select id="municipality" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option selected>Select</option>
                            <option value="bdo">Quezon City</option>
                            <option value="un">Manila</option>
                            <option value="metrobank">Pasig</option>
                        </select>
                    </div>
                    <div class="mb-5">
                        <label for="region" class="block mb-2 text-sm font-bold text-gray-900 dark:text-white">Region <span class="text-red-700">*</span></label>
                        <select id="region" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option selected>Select</option>
                            <option value="bdo">Region 1</option>
                            <option value="un">Region 2</option>
                            <option value="metrobank">Region 3</option>
                        </select>
                    </div>
                </div>

                <div class="w-full px-6">
                    <button type="button"
                        class="text-white bg-primary hover:bg-primary-dark font-medium rounded-lg w-full sm:w-auto px-5 py-3.5 text-center">
                        Save
                    </button>
                </div>
                
            </div>
        </div>
    </div>
</div>
    
<x-footer-component />

@endsection