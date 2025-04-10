@extends('admin.layouts.app')

@section('pagetitle')
    Edit Album
@endsection

@section('pagecss')
    <link href="{{ asset('lib/bselect/dist/css/bootstrap-select.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/ion-rangeslider/css/ion.rangeSlider.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/slick/slick.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/slick/slick-theme.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/animate.css/animate.min.css') }}" rel="stylesheet">
    <style>
        #errorMessage {
            list-style-type: none;
            padding: 0;
            margin-bottom: 0px;
        }
        .image_path {
            opacity: 0;
            width: 0;
        }

        .slick-slide img {
            width: 100%;
        }

        .banner-overlay {
            position: absolute;
            left: 0;
            right: 0;
            top: 0;
            bottom: 0;
            z-index: 97;
            max-width: 100%;
            opacity: 1;
            background: -webkit-gradient(
                linear,
                left top,
                right top,
                from(white),
                to(rgba(255, 255, 255, 0))
            );
            background: linear-gradient(
                to right,
                white 0%,
                rgba(255, 255, 255, 0) 100%
            );
        }

        .banner-caption {
            position: relative;
            z-index: 3;
            display: -webkit-box !important;
            display: -ms-flexbox !important;
            display: flex !important;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            -vendor-animation-duration: 3s;
            -vendor-animation-delay: 0s;
            -vendor-animation-iteration-count: infinite;
            position: absolute;
            left: 0;
            right: 0;
            top: 0;
            bottom: 0;
            z-index: 98;
            max-width: 100%;
            margin: 0 auto;
            -webkit-box-orient: horizontal;
            -webkit-box-direction: normal;
            -ms-flex-direction: row;
            flex-direction: row;
            padding: 0 70px;
        }

        .banner-caption h2 {
            font-family: "Roboto", sans-serif;
            font-weight: 800 !important;
            font-size: 3.25em;
            line-height: 1.2;
            font-weight: 400;
            color: #981c1e;
            padding: 0;
            margin: 0 auto;
            text-align: left;
            text-transform: initial;
        }

        .banner-caption hr {
            border-top: 2px solid #981c1e;
            width: 50px;
            margin: 2em auto;
        }

        .banner-caption p {
            font-family: "Roboto", sans-serif;
            font-size: 1.15em !important;
            font-weight: 400;
            color: #0e1317;
            padding: 0;
            line-height: 1.5;
            margin: 1em 0 1.5em;
        }

        .banner-caption .primary-btn {
            font-family: "Roboto", sans-serif;
            font-size: 1em;
            font-weight: 400;
            background: #981c1e;
            color: white;
            border-radius: 5px;
            border: 2px solid #981c1e;
            -webkit-transition: all 0.2s ease-in-out;
            transition: all 0.2s ease-in-out;
            cursor: pointer;
            padding: 7px 30px;
        }
        .banner-caption .primary-btn:hover {
            color: white;
            text-decoration: none;
            background: #6d1416;
            border: 2px solid #6d1416;
            -webkit-transition: all 0.2s ease-in-out;
            transition: all 0.2s ease-in-out;
        }
    </style>
@endsection

@php
    function extract_file_name($fileName) {
        $path = explode('/', $fileName);
        $nameIndex = count($path) - 1;
        if ($nameIndex < 0)
            return '';

        return $path[$nameIndex];
    }
@endphp

