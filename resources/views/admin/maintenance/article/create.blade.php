@extends('admin.layouts.app')

@section('pagetitle')
    Category Management
@endsection

@section('pagecss')
    <style>
        #errorMessage {
            list-style-type: none;
            padding: 0;
            margin-bottom: 0px;
        }
    </style>
@endsection

@section('content')
<div class="container pd-x-0">
    <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                    <li class="breadcrumb-item" aria-current="page"><a href="{{route('dashboard')}}">CMS</a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="{{route('news-categories.index')}}">News Category</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Create News Category</li>
                </ol>
            </nav>
            <h4 class="mg-b-0 tx-spacing--1">Create News Category</h4>
        </div>
    </div>

    <div class="row row-sm">
        <div class="col-lg-12">
                <form autocomplete="off" action="{{ route('news-categories.store') }}" method="post">
                    @method('POST')
                    @csrf
                    <div class="row row-sm">
                        <div class="col-sm-6">
                            <div class="form-group mg-b-20">
                                <label class="mg-b-5 tx-color-03">Category Name <i class="tx-danger">*</i></label>
                                <input required type="text" class="form-control @error('category_name') is-invalid @enderror" name="category_name" id="category_title" @htmlValidationMessage({{__('standard.empty_all_field')}})>
                                <x-error-message inputName="category_name" />
                                <small id="category_slug"></small>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary btn-uppercase">Save Category</button>
                    <a href="{{ route('news-categories.index') }}" class="btn btn-outline-secondary btn-sm btn-uppercase">Cancel</a>
                </form>
        </div>
    </div>
</div>
@endsection

@section('customjs')
    <script>
       /** Generation of the page slug **/
       $(function() {
           $('#category_title').change(function(){
               let url = $('#category_title').val();
               $.ajax({
                   type: "POST",
                   url: "{{ route('news.categories.get-slug') }}",
                   data: { url: url, _token: "{{ csrf_token() }}" }
               })
               .done(function(response) {
                   slug_url = '{{env('APP_URL')}}/news/'+response;
                   $('#category_slug').html("<a target='_blank' href='"+slug_url+"'>"+slug_url+"</a>");
               });
           });
       });

    </script>
@endsection
