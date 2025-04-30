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

                @if (session('status'))
                    <div class="my-4 text-green-600 font-semibold text-left">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="bg-red-100 text-red-600 p-4 rounded mb-4">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
        
                <form class="" action="{{ route('password.send_reset_link_email') }}" method="POST">
                    @csrf
                    <div class="mt-5">
                        <div class="mb-5">
                            <label for="email" class="block mb-2 font-bold text-gray-900 ">Email Address</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900  rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 @if ($errors->has('email')) border-red-500 @endif"
                                placeholder="email@email.com" required />
                            @error('email')
                                <p class="text-sm text-red-500 mt-2 text-left">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit" class="text-white bg-primary text-center custom-btn btn-primary-dark focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-md w-full px-5 py-3.5 ">
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