@extends('admin.layouts.app')

@section('pagetitle')
    Edit Album
@endsection

@section('pagecss')
	<link href="{{ asset('lib/bselect/dist/css/bootstrap-select.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/ion-rangeslider/css/ion.rangeSlider.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/owl.carousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/owl.carousel/assets/owl.theme.default.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/swiper-bundle.min.css') }}">
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
                    <li class="breadcrumb-item active" aria-current="page">Edit an Album</li>
                </ol>
            </nav>
            <h4 class="mg-b-0 tx-spacing--1">Edit an Album</h4>
        </div>
        <div>
            <a href="#" data-toggle="modal" data-target="#preview-banner" class="btn btn-outline-primary btn-sm btn-uppercase" data-toggle="modal">Preview banner</a>
        </div>
    </div>
    <form id="updateForm" method="POST" action="{{ route('albums.update', $album->id) }}" enctype="multipart/form-data">
        @method('PUT')
        @csrf
        <div class="row row-sm">
            <div class="col-lg-6">
                <div class="form-group">
                    <label class="d-block">Album Name *</label>
                    <input name="user_id" type="hidden" value="{{Auth::user()->id}}">
                    <input name="name" type="text" class="form-control @error('name') is-invalid @enderror" required value="{{ old('name',$album->name) }}">
                    @error('name')
                    @enderror
                </div>
                <div class="form-group">
                    <label class="d-block">Effect *</label>
                    <select name="effect" class="selectpicker mg-b-5" data-style="btn btn-outline-light btn-sm btn-block tx-left" id="effectSelect" title="Select effect" data-width="100%">
                        @foreach (['slide', 'fade', 'cube', 'coverflow', 'flip', 'creative', 'cards'] as $effect)
                            <option {{ (old("effect", $album->effect) == $effect ? "selected":"") }} value="{{ $effect }}">{{ ucfirst($effect) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mg-b-0">
                    <input type="file" name="banner" class="d-none" id="upload_image" accept="image/*" multiple>
                    <button type="button" class="btn btn-light btn-xs btn-uppercase upload @error('banners') is-invalid @enderror" type="submit"><i data-feather="upload"></i> Upload image*</button>
                    @error('banners')
                    @enderror
                    @if ($album->id == 1)
                        <p class="tx-10">
                            Required image dimension: {{ env('MAIN_BANNER_WIDTH') }}px by {{ env('MAIN_BANNER_HEIGHT') }}px <br /> Maximum file size: 1MB <br /> Required file type: .jpeg .png
                        </p>
                    @else
                        <p class="tx-10">
                            Required image dimension: {{ env('SUB_BANNER_WIDTH') }}px by {{ env('SUB_BANNER_HEIGHT') }}px <br /> Maximum file size: 1MB <br /> Required file type: .jpeg .png
                        </p>
                    @endif
                </div>
            </div>

            <div class="col-md-12">
                <div class="row draggable-portlets">
                    <div class="col-md-12" id="banners">
                        @php $banners = old('banners', $album->banners); @endphp
                        @foreach ($banners as $key => $banner)
                            @php if(!isset($banner['id'])) {
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
                                                <div class="form-group upload-image mg-b-0" style="background: url('{{ $banner['image_path'] }}');background-size: cover;">
                                                    <div class="marker pos-absolute t-10 l-20 p-0 bg-transparent">
                                                        {{--                                                    <button class="btn btn-light btn-xs btn-uppercase" type="submit"><i data-feather="upload"></i> Upload image</button>--}}
                                                        <button type="button" class="btn btn-danger btn-xs btn-uppercase remove-upload" type="button" data-id="{{ $banner['id'] }}"><i data-feather="x"></i> Remove image</button>
                                                        @if(!isset($banner['new']))
                                                            <input name="banners[{{ $banner['id'] }}][id]" type="hidden" value="{{ $banner['id'] }}" />
                                                        @endif
                                                        <input name="banners[{{ $banner['id'] }}][image_path]" class="image_path" type="text" value="{{ $banner['image_path'] }}"  required/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
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
                @if (count($banners) > 0)
                <div class="swiper admin-swiper" style="width:100%;">
                    <div class="swiper-wrapper">
                        @foreach ($banners as $key => $banner)
                            <div class="swiper-slide">
                                <img src="{{$banner['image_path']}}" style="width:100%; height:100%; object-fit:cover">
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
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
    <script src="{{ asset('lib/jqueryui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('lib/owl.carousel/owl.carousel.js') }}"></script>
    <script src="{{ asset('js/swiper-bundle.min.js') }}"></script>

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

            $('#previewCarousel').owlCarousel({
                animateOut: "{{$album->animationOut->value}}",
                animateIn: "{{$album->animationIn->value}}",
                loop: true,
                dots: false,
                margin: 0,
                autoplay: true,
                autoplayTimeout: ("{{$album->transition}}")*1000,
                autoplayHoverPause: false,
                nav: false,
                responsive: {
                    0: {
                        items: 1
                    },
                    600: {
                        items: 1
                    },
                    1000: {
                        items: 1
                    }
                }
            });

            $(document).on('click', '.upload', function() {
                objUpload = $(this);
                $('#upload_image').click();
            });

            function upload_image(file)
            {
                let data = new FormData();
                data.append("_token", "{{ csrf_token() }}");
                data.append("banner", file);
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
                                                            <div class="col-lg-12">
                                                                <div class="form-group upload-image mg-b-0" style="background: url('`+returnData.image_url+`');background-size: cover;">
                                                                    <div class="marker pos-absolute t-10 l-20 p-0 bg-transparent">
                                                                    <button type="button" class="btn btn-danger btn-xs btn-uppercase remove-upload" type="submit" data-image-path="`+returnData.image_url+`"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg> Remove image</button>
                                                                    <input name="banners[`+image_count+`][image_path]" class="image_path" type="text" value="`+returnData.image_url+`" required onvalid="this.setCustomValidity('')" oninvalid="this.setCustomValidity('Please upload image.')" oninput="this.setCustomValidity('')"/>
                                                                </div>
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
                objRemove.parent().parent().parent().parent().parent().parent().remove();
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
        });
    </script>
    <script>
    (function () {
    const effectSelect = document.getElementById('effectSelect');
    const el = document.querySelector('.admin-swiper');

    let swiper;

    function buildOptions(effect, speedMs) {
        const opts = {
        effect,
        slidesPerView: 1,
        loop: false,
        speed: speedMs,
        autoplay: false,
        navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
        pagination: { el: '.swiper-pagination', clickable: true }
        };

        switch (effect) {
        case 'fade':
            opts.fadeEffect = { crossFade: true };
            break;
        case 'cube':
            opts.cubeEffect = { shadow: true, slideShadows: true, shadowOffset: 20, shadowScale: 0.94 };
            break;
        case 'coverflow':
            opts.centeredSlides = true;
            opts.coverflowEffect = { rotate: 0, stretch: 0, depth: 120, modifier: 1, slideShadows: true };
            break;
        case 'flip':
            opts.flipEffect = { slideShadows: true, limitRotation: true };
            break;
        case 'cards':
            opts.grabCursor = true;
            break;
        case 'creative':
            opts.creativeEffect = {
            prev: { translate: ['-20%', 0, -1], opacity: 0.6, scale: 0.85 },
            next: { translate: ['20%', 0, -1],  opacity: 0.6, scale: 0.85 }
            };
            break;
        }
        return opts;
    }

    function init() {
        const effect = effectSelect.value.toLowerCase();

        if (swiper) swiper.destroy(true, true);
        swiper = new Swiper(el, buildOptions(effect, 1000));
    }

    init(); // first load
    })();
    </script>
@endsection
