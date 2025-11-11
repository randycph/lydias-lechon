@extends('admin.layouts.app')

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
                    <li class="breadcrumb-item" aria-current="page"><a href="{{route('admin.locations.index')}}">Deliverable Locations</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Create new rate</li>
                </ol>
            </nav>
            <h4 class="mg-b-0 tx-spacing--1">Create new rate</h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <form id="delivery_form" autocomplete="off" action="{{ route('admin.locations.store') }}" method="post">
                    @csrf
                    @method('POST')
                    <div class="form-group">
                        <label class="d-block">Region *</label>
                        <select class="form-control" id="region_select" name="region" style="width:100%">
                            <option value="">Select Region</option>
                        </select>
                        @if ($errors->has('region'))
                        <span class="text-danger">{{ $errors->first('region') }}</span>
                        @endif
                    </div>

                    <div class="form-group">
                        <label class="d-block">Province *</label>
                        <select class="form-control" id="province_select" name="province" style="width:100%" disabled>
                            <option value="">Select Province</option>
                        </select>
                        @if ($errors->has('province'))
                        <span class="text-danger">{{ $errors->first('province') }}</span>
                        @endif
                    </div>

                    <div class="form-group">
                        <label class="d-block">City/Municipality *</label>
                        <select class="form-control" id="city_select" name="city" style="width:100%" disabled>
                            <option value="">Select City/Municipality</option>
                        </select>
                        @if ($errors->has('city'))
                        <span class="text-danger">{{ $errors->first('city') }}</span>
                        @endif
                    </div>
                    <div class="form-group" id="region_div">
                        <label class="d-block">Rate *</label>
                        <input type="number" class="form-control" name="rate" min="1" step="0.01" value="{{old('rate',0.00)}}">     
                        @if ($errors->has('rate'))
                            <span class="text-danger">{{ $errors->first('rate') }}</span>
                        @endif                                  
                    </div> 
                    <div class="form-group" id="region_div">
                        <label class="d-block">Item Type *</label>
                        <select name="item_type" id="item_type" class="form-control" required="required">

                            <option value=""> - Select - </option>
                            <option value="misc">Miscellaneous</option>
                            <option value="lechon">Lechon</option>
                        </select>                             
                        @if ($errors->has('item_type'))
                            <span class="text-danger">{{ $errors->first('item_type') }}</span>
                        @endif      
                    </div>   
                    <div class="form-group">
                        <label class="d-block">Outside or Within Manila</label>
                        <div class="custom-control custom-switch @error('status') is-invalid @enderror">
                            <input type="checkbox" class="custom-control-input" name="outside_manila" id="customSwitch1">
                            <label class="custom-control-label" id="label_visibility" for="customSwitch1">Within Manila</label>                           
                        </div>                             
                    </div>     
                    
                    {{-- --- ACTIVE / MANUAL OVERRIDE / SCHEDULE --- --}}
                    <div class="form-group">
                        <label class="d-block">Active</label>
                        <div class="custom-control custom-switch">
                            <input type="checkbox"
                                class="custom-control-input"
                                id="is_active"
                                name="is_active"
                                {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="is_active">
                                <span id="is_active_label">{{ old('is_active', true) ? 'ON' : 'OFF' }}</span>
                            </label>
                        </div>
                        @error('is_active') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>


                    <div class="form-group">
                        <label class="d-block">Control Mode (Scheduled)</label>
                        <select name="control_mode" id="control_mode" class="form-control">
                            <option value="">Select Schedule type</option>
                            <option value="auto_on">Auto On</option>
                            <option value="auto_off">Auto Off</option>
                        </select>
                        <small class="form-text text-muted">
                            Choose a schedule type to set automatic ON/OFF at specific times.
                        </small>
                        @error('control_mode') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    {{-- --- ONE-SHOT SCHEDULED FLIPS --- --}}
                    <div  class="">
                        <div class="form-group" id="scheduled_on_block">
                            <label>Auto ON At</label>
                            <input type="datetime-local"
                                class="form-control"
                                name="auto_on_at"
                                value="{{ old('auto_on_at') }}">
                            @error('auto_on_at') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group" id="scheduled_off_block">
                            <label>Auto OFF At</label>
                            <input type="datetime-local"
                                class="form-control"
                                name="auto_off_at"
                                value="{{ old('auto_off_at') }}">
                            @error('auto_off_at') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    {{-- (read-only helper) --}}
                    @if(old('last_changed_at'))
                        <div class="form-group">
                            <label class="d-block">Last Changed</label>
                            <input type="text" class="form-control" value="{{ old('last_changed_at') }}" readonly>
                        </div>
                    @endif

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
<script src="{{ asset('lib/select2/js/select2.min.js') }}"></script>
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const control = document.getElementById('control_mode');
        const controlModeLabel = document.getElementById('control_mode_label');
        const manual  = document.getElementById('manual_block');
        const sched   = document.getElementById('scheduled_block');
        const isActive = document.getElementById('is_active');
        const isActiveLabel = document.getElementById('is_active_label');
        const outside = document.getElementById('customSwitch1');
        const outsideLabel = document.getElementById('label_visibility');
        const auto_on_block  = document.getElementById('scheduled_on_block');
        const auto_off_block = document.getElementById('scheduled_off_block');

        function syncBlocks() {
            if (control.value === 'auto_on') {
                auto_on_block.classList.remove('d-none');
                auto_off_block.classList.add('d-none');
            } else if (control.value === 'auto_off') {
                auto_on_block.classList.add('d-none');
                auto_off_block.classList.remove('d-none');
            } else {
                auto_on_block.classList.add('d-none');
                auto_off_block.classList.add('d-none');
            }
        }

        control.addEventListener('change', syncBlocks);
        syncBlocks();

        function syncLabels() {
            isActiveLabel.textContent = isActive.checked ? 'ON' : 'OFF';
            if (outside && outsideLabel) {
                outsideLabel.textContent = outside.checked ? 'Outside Manila' : 'Within Manila';
            }
        }
        isActive.addEventListener('change', syncLabels);
        if (outside) outside.addEventListener('change', syncLabels);
        syncLabels();
    });
