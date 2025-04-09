@extends('layouts.guest', ['page' => $page])

@section('content')

<div x-data="{ expanded: false }" class="bg-cream">
    <div class="flex container">
        <div class="w-full lg:w-1/2 lg:pr-10 pr-0">
            <div class="pb-20 px-4">
                <div class="pt-20 pb-5">
                    <h1 class="text-4xl font-cubao font-medium text-primary text-left mt-10">Forgot Password</h1>
                    <h3 class="font-medium text-left">
                        Enter your email address and we will send you a link to reset your password.
                    </h3>
                </div>
        
                <div class="">
                    <div class="mt-5">
                        <div class="mb-5">
                            <label for="email" class="block mb-2 font-bold text-gray-900 ">Email Address</label>
                            <input type="email" id="email"
                                class="bg-gray-50 border border-gray-300 text-gray-900  rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                                placeholder="email@email.com" required />
                        </div>
                        <div class="text-white bg-primary text-center custom-btn btn-primary-dark focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-md w-full sm:w-auto px-5 py-3.5 ">
                            <button  type="button"
                                class="text-center">
                                Reset Password
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="w-full lg:w-1/2 hidden lg:block">
            <img src="{{ asset('images/signin.png') }}" alt="signin">
        </div>
    </div>
</div>
    
<x-footer-component />

@endsection