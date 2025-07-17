@extends('layouts.guest', ['page' => $page])

@section('alpine.plugins')
<script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/mask@3.x.x/dist/cdn.min.js"></script>
@endsection

@section('content')

<div class="bg-cream">
    <div class="flex container">
        <div class="w-full lg:w-1/2 lg:pr-10 pr-0">
            <div class="pb-20 px-4 ">
                <div class="pt-20 pb-5">
                    <h1 class="text-4xl font-cubao font-medium text-primary text-left mt-10">sign up now!</h1>
                    <h3 class="font-medium text-left">Create an account with Lydia's Lechon to enjoy faster orders, exclusive offers, and stay updated on our latest promos!</h3>
                </div>
        
                <form 
                    class="" 
                    action="{{ route('signup.store') }}" 
                    method="POST"
                    x-data="registrationForm">
                    @csrf
                    <div class="mx-auto py-4">
                        <!-- Stepper Indicator -->
                        <div class="flex items-center justify-between mb-4">
                            <template x-for="index in 4" :key="index">
                                <div class="flex-1 flex items-center">
                                    <div 
                                        class="h-2 w-full rounded-lg transition-all"
                                        :class="step >= index ? 'bg-primary' : 'bg-gray-300'"
                                    ></div>
                                    <div class="w-4"></div> <!-- Spacing -->
                                </div>
                            </template>
                        </div>
                    </div>
        
                    <template x-if="step === 1">
                        <div class="flex gap-2 flex-col lg:flex-row items-center justify-center mt-5 w-full">
                            <a href="{{ route('google.login') }}" class="hover:bg-gray-50 bg-indigo-100 border m-auto border-indigo-100 text-primary px-6 py-4 rounded-md flex justify-center w-full gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 512 512">
                                    <path fill="#fbbd00" d="M120 256c0-25.367 6.989-49.13 19.131-69.477v-86.308H52.823C18.568 144.703 0 198.922 0 256s18.568 111.297 52.823 155.785h86.308v-86.308C126.989 305.13 120 281.367 120 256z" data-original="#fbbd00" />
                                    <path fill="#0f9d58" d="m256 392-60 60 60 60c57.079 0 111.297-18.568 155.785-52.823v-86.216h-86.216C305.044 385.147 281.181 392 256 392z" data-original="#0f9d58" />
                                    <path fill="#31aa52" d="m139.131 325.477-86.308 86.308a260.085 260.085 0 0 0 22.158 25.235C123.333 485.371 187.62 512 256 512V392c-49.624 0-93.117-26.72-116.869-66.523z" data-original="#31aa52" />
                                    <path fill="#3c79e6" d="M512 256a258.24 258.24 0 0 0-4.192-46.377l-2.251-12.299H256v120h121.452a135.385 135.385 0 0 1-51.884 55.638l86.216 86.216a260.085 260.085 0 0 0 25.235-22.158C485.371 388.667 512 324.38 512 256z" data-original="#3c79e6" />
                                    <path fill="#cf2d48" d="m352.167 159.833 10.606 10.606 84.853-84.852-10.606-10.606C388.668 26.629 324.381 0 256 0l-60 60 60 60c36.326 0 70.479 14.146 96.167 39.833z" data-original="#cf2d48" />
                                    <path fill="#eb4132" d="M256 120V0C187.62 0 123.333 26.629 74.98 74.98a259.849 259.849 0 0 0-22.158 25.235l86.308 86.308C162.883 146.72 206.376 120 256 120z" data-original="#eb4132" />
                                </svg>
                                <span>Sign in with Google</span>
                            </a>
                            <a href="#" class="hover:bg-gray-50 bg-indigo-100 border m-auto border-indigo-100 text-primary px-6 py-4 rounded-md flex justify-center lg:w-max w-full gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 inline-block" viewBox="0 0 22.773 22.773">
                                    <path d="M15.769 0h.162c.13 1.606-.483 2.806-1.228 3.675-.731.863-1.732 1.7-3.351 1.573-.108-1.583.506-2.694 1.25-3.561C13.292.879 14.557.16 15.769 0zm4.901 16.716v.045c-.455 1.378-1.104 2.559-1.896 3.655-.723.995-1.609 2.334-3.191 2.334-1.367 0-2.275-.879-3.676-.903-1.482-.024-2.297.735-3.652.926h-.462c-.995-.144-1.798-.932-2.383-1.642-1.725-2.098-3.058-4.808-3.306-8.276v-1.019c.105-2.482 1.311-4.5 2.914-5.478.846-.52 2.009-.963 3.304-.765.555.086 1.122.276 1.619.464.471.181 1.06.502 1.618.485.378-.011.754-.208 1.135-.347 1.116-.403 2.21-.865 3.652-.648 1.733.262 2.963 1.032 3.723 2.22-1.466.933-2.625 2.339-2.427 4.74.176 2.181 1.444 3.457 3.028 4.209z" data-original="#000000"></path>
                                </svg>
                                <span class="block lg:hidden">Sign in with Apple</span>
                            </a>
                            <a href="#" class="hover:bg-gray-50 bg-indigo-100 border m-auto border-indigo-100 text-primary px-6 py-4 rounded-md flex justify-center lg:w-max w-full gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 512 512">
                                    <path fill="#1877f2" d="M512 256c0 127.78-93.62 233.69-216 252.89V330h59.65L367 256h-71v-48.02c0-20.25 9.92-39.98 41.72-39.98H370v-63s-29.3-5-57.31-5c-58.47 0-96.69 35.44-96.69 99.6V256h-65v74h65v178.89C93.62 489.69 0 383.78 0 256 0 114.62 114.62 0 256 0s256 114.62 256 256z" data-original="#1877f2" />
                                    <path fill="#fff" d="M355.65 330 367 256h-71v-48.021c0-20.245 9.918-39.979 41.719-39.979H370v-63s-29.296-5-57.305-5C254.219 100 216 135.44 216 199.6V256h-65v74h65v178.889c13.034 2.045 26.392 3.111 40 3.111s26.966-1.066 40-3.111V330z" data-original="#ffffff" />
                                </svg>
                                <span class="block lg:hidden">Sign in with Facebook</span>
                            </a>
                            <a href="#" class="hover:bg-gray-50 bg-indigo-100 border m-auto border-indigo-100 text-primary px-6 py-4 rounded-md flex justify-center lg:w-max w-full gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg"  fill="currentColor"  viewBox="0 0 16 16" class="w-6 h-6 text-[#1DA1F2]">
                                    <path d="M5.026 15c6.038 0 9.341-5.003 9.341-9.334q.002-.211-.006-.422A6.7 6.7 0 0 0 16 3.542a6.7 6.7 0 0 1-1.889.518 3.3 3.3 0 0 0 1.447-1.817 6.5 6.5 0 0 1-2.087.793A3.286 3.286 0 0 0 7.875 6.03a9.32 9.32 0 0 1-6.767-3.429 3.29 3.29 0 0 0 1.018 4.382A3.3 3.3 0 0 1 .64 6.575v.045a3.29 3.29 0 0 0 2.632 3.218 3.2 3.2 0 0 1-.865.115 3 3 0 0 1-.614-.057 3.28 3.28 0 0 0 3.067 2.277A6.6 6.6 0 0 1 .78 13.58a6 6 0 0 1-.78-.045A9.34 9.34 0 0 0 5.026 15"/>
                                  </svg>
                                <span class="block lg:hidden">Sign in with Twitter</span>
                            </a>
                            <a href="#" class="hover:bg-gray-50 bg-indigo-100 border m-auto border-indigo-100 text-primary px-6 py-4 rounded-md flex justify-center lg:w-max w-full gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg"  fill="currentColor"  viewBox="0 0 16 16" class="w-6 h-6 text-[#0072b1]">
                                    <path d="M0 1.146C0 .513.526 0 1.175 0h13.65C15.474 0 16 .513 16 1.146v13.708c0 .633-.526 1.146-1.175 1.146H1.175C.526 16 0 15.487 0 14.854zm4.943 12.248V6.169H2.542v7.225zm-1.2-8.212c.837 0 1.358-.554 1.358-1.248-.015-.709-.52-1.248-1.342-1.248S2.4 3.226 2.4 3.934c0 .694.521 1.248 1.327 1.248zm4.908 8.212V9.359c0-.216.016-.432.08-.586.173-.431.568-.878 1.232-.878.869 0 1.216.662 1.216 1.634v3.865h2.401V9.25c0-2.22-1.184-3.252-2.764-3.252-1.274 0-1.845.7-2.165 1.193v.025h-.016l.016-.025V6.169h-2.4c.03.678 0 7.225 0 7.225z"/>
                                  </svg>
                                <span class="block lg:hidden">Sign in with LinkedIn</span>
                            </a>
                        </div>
        
                        <div class="flex text-sm items-center justify-center gap-4 mt-6 w-full">
                            <div class="border-t border-[#DFDFDF] w-1/6"></div>
                            <div class="text-gray-400 uppercase text-center">OR SIGN UP WITH your EMAIL</div>
                            <div class="border-t border-[#DFDFDF] w-1/6"></div>
                        </div>
                    </template>
        
                    <template x-if="step === 1">
                        <div class="mt-5">
                            <div class="mb-5">
                                <label for="email" class="block mb-2 font-bold text-gray-900">Email Address</label>
                                <input type="email" id="email" x-model="email" value="{{ old('email') }}" name="email"
                                    class="bg-gray-50 border border-gray-300 text-gray-900  rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                    placeholder="" required />
                                <template x-if="errors.email">
                                    <p class="text-sm text-red-500 mt-1" x-text="errors.email[0]"></p>
                                </template>
                            </div>
                            <div class="mb-5">
                                <label for="password" class="block mb-2 font-bold text-gray-900">Password</label>
                                <input type="password" id="password" x-model="password" value="{{ old('password') }}" name="password"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                    required />
                                <template x-if="errors.password">
                                    <p class="text-sm text-red-500 mt-1" x-text="errors.password[0]"></p>
                                </template>
                            </div>
                            <div class="mb-5">
                                <label for="password_confirmation" class="block mb-2 font-bold text-gray-900">Confirm Password</label>
                                <input type="password" id="password_confirmation" x-model="password_confirmation" value="{{ old('password_confirmation') }}" name="password_confirmation"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                    required />
                                <template x-if="password_confirmation.email">
                                    <p class="text-sm text-red-500 mt-1" x-text="password_confirmation.email[0]"></p>
                                </template>
                            </div>
                            <button type="button" @click="nextStep($event)" :disabled="step === 4"
                                class="lg:w-1/2 text-white bg-primary custom-btn btn-primary-dark focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg w-full px-5 py-3.5 text-center">
                                Continue
                            </button>
        
                            <div class="flex items-center justify-center mt-8">
                                <div class="text-center text-sm">Already have an account? <a class="text-primary font-bold underline hover:text-primary-dark" href="{{ route('login') }}">Sign in now</a></div>
                            </div>
                        </div>
                    </template>
        
                    <template x-if="step === 2">
                        <div x-data="{ selected: 'individual' }" class="mx-auto">
                            <h2 class="text font-bold text-green-700 mb-4">Choose Account Type</h2>
                        
                            <!-- Individual Account -->
                            <button 
                                type="button"
                                @click="selected = 'individual'; accountType = 'individual'" 
                                class="flex items-center justify-between w-full p-4 border-2 rounded-lg transition duration-300 ease-in-out relative"
                                :class="selected === 'individual' ? 'border-primary bg-green-50' : 'border-gray-300 bg-white'"
                            >
                                <div class="flex items-center gap-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                    </svg>
                                      
                                    <div>
                                        <p class="font-bold text-gray-800 text-left">Individual</p>
                                        <p class="text-sm text-gray-500 text-left">I am creating an account for myself.</p>
                                    </div>
                                </div>
                                <svg x-show="selected === 'individual'" class="w-6 h-6 text-primary absolute top-2 right-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-3-3a1 1 0 011.414-1.414L9 11.086l6.293-6.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        
                            <!-- Organization Account -->
                            <button 
                                type="button"
                                @click="selected = 'organization'; accountType = 'organization'" 
                                class="flex items-center justify-between w-full p-4 mt-3 border-2 rounded-lg transition duration-300 ease-in-out relative"
                                :class="selected === 'organization' ? 'border-primary bg-green-50' : 'border-gray-300 bg-white'"
                            >
                                <div class="flex items-center gap-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                                    </svg>
                                      
                                    <div>
                                        <p class="font-bold text-gray-800 text-left">Organization</p>
                                        <p class="text-sm text-gray-500 text-left">I'm creating an account for my business or group.</p>
                                    </div>
                                </div>
                                <svg x-show="selected === 'organization'" class="w-6 h-6 text-primary absolute top-2 right-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-3-3a1 1 0 011.414-1.414L9 11.086l6.293-6.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                            
                            <div class="flex flex-col mt-6 lg:flex-row gap-4">
                                <button type="button" @click="step > 1 ? step-- : step" :disabled="step === 1"
                                    class="text-primary bg-white border border-primary hover:bg-primary hover:text-white font-medium rounded-lg w-full lg:w-1/2 sm:w-auto px-5 py-3.5 text-center">
                                    Back
                                </button>
                                <button type="button" @click="nextStep($event)" :disabled="step === 4"
                                    class="text-white bg-primary custom-btn btn-primary-dark font-medium rounded-lg w-full lg:w-1/2 sm:w-auto px-5 py-3.5 text-center">
                                    Continue
                                </button>
                            </div>
                        </div>
                    </template>
        
                    <template x-if="step === 3" && accountType == 'individual'">
                        <template x-if="accountType == 'individual'">
                            <div>
                                <h2 class="text font-bold text-green-700 mb-4">Personal Information</h2>
                                
                                <div>
                                    <div class="flex flex-col gap-4 mb-5">
                                        <div class="w-full">
                                            <label for="first_name" class="block mb-2 font-bold text-gray-900">First Name </label>
                                            <input type="text" id="first_name" x-model="first_name" value="{{ old('first_name') }}" name="first_name"
                                                class="bg-gray-50 border border-gray-300 text-gray-900  rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                                placeholder="" required />
                                            <template x-if="errors.first_name">
                                                <p class="text-sm text-red-500 mt-1" x-text="errors.first_name[0]"></p>
                                            </template>
                                        </div>
                                        <div class="w-full">
                                            <label for="last_name" class="block mb-2 font-bold text-gray-900">Last Name </label>
                                            <input type="text" id="last_name" x-model="last_name" value="{{ old('last_name') }}" name="last_name"
                                                class="bg-gray-50 border border-gray-300 text-gray-900  rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                                placeholder="" required />
                                            <template x-if="errors.last_name">
                                                <p class="text-sm text-red-500 mt-1" x-text="errors.last_name[0]"></p>
                                            </template>
                                        </div>
                                    </div>
                                    <div class="flex flex-col gap-4 mb-5">
        
                                        <div class="w-full">
                                            <label for="date" class="block mb-2 text-sm font-bold text-gray-900">Birth Date</label>
                                            <div class="relative w-full">
                                                <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                                                </svg>
                                                </div>
                                                <input id="default-datepicker" type="date" x-model="birth_date" name="birth_date" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-3" placeholder="Select date">
                                                <template x-if="errors.birth_date">
                                                    <p class="text-sm text-red-500 mt-1" x-text="errors.birth_date[0]"></p>
                                                </template>
                                            </div>
                                        </div>
        
                                        <div class="w-full">
                                            <label for="country" class="block mb-2 text-sm font-bold text-gray-900">Country</label>
                                            <select name="country" x-model="country" @change="changeCountry" id="country" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                                <option selected value="">Select</option>
                                                <option value="Philippines">Philippines</option>
                                                <option value="USA">USA</option>
                                                <option value="Canada">Canada</option>
                                                <option value="Others">Other Countries</option>
                                            </select>
                                            <template x-if="errors.country">
                                                <p class="text-sm text-red-500 mt-1" x-text="errors.country[0]"></p>
                                            </template>
                                        </div>
                                    </div>
                                    <template x-if="!local">
                                        <div class="flex flex-col gap-4 mb-5">
                                            <div class="w-full">
                                                <label for="int-address" class="block mb-2 text-sm font-bold text-gray-900">Address</label>
                                                <textarea name="international_address" x-model="international_address" id="int-address" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                                    {{ old('international_address') }}
                                                </textarea>
                                                <template x-if="errors.international_address">
                                                    <p class="text-sm text-red-500 mt-1" x-text="errors.international_address[0]"></p>
                                                </template>
                                            </div>
                                        </div>
                                    </template>
                                    <template x-if="local">
                                        <div 
                                            x-init="loadData()"
                                            class="space-y-4">

                                            <div>
                                                <label for="address_street" class="block mb-2 font-bold text-gray-900">Street Address </label>
                                                <input type="text" id="address_street" x-model="address_street" value="{{ old('address_street') }}" name="address_street"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900  rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                                    placeholder="" required />
                                                <template x-if="errors.address_street">
                                                    <p class="text-sm text-red-500 mt-1" x-text="errors.address_street[0]"></p>
                                                </template>
                                            </div>

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
                                                
                                                <template x-if="errors.address_region">
                                                    <p class="text-red-500 text-sm mt-2" x-text="errors.address_region[0]"></p>
                                                </template>
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

                                                <template x-if="errors.address_municipality">
                                                    <p class="text-red-500 text-sm mt-2" x-text="errors.address_municipality[0]"></p>
                                                </template>

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

                                                <template x-if="errors.address_city">
                                                    <p class="text-red-500 text-sm mt-2" x-text="errors.address_city[0]"></p>
                                                </template>
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

                                                <template x-if="errors.address_brgy">
                                                    <p class="text-red-500 text-sm mt-2" x-text="errors.address_brgy[0]"></p>
                                                </template>
                                            </div>
                                        </div>
                                    </template>
                                </div>
        
                                <div class="flex flex-col mt-6 lg:flex-row gap-4">
                                    <button type="button" @click="step > 1 ? step-- : step" :disabled="step === 1"
                                        class="text-primary bg-white border border-primary hover:bg-primary hover:text-white font-medium rounded-lg w-full lg:w-1/2 sm:w-auto px-5 py-3.5 text-center">
                                        Back
                                    </button>
                                    <button type="button" @click="nextStep($event)" :disabled="step === 4"
                                        class="text-white bg-primary custom-btn btn-primary-dark font-medium rounded-lg w-full lg:w-1/2 sm:w-auto px-5 py-3.5 text-center">
                                        Continue
                                    </button>
                                </div>
                            </div>
                        </template>
        
                    </template>
        
                    <template x-if="step === 3" && accountType == 'organization'">
                        <template x-if="accountType == 'organization'">
                            <div>
                                <h2 class="text font-bold text-green-700 mb-4">Organization Information</h2>
                            
                                <div>
                                    <div class="mb-5">
                                        <label for="org_name" class="block mb-2 font-bold text-gray-900">Organization Name </label>
                                        <input type="text" id="org_name" x-model="org_name" value="{{ old('org_name') }}" name="org_name"
                                            class="bg-gray-50 border border-gray-300 text-gray-900  rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                            placeholder="" required />
                                        <template x-if="errors.org_name">
                                            <p class="text-sm text-red-500 mt-1" x-text="errors.org_name[0]"></p>
                                        </template>
                                    </div>
                                    <div class="mb-5">
                                        <label for="contact_person" class="block mb-2 font-bold text-gray-900">Contact Person </label>
                                        <input type="text" id="contact_person" x-model="contact_person" value="{{ old('contact_person') }}" name="contact_person"
                                            class="bg-gray-50 border border-gray-300 text-gray-900  rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                            placeholder="" required />
                                        <template x-if="errors.contact_person">
                                            <p class="text-sm text-red-500 mt-1" x-text="errors.contact_person[0]"></p>
                                        </template>
                                    </div>
        
                                    <div class="w-full mb-5">
                                        <label for="country" class="block mb-2 text-sm font-bold text-gray-900">Country</label>
                                        <select name="country" x-model="country" @change="changeCountry" id="country" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                            <option selected value="">Select</option>
                                            <option value="Philippines">Philippines</option>
                                            <option value="USA">USA</option>
                                            <option value="Canada">Canada</option>
                                        </select>
                                        <template x-if="errors.country">
                                            <p class="text-sm text-red-500 mt-1" x-text="errors.country[0]"></p>
                                        </template>
                                    </div>
                                    <template x-if="!local">
                                        <div class="flex flex-col gap-4 mb-5">
                                            <div class="w-full">
                                                <label for="int-address" class="block mb-2 text-sm font-bold text-gray-900">Address</label>
                                                <textarea name="international_address" x-model="international_address" id="int-address" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                                    {{ old('international_address') }}
                                                </textarea>
                                                <template x-if="errors.international_address">
                                                    <p class="text-sm text-red-500 mt-1" x-text="errors.international_address[0]"></p>
                                                </template>
                                            </div>
                                        </div>
                                    </template>
                                    <template x-if="local">
                                        <div 
                                            x-init="loadData()"
                                            class="space-y-4">

                                            <div>
                                                <label for="address_street" class="block mb-2 font-bold text-gray-900">Address </label>
                                                <input type="text" id="address_street" x-model="address_street" value="{{ old('address_street') }}" name="address_street"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900  rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                                    placeholder="" required />
                                                <template x-if="errors.address_street">
                                                    <p class="text-sm text-red-500 mt-1" x-text="errors.address_street[0]"></p>
                                                </template>
                                            </div>

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
                                                
                                                <template x-if="errors.address_region">
                                                    <p class="text-red-500 text-sm mt-2" x-text="errors.address_region[0]"></p>
                                                </template>
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

                                                <template x-if="errors.address_municipality">
                                                    <p class="text-red-500 text-sm mt-2" x-text="errors.address_municipality[0]"></p>
                                                </template>

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

                                                <template x-if="errors.address_city">
                                                    <p class="text-red-500 text-sm mt-2" x-text="errors.address_city[0]"></p>
                                                </template>
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

                                                <template x-if="errors.address_brgy">
                                                    <p class="text-red-500 text-sm mt-2" x-text="errors.address_brgy[0]"></p>
                                                </template>
                                            </div>
                                        </div>
                                    </template>
        
                                    <div class="flex flex-col mt-6 lg:flex-row gap-4">
                                        <button type="button" @click="step > 1 ? step-- : step" :disabled="step === 1"
                                            class="text-primary bg-white border border-primary hover:bg-primary hover:text-white font-medium rounded-lg w-full lg:w-1/2 sm:w-auto px-5 py-3.5 text-center">
                                            Back
                                        </button>
                                        <button type="button" @click="nextStep($event)" :disabled="step === 4"
                                            class="text-white bg-primary custom-btn btn-primary-dark font-medium rounded-lg w-full lg:w-1/2 sm:w-auto px-5 py-3.5 text-center">
                                            Continue
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </template>
        
                    <template x-if="step === 4">
                        <div>
                            <h2 class="text font-bold text-green-700 mb-4">Contact Details</h2>
                        
                            <div>
                                <div class="mb-5">
                                    <label for="mobile" class="block mb-2 font-bold text-gray-900">Mobile Number </label>
                                    <input type="tel" id="mobile" x-model="mobile" value="{{ old('mobile') }}" name="mobile"
                                        class="bg-gray-50 border border-gray-300 text-gray-900  rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                        placeholder="" required />
                                    <template x-if="errors.mobile">
                                        <p class="text-sm text-red-500 mt-1" x-text="errors.mobile[0]"></p>
                                    </template>
                                </div>
                                <div class="mb-5">
                                    <label for="tel" class="block mb-2 font-bold text-gray-900">Telephone Number</label>
                                    <input type="tel" id="tel" x-model="tel" value="{{ old('tel') }}" name="tel"
                                        class="bg-gray-50 border border-gray-300 text-gray-900  rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                        placeholder="" />
                                    <template x-if="errors.tel">
                                        <p class="text-sm text-red-500 mt-1" x-text="errors.tel[0]"></p>
                                    </template>
                                </div>
                                <div class="mb-5">
                                    <label for="fax" class="block mb-2 font-bold text-gray-900">Fax Number</label>
                                    <input type="tel" id="fax" x-model="fax" value="{{ old('fax') }}" name="fax"
                                        class="bg-gray-50 border border-gray-300 text-gray-900  rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                        placeholder="" />
                                    <template x-if="errors.fax">
                                        <p class="text-sm text-red-500 mt-1" x-text="errors.fax[0]"></p>
                                    </template>
                                </div>
                                <div class="mb-5">
                                    <label for="agent_code" class="block mb-2 font-bold text-gray-900">Agent Code</label>
                                    <input type="text" id="agent_code" x-model="agent_code" value="{{ old('agent_code') }}" name="agent_code"
                                        class="bg-gray-50 border border-gray-300 text-gray-900  rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                        placeholder="" />
                                    <template x-if="errors.agent_code">
                                        <p class="text-sm text-red-500 mt-1" x-text="errors.agent_code[0]"></p>
                                    </template>
                                </div>
        
                                <div class="flex items-start mb-2">
                                    <div class="flex items-center h-5">
                                        <input id="is_subscribe" x-model="is_subscribe" type="checkbox" value="" name="is_subscribe"
                                            class="w-4 h-4 border border-gray-300 rounded-sm bg-gray-50 focus:ring-3 focus:ring-blue-300" />
                                    </div>
                                    <label for="is_subscribe" class="ms-2 text-sm font-medium">I want to receive exclusive offers and promotions.</label>
                                </div>
                     
                                <div class="flex items-start mb-5">
                                    <div class="flex items-center h-5">
                                        <input id="privacy" type="checkbox" x-model="privacy" name="privacy"
                                            class="w-4 h-4 border border-gray-300 rounded-sm bg-gray-50 focus:ring-3 focus:ring-blue-300" />
                                    </div>
                                    <label for="privacy" class="ms-2 text-sm font-medium">
                                        I agree to 
                                        <a class="underline" target="_blank" href="/privacy-policy">Lydia’s Lechon’s Privacy Protection Policy</a>
                                    </label>
                                </div>
                                <template x-if="errors.privacy">
                                    <p class="text-red-500 text-xs mt-1" x-text="errors.privacy[0]"></p>
                                </template>
                                
                                <template x-for="[key, value] in Object.entries({
                                    email,
                                    password,
                                    password_confirmation,
                                    account_type: accountType,
                                    first_name,
                                    last_name,
                                    birth_date,
                                    org_name,
                                    contact_person,
                                    address,
                                    country,
                                    address_street,
                                    address_city,
                                    address_brgy,
                                    address_municipality,
                                    address_region,
                                    international_address,
                                    mobile,
                                    tel,
                                    fax,
                                    agent_code,
                                    is_subscribe
                                })" :key="key">
                                    <input type="hidden" :name="key" :value="value">
                                </template>
        
                                <div class="flex flex-col mt-6 lg:flex-row gap-4">
                                    <button type="button" @click="step > 1 ? step-- : step" :disabled="step === 1"
                                        class="text-primary bg-white border border-primary hover:bg-primary hover:text-white font-medium rounded-lg w-full lg:w-1/2 sm:w-auto px-5 py-3.5 text-center">
                                        Back
                                    </button>
                                    <button @click="nextStep($event)" type="button"
                                        class="text-white bg-primary custom-btn btn-primary-dark font-medium rounded-lg w-full lg:w-1/2 sm:w-auto px-5 py-3.5 text-center">
                                        Sign up
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                    
                    @if(request()->has('redirect'))
                        <input type="hidden" name="redirect" value="{{ request()->get('redirect') }}">
                    @endif
        
                </form>
            </div>
        </div>

        <div class="w-full lg:w-1/2 hidden lg:block">
            <img src="{{ asset('images/signup.png') }}" alt="Signup">
        </div>
    </div>
