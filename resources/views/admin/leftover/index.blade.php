@extends('admin.layouts.app')

@section('pagetitle')
    Daily Leftovers
@endsection

@section('pagecss')
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
                        <li class="breadcrumb-item active" aria-current="page">Daily Leftovers</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0 tx-spacing--1">Daily Leftovers</h4>
            </div>
        </div>

        <div class="row row-sm">

            <!-- Start Filters -->
            <div class="col-md-12">
                <div class="filter-buttons">
                    <div class="d-md-flex bd-highlight">                        
                        
                        <div class="ml-auto bd-highlight mg-t-12 mg-r-12">
                            <form class="form-inline" id="searchForm">
                                <div class="mg-r-12">
                                    <select name="branch" id="branch" class="form-control">
                                        <option value="">Select Branch</option>
                                        @forelse($branches as $b)
                                            @php 
                                                $name = \App\EcommerceModel\Branch::whereId($b->branch_id)->first();
                                            @endphp
                                            <option value="{{$b->branch_id}}">{{$name->name}}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                    <input type="date" class="form-control" name="date">
                                    <button class="btn filter" type="submit" id="btnSearch"><i data-feather="search"></i></button>
                                </div>
                            </form>
                        </div>
                        <div class="">
                            @if (auth()->user()->has_access_to_route('leftover.create'))
                            <a class="btn btn-primary btn-sm mg-b-20" href="{{ route('leftover.create') }}">Add Daily Leftover</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- End Filters -->

            <!-- Start Pages -->
            <div class="col-md-12">
                <div class="table-list mg-b-10">
                    <div class="table-responsive-lg">
                        <table class="table mg-b-0 table-light table-hover">
                            <thead>
                            <tr>
                              
                                <th>Branch</th>
                                <th>Date</th>                               
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($dates as $date)
                                <tr>
                                    
                                    <td> {{$date->branch->name }}</td>
                                    <td>{{ $date->date }}</td>                                    
                                    <td>
                                        <nav class="nav table-options">  
                                            @if (auth()->user()->has_access_to_route('leftover.update_all') || auth()->user()->has_access_to_route('leftover.edit_all'))
                                            <a class="nav-link" href="{{ route('leftover.edit_all',['date' => $date->date, 'branch' => $date->branch_id]) }}" title="Edit This date"><i data-feather="edit"></i></a>
                                            @endif
                                            @if (auth()->user()->has_access_to_route('leftover.index') || auth()->user()->has_access_to_route('leftover.show') || auth()->user()->has_access_to_route('leftover.print'))
                                            <a class="nav-link" target="_blank" href="{{ route('leftover.print',['date' => $date->date, 'branch' => $date->branch_id]) }}" title="Print"><i data-feather="file-text"></i></a>
                                            @endif  
                                            @if (auth()->user()->has_access_to_route('leftover.delete'))
                                                <a href="#modalDeleteLeftover" class="nav-link delete_permission" data-pid="{{ $date->id }}" data-toggle="modal"><i data-feather="trash"></i></a>
                                            @endif 
                                        </nav>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <th colspan="6" style="text-align: center;"> <p class="text-danger">No Record found.</p></th>
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

    <form action="" id="posting_form" style="display:none;" method="post">
        @csrf
        <input type="text" id="pages" name="pages">
        <input type="text" id="status" name="status">
    </form>


    

    <div class="modal effect-scale" id="modalDeleteLeftover" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">{{__('standard.account_management.permissions.delete_confirmation_title')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('leftover.delete')}}" method="post">
                    @csrf
                    <input type="hidden" id="pid" name="id">
                    <div class="modal-body">
                        <p>You are about to delete this leftover record. Do you want to continue?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-sm btn-danger">Yes, Delete</button>
                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

   

@endsection

@section('pagejs')
    <script src="{{ asset('lib/bselect/dist/js/bootstrap-select.js') }}"></script>
    <script src="{{ asset('lib/bselect/dist/js/i18n/defaults-en_US.js') }}"></script>
    <script src="{{ asset('lib/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>


@endsection

@section('customjs')
    <script>
        $(document).on('click','.delete_permission', function(){
            $('#modalDeleteLeftover').show();

            $('#pid').val($(this).data('pid'));
        });
    </script>
@endsection
