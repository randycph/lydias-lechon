@extends('admin.layouts.app')

@section('pagetitle')
    Branch Manager
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
                        <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                            <li class="breadcrumb-item" aria-current="page"><a href="{{route('dashboard')}}">CMS</a></li>
                            <li class="breadcrumb-item" aria-current="page"><a href="{{route('branch.index')}}">Branch</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Branch</li>
                        </ol>
                    </nav>
                    <h4 class="mg-b-0 tx-spacing--1">Edit a Branch</h4>
                </div>
            </div>


            <form autocomplete="off" action="{{ route('branch.update', $branches->id) }}" method="post" id="branchForm">
                <div class="row row-sm">
                    @method('PUT')
                    @csrf
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="d-block">Branch Name *</label>
                            <input name="name" id="name" value="{{ old('name', $branches->name) }}" required type="text" class="form-control @error('name') is-invalid @enderror" maxlength="250">
                            @error('name')
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="d-block">Code *</label>
                            <input name="code" id="code" value="{{ old('code',$branches->code) }}" required type="text" class="form-control @error('code') is-invalid @enderror" maxlength="250">
                            @error('code')
                            @enderror
                        </div>
                        <div class="form-group mg-b-20">
                            <label class="mg-b-5 tx-color-03">Store Type </label>
                            <select name="branch_type" class="form-control" id="branch_type">
                                <option value="">Select</option>
                                <option value="Restaurant" @if($branches->branch_type=='Restaurant') selected="selected" @endif>Restaurant</option>
                                <option value="Mall Based Foodcourt" @if($branches->branch_type=='Mall Based Foodcourt') selected="selected" @endif>Mall Based Foodcourt</option>
                                <option value="Kiosk" @if($branches->branch_type=='Kiosk') selected="selected" @endif>Kiosk</option>
                            </select>
                        </div>
                        <div class="form-group mg-b-20">
                            <input type="checkbox" name="is_head_office" id="is_head_office" @if($branches->is_head_office=='1') checked="checked" @endif>
                            <label class="mg-b-5 tx-color-03" for="is_head_office">Is Head Office?</label>                                
                        </div>
                        <div class="form-group mg-b-20">
                            <input type="checkbox" name="pickup_branch" id="pickup_branch" @if($branches->pickup_branch=='1') checked="checked" @endif>
                            <label class="mg-b-5 tx-color-03" for="pickup_branch">Pickup Branch</label>                                
                        </div>
                        <div class="form-group mg-b-20">
                            <input type="checkbox" name="delivery_branch" id="delivery_branch" @if($branches->delivery_branch=='1') checked="checked" @endif>
                            <label class="mg-b-5 tx-color-03" for="delivery_branch">Delivery Branch</label>                                
                        </div>
                        <div class="form-group mg-b-20">
                            <input type="checkbox" name="jo_select_branch" id="jo_select_branch" @if($branches->jo_select_branch=='1') checked="checked" @endif>
                            <label class="mg-b-5 tx-color-03" for="jo_select_branch">JO Select Branch</label>                   
                        </div>
                        <div class="form-group mg-b-20">
                            <label class="d-block">Status</label>
                            <div class="custom-control custom-switch @error('status') is-invalid @enderror">
                                <input type="checkbox" class="custom-control-input" name="status" {{ (old("status", $branches->status) ? "checked":"") }} id="customSwitch13">
                                <label class="custom-control-label" id="label_visibility13" for="customSwitch13">{{ (old("status", $branches->status) ? "Yes":"No") }}</label>
                                <x-error-message inputName="status" />
                            </div>
                        </div>
                        <div class="form-group" style="display:none;">
                            <label class="d-block">Token *</label>
                            <input name="token" id="token" value="{{ old('token',$branches->token) }}" required type="text" class="form-control @error('token') is-invalid @enderror" maxlength="250">
                            @error('token')
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="d-block">Address *</label>
                            <input name="address" id="address" value="{{ old('address',$branches->address) }}" required type="text" class="form-control @error('address') is-invalid @enderror" maxlength="250">
                            @error('address')
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="d-block">Contact Number *</label>
                            <input name="contact_nos" id="contact_nos" value="{{ old('contact_nos',$branches->contact_nos) }}" required type="text" class="form-control @error('contact_nos') is-invalid @enderror" maxlength="250">
                            @error('contact_nos')
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="d-block">Contact Person *</label>
                            <input name="contact_person" id="contact_person" value="{{ old('name',$branches->contact_person) }}" type="text" class="form-control @error('contact_person') is-invalid @enderror" maxlength="250">
                            @error('contact_person')
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="d-block">Hotline</label>
                            <input name="hotline" id="hotline" value="{{ old('name',$branches->hotline) }}" type="text" class="form-control @error('hotline') is-invalid @enderror" maxlength="250">
                            @error('hotline')
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="d-block">Email Address *</label>
                            <input name="email_address" id="email_address" value="{{ old('name',$branches->email_address) }}" type="text" class="form-control @error('email_address') is-invalid @enderror" maxlength="250">
                            @error('email_address')
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="d-block">Commissary</label>
                            <input name="commissary" id="commissary" value="{{ old('name',$branches->commissary) }}" type="text" class="form-control @error('commissary') is-invalid @enderror" maxlength="225">
                            @error('commissary')
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="d-block">Direction Link</label>
                            <input name="direction_link" id="direction_link" value="{{ old('name',$branches->direction_link) }}" type="text" class="form-control @error('direction_link') is-invalid @enderror" maxlength="225">
                            @error('direction_link')
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="d-block">Google Map Link</label>
                            <input name="google_map_link" id="google_map_link" value="{{ old('name',$branches->google_map_link) }}" type="text" class="form-control @error('google_map_link') is-invalid @enderror" maxlength="225">
                            @error('google_map_link')
                            @enderror
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <div class="card mt-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <strong>Branch Numbers</strong>
                                <button type="button" class="btn btn-sm btn-primary" id="addBranchRow">+ Add</button>
                            </div>
                            <div class="card-body p-0">
                                <table class="table mb-0" id="branchNumbersTable">
                                    <thead>
                                        <tr>
                                            <th>Type</th>
                                            <th>Number</th>
                                            <th>Name</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="branchNumbersTableBody">
                                        @foreach($branches->numbers as $i => $number)
                                        <tr>
                                            <td>
                                                <select name="branches[{{ $i }}][type]" class="form-control my-2" required>
                                                    <option value="Mobile" @if($number->type=='Mobile') selected="selected" @endif>Mobile</option>
                                                    <option value="Phone" @if($number->type=='Phone') selected="selected" @endif>Phone</option>
                                                    <option value="Fax" @if($number->type=='Fax') selected="selected" @endif>Fax</option>
                                                    <option value="Email" @if($number->type=='Email') selected="selected" @endif>Email</option>
                                                    <option value="Hotline" @if($number->type=='Hotline') selected="selected" @endif>Hotline</option>
                                                </select>
                                            </td>
                                            <td><input type="text" name="branches[{{ $i }}][number]" class="form-control my-2" value="{{ $number->number }}" required></td>
                                            <td><input type="text" name="branches[{{ $i }}][name]" class="form-control my-2" value="{{ $number->name }}"></td>
                                            <td><button type="button" class="btn btn-sm btn-danger removeBranchRow">Delete</button></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <script type="text/template" id="branchRowTemplate">
                            <tr class="">
                                <td>
                                    <select name="branches[][type]" class="form-control my-2" required>
                                        <option value="Mobile">Mobile</option>
                                        <option value="Phone">Phone</option>
                                        <option value="Fax">Fax</option>
                                        <option value="Email">Email</option>
                                        <option value="Hotline">Hotline</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="branches[][number]" class="form-control my-2" required placeholder="09171584569 or +639158123600"></td>
                                <td><input type="text" name="branches[][name]" class="form-control my-2" placeholder="Globe"></td>
                                <td><button type="button" class="btn btn-sm btn-danger removeBranchRow">Delete</button></td>
                            </tr>
                        </script>
                    </div>


                    <div class="col-lg-12 mg-t-30">
                        <button class="btn btn-primary btn-sm btn-uppercase" type="submit" id="updateBranchBtn">
                            Update Branch
                        </button>
                        <a href="{{ route('branch.index') }}" class="btn btn-outline-secondary btn-sm btn-uppercase">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
