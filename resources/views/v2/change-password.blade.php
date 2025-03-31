@extends('layouts.guest', ['page' => $page])

@section('content')

<div x-data="{ passwordSuccess: false }" class="bg-cream">
    <div class="py-20 px-4 container">
        <div class="flex gap-6 lg:flex-row flex-col mt-10">

            <div class="w-full lg:w-1/4">
                <x-account-menu-component />
            </div>

            <div class="w-full lg:w-3/4">
                <div class="rounded-lg border bg-white border-[#DFDFDF] shadow-md">
                    <div class="px-6 py-4 border-b border-[#DFDFDF]">
                        <h2 class="font-semibold text-tertiary text-left uppercase">Change Password</h2>
                    </div>
                    <div class="flex items-start flex-col gap-2  py-5 border-b border-[#DFDFDF]">
                        <div x-show="!passwordSuccess">
                            <div class="text-sm text-gray-500 px-6">
                                To change your password, please fill in the fields below. Your password must contain at
                                <strong>least 10 characters</strong>, it must also include at least one upper case
                                letter, one lower case letter, one number and one special character.
                            </div>
                            <div class="px-6 w-full text-sm">
                                <div class="mb-5">
                                    <label for="current-password" class="block mb-2 font-bold text-gray-900">Current
                                        Password <span class="text-red-800">*</span> </label>
                                    <input type="password" id="current-password"
                                        class="bg-gray-50 border border-gray-300 text-gray-900  rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                        placeholder="" required />
                                </div>
                                <div class="mb-5">
                                    <label for="new-password" class="block mb-2 font-bold text-gray-900">New Password
                                        <span class="text-red-800">*</span> </label>
                                    <input type="password" id="new-password"
                                        class="bg-gray-50 border border-gray-300 text-gray-900  rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                        placeholder="" required />
                                </div>
                                <div class="mb-5">
                                    <label for="confirm-password" class="block mb-2 font-bold text-gray-900">Confirm
                                        Password <span class="text-red-800">*</span> </label>
                                    <input type="password" id="confirm-password"
                                        class="bg-gray-50 border border-gray-300 text-gray-900  rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                        placeholder="" required />
                                </div>
                            </div>

                            <div class="w-full px-6">
                                <button @click="passwordSuccess = true" type="button"
                                    class="text-white bg-primary hover:bg-primary-dark font-medium rounded-lg w-full sm:w-auto px-5 py-3.5 text-center">
                                    Change password
                                </button>
                            </div>
                        </div>

                        <div class="sm:flex sm:items-end" x-show="passwordSuccess">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor"
                                    class="mb-2 rounded-full size-10 bg-primary flex items-center justify-center mx-auto text-white">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>

                                <h3 class="text-lg font-semibold" id="modal-title">Password Updated</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">Your password has been changed successfully! Use
                                        your new password to login</p>
                                </div>
                            </div>
                            <div class="w-full px-6 mt-5">
                                <a href="{{ route('login') }}" type="button"
                                    class="text-white w-full bg-primary flex justify-center hover:bg-primary-dark font-medium rounded-lg px-5 py-3.5 text-center">
                                    Go to Login
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

<x-footer-component />

@endsection