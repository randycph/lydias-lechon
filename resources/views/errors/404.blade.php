@extends('layouts.guest', ['page' => ''])

@section('title', 'Page Not Found | ')
@section('meta_description', 'The page you are looking for does not exist. Please check the URL or return to the homepage.')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-[#ffe7c1] px-4">
    <div class="text-center max-w-lg  py-20">
        {{-- Header --}}
        {{-- Placeholder Image --}}
        <img 
            src="{{ asset('images/404-image.png') }}" 
            alt="Lechon being cooked" 
            class="mx-auto mb-6 rounded"
        >

        <h1 class="text-4xl font-bold text-red-700 mb-5">404 - Page Not Found</h1>
        
        <p class="mb-4 font-semibold text-lg">
            Looks like the page you're looking for is still roasting… <br>
            Maybe it's not ready to be served just yet.
        </p>

        <a 
            href="{{ url('/') }}" 
            class="inline-block px-6 py-3 bg-red-600 text-white font-semibold rounded hover:bg-red-700 transition"
        >
        Back to Homepage
        </a>
    </div>
</div>
@endsection
