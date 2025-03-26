@extends('theme.'.config('app.frontend_template').'.main')

@section('pagecss')
    <link rel="stylesheet" href="{{ asset('theme/lydias/plugins/jssocials/jssocials-theme-flat.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('theme/lydias/plugins/jssocials/jssocials.css') }}" />
    <style>
        .timeline-item:hover .border {background-color: #28a745 !important;border:none !important;}
        .timeline-item:hover .card {border-color: #28a745 !important;box-shadow: 0 .5rem 1rem rgba(0,0,0,.15) !important;}
    </style>
@endsection

@section('content')
    {!! Setting::getCareers()->contents !!}
@endsection

@section('jsscript')
    <script src="{{ asset('theme/lydias/plugins/jssocials/jssocials.js') }}"></script>

@endsection
