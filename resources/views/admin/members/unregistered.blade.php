@extends('admin.layouts.app')

@section('pagetitle')
    Unregistered Members
@endsection

@section('pagecss')
    <link href="{{ asset('lib/ion-rangeslider/css/ion.rangeSlider.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/select2/css/select2.min.css') }}" rel="stylesheet">
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
                        <li class="breadcrumb-item active" aria-current="page">Members</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0 tx-spacing--1">Unregistered Members</h4>
            </div>
        </div>

        <div class="row row-sm">
            <!-- Start Pages -->
            <div class="col-md-12">
                <div class="table-list mg-b-10">
                    <div class="table-responsive-lg">
                        <table class="table mg-b-0 table-light table-hover">
                            <thead>
                            <tr>
                                <th style="width: 25%;">Full Name</th>
                                <th style="width: 25%;">Sponsor</th>
                                <th style="width: 25%;">Join Date</th>
                                <th style="width: 25%;">Member Code</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($members as $member)
                                <tr>
                                    <td>{!! $member->name !!}</td>
                                    <td>@if($member->sponsor) {{ $member->sponsor->name }} @else <span class="badge badge-light">Abandoned</span> @endif</td>
                                    <td>{{ Setting::date_for_listing($member->created_at) }}</td>
                                    <td>
                                        <form action="{{route('admin.members.update_code')}}" method="post">
                                            @csrf
                                            <input type="hidden" name="_method" value="POST">
                                            <input type="hidden" name="member_id" value="{{$member->id}}">
                                            <input type="text" name="member_code">
                                            <input type="submit" class="btn btn-xs btn-success">
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" style="text-align: center;"> <p class="text-danger">No unregistered members found.</p></td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- End Pages -->
            
        </div>
    </div>

@endsection

@section('pagejs')
    <script src="{{ asset('lib/bselect/dist/js/bootstrap-select.js') }}"></script>
    <script src="{{ asset('lib/bselect/dist/js/i18n/defaults-en_US.js') }}"></script>
    <script src="{{ asset('lib/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>
    <script src="{{ asset('lib/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('js/listing.js') }}"></script>
@endsection

@section('customjs')
   
@endsection
