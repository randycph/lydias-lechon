@extends('admin.layouts.app')

@section('content')
<div class="container pd-x-0">
    <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                    <li class="breadcrumb-item" aria-current="page"><a href="{{route('dashboard')}}">CMS</a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="{{route('deliveryrate.index')}}">Delivery Rate</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Create new rate</li>
                </ol>
            </nav>
            <h4 class="mg-b-0 tx-spacing--1">Create new rate</h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <form id="delivery_form" autocomplete="off" action="{{ route('deliveryrate.store') }}" method="post">
                    @csrf
                    @method('POST')
                    <div class="form-group" id="region_div">
                        <label class="d-block">Region *</label>
                        <select name="region_select" id="region" required="required" class="form-control">
                            <option value="" selected="selected"> - Select Region -</option>                           
                        </select>                                         
                    </div>

                    <div class="form-group" id="province_div" style="display:none;">
                        <label class="d-block">Province *</label>
                        <select name="province_select" id="province" required="required" class="form-control">
                            <option value="" selected="selected"> - Select Province -</option>                           
                        </select>                                         
                    </div>

                    <div class="form-group" id="municipality_div" style="display:none;">
                        <label class="d-block">Municipality/City *</label>
                        <select name="municipality_select" id="municipality" required="required" class="form-control">
                            <option value="" selected="selected"> - Select Municipality/City -</option>                           
                        </select>                                         
                    </div>

                    <div class="form-group" id="brgy_div" style="display:none;">
                        <label class="d-block">Brgy *</label>
                        <select name="brgy" id="brgy" required="required" class="form-control">
                            <option value="" selected="selected"> - Select Brgy -</option>                           
                        </select>                                         
                    </div>
                    
                    <div id="others_div" style="display:none;">
                        <div class="form-group" style="display:none;">
                            <label class="d-block">Zip Code *</label>
                            <input type="text" class="form-control" required="required" value="0000" name="zip" id="zip">
                        </div>
                        <div class="form-group">
                            <label class="d-block">Delivery SLA *</label>
                            <input type="text" class="form-control" required="required" name="sla" id="sla">
                        </div>
                        <div class="form-group">
                            <label class="d-block">Delivery Rate *</label>
                            <input type="number" class="form-control text-right" step="0.01" required="required" name="rate" id="rate">
                        </div>
                        <div class="form-group">
                            <label class="d-block">Excess 3 kls Rate *</label>
                            <input type="number" class="form-control text-right" step="0.01" required="required" name="excess_fee" id="excess_fee">
                        </div>
                    </div>

                    <input type="hidden" name="region" id="region_hidden">
                    <input type="hidden" name="province" id="province_hidden">
                    <input type="hidden" name="municipality" id="municipality_hidden">
                    
                    <button class="btn btn-primary btn-sm btn-uppercase" type="submit">Submit</button>
                    <a class="btn btn-outline-secondary btn-sm btn-uppercase" href="{{ route('deliveryrate.index') }}">Cancel</a>
            </form>
            </div>
        </div>
    </div>
</div>
<div id="aaa"></div>
@endsection

@section('pagejs')
   <script>
        $(document).ready(function(){            
            $.ajax({ 
                type: 'GET', 
                url: '{{asset('data/philippines/json/refregion.json')}}', 
                data: { get_param: 'value' }, 
                dataType: 'json',
                success: function (data) { 
                    
                    $.each(data, function(index, element) {

                        $.each(element, function(key, val) {
                            if(val.regCode == '04' || val.regCode == '13')
                            $('#region').append(`<option value="${val.regCode}">${val.regDesc}</option>`);
                            
                        });
                    });
                }
            });        
            
        });

        $('#region').change(function(){

            $('#province_div').find('option').not(':first').remove();
            $('#municipality_div').find('option').not(':first').remove();
            $('#brgy_div').find('option').not(':first').remove();

            $('#province_div').prop('selectedIndex',0);
            $('#municipality_div').prop('selectedIndex',0);
            $('#brgy_div').prop('selectedIndex',0);

            $.ajax({ 
                type: 'GET', 
                url: '{{asset('data/philippines/json/refprovince.json')}}', 
                data: { get_param: 'value' }, 
                dataType: 'json',
                success: function (data) { 
                    
                    $.each(data, function(index, element) {

                        $.each(element, function(key, val) {
                            if(val.regCode == $('#region').val())
                                $('#province').append(`<option value="${val.provCode}">${val.provDesc}</option>`);
                            
                        });
                    });
                }
            });
            $('#province_div').show();
        });

        $('#province').change(function(){

            $('#municipality_div').find('option').not(':first').remove();
            $('#brgy_div').find('option').not(':first').remove();

            $('#municipality_div').prop('selectedIndex',0);
            $('#brgy_div').prop('selectedIndex',0);
            $.ajax({ 
                type: 'GET', 
                url: '{{asset('data/philippines/json/refcitymun.json')}}', 
                data: { get_param: 'value' }, 
                dataType: 'json',
                success: function (data) { 
                    
                    $.each(data, function(index, element) {

                        $.each(element, function(key, val) {
                            if(val.provCode == $('#province').val())
                                $('#municipality').append(`<option value="${val.citymunCode}">${val.citymunDesc}</option>`);
                            
                        });
                    });
                }
            });
            $('#municipality_div').show();
        });

        $('#municipality').change(function(){

            $('#brgy_div').find('option').not(':first').remove();

            $('#brgy_div').prop('selectedIndex',0);
            $.ajax({ 
                type: 'GET', 
                url: '{{asset('data/philippines/json/refbrgy.json')}}', 
                data: { get_param: 'value' }, 
                dataType: 'json',
                success: function (data) { 
                    
                    $.each(data, function(index, element) {

                        $.each(element, function(key, val) {
                            if(val.citymunCode == $('#municipality').val())
                                $('#brgy').append(`<option value="${val.brgyDesc}">${val.brgyDesc}</option>`);
                            
                        });
                    });
                }
            });
            $('#brgy_div').show();
        });

        $('#brgy').change(function(){            
            $('#others_div').show();
        });
        
        $("#delivery_form").submit(function(){
            //e.preventDefault();
            $('#region_hidden').val($("#region option:selected").text());
            $('#province_hidden').val($("#province option:selected").text());
            $('#municipality_hidden').val($("#municipality option:selected").text());
            return true;
            //$("#delivery_form").submit();
        });
   </script>
@endsection

