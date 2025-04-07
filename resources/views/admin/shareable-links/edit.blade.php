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
                        <li class="breadcrumb-item active" aria-current="page">Edit Shareable Link</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0 tx-spacing--1">Edit a Shareable Link</h4>
            </div>
        </div>

        <div class="row row-sm">
            <div class="col-lg-12">
                <form autocomplete="off" action="{{ route('shareable-links.update',$link->id) }}" method="post">
                    @method('PUT')
                    @csrf
                    <div class="row row-sm">
                        <div class="col-sm-6">
                            <div class="form-group mg-b-20">
                                <label class="mg-b-5 tx-color-03">Name <i class="tx-danger">*</i></label>
                                <input required type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $link->name }}" maxlength="150">
                                <x-error-message inputName="name" />
                            </div>

                            @php
                                $arr_media = ['facebook','messenger','twitter','youtube','linkedin','instagram','pinterest','tumblr','flickr','reddit','snapchat','whatsapp'];
                            @endphp

                            <div class="form-group mg-b-20">
                                <label class="mg-b-5 tx-color-03">Social Media <i class="tx-danger">*</i></label>
                                <select class="form-control @error('soc_med') is-invalid @enderror" name="soc_med" id="soc_med">
                                    <option value="">Choose One</option>
                                    <option @if($link->soc_media == 'facebook') selected @endif value="facebook">Facebook</option>
                                    <option @if($link->soc_media == 'messenger') selected @endif value="messenger">Messenger</option>
                                    <option @if($link->soc_media == 'twitter') selected @endif value="twitter">Twitter</option>
                                    <option @if($link->soc_media == 'youtube') selected @endif value="youtube">Youtube</option>
                                    <option @if($link->soc_media == 'linkedin') selected @endif value="linkedin">LinkedIn</option>
                                    <option @if($link->soc_media == 'instagram') selected @endif value="instagram">Instagram</option>
                                    <option @if($link->soc_media == 'pinterest') selected @endif value="pinterest">Pinterest</option>
                                    <option @if($link->soc_media == 'tumblr') selected @endif value="tumblr">Tumblr</option>
                                    <option @if($link->soc_media == 'flickr') selected @endif value="flickr">Flickr</option>
                                    <option @if($link->soc_media == 'reddit') selected @endif value="reddit">Reddit</option>
                                    <option @if($link->soc_media == 'snapchat') selected @endif value="snapchat">Snapchat</option>
                                    <option @if($link->soc_media == 'whatsapp') selected @endif value="whatsapp">WhatsApp</option>
                                    <option @if(!in_array($link->soc_media, $arr_media)) selected @endif value="other">Other</option>
                                </select>
                                <x-error-message inputName="soc_med" />
                            </div>

                            <div class="form-group mg-b-20" id="other_socmed" style="@if(!in_array($link->soc_media, $arr_media)) display:block; @else display:none; @endif">
                                <input type="text" class="form-control" name="other" id="other" placeholder="Enter Social Media Channel" value="@if(!in_array($link->soc_media, $arr_media)) {{ $link->soc_media }} @endif">
                            </div>

                            <div class="form-group mg-b-20">
                                <label class="mg-b-5 tx-color-03">URL <i class="tx-danger">*</i></label>
                                <input type="url" class="form-control @error('url') is-invalid @enderror" name="url" value="{{ strstr($link->url, '?', true) }}">
                                <x-error-message inputName="url" />
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary btn-uppercase">Update Link</button>
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
