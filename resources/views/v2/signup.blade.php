@extends('layouts.guest', ['page' => $page])

@section('content')

<div x-data="{ step: 2 }" class="bg-cream">
    <div class="pb-20 px-4">
        <div class="pt-20 pb-5">
            <h1 class="text-4xl font-cubao font-medium text-primary text-left mt-10">sign up now!</h1>
            <h3 class="font-medium text-left">Create an account with Lydia's Lechon to enjoy faster orders, exclusive offers, and stay updated on our latest promos!</h3>
        </div>

        <div class="">
            <div class="max-w-lg mx-auto p-4">
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
            
            <button class="border border-primary text-primary px-6 py-4 mt-4 w-full rounded-md">
                <img src="{{ asset('images/google.png') }}" alt="Google" class="w-6 h-6 inline-block">
                <span>Sign in with Google</span>
            </button>

            <div class="flex text-sm items-center justify-center gap-4 mt-6 w-full">
                <div class="border-t border-[#DFDFDF] w-1/6"></div>
                <div class="text-gray-400 uppercase text-center">OR SIGN UP WITH your EMAIL</div>
                <div class="border-t border-[#DFDFDF] w-1/6"></div>
            </div>

            <template x-if="step === 1">
                <div class="mt-5">
                    <div class="mb-5">
                        <label for="email" class="block mb-2 font-bold text-gray-900 dark:text-white">Email Address</label>
                        <input type="email" id="email"
                            class="bg-gray-50 border border-gray-300 text-gray-900  rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="email@email.com" required />
                    </div>
                    <div class="mb-5">
                        <label for="password" class="block mb-2 font-bold text-gray-900 dark:text-white">Password</label>
                        <input type="password" id="password"
                            class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            required />
                    </div>
                    <div class="mb-5">
                        <label for="password" class="block mb-2 font-bold text-gray-900 dark:text-white">Confirm Password</label>
                        <input type="password" id="password"
                            class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            required />
                    </div>
                    <button type="submit" @click="step < 4 ? step++ : step" :disabled="step === 4"
                        class="text-white bg-primary hover:bg-primary-dark focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg w-full sm:w-auto px-5 py-3.5 text-center">
                        Continue
                    </button>
                </div>

                <div class="flex items-center justify-center mt-8">
                    <div class="text-center text-sm">Already have an account? <a class="text-primary font-bold underline hover:text-primary-dark" href="{{ route('login') }}">Sign in now</a></div>
                </div>
            </template>

            <template x-if="step === 2">
                <div x-data="{ selected: 'individual' }" class="max-w-md mx-auto">
                    <h2 class="text font-bold text-green-700 mb-4 mt-5">Choose Account Type</h2>
                
                    <!-- Individual Account -->
                    <button 
                        @click="selected = 'individual'" 
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
                        @click="selected = 'organization'" 
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
                    
                    <div class="flex flex-col mt-6">
                        <button type="button" @click="step < 4 ? step++ : step" :disabled="step === 4"
                            class="text-white bg-primary hover:bg-primary-dark font-medium rounded-lg w-full sm:w-auto px-5 py-3.5 text-center">
                            Continue
                        </button>
                        <button type="button" @click="step > 1 ? step-- : step" :disabled="step === 1"
                            class="text-primary bg-white border border-primary hover:bg-primary hover:text-white font-medium rounded-lg w-full sm:w-auto px-5 py-3.5 text-center mt-2">
                            Back
                        </button>
                    </div>
                </div>
            </template>

            <template x-if="step === 3">

                <h2 class="text font-bold text-green-700 mb-4 mt-5">Personal Information</h2>

            </template>

        </div>
    </div>
</div>
    
<x-footer-component />

@endsection