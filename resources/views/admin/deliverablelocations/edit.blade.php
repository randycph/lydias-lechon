@extends('admin.layouts.app')

@section('content')
<div class="container pd-x-0">
    <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                    <li class="breadcrumb-item" aria-current="page"><a href="{{route('dashboard')}}">CMS</a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="{{route('admin.locations.index')}}">Delivery Rate</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Delivery Rate</li>
                </ol>
            </nav>
            <h4 class="mg-b-0 tx-spacing--1">Update Delivery Rate</h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <form id="delivery_form" autocomplete="off" action="{{ route('admin.locations.update',$rate->id) }}" method="post">
                    @csrf
                    @method('PUT')

                    <div class="form-group" id="region_div">
                        <label class="d-block">Location *</label>
                        <input type="text" class="form-control" name="name" value="{{$rate->name}}">                                       
                    </div>
                    <div class="form-group" id="region_div">
                        <label class="d-block">Area *</label>
                        <input type="text" class="form-control" name="area" placeholder="North or South" value="{{$rate->area}}">                                       
                    </div>
                    <div class="form-group" id="region_div">
                        <label class="d-block">Rate *</label>
                        <input type="number" class="form-control" name="rate" min="0" step="0.01" value="{{$rate->rate}}">                                       
                    </div>    
                    <div class="form-group" id="region_div">
                        <label class="d-block">Item Type *</label>
                        <select name="item_type" id="item_type" class="form-control" required="required">
                            <option value=""> - Select - </option>
                            <option value="misc" @if($rate->item_type == 'misc') selected="selected" @endif>Miscellaneous</option>
                            <option value="lechon" @if($rate->item_type == 'lechon') selected="selected" @endif>Lechon</option>
                        </select>                                   
                    </div>    
                    <div class="form-group">
                        <label class="d-block">Outside or Within Manila</label>
                        <div class="custom-control custom-switch @error('status') is-invalid @enderror">
                            <input type="checkbox" class="custom-control-input" name="outside_manila" id="customSwitch1"  {{ ($rate->outside_manila == "0" ? "":"checked") }}>
                            <label class="custom-control-label" id="label_visibility" for="customSwitch1">{{ ($rate->outside_manila == "0" ? "Within":"Outside") }} Manila</label>                           
                        </div>                             
                    </div>               
                    <button class="btn btn-primary btn-sm btn-uppercase" type="submit">Submit</button>
                    <a class="btn btn-outline-secondary btn-sm btn-uppercase" href="{{ route('admin.locations.index') }}">Cancel</a>
            </form>
            </div>
        </div>
    </div>
</div>
<div id="aaa"></div>
@endsection

@section('pagejs')
  <script>
      $("#customSwitch1").change(function() {
        if(this.checked) {
            $('#label_visibility').html('Outside Manila');
        }
        else{
            $('#label_visibility').html('Within Manila');
        }
    });
  </script>
@endsection

