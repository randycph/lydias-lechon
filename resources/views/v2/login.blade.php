@extends('layouts.guest', ['page' => $page])

@section('content')

<div x-data="{ expanded: false }" class="bg-cream">
    <div class="pb-20 px-4 container">
        <div class="pt-20 pb-5">
            <h1 class="text-4xl font-cubao font-medium text-primary text-left mt-10">Welcome back!</h1>
            <h3 class="font-medium text-left">Sign in to your account now</h3>
        </div>

        <div class="">
            <button class="hover:bg-gray-50 border border-primary text-primary px-6 py-4 mt-4 w-full rounded-md">
                <img src="{{ asset('images/google.png') }}" alt="Google" class="w-6 h-6 inline-block">
                <span>Sign in with Google</span>
            </button>

            <div class="flex text-sm items-center justify-center gap-4 mt-6 w-full">
                <div class="border-t border-[#DFDFDF] w-1/6"></div>
                <div class="text-gray-400 uppercase text-center">Or sign in with your email</div>
                <div class="border-t border-[#DFDFDF] w-1/6"></div>
            </div>

            <div class="mt-5">
                <div class="mb-5">
                    <label for="email" class="block mb-2 font-bold text-gray-900 ">Email Address</label>
                    <input type="email" id="email"
                        class="bg-gray-50 border border-gray-300 text-gray-900  rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                        placeholder="email@email.com" required />
                </div>
                <div class="mb-5">
                    <label for="password" class="block mb-2 font-bold text-gray-900 ">Password</label>
                    <input type="password" id="password"
                        class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                        required />
                </div>
                <div class="flex items-start mb-5">
                    <a href="#" class="text-sm underline font-bold text-primary dark:text-blue-500">Forgot your
                        password?</a>
                </div>
                <div class="flex items-start mb-5">
                    <div class="flex items-center h-5">
                        <input id="remember" type="checkbox" value=""
                            class="w-4 h-4 border border-gray-300 rounded-sm bg-gray-50 focus:ring-3 focus:ring-blue-300" />
                    </div>
                    <label for="remember" class="ms-2 text-sm font-medium dark:text-gray-300">Remember me</label>
                </div>
                <div class="text-white bg-primary text-center custom-btn btn-primary-dark focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-md w-full sm:w-auto px-5 py-3.5 ">
                    <a href="{{ route('my-account') }}" type="submit"
                        class="text-center">
                        Sign in
                    </a>
                </div>
            </div>

            <div>
                <div class="flex items-center justify-center mt-8">
                    <div class="text-center text-sm">Don't have an account yet? <a class="text-primary font-bold underline hover:text-primary-dark" href="{{ route('signup') }}">Sign up now</a></div>
                </div>
            </div>
        </div>
    </div>
</div>
    
<x-footer-component />

@endsection