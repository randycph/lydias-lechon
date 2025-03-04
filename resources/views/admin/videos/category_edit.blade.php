@extends('admin.layouts.app')

@section('pagetitle')
    Edit Video Category
@endsection

@section('pagecss')
    <link href="{{ asset('lib/select2/css/select2.min.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container pd-x-0">
    <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                    <li class="breadcrumb-item" aria-current="page"><a href="{{route('dashboard')}}">CMS</a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="{{route('video-categories.index')}}">Video Category</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Video Category</li>
                </ol>
            </nav>
            <h4 class="mg-b-0 tx-spacing--1">Edit Video Category</h4>
        </div>
    </div>

    @if (count($errors) > 0)
        <div class = "alert alert-danger">
            <ul>
               @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
               @endforeach
            </ul>
        </div>
    @endif

    @if($message = Session::get('duplicate'))
        <div class="alert alert-warning d-flex align-items-center mg-t-15" role="alert">
            <p class="mg-b-0"><i data-feather="alert-circle" class="mg-r-10"></i>{{ $message }}
        </div>
    @endif

    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card ht-lg-100p">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h6 class="mg-b-0">Video Category Form</h6>
                </div><!-- card-header -->
                <div class="card-body pd-0">
                    <div class="table-responsive">
                        <form action="{{ route('video-categories.update',$category->id) }}" method="post" id="selectForm2" class="parsley-style-1" data-parsley-validate novalidate>
                            @method('PUT')
                            @csrf
                            <div class="modal-body pd-sm-t-30 pd-sm-b-40 pd-sm-x-30">
                                <div class="row row-sm">
                                    <div class="col-sm-12">
                                        <div id="fname" class="parsley-input">
                                            <label class="tx-10 tx-uppercase tx-medium tx-spacing-1 mg-b-5 tx-color-03">Video Category Name <i class="tx-danger">*</i></label>
                                            <input type="text" name="category" value="{{ old('category',$category->name)}}" class="form-control" data-parsley-class-handler="#fname" required>
                                            <small id="category_slug"><a target="_blank" href="{{env('APP_URL')}}/video-gallery/?type=category&criteria={{$category->id}}">{{env('APP_URL')}}/video-gallery/?type=category&criteria={{$category->id}}</a></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer pd-x-20 pd-y-15">
                                <a href="{{ route('video-categories.index') }}" class="btn btn-outline-secondary btn-sm btn-uppercase">Cancel</a>
                                <button type="submit" class="btn btn-sm btn-primary">Update Category</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('pagejs')
    <script src="{{ asset('lib/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('lib/parsleyjs/parsley.min.js') }}"></script>
@endsection

@section('customjs')
	<script>
        $(function(){
            'use strict'
            $('.select2-no-search').select2({
                minimumResultsForSearch: Infinity,
                placeholder: 'Choose User Role'
            });
        });

        $(function(){
            'use strict'
            $('.select2-no-search').select2({
                minimumResultsForSearch: Infinity,
                placeholder: 'Choose User Role'
            });
        });
    </script>
@endsection