</div>
    
<x-footer-component />

<script>
function registrationForm() {
    return {
        step: 1,
        accountType: 'individual',
        errors: {},

        // Collected data
        email: '',
        password: '',
        password_confirmation: '',
        first_name: '',
        last_name: '',
        birth_date: '',
        org_name: '',
        contact_person: '',
        address: '',
        address_street: '',
        address_city: '',
        address_brgy: '',
        address_municipality: '',
        address_region: '',
        international_address: '',
        mobile: '',
        tel: '',
        fax: '',
        agent_code: '',
        is_subscribe: false,
        privacy: false,

        async nextStep(event) {
            this.errors = {};

            const form = event.target.closest('form');
            const formData = new FormData(form);

            formData.append('step', this.step);
            formData.append('account_type', this.accountType);

            try {
                const response = await fetch(`{{ route('signup.validate-fields') }}`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                });

                const result = await response.json();

                if (!response.ok) {
                    this.errors = result.errors || {};
                    return;
                }

                if (this.step < 4) {
                    this.step++;
                } else {
                    this.injectHiddenFields(form);
                    if (!this.privacy) {
                        this.errors.privacy = ['You must agree to the privacy policy.'];
                        return;
                    } else {
                        this.errors.privacy = null;
                    }
                    form.submit();
                }

            } catch (error) {
                console.error('Validation failed', error);
            }
        },

        injectHiddenFields(form) {
            const fields = {
                email: this.email,
                password: this.password,
                password_confirmation: this.password_confirmation,
                account_type: this.accountType,
                first_name: this.first_name,
                last_name: this.last_name,
                birth_date: this.birth_date,
                org_name: this.org_name,
                contact_person: this.contact_person,
                address_street: this.address_street,
                address_city: this.cityCode,
                address_brgy: this.barangayCode,
                address_municipality: this.provinceCode,
                address_region: this.regionCode,
                international_address: this.international_address,
                mobile: this.mobile,
                tel: this.tel,
                fax: this.fax,
                agent_code: this.agent_code,
                is_subscribe: this.is_subscribe ? 1 : 0,
            };

            for (const [name, value] of Object.entries(fields)) {
                let hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = name;
                hiddenInput.value = value ?? '';
                form.appendChild(hiddenInput);
            }
        },


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
                this.regionCode = @json(old('address_region', auth()->user()?->address_region ?? ''));
                this.filterProvinces();

                this.$nextTick(() => {
                    this.provinceCode = @json(old('address_municipality', auth()->user()?->address_municipality ?? ''));
                    this.filterCities();

                    this.$nextTick(() => {
                        this.cityCode = @json(old('address_city', auth()->user()?->address_city ?? ''));
                        this.filterBarangays();

                        this.$nextTick(() => {
                            this.barangayCode = @json(old('address_brgy', auth()->user()?->address_brgy ?? ''));
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
        },
        country: 'Philippines', 
        local: true, 
        changeCountry() {
            this.local = this.country == 'Philippines';
        }
    }
}

</script>

@endsection