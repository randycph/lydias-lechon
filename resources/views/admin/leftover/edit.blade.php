@extends('admin.layouts.app')

@section('pagetitle')
    Update Leftover Record
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
    <form autocomplete="off" action="{{ route('leftover.update_all') }}" method="post">
    @method('POST')
    @csrf

    <div class="container pd-x-0">
        <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-style1 mg-b-5">
                        <li class="breadcrumb-item" aria-current="page"><a href="{{route('dashboard')}}">CMS</a></li>
                        <li class="breadcrumb-item" aria-current="page"><a href="{{route('leftover.index')}}">Daily Leftover</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Update Leftover Record</li>
                    </ol>
                </nav>
                
                <h4 class="mg-b-0 tx-spacing--1">Update Leftover Record</h4>
            </div>
        </div>

        <div class="row row-sm">
            <div class="col-lg-4 offset-lg-4">
                <div class="form-group">
                    <label class="tx-color-03">Branch <i class="tx-danger">*</i></label>
                    <select class="form-control" required="required" name="branch" id="branch">
                        <option selected="selected" value="{{$branched->id}}">{{$branched->name}}</option>                        
                    </select>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label class="tx-color-03">Date <i class="tx-danger">*</i></label>
                    <input type="date" class="form-control" name="date" id="date" value="{{$date}}" min="{{date('Y-m-d',strtotime('-2 days'))}}" max="{{date('Y-m-d')}}">
                </div>                
            </div>
        </div>
        <div class="row row-sm">
            <h5>Products</h5>
            <div class="col-lg-12">
                    
                    <table class="table" id="producttable">
                        <thead>
                            <tr>
                                <th>&nbsp;</th>
                                <th>Product</th>
                                <th>Qty</th>
                                <th>UoM</th>
                                <th>Remark</th>                                
                            </tr>
                        </thead>
                        <tbody>
                            @php $y=0; @endphp
                            @forelse($los as $lo)
                                @php $y++; @endphp
                                <tr id="tr{{$y}}">
                                    <td width="5%"><a href="javascript:void(0)" class="btn btn-xs btn-danger" onclick="remove_product({{$y}})">X</a></td>
                                    <td width="40%">
                                        <select class="form-control jt{{$y}}" name="prod{{$y}}" id="prod{{$y}}">
                                            <option selected="selected" value="{{$lo->product_id}}">{{$lo->product->name}}</option>
                                            @forelse($products as $p)
                                                @if($p->id <> 42)
                                                <option value="{{$p->id}}">{{$p->name}}</option>
                                                @endif
                                            @empty
                                            @endforelse
                                        </select>
                                    </td>                                    
                                    <td width="10%">
                                        <input class="form-control jt{{$y}}" type="number" step="0.01" name="qty{{$y}}" id="qty{{$y}}" value="{{$lo->qty}}">
                                    </td>
                                    <td width="10%">
                                        <input class="form-control jt{{$y}}" type="text" name="uom{{$y}}" id="uom{{$y}}" value="{{$lo->uom}}">
                                    </td>
                                    <td width="25%">
                                        <textarea class="form-control jt{{$y}}" name="remark{{$y}}" id="remark{{$y}}" cols="30" rows="1">{!!$lo->remarks!!}</textarea>
                                    </td>
                                </tr>
                            @empty
                            @endforelse
                            @for($x=($y + 1);$x<=100;$x++)
                                <tr id="tr{{$x}}" style="display:none;">
                                    <td width="5%"><a href="javascript:void(0)" class="btn btn-xs btn-danger" onclick="remove_product({{$x}})">X</a></td>
                                    <td width="40%">
                                        <select class="form-control jt{{$x}}" name="prod{{$x}}" id="prod{{$x}}">
                                            <option selected="selected" value="">Select Product</option>
                                            @forelse($products as $p)
                                                <option value="{{$p->id}}">{{$p->name}}</option>
                                            @empty
                                            @endforelse
                                        </select>
                                    </td>                                    
                                    <td width="10%">
                                        <input class="form-control jt{{$x}}" type="number" step="0.01" name="qty{{$x}}" id="qty{{$x}}" value="0.00">
                                    </td>
                                    <td width="10%">
                                        <input class="form-control jt{{$x}}" type="text" name="uom{{$x}}" id="uom{{$x}}" value="pc">
                                    </td>
                                    <td width="25%">
                                        <textarea class="form-control jt{{$x}}" name="remark{{$x}}" id="remark{{$x}}" cols="30" rows="1"></textarea>
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>

                    <table width="100%">
                        <tr>
                            <input type="hidden" id="cntr" value="{{($y+1)}}">
                            <td align="left"><a href="javascript:void(0)" onclick="add_product();" class="btn btn-sm btn-primary btn-uppercase">Add More Product</a></td>
                            <td align="right"><button type="submit" class="btn btn-success btn-sm btn-uppercase">Submit</a></td>
                        </tr>
                    </table>
                    
                    
                
            </div>
        </div>
        
    </div>
    </form>
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
    function add_product(){
     
        var cntr = parseInt($('#cntr').val())+1;
        $('#tr'+cntr).show();
        $('#cntr').val(cntr);

    }

    function remove_product(x){
        $('.jt'+x).val('');
        $('#tr'+x).hide();
    }
</script>

@endsection
