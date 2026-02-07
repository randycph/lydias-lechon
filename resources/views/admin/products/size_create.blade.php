@extends('admin.layouts.app')

@section('pagetitle')
    Create Product Size
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
                    <li class="breadcrumb-item active" aria-current="page">Create a Product Size</li>
                </ol>
            </nav>
            <h4 class="mg-b-0 tx-spacing--1">Create a Product Size</h4>
        </div>
    </div>

{{--    @if($message = Session::get('duplicate'))--}}
{{--        <div class="alert alert-warning d-flex align-items-center mg-t-15" role="alert">--}}
{{--            <p class="mg-b-0"><i data-feather="alert-circle" class="mg-r-10"></i>{{ $message }}--}}
{{--        </div>--}}
{{--    @endif--}}

    <div class="row">
        <div class="col-md-6">
            <form action="{{ route('sizes.store') }}" method="post">
                    @csrf
                    @method('POST')
                    <div class="form-group">
                        <label class="d-block">Product *</label>
                        <select name="product_id" id="product_id" class="form-control selectpicker @error('product_id') is-invalid @enderror" data-live-search="true">
                            <option value="">Select Product</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}" {{ (old("product_id") == $product->id ? "selected":"") }}>{{ $product->name }}</option>
                            @endforeach
                        </select>
                        <x-error-message inputName="product_id" />
                    </div>
                    <div class="form-group">
                        <label class="d-block">Name *</label>
                        <input type="text" name="name" id="name" value="{{ old('name')}}" class="form-control @error('name') is-invalid @enderror">
                        <x-error-message inputName="name" />
                    </div>

                    <div class="form-group">
                        <label class="d-block">Description</label>
                        <textarea rows="3" class="form-control @error('description') is-invalid @enderror" name="description">{{ old('description') }}</textarea>
                        <x-error-message inputName="description" />
                    </div>

                    <div class="form-group">
                        <label class="d-block">Page Visibility</label>
                        <div class="custom-control custom-switch @error('visibility') is-invalid @enderror">
                            <input type="checkbox" class="custom-control-input" name="visibility" {{ (old("visibility") ? "checked":"") }} id="customSwitch1">
                            <label class="custom-control-label" id="label_visibility" for="customSwitch1">Private</label>
                        </div>
                    </div>

                    <button class="btn btn-primary btn-sm btn-uppercase" type="submit">Create Size</button>
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
    <script>
        document.getElementById('image').addEventListener('change', function(event) {
            const file = event.target.files[0];
        
            if (file) {
                const reader = new FileReader();
        
                reader.onload = function(e) {
                    document.getElementById('img_temp').src = e.target.result;
                    document.getElementById('image_div').style.display = 'block';
                    document.getElementById('img_name').innerText = file.name;
                };
        
                reader.readAsDataURL(file);
            }
        });
        
        function remove_image() {
            document.getElementById('image').value = "";
            document.getElementById('img_temp').src = "";
            document.getElementById('image_div').style.display = 'none';
            document.getElementById('img_name').innerText = "Choose file";
        }
    </script>
@endsection