</script>

<script>
    $(function () {
    const urls = [
        '{{ asset("addresses/philippine_provinces_cities_municipalities_and_barangays_2019v2.json") }}'
    ];

    const DEFAULT_REGION_NAME = 'NCR';

    const initial = {
        region:        @json(old('region',        '')),
        region_code:   @json(old('region_code',   '')), 
        province:      @json(old('province',      '')),
        city:          @json(old('city',          '')),
        barangay:      @json(old('barangay',      '')),
    };

    const $region   = $('#region_select');
    const $province = $('#province_select');
    const $city     = $('#city_select');
    const $barangay = $('#barangay_select');

    // Init Select2 shells
    initSelect2($region,   'Select Region');
    initSelect2($province, 'Select Province');
    initSelect2($city,     'Select City/Municipality');
    initSelect2($barangay, 'Select Barangay');

    let DATA = null;

    loadJSONWithFallbacks(urls).then(json => {
        DATA = json || {};
        populateRegions();

        // --- Resolve which region to use ---
        let useRegionCode = null;

        // 1) If old region_code exists AND is in DATA, use it
        if (initial.region_code && DATA[initial.region_code]) {
            useRegionCode = initial.region_code;
        }
        // 2) Else if old region (name) exists, map to region_code
        else if (initial.region) {
            useRegionCode = findRegionCodeByName(initial.region) || null;
        }
        // 3) Else if old city exists, derive region+province from city
        else if (initial.city) {
            const found = findByCity(initial.city);
            if (found) {
                useRegionCode   = found.regionCode;
                initial.province = found.provinceName; // ensure province aligns with city
            }
        }
        // 4) Else if old province exists, derive region from province
        else if (initial.province) {
            useRegionCode = findRegionByProvince(initial.province) || null;
        }
        // 5) Else default to NCR
        if (!useRegionCode) {
            useRegionCode = findRegionCodeByName(DEFAULT_REGION_NAME) || Object.keys(DATA)[0] || '';
        }

        // Preselect region
        if (useRegionCode) {
            $region.val(useRegionCode).trigger('change.select2');
            onRegionChange(false); // populate provinces without clearing
        }

        // Preselect province (if available)
        if (initial.province) {
            setSelectByText($province, initial.province);
            onProvinceChange(false); // populate cities without clearing
        }

        // Preselect city (if available)
        if (initial.city) {
            setSelectByText($city, initial.city);
            onCityChange(false); // populate barangays without clearing
        }

        // Preselect barangay (if available)
        if (initial.barangay) {
            setSelectByText($barangay, initial.barangay);
        }

    }).catch(err => {
        console.error('Failed to load LGU JSON:', err);
        alert('Location list failed to load. Ensure /public/addresses/2019v2.json is present.');
    });

    // Events
    $region.on('change', () => onRegionChange(true));
    $province.on('change', () => onProvinceChange(true));
    $city.on('change', () => onCityChange(true));

    // ---------- Select2 helpers ----------
    function initSelect2($el, placeholder){
        $el.select2({ placeholder, allowClear: true, width: '100%' });
    }
    async function loadJSONWithFallbacks(list){
        let lastErr;
        for (const u of list){
            try { return await $.getJSON(u, { cache: true }); }
            catch (e){ lastErr = e; console.warn('JSON load failed for', u, e); }
        }
        throw lastErr || new Error('All sources failed');
    }

    // ---------- Populate ----------
    function populateRegions(){
        const regions = Object.keys(DATA).map(code => ({
            code, name: String(DATA[code]?.region_name || '')
        })).sort((a,b)=> a.name.localeCompare(b.name));

        $region.empty().append(new Option('', '', false, false));
        regions.forEach(r => $region.append(new Option(r.name, r.code, false, false)));
        $region.prop('disabled', regions.length === 0).trigger('change.select2');
    }
    function populateProvinces(regionCode){
        const provObj = DATA?.[regionCode]?.province_list || {};
        const provinces = Object.keys(provObj).sort((a,b)=> a.localeCompare(b));
        $province.empty().append(new Option('', '', false, false));
        provinces.forEach(p => $province.append(new Option(p, p, false, false)));
        $province.prop('disabled', provinces.length === 0).trigger('change.select2');
    }
    function populateCities(regionCode, provinceName){
        const muniObj = DATA?.[regionCode]?.province_list?.[provinceName]?.municipality_list || {};
        const cities = Object.keys(muniObj).sort((a,b)=> a.localeCompare(b));
        $city.empty().append(new Option('', '', false, false));
        cities.forEach(c => $city.append(new Option(c, c, false, false)));
        $city.prop('disabled', cities.length === 0).trigger('change.select2');
    }
    function populateBarangays(regionCode, provinceName, cityName){
        const brgys = (DATA?.[regionCode]?.province_list?.[provinceName]?.municipality_list?.[cityName]?.barangay_list || [])
        .slice().sort((a,b)=> a.localeCompare(b));
        $barangay.empty().append(new Option('', '', false, false));
        brgys.forEach(b => $barangay.append(new Option(b, b, false, false)));
        $barangay.prop('disabled', brgys.length === 0).trigger('change.select2');
    }

    // ---------- Cascades ----------
    function onRegionChange(clearDownstream){
        const regionCode = $region.val() || null;
        if (!regionCode){ return disableBelowRegion(); }
        populateProvinces(regionCode);
        if (clearDownstream){
            $province.val(null).trigger('change.select2');
            disableBelowProvince();
        }
    }
    function onProvinceChange(clearDownstream){
        const regionCode = $region.val();
        const provinceName = $province.val();
        if (!regionCode || !provinceName){ return disableBelowProvince(); }
        populateCities(regionCode, provinceName);
        if (clearDownstream){
            $city.val(null).trigger('change.select2');
            disableBelowCity();
        }
    }
    function onCityChange(clearDownstream){
        const regionCode = $region.val();
        const provinceName = $province.val();
        const cityName = $city.val();
        if (!regionCode || !provinceName || !cityName){ return disableBelowCity(); }
        populateBarangays(regionCode, provinceName, cityName);
        if (clearDownstream){
            $barangay.val(null).trigger('change.select2');
        }
    }

    // ---------- Resets ----------
    function disableBelowRegion(){
        $province.empty().append(new Option('', '', false, false)).prop('disabled', true).trigger('change.select2');
        disableBelowProvince();
    }
    function disableBelowProvince(){
        $city.empty().append(new Option('', '', false, false)).prop('disabled', true).trigger('change.select2');
        disableBelowCity();
    }
    function disableBelowCity(){
        $barangay.empty().append(new Option('', '', false, false)).prop('disabled', true).trigger('change.select2');
    }

    // ---------- Finders / setters ----------
    function findRegionCodeByName(regionName){
        const target = (regionName||'').toString().trim().toLowerCase();
        for (const code of Object.keys(DATA)){
            const name = (DATA[code]?.region_name || '').toString().trim().toLowerCase();
            if (name === target) return code;
        }
        return null;
    }
    function findRegionByProvince(provinceName){
        const p = (provinceName||'').toString().trim().toLowerCase();
        for (const code of Object.keys(DATA)){
            const provObj = DATA[code]?.province_list || {};
            for (const prov of Object.keys(provObj)){
                if (prov.toLowerCase() === p) return code;
            }
        }
        return null;
    }
    function findByCity(cityName){
        const c = (cityName||'').toString().trim().toLowerCase();
        for (const code of Object.keys(DATA)){
            const provObj = DATA[code]?.province_list || {};
            for (const prov of Object.keys(provObj)){
                const muniObj = provObj[prov]?.municipality_list || {};
                for (const muni of Object.keys(muniObj)){
                    if (muni.toLowerCase() === c) return { regionCode: code, provinceName: prov };
                }
            }
        }
        return null;
    }
    function setSelectByText($el, text){
        const target = (text||'').toString().trim().toLowerCase();
        let found = null;
        $el.find('option').each(function(){
            if ($(this).text().trim().toLowerCase() === target) { found = $(this).val(); return false; }
        });
        if (found !== null) { $el.val(found).trigger('change.select2'); }
    }
    });
</script>

@endsection

