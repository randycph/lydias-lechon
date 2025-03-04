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
                        <li class="breadcrumb-item" aria-current="page"><a href="{{route('branch.index')}}">Social Media Shareable Links</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Create Shareable Link</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0 tx-spacing--1">Create a Shareable Link</h4>
            </div>
        </div>

        <div class="row row-sm">
            <div class="col-lg-12">
                <form autocomplete="off" action="{{ route('shareable-links.store') }}" method="post">
                    @method('POST')
                    @csrf
                    <div class="row row-sm">
                        <div class="col-sm-6">
                            <div class="form-group mg-b-20">
                                <label class="mg-b-5 tx-color-03">Name <i class="tx-danger">*</i></label>
                                <input required type="text" class="form-control @error('name') is-invalid @enderror" name="name" maxlength="150">
                                @hasError(['inputName' => 'name'])
                                @endhasError
                            </div>
                            <div class="form-group mg-b-20">
                                <label class="mg-b-5 tx-color-03">Social Media <i class="tx-danger">*</i></label>
                                <select class="form-control @error('soc_med') is-invalid @enderror" name="soc_med" id="soc_med">
                                    <option value="">Choose One</option>
                                    <option value="facebook">Facebook</option>
                                    <option value="messenger">Messenger</option>
                                    <option value="twitter">Twitter</option>
                                    <option value="youtube">Youtube</option>
                                    <option value="linkedin">LinkedIn</option>
                                    <option value="instagram">Instagram</option>
                                    <option value="pinterest">Pinterest</option>
                                    <option value="tumblr">Tumblr</option>
                                    <option value="flickr">Flickr</option>
                                    <option value="reddit">Reddit</option>
                                    <option value="snapchat">Snapchat</option>
                                    <option value="whatsapp">WhatsApp</option>
                                    <option value="other">Other</option>
                                </select>
                                @hasError(['inputName' => 'soc_med'])
                                @endhasError
                            </div>

                            <div class="form-group mg-b-20" id="other_socmed" style="display:none;">
                                <input type="text" class="form-control" name="other" id="other" placeholder="Enter Social Media Channel">
                            </div>

                            <div class="form-group mg-b-20">
                                <label class="mg-b-5 tx-color-03">URL <i class="tx-danger">*</i></label>
                                <input type="url" class="form-control @error('url') is-invalid @enderror" name="url" >
                                @hasError(['inputName' => 'url'])
                                @endhasError
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary btn-uppercase">Save Link</button>
                    <a href="{{ route('shareable-links.index') }}" class="btn btn-outline-secondary btn-sm btn-uppercase">Cancel</a>
                </form>
            </div>
        </div>
    </div>


@endsection

@section('pagejs')
    <script src="{{ asset('lib/bselect/dist/js/bootstrap-select.js') }}"></script>
    <script src="{{ asset('lib/bselect/dist/js/i18n/defaults-en_US.js') }}"></script>
    <script src="{{ asset('lib/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>

    <script src="{{ asset('js/listing.js') }}"></script>
@endsection

@section('customjs')
    <script>

        $(".js-range-slider").ionRangeSlider({
            grid: true,
            from: selected,
            values: perPage
        });

        $(document).ready(function () {
            //called when key is pressed in textbox
            $("#contact_nos").keypress(function (e) {

                var charCode = (e.which) ? e.which : event.keyCode
                if (charCode != 43 && charCode > 31 && (charCode < 48 || charCode > 57))
                    return false;
                return true;

            });
        }); 

        $('#soc_med').change(function(){
            var x = $(this).val();

            if(x == 'other'){
                $('#other_socmed').css('display','block');
                $('#other').attr('required',true);
            } else {
                $('#other_socmed').css('display','none');
                $('#other').attr('required',false);
            }
        });

    </script>
@endsection
