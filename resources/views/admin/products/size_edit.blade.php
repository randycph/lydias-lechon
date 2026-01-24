@extends('admin.layouts.app')

@section('pagetitle')
    Edit Product Size
@endsection

@section('pagecss')
    <link href="{{ asset('lib/bselect/dist/css/bootstrap-select.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container pd-x-0">
    <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                    <li class="breadcrumb-item" aria-current="page"><a href="{{route('dashboard')}}">CMS</a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="{{route('sizes.index')}}">Products</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit a Product Size</li>
                </ol>
            </nav>
            <h4 class="mg-b-0 tx-spacing--1">Edit a Product Size</h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <form action="{{ route('sizes.update',$size->id) }}" method="post" enctype="multipart/form-data" id="size_form">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label class="d-block">Name *</label>
                        <input type="text" name="name" id="name" value="{{ old('name',$size->name)}}" class="form-control @error('name') is-invalid @enderror">
                        <x-error-message inputName="name" />
                    </div>

                    <div class="form-group">
                        <label class="d-block">Description *</label>
                        <textarea rows="3" class="form-control @error('description') is-invalid @enderror" name="description">{{ old('description',$size->description) }}</textarea>
                        <x-error-message inputName="description" />
                    </div>

                    <div class="form-group">
                        <label class="d-block">Visibility</label>
                        <div class="custom-control custom-switch @error('visibility') is-invalid @enderror">
                            <input type="checkbox" class="custom-control-input" name="visibility" {{ ($size->status == 'PUBLISHED' ? "checked":"") }} id="customSwitch1">
                            <label class="custom-control-label" id="label_visibility" for="customSwitch1">{{ucwords(strtolower($size->status))}}</label>
                        </div>
                        @error('visibility')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <button class="btn btn-primary btn-sm btn-uppercase" type="submit">Update Size</button>
                    <a class="btn btn-outline-secondary btn-sm btn-uppercase" href="{{ route('sizes.index') }}">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('pagejs')
    <script src="{{ asset('lib/bselect/dist/js/bootstrap-select.js') }}"></script>
@endsection

@section('customjs')
    <script>
        $(function() {
            $('.selectpicker').selectpicker();
        });

        $("#customSwitch1").change(function() {
            if(this.checked) {
                $('#label_visibility').html('Published');
            }
            else{
                $('#label_visibility').html('Private');
            }
        });

    </script>
@endsection
