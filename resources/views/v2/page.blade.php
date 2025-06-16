@extends('layouts.guest')

@section('title', $page->meta_title ?? $page->label ?? 'Careers at Lydia\'s Lechon')
@section('meta_description', $page->meta_description ?? 'Join the Lydia\'s Lechon family! Explore exciting career opportunities and be part of our mission to bring joy and delicious lechon to every Filipino home. Apply now!')
@section('image', $page->image_url ?? null)

@section('content')

    <div class="container">
        <div class="pt-32 pb-10 px-4">

            @if ($page->contents)
                {!! $page->contents !!}
            @endif

        </div>
    </div>

    <x-footer-component />
    
@endsection

