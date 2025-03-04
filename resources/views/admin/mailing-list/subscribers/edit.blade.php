@extends('admin.layouts.app')

@section('pagecss')
@endsection

@section('content')
    <div class="container pd-x-0">
        <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                        <li class="breadcrumb-item" aria-current="page"><a href="{{route('dashboard')}}">CMS</a></li>
                        <li class="breadcrumb-item" aria-current="page"><a href="{{route('mailing-list.subscribers.index')}}">Manage Subscribers</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Subscriber</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0 tx-spacing--1">Edit Subscriber</h4>
            </div>
        </div>

        <form class="row row-sm" method="POST" action="{{ route('mailing-list.subscribers.update', $subscriber->id) }}">
            @csrf
            @method('put')
            <div class="col-lg-6">
                <div class="form-group">
                    <label class="d-block">First Name</label>
                    <input type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name', $subscriber->first_name) }}">
                    @hasError(['inputName' => 'first_name'])
                    @endhasError
                </div>
                <div class="form-group">
                    <label class="d-block">Last Name</label>
                    <input type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name', $subscriber->last_name) }}">
                    @hasError(['inputName' => 'last_name'])
                    @endhasError
                </div>
                <div class="form-group">
                    <label class="d-block">E-mail Address *</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $subscriber->email) }}" required>
                    @hasError(['inputName' => 'email'])
                    @endhasError
                </div>
                <div class="form-group">
                    <label class="d-block">Status</label>
                    <div class="custom-control custom-switch @error('is_active') is-invalid @enderror">
                        <input type="checkbox" class="custom-control-input" name="is_active" {{ (old("is_active", $subscriber->is_active) ? "checked":"") }} id="status">
                        <label class="custom-control-label" id="label_is_active" for="status">@if (old("is_active", $subscriber->is_active)) Active @else Inactive @endif</label>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 mg-t-10">
                <button class="btn btn-primary btn-sm btn-uppercase" type="submit">Update Subscriber</button>
                <a href="{{ route('mailing-list.subscribers.index') }}" class="btn btn-outline-secondary btn-sm btn-uppercase">Cancel</a>
            </div>
        </form>

    </div>
@endsection

@section('pagejs')
@endsection

@section('customjs')
    <script>
        $("#status").change(function() {
            if(this.checked) {
                $('#label_is_active').html('Active');
            }
            else{
                $('#label_is_active').html('Inactive');
            }
        });
    </script>
@endsection
