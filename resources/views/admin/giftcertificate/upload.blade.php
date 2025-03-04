@extends('admin.layouts.app')

@section('pagetitle')
    Gift Certificate Manager
@endsection

@section('pagecss')
    <link href="{{ asset('lib/bselect/dist/css/bootstrap-select.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/ion-rangeslider/css/ion.rangeSlider.min.css') }}" rel="stylesheet">
    <style>
        .row-selected {
            background-color: #92b7da !important;
        }
    </style>
@endsection

@section('content')

    <div class="container pd-x-0">
        <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-style1 mg-b-5">
                        <li class="breadcrumb-item" aria-current="page"><a href="{{route('dashboard')}}">CMS</a></li>
                        <li class="breadcrumb-item" aria-current="page"><a href="{{route('gift-certificate.index')}}">Gift Certificate</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Upload Gift Certificate</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0 tx-spacing--1">Upload Gift Certificate</h4>
            </div>
        </div>

        <div class="row row-sm">
            <div class="col-lg-12">
            
                  
                    @if(Session::has('error'))
                        <div class="alert alert-danger" role="alert">
                          Error: {!! Session::get('error') !!}
                        </div>
                    @endif

                    <div class="row row-sm">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Upload Gift Certificate</h5>
                                    <p class="card-text">Note: Make sure to use and follow the csv template. Any addition of column will invalidate your upload.</p>
                                    <form action="{{route('gift-certificate.upload_submit')}}" method="post" enctype="multipart/form-data">
                                        @method('POST')
                                        @csrf
                                        <input type="file" name="uploaded_file" required="required">
                                        <input type="submit" class="btn btn-md btn-info" value="Upload">
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Download Template</h5>
                                    <a href="{{ asset('data/gc/gc-template.csv') }}" class="card-link">GC-Template.csv</a>
                                </div>
                            </div>
                        </div>
                    </div>
           
            </div>
        </div>
    </div>

@endsection

@section('pagejs')
    <script src="{{ asset('lib/bselect/dist/js/bootstrap-select.js') }}"></script>
    <script src="{{ asset('lib/bselect/dist/js/i18n/defaults-en_US.js') }}"></script>
    <script src="{{ asset('lib/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>

    <script>
        {{--let searchType = "{{ $searchType }}";--}}
    </script>

    <script src="{{ asset('js/listing.js') }}"></script>
@endsection

@section('customjs')

    <script>
    
    </script>

@endsection