@endsection

@section('pagejs')
    <script src="{{ asset('lib/bselect/dist/js/bootstrap-select.js') }}"></script>
    <script src="{{ asset('lib/bselect/dist/js/i18n/defaults-en_US.js') }}"></script>
    <script src="{{ asset('lib/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>

    <script src="{{ asset('js/listing.js') }}"></script>

    <script>
        $("#branchForm").submit(function(e){
            const btn = document.getElementById('updateBranchBtn');
            btn.disabled = true;
            btn.innerText = 'Updating...';
        });
        $(document).ready(function () {
            let branchRowIndex = {{ $branches->numbers->count() }};

            $('#addBranchRow').click(function () {
                const row = `
                    <tr>
                        <td>
                            <select name="branches[${branchRowIndex}][type]" class="form-control my-2" required>
                                <option value="Mobile">Mobile</option>
                                <option value="Phone">Phone</option>
                                <option value="Fax">Fax</option>
                                <option value="Email">Email</option>
                                <option value="Hotline">Hotline</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" name="branches[${branchRowIndex}][number]" class="form-control my-2" required placeholder="09171584569 or +639158123600">
                        </td>
                        <td>
                            <input type="text" name="branches[${branchRowIndex}][name]" class="form-control my-2" placeholder="Globe">
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-danger removeBranchRow">Delete</button>
                        </td>
                    </tr>
                `;
                $('#branchNumbersTableBody').append(row);
                branchRowIndex++;
            });

            $(document).on('click', '.removeBranchRow', function () {
                $(this).closest('tr').remove();
            });

            $("#customSwitch13").change(function() {
                if(this.checked) {
                    $('#label_visibility13').html('Yes');
                }
                else{
                    $('#label_visibility13').html('No');
                }
            });

            // $('#branchForm').submit(function (e) {
            //     const $btn = $('#updateBranchBtn');
            //     $btn.prop('disabled', true);
            //     $.ajax({
            //         url: '{{ route("branch.update", $branches->id) }}',
            //         method: 'POST',
            //         data: $(this).serialize(),
            //         complete: function () { $btn.prop('disabled', false); },
            //         success: function () { /* ... */ },
            //         error: function (err) { console.error(err); alert('Something went wrong.'); }
            //     });
            // });
        });
    </script>
    <script>
        $(document).ready(function () {
            //called when key is pressed in textbox
            $("#contact_nos").keypress(function (e) {

                var charCode = (e.which) ? e.which : event.keyCode
                if (charCode != 43 && charCode > 31 && (charCode < 48 || charCode > 57))
                    return false;
                return true;

            });
        });  
    </script>
@endsection


