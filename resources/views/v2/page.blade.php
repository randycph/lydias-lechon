@extends('layouts.guest')

@section('title', $page->meta_title ?? $page->label ?? 'Careers at Lydia\'s Lechon')
@section('meta_description', $page->meta_description ?? 'Join the Lydia\'s Lechon family! Explore exciting career opportunities and be part of our mission to bring joy and delicious lechon to every Filipino home. Apply now!')
@section('image', $page->image_url ?? null)

@section('content')

    <div>
        @if ($page->contents)
            @php
                $cleanContent = stripslashes($page->contents);
            @endphp

            {!! parse_shortcodes($cleanContent) !!}
        @endif
    </div>

    <x-footer-component />
    
@endsection