@section('content')
    <div class="container pd-x-0">
        <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                        <li class="breadcrumb-item" aria-current="page"><a href="{{route('dashboard')}}">CMS</a></li>
                        <li class="breadcrumb-item" aria-current="page"><a href="{{route('albums.index')}}">Albums</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Home Banner</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0 tx-spacing--1">Edit Home Banner</h4>
            </div>
            <div>
                <a href="#" data-toggle="modal" data-target="#preview-banner" class="btn btn-outline-primary btn-sm btn-uppercase" data-toggle="modal">Preview banner</a>
            </div>
        </div>
        <form id="updateForm" method="POST" action="{{ route('albums.update', $album->id) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            @if ($errors->any())
                <div class="alert alert-solid alert-danger d-flex align-items-center" role="alert">
                    <ul id="errorMessage">
                        @foreach ($errors->all() as $error)
                            <li>
                                <i data-feather="x-circle" class="mg-r-10"></i> {{ $error }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="row row-sm">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="d-block">Album Name *</label>
                        <input name="name" type="text" class="form-control" required value="{{ $album->name }}" readonly>
                    </div>
                    <div class="form-group">
                        <label class="d-block">Transition In *</label>
                        <select name="transition_in" class="selectpicker mg-b-5" data-style="btn btn-outline-light btn-sm btn-block tx-left" title="Select transition" data-width="100%">
                            @foreach ($animations as $animation)
                                @if ($animation->is_entrance_field_type())
                                    <option @if ($album->transition_in == $animation->id) selected @endif value="{{ $animation->id }}">{{ $animation->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="d-block">Transition Out *</label>
                        <select name="transition_out" class="selectpicker mg-b-5" data-style="btn btn-outline-light btn-sm btn-block tx-left" title="Select transition" data-width="100%">
                            @foreach ($animations as $animation)
                                @if ($animation->is_exit_field_type())
                                    <option @if ($album->transition_out == $animation->id) selected @endif value="{{ $animation->id }}">{{ $animation->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="d-block">Transition Duration (seconds) *</label>
                        <input name="transition" type="text" class="js-range-slider" name="my_range" value="{{ $album->transition }}" />
                    </div>
                    <div class="form-group">
                        <label class="d-block">Banner Type</label>
                        <div class="custom-control custom-switch @error('visibility') is-invalid @enderror">
                            <input type="checkbox" class="custom-control-input" name="banner_type" value="video" {{ (old("banner_type") == "ON" || $album->banner_type == "video" ? "checked":"") }} id="banner_type">
                            <label class="custom-control-label" id="banner_type_label" for="banner_type">@if (old("banner_type") == "ON" || $album->banner_type == "video") Video @else Image @endif</label>
                        </div>
                    </div>
                    <div class="form-group mg-b-0" id="imageDiv" @if (old("banner_type") == "ON" || $album->banner_type == "video") style="display: none;" @endif>
						<input type="file" name="banner" class="d-none" id="upload_image" accept="image/*" multiple>
                        <button type="button" class="btn btn-light btn-xs btn-uppercase upload @error('banners') is-invalid @enderror" type="submit"><i data-feather="upload"></i> Upload images*</button>
                        @error('banners')
						@enderror
						@if ($album->is_main_banner())
                            <p class="tx-10">
                                Required image dimension: {{ env('MAIN_BANNER_WIDTH') }}px by {{ env('MAIN_BANNER_HEIGHT') }}px <br /> Maximum file size: 1MB <br /> Required file type: .jpeg .png
                            </p>
                        @else
                            <p class="tx-10">
                                Required image dimension: {{ env('BANNER_WIDTH') }}px by {{ env('BANNER_HEIGHT') }}px <br /> Maximum file size: 1MB <br /> Required file type: .jpeg .png
                            </p>
                        @endif
                    </div>
                    <div class="form-group mg-b-0" id="videoDiv" @if (old("banner_type") == "OFF" || $album->banner_type != "video") style="display: none;" @endif>
                        <input type="file" name="banner" class="d-none" id="upload_video" accept="video/mp4" multiple>
                        <button type="button" class="btn btn-light btn-xs btn-uppercase upload-video @error('banners') is-invalid @enderror" type="submit"><i data-feather="upload"></i> Upload videos*</button>
                        @error('banners')
                        @enderror
                        <p class="tx-10">
                            Maximum file size: 25MB <br /> Required file type: .mp4
                        </p>
                    </div>
                </div>
                <div class="col-md-12" id="imageList" @if ($album->banner_type == 'video') style="display: none;" @endif>
                    <div class="row draggable-portlets">
                        <div class="col-md-12" id="banners">
                            @if ($album->banner_type != 'video')
                                @php $banners = old('banners', $album->banners); @endphp
                                @foreach ($banners as $banner)
                                    <div class="sorted">
                                        <div class="card upload-card p-10 mg-t-20">
                                            <div class="card-header ui-sortable-handle"><i data-feather="move"></i> {{ extract_file_name($banner['image_path']) }}</div>
                                            <div class="card-body">
                                                <div class="row row-sm">
                                                    <div class="col-lg-4 col-md-12" @if ($album->banner_type == 'video') style="display: none;" @endif>
                                                        <div class="form-group upload-image mg-b-0" style="background: url('{{ $banner['image_path'] }}');background-size: cover;">
                                                            <div class="marker pos-absolute t-10 l-20 p-0 bg-transparent">
                                                                <button type="button" class="btn btn-danger btn-xs btn-uppercase remove-upload" type="button" data-id="{{ $banner['id'] }}"><i data-feather="x"></i> Remove image</button>
                                                                <input name="banners[{{ $banner['id'] }}][id]" type="hidden" value="{{ $banner['id'] }}" />
                                                                <input name="banners[{{ $banner['id'] }}][image_path]" class="image_path" type="text" value="{{ $banner['image_path'] }}"  required oninvalid="this.setCustomValidity('Please upload image.')" oninput="this.setCustomValidity('')"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-8 col-md-12 imageList" @if ($album->banner_type == 'video') style="display: none;" @endif>
                                                        <div class="form-group">
                                                            <input name="banners[{{ $banner['id'] }}][title]" type="text" class="form-control" placeholder="Caption heading" value="{{ $banner['title'] }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <textarea name="banners[{{ $banner['id'] }}][description]" class="form-control" placeholder="Description">{{ $banner['description'] }}</textarea>
                                                        </div>
                                                        <div class="form-row">
                                                            <div class="form-group col-md-4">
                                                                <input name="banners[{{ $banner['id'] }}][button_text]" type="text" class="form-control" placeholder="Button Text (Example: Read More)" value="{{ $banner['button_text'] }}" maxlength="30">
                                                            </div>
                                                            <div class="form-group col-md-8">
                                                                <input name="banners[{{ $banner['id'] }}][url]" type="url" class="form-control" placeholder="URL" value="{{ $banner['url'] }}">
                                                            </div>
                                                        </div>
                                                        <div class="form-group mg-b-0">
                                                            <input name="banners[{{ $banner['id'] }}][alt]" type="text" class="form-control" placeholder="Alt" value="{{ $banner['alt'] }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <div class="col-lg-12 mg-t-30">
                            <hr>
                            <button type="submit" class="btn btn-primary btn-sm btn-uppercase" type="submit">Update Album</button>
                            <a href="{{ route('albums.index') }}" class="btn btn-outline-secondary btn-sm btn-uppercase" type="cancel">Cancel</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-12" id="videoList" @if ($album->banner_type != 'video') style="display: none;" @endif>
                    <div class="row draggable-portlets">
                        <div class="col-md-12" id="videos">
                            @if ($album->banner_type == 'video')
                                @php $banners = old('banners', $album->banners); @endphp
                                @foreach ($banners as $key => $banner)
                                    @php
                                        if(!isset($banner['id'])) {
                                            $banner['id'] = $key;
                                            $banner['new'] = true;
                                        }
                                    @endphp
                                    <div class="sorted">
                                        <div class="card upload-card p-10 mg-t-20">
                                            <div class="card-header ui-sortable-handle"><i data-feather="move"></i> {{ extract_file_name($banner['image_path']) }}</div>
                                            <div class="card-body">
                                                <div class="row row-sm">
                                                    <div class="col-lg-12">
                                                        <div class="form-group upload-video">
                                                            <video autoplay muted loop width="100%" height="100%">
                                                                <source src="{{ $banner['image_path'] }}" type="video/mp4">
                                                            </video>
                                                            <div class="marker pos-absolute t-10 l-20 p-0 bg-transparent">
                                                                <button type="button" class="btn btn-danger btn-xs btn-uppercase remove-upload" type="button" data-id="{{ $banner['id'] }}"><i data-feather="x"></i> Remove video</button>
                                                                @if(!isset($banner['new']))
                                                                    <input name="banners[{{ $banner['id'] }}][id]" type="hidden" value="{{ $banner['id'] }}" />
                                                                @endif
                                                                <input name="banners[{{ $banner['id'] }}][image_path]" class="image_path" type="text" value="{{ $banner['image_path'] }}"  required/>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <input name="banners[{{ $banner['id'] }}][title]" type="text" class="form-control" placeholder="Caption heading" value="{{ $banner['title'] }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <textarea name="banners[{{ $banner['id'] }}][description]" class="form-control" placeholder="Description">{{ $banner['description'] }}</textarea>
                                                        </div>
                                                        <div class="form-row">
                                                            <div class="form-group col-md-4">
                                                                <input name="banners[{{ $banner['id'] }}][button_text]" type="text" class="form-control" placeholder="Button Text (Example: Read More)" value="{{ $banner['button_text'] }}" maxlength="30">
                                                            </div>
                                                            <div class="form-group col-md-8">
                                                                <input name="banners[{{ $banner['id'] }}][url]" type="url" class="form-control" placeholder="URL" value="{{ $banner['url'] }}">
                                                            </div>
                                                        </div>
                                                        <div class="form-group mg-b-0">
                                                            <input name="banners[{{ $banner['id'] }}][alt]" type="text" class="form-control" placeholder="Alt" value="{{ $banner['alt'] }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <div class="col-lg-12 mg-t-30">
                            <hr>
                            <button type="submit" class="btn btn-primary btn-sm btn-uppercase" type="submit">Update Album</button>
                            <a href="{{ route('albums.index') }}" class="btn btn-outline-secondary btn-sm btn-uppercase" type="cancel">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="modal effect-scale" id="prompt-remove" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Remove image</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>{{__('standard.banner.remove_image')}}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-danger" id="btnRemove">Yes, remove image</button>
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="preview-banner" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content tx-14">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel3">Preview</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="slick-slider" id="previewCarousel">
                        @foreach ($banners as $key => $banner)
                            <div class="hero-slide">
                                <div class="banner-overlay"></div>
                                <img src="{{$banner['image_path']}}">
                                <div class="banner-caption">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-lg-5 col-md-5">
                                                <h2>{{ $banner['title'] }}</h2>
                                                <p>
                                                    {{ $banner['description'] }}
                                                </p>
                                                @if($banner['url'])
                                                    <a class="primary-btn" href="#">Read more</a>
                                                @endif
                                            </div>
                                            <div class="col-lg-7 col-md-7">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary tx-13" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('pagejs')
    <script src="{{ asset('lib/bselect/dist/js/bootstrap-select.js') }}"></script>
    <script src="{{ asset('lib/bselect/dist/js/i18n/defaults-en_US.js') }}"></script>
    <script src="{{ asset('lib/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>
    <script src="{{ asset('lib/slick/slick.js') }}"></script>
    <script src="{{ asset('lib/jqueryui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/joinable.js') }}"></script>
    <script src="{{ asset('js/resizeable.js') }}"></script>
    {{--    Image validation--}}
    <script>
        const IS_MAIN_BANNER = "{{ $album->is_main_banner() }}";
        let BANNER_WIDTH;
        let BANNER_HEIGHT;

        if (IS_MAIN_BANNER) {
            BANNER_WIDTH = "{{ env('MAIN_BANNER_WIDTH') }}";
            BANNER_HEIGHT = "{{ env('MAIN_BANNER_HEIGHT') }}";
        } else {
            BANNER_WIDTH = "{{ env('SUB_BANNER_WIDTH') }}";
            BANNER_HEIGHT =  "{{ env('SUB_BANNER_HEIGHT') }}";
        }
    </script>
    <script src="{{ asset('js/image-upload-validation.js') }}"></script>
    {{--    End Image validation--}}
@endsection

@section('customjs')
    <script>
        $(function() {
            let image_count = 1;
            let objUpload;
            let objRemove;
            $('.selectpicker').selectpicker();

            $(".js-range-slider").ionRangeSlider({
                grid: true,
                min: 2,
                max: 10
            });

            setInterval(function() {
                $("#previewCarousel div.hero-slide").attr("data-animation-in", "{{$album->animationIn->value}}");
                $("#previewCarousel div.hero-slide").attr("data-animation-out", "{{$album->animationOut->value}}");
                $("#previewCarousel div.hero-slide").attr("data-delay", "0s");
            }, 3000);

            $("#previewCarousel").slick({
                autoplay: true,
                autoplaySpeed: ("{{$album->transition}}")*1000,
                dots: true,
                fade: true,
                arrows: false,
                pauseOnHover: false
            });

            function doAnimationIn(elements) {
                var animationEndEvents =
                "webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend";
                elements.each(function() {
                var $this = $(this);
                var $animationDelay = $this.data("delay");
                var $animationType = "animated " + $this.data("animation-in");
                $this.addClass("{{$album->animationIn->value}}");
                $this.css({
                    "animation-delay": $animationDelay,
                    "-webkit-animation-delay": $animationDelay
                });
                $this.addClass($animationType).one(animationEndEvents, function() {
                    $this.removeClass($animationType);
                });
                });
            }
            function doAnimationOut(elements) {
                var animationEndEvents =
                "webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend";
                elements.each(function() {
                var $this = $(this);
                var $animationDelay = $this.data("delay");
                var $animationType = "animated " + $this.data("animation-out");
                $this.css({
                    "animation-delay": $animationDelay,
                    "-webkit-animation-delay": $animationDelay
                });
                $this.addClass($animationType).one(animationEndEvents, function() {
                    $this.removeClass($animationType);
                    console.log($animationType);
                });
                });
            }

            $("#previewCarousel").on("beforeChange", function(
                e,
                slick,
                currentSlide,
                nextSlide
            ) {
                var $animatingElementsOut = $(
                'div.slick-slide[data-slick-index="' + currentSlide + '"]'
                ).find("[data-animation-out]");
                doAnimationOut($animatingElementsOut);
                var $animatingElementsIn = $(
                'div.slick-slide[data-slick-index="' + nextSlide + '"]'
                ).find("[data-animation-in]");
                doAnimationIn($animatingElementsIn);
            });
            // $('#previewCarousel').owlCarousel({
            //     animateOut: "{{$album->animationOut->value}}",
            //     animateIn: "{{$album->animationIn->value}}",
            //     loop: true,
            //     dots: false,
            //     margin: 0,
            //     autoplay: true,
            //     autoplayTimeout: ("{{$album->transition}}")*1000,
            //     autoplayHoverPause: false,
            //     nav: false,
            //     responsive: {
            //         0: {
            //             items: 1
            //         },
            //         600: {
            //             items: 1
            //         },
            //         1000: {
            //             items: 1
            //         }
            //     }
            // });

            $(document).on('click', '.upload', function() {
                objUpload = $(this);
                $('#upload_image').click();
            });

            $(document).on('click', '.upload-video', function() {
                objUpload = $(this);
                $('#upload_video').click();
            });

            function upload_image(file)
            {
                let data = new FormData();
                data.append("_token", "{{ csrf_token() }}");
                data.append("banner", file);
                $('#upload_image').val('');
                $.ajax({
                    data: data,
                    type: "POST",
                    url: "{{ route('albums.upload') }}",
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(returnData) {
                        console.log(returnData);
                        if (returnData.status == "success") {
                            while ($('input[name="banners['+image_count+'][image_path]"]').length) {
                                image_count += 1;
                            }

                            let bannerHTML = `<div class="sorted">
                                            <div class="card upload-card p-10 mg-t-20">
                                                <div class="card-header ui-sortable-handle"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-move"><polyline points="5 9 2 12 5 15"></polyline><polyline points="9 5 12 2 15 5"></polyline><polyline points="15 19 12 22 9 19"></polyline><polyline points="19 9 22 12 19 15"></polyline><line x1="2" y1="12" x2="22" y2="12"></line><line x1="12" y1="2" x2="12" y2="22"></line></svg> `+returnData.image_name+`</div>
                                                    <div class="card-body">
                                                        <div class="row row-sm">
                                                            <div class="col-lg-4 col-md-12">
                                                                <div class="form-group upload-image mg-b-0" style="background: url('`+returnData.image_url+`');background-size: cover;">
                                                                    <div class="marker pos-absolute t-10 l-20 p-0 bg-transparent">
                                                                    <button type="button" class="btn btn-danger btn-xs btn-uppercase remove-upload" type="submit"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg> Remove image</button>
                                                                    <input name="banners[`+image_count+`][image_path]" class="image_path" type="text" value="`+returnData.image_url+`" required onvalid="this.setCustomValidity('')" oninvalid="this.setCustomValidity('Please upload image.')" oninput="this.setCustomValidity('')"/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-8 col-md-12">
                                                            <div class="form-group">
                                                                <input name="banners[`+image_count+`][title]" type="text" class="form-control" placeholder="Caption heading">
                                                            </div>
                                                            <div class="form-group">
                                                                <textarea name="banners[`+image_count+`][description]" class="form-control" placeholder="Description"></textarea>
                                                            </div>
                                                            <div class="form-group">
                                                                <input name="banners[`+image_count+`][url]" type="url" class="form-control" placeholder="URL">
                                                            </div>
                                                            <div class="form-group mg-b-0">
                                                                <input name="banners[`+image_count+`][alt]" type="text" class="form-control" placeholder="Alt">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>`;
                            $('#banners').append(bannerHTML);
                            dragInit();
                        }
                    },
                    failed: function() {
                        alert('FAILED NGA!');
                    }
                });
            }

            $('#upload_image').change(function (evt) {
                validate_images(evt, upload_image);
            });

            $(document).on('click', '.remove-upload', function() {
                objRemove = $(this);
                $('#prompt-remove').modal('show');
            });

            $('#btnRemove').on('click', function() {
                objRemove.parent().parent().parent().parent().parent().parent().parent().remove();
                let attr = objRemove.attr('data-id');
                if (typeof(attr) != 'undefined') {
                    $('#updateForm').prepend('<input type="hidden" name="remove_banners[]" value="'+attr+'">');
                }

                $('#prompt-remove').modal('hide');
            });

            /* Draggable */
            function dragInit() {
                var $draggable_portlets = $(".draggable-portlets");

                $(".draggable-portlets .sorted").sortable({
                    connectWith: ".draggable-portlets .sorted",
                    handle: '.card-header',
                    start: function () {
                        $draggable_portlets.addClass('dragging');
                    },
                    stop: function () {
                        $draggable_portlets.removeClass('dragging');
                    }
                });

                $(".draggable-portlets .sorted .card-header").disableSelection();
            }

            dragInit();
            /* End Draggable */

            $("#banner_type").change(function() {
                if(this.checked) {
                    $('#banner_type_label').html('Video');
                    $('#imageDiv').hide();
                    $('#videoDiv').show();
                    $('#videoList').show();
                    $('#imageList').hide();
                }
                else{
                    $('#videoDiv').hide();
                    $('#imageDiv').show();
                    $('#videoList').hide();
                    $('#imageList').show();
                    $('#banner_type_label').html('Image');
                }
            });

            function upload_video(file)
            {
                let data = new FormData();
                data.append("_token", "{{ csrf_token() }}");
                data.append("banner", file);
                $('#upload_video').val('');
                $.ajax({
                    data: data,
                    type: "POST",
                    url: "{{ route('albums.upload') }}",
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(returnData) {
                        console.log(returnData);
                        if (returnData.status == "success") {
                            let bannerHTML = `<div class="sorted">
                                            <div class="card upload-card p-10 mg-t-20">
                                                <div class="card-header ui-sortable-handle"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-move"><polyline points="5 9 2 12 5 15"></polyline><polyline points="9 5 12 2 15 5"></polyline><polyline points="15 19 12 22 9 19"></polyline><polyline points="19 9 22 12 19 15"></polyline><line x1="2" y1="12" x2="22" y2="12"></line><line x1="12" y1="2" x2="12" y2="22"></line></svg> `+returnData.image_name+`</div>
                                                    <div class="card-body">
                                                        <div class="row row-sm">
                                                            <div class="col-lg-12">
                                                                <div class="form-group">
                                                                    <video autoplay muted loop width="100%" height="100%">
                                                                      <source src="`+returnData.image_url+`" type="video/mp4">
                                                                    </video>
                                                                    <div class="marker pos-absolute t-10 l-20 p-0 bg-transparent">
                                                                        <button type="button" class="btn btn-danger btn-xs btn-uppercase remove-upload" type="submit" data-image-path="`+returnData.image_url+`"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg> Remove video</button>
                                                                        <input name="banners[0][image_path]" class="image_path" type="text" value="`+returnData.image_url+`" required onvalid="this.setCustomValidity('')" oninvalid="this.setCustomValidity('Please upload image.')" oninput="this.setCustomValidity('')"/>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <input name="banners[0][title]" type="text" class="form-control" placeholder="Caption heading">
                                                                </div>
                                                                <div class="form-group">
                                                                    <textarea name="banners[0][description]" class="form-control" placeholder="Description"></textarea>
                                                                </div>
                                                                <div class="form-row">
                                                                    <div class="form-group col-md-4">
                                                                        <input name="banners[0][button_text]" type="text" class="form-control" placeholder="Button Text (Example: Read More)" maxlength="30">
                                                                    </div>
                                                                    <div class="form-group col-md-8">
                                                                        <input name="banners[0][url]" type="url" class="form-control" placeholder="URL">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group mg-b-0">
                                                                    <input name="banners[0][alt]" type="text" class="form-control" placeholder="Alt">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>`;
                            $('#videos').html(bannerHTML);
                            dragInit();
                        }
                    },
                    failed: function() {
                        alert('FAILED NGA!');
                    }
                });
            }

            $('#upload_video').change(function (evt) {
                validate_videos(evt, upload_video);
            });

        });
    </script>
@endsection
