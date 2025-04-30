@extends('layouts.guest', ['page' => 'Reset Password'])

@section('content')

<div class="bg-cream">
    <div class="flex container">
        <div class="w-full lg:w-1/2 lg:pr-10 pr-0">
            <div class="pb-20 px-4">
                <div class="pt-20 pb-5">
                    <h1 class="text-4xl font-cubao font-medium text-primary text-left mt-10">Reset Password</h1>
                    <h3 class="font-medium text-left">
                        Enter your new password below to reset your account.
                    </h3>
                </div>

                @if ($errors->any())
                    <div class="bg-red-100 text-red-600 p-4 rounded mb-4">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('password.update') }}" method="POST">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">
                    <input type="hidden" name="email" value="{{ old('email', $email) }}">

                    <div class="mt-5">
                        <div class="mb-5">
                            <label for="password" class="block mb-2 font-bold text-gray-900">New Password</label>
                            <input type="password" name="password" id="password" required
                                class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 @error('password') border-red-500 @enderror">
                            @error('password')
                                <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-5">
                            <label for="password_confirmation" class="block mb-2 font-bold text-gray-900">Confirm Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" required
                                class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        </div>

                        <button type="submit"
                            class="text-white bg-primary text-center custom-btn btn-primary-dark focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-md w-full px-5 py-3.5">
                            Reset Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="w-full lg:w-1/2 hidden lg:block">
            <img src="{{ asset('images/signin.png') }}" alt="signin">
        </div>
    </div>
</div>

<x-footer-component />

@endsection
