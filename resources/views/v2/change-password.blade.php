@extends('layouts.guest', ['page' => $page])

@section('title', 'Change Password | ')
@section('meta_description', 'Change your password to enhance the security of your account. Ensure your new password meets the required criteria for a secure login experience.')

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

                    @if (session('success'))
                    <div class="flex justify-center py-10">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor"
                                class="mb-2 rounded-full size-10 bg-primary flex items-center justify-center mx-auto text-white">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>

                            <h3 class="text-lg font-semibold" id="modal-title">Password Updated</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">Your password has been changed successfully. You can now
                                    log in with your new password.</p>
                            </div>
                        </div>
                    </div>
                    @else

                    <form action="{{ route('my-account.update-password') }}" method="POST" class="flex items-start flex-col gap-2  py-5 border-b border-[#DFDFDF]">
                        @csrf
                        <div x-show="!passwordSuccess">
                            <div class="text-sm text-gray-500 px-6">
                                To change your password, please fill in the fields below. Your password must contain at
                                <strong>least 10 characters</strong>, it must also include at least one upper case
                                letter, one lower case letter, one number and one special character.
                            </div>
                            <div class="px-6 w-full text-sm mt-5">
                                <div class="mb-5">
                                    <label for="current-password" class="block mb-2 font-bold text-gray-900">Current
                                        Password <span class="text-red-800">*</span> </label>
                                    <input type="password" id="current-password" name="current_password" value="{{ old('current_password') }}"
                                        class="bg-gray-50 border border-gray-300 text-gray-900  rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                        placeholder="" required />
                                    
                                    @error('current_password')
                                        <div class="text-red-500 text-sm mt-1">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="mb-5">
                                    <label for="new-password" class="block mb-2 font-bold text-gray-900">New Password
                                        <span class="text-red-800">*</span> </label>
                                    <input type="password" id="new-password" name="password" value="{{ old('password') }}"
                                        class="bg-gray-50 border border-gray-300 text-gray-900  rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                        placeholder="" required />

                                    @error('password')
                                        <div class="text-red-500 text-sm mt-1">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="mb-5">
                                    <label for="password_confirmation" class="block mb-2 font-bold text-gray-900">Confirm
                                        Password <span class="text-red-800">*</span> </label>
                                    <input type="password" id="password_confirmation" name="password_confirmation" value="{{ old('password_confirmation') }}"
                                        class="bg-gray-50 border border-gray-300 text-gray-900  rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                        placeholder="" required />

                                    @error('password_confirmation')
                                        <div class="text-red-500 text-sm mt-1">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="w-full px-6">
                                <button type="submit"
                                    class="text-white bg-primary custom-btn btn-primary-dark font-medium rounded-md w-full sm:w-auto px-5 py-3.5 text-center">
                                    Change password
                                </button>
                            </div>
                        </div>
                    </form>
                    
                    @endif
                </div>
            </div>

        </div>

    </div>
</div>

<x-footer-component />

@endsection