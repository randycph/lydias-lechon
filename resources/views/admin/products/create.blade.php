@extends('admin.layouts.app')

@section('pagetitle')
    Create Product
@endsection

@section('pagecss')
    <link href="{{ asset('lib/bselect/dist/css/bootstrap-select.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/bootstrap-tagsinput/bootstrap-tagsinput.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom-product.css') }}" rel="stylesheet">
    <script src="{{ asset('lib/ckeditor/ckeditor.js') }}"></script>
    <style>
        #errorMessage {
            list-style-type: none;
            padding: 0;
            margin-bottom: 0px;
        }
        .image_path {
            opacity: 0;
            width: 0;
            display: none;
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
                        <li class="breadcrumb-item active" aria-current="page"><a href="{{route('products.index')}}">Products</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Create Product</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0 tx-spacing--1">Create a Product</h4>
            </div>
        </div>
        <form id="albumForm" method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
            <div class="row row-sm">
                @method('POST')
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

                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="d-block">Code</label>
                        <input name="code" id="code" value="{{ old('code') }}" type="text" class="form-control @error('code') is-invalid @enderror" maxlength="250">
                        <x-error-message inputName="code" />
                    </div>
                    <div class="form-group">
                        <label class="d-block">Name *</label>
                        <input name="name" id="name" value="{{ old('name') }}" required type="text" class="form-control @error('name') is-invalid @enderror" maxlength="250">
                        <x-error-message inputName="name" />
                    </div>
                    <div class="form-group">
                        <label class="d-block">Category</label>
                        <select name="category" id="category" class="selectpicker mg-b-5" data-style="btn btn-outline-light btn-md btn-block tx-left" title="Select category" data-width="100%">
                            <option value="0">-- Select Category --</option>
                            @foreach($categories as $category)
                                <option value="{{$category->id}}">{{strtoupper($category->name)}}</option>
                            @endforeach
                        </select>
                        <x-error-message inputName="category" />
                    </div>
                    <div class="form-group">
                        <label class="d-block">Short Description</label>
                        <textarea name="short_description" rows="3" class="form-control">{{ old('short_description') }}</textarea>
                        <x-error-message inputName="short_description" />
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="d-block" id="long_descriptionLabel">Description</label>
                        <textarea name="long_description" id="editor1" rows="10" cols="80">
                             {{ old('long_description') }}
                        </textarea>
                                <x-error-message inputName="long_description" />
                                <span class="invalid-feedback" role="alert" id="long_descriptionRequired" style="display: none;">
                            <strong>The description field is required</strong>
                        </span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="d-block">For sale</label>
                    <div class="custom-control custom-switch @error('for_sale') is-invalid @enderror">
                        <input type="checkbox" class="custom-control-input" name="for_sale" {{ (old("for_sale") ? "checked":"") }} id="customSwitch3">
                        <label class="custom-control-label" id="label_visibility3" for="customSwitch3">No</label>
                        <x-error-message inputName="for_sale" />
                    </div>
                </div>
                <div class="form-group" id="for_sale_web_div" style="display:none;">
                    <label class="d-block">For sale on Web</label>
                    <div class="custom-control custom-switch @error('for_sale_web') is-invalid @enderror">
                        <input type="checkbox" class="custom-control-input" name="for_sale_web" {{ (old("for_sale_web") ? "checked":"") }} id="for_sale_web">
                        <label class="custom-control-label" id="for_sale_web_label" for="for_sale_web">No</label>
                        <x-error-message inputName="for_sale_web" />
                    </div>
                </div>
                <div class="form-group" id="for_sale_kiosk_div" style="display:none;">
                    <label class="d-block">For sale on Kiosk</label>
                    <div class="custom-control custom-switch @error('for_sale_kiosk') is-invalid @enderror">
                        <input type="checkbox" class="custom-control-input" name="for_sale_kiosk" {{ (old("for_sale_kiosk") ? "checked":"") }} id="for_sale_kiosk">
                        <label class="custom-control-label" id="for_sale_kiosk_label" for="for_sale_kiosk">No</label>
                        <x-error-message inputName="for_sale_kiosk" />
                    </div>
                </div>
                
                <div class="form-group" id="is_misc_div" style="display:none;">
                    <label class="d-block">Miscellaneous Item</label>
                    <div class="custom-control custom-switch @error('is_misc') is-invalid @enderror">
                        <input type="checkbox" class="custom-control-input" name="is_misc" {{ (old("is_misc") ? "checked":"") }} id="is_misc">
                        <label class="custom-control-label" id="is_misc_label" for="is_misc">No</label>
                        <x-error-message inputName="is_misc" />
                    </div>
                </div>

                <div class="form-group" id="production_item_div" style="display:none;">
                    <label class="d-block">This Product Requires Production Time</label>
                    <div class="custom-control custom-switch @error('production_item') is-invalid @enderror">
                        <input type="checkbox" class="custom-control-input" name="production_item" {{ (old("production_item") ? "checked":"") }} id="production_item">
                        <label class="custom-control-label" id="production_item_label" for="production_item">No</label>
                        <x-error-message inputName="production_item" />
                    </div>
                </div>

                <div class="col-lg-12 mg-t-30">
                    <h4 class="mg-b-0 tx-spacing--1">Size & Paella Options</h4>
                    <hr>
                    <input type="hidden" id="total_sizes" name="total_sizes" value="1">
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="d-block">Size</label>
                        <x-error-message inputName="size" />
                    </div>
                    <div class="form-group">
                        <label class="d-block">Paella Option</label>
                        <div class="custom-control custom-switch @error('paella_option') is-invalid @enderror">
                            <input type="checkbox" class="custom-control-input" name="paella_option" {{ (old("paella_option") ? "checked":"") }} id="paella_option">
                            <label class="custom-control-label" id="paella_optiond" for="paella_option">Without Paella</label>
                            <x-error-message inputName="paella_option" />
                        </div>
                    </div>
                    <div class="form-group" id="paella_price_div" style="display:none;">
                        <label class="d-block">Paella Price *</label>
                        <input name="paella_price" id="paella_price" value="{{ old('paella_price') }}" type="number" step="0.01" class="form-control @error('paella_price') is-invalid @enderror" maxlength="250">
                        <x-error-message inputName="paella_price" />
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="row" id="size_table">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Weight (in kilograms)</label>
                                <input class="form-control" type="text" name="weight1" id="weight1">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>No. of Persons (in pax)</label>
                                <input class="form-control" type="text" name="pax1" id="pax1">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Price (in Php)</label>
                                <input class="form-control" type="number" step="0.01" min="0.00" value="0.00" name="price1" id="price1">
                            </div>
                        </div>


                    </div>
                    <div id="options"></div>
                    <a href="#" class="tx-12 tx-semibold" onclick="add_size();">Add another size</a>
                    <hr>
                </div>

                <div class="col-lg-6 mg-t-20">
                    <div class="form-group">
                        <label class="d-block">Upload images</label>
                        <input type="file" id="upload_image" class="image_path" accept="image/*" multiple>
                        <button type="button" class="btn btn-secondary btn-sm upload" id="selectImages">Select images</button>
                        <p class="tx-10">
                            Required image dimension: {{ env('PRODUCT_WIDTH') }}px by {{ env('PRODUCT_HEIGHT') }}px <br /> Maximum file size: 1MB <br /> Required file type: .jpeg .png <br /> Maximum images: 5
                        </p>
                        <div class="prodimg-thumb" id="bannersDiv" style="display: none;">
                            <ul id="banners">
                                <li id="addMoreBanner">
                                    <div class="add-more txt-center upload">
                                        <i data-feather="plus-circle"></i>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="d-block">Tags</label>
                        <input type="text" class="form-control @error('tags') is-invalid @enderror" data-role="tagsinput" name="tags" id="tags" value="{{ old('tags') }}">
                        <x-error-message inputName="tags" />
                    </div>
                    <div class="form-group">
                        <label class="d-block">Visibility</label>
                        <div class="custom-control custom-switch @error('status') is-invalid @enderror">
                            <input type="checkbox" class="custom-control-input" name="status" {{ (old("status") ? "checked":"") }} id="customSwitch1">
                            <label class="custom-control-label" id="label_visibility" for="customSwitch1">Private</label>
                            <x-error-message inputName="status" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="d-block">Display</label>
                        <div class="custom-control custom-switch @error('is_featured') is-invalid @enderror">
                            <input type="checkbox" class="custom-control-input" name="is_featured" {{ (old("is_featured") ? "checked":"") }} id="customSwitch2">
                            <label class="custom-control-label" for="customSwitch2">Featured</label>
                        </div>
                        <x-error-message inputName="is_featured" />
                    </div>
                </div>

                <div class="col-lg-12 mg-t-30">
                    <h4 class="mg-b-0 tx-spacing--1">Manage SEO</h4>
                    <hr>
                </div>

                <div class="col-lg-6 mg-t-30">
                    <div class="form-group">
                        <label class="d-block">Title <code>(meta title)</code></label>
                        <input type="text" class="form-control @error('seo_title') is-invalid @enderror" name="seo_title" value="{{ old('seo_title') }}">
                        <x-error-message inputName="seo_title" />
                        <p class="tx-11 mg-t-4">{{ __('standard.seo.title') }}</p>
                    </div>
                    <div class="form-group">
                        <label class="d-block">Description <code>(meta description)</code></label>
                        <textarea rows="3" class="form-control @error('seo_description') is-invalid @enderror" name="seo_description">{{ old('seo_description') }}</textarea>
                        <x-error-message inputName="seo_description" />
                        <p class="tx-11 mg-t-4">{{ __('standard.seo.description') }}</p>
                    </div>
                    <div class="form-group">
                        <label class="d-block">Keywords <code>(meta keywords)</code></label>
                        <textarea rows="3" class="form-control @error('seo_keywords') is-invalid @enderror" name="seo_keywords">{{ old('seo_keywords') }}</textarea>
                        <x-error-message inputName="seo_keywords" />
                        <p class="tx-11 mg-t-4">{{ __('standard.seo.keywords') }}</p>
                    </div>
                </div>

                <div class="col-lg-12 mg-t-30">
                    <input class="btn btn-primary btn-sm btn-uppercase" type="submit" value="Save Product">
                    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-sm btn-uppercase">Cancel</a>
                </div>

            </div>
        </form>
        <!-- row -->
    </div>

    <div class="modal fade" id="remove-image" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog  modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Remove image?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this image? You cannot undo this action.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="removeImage">Yes, Delete</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="image-details" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog  modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Image details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>File Name</label>
                        <input type="text" class="form-control" value="" disabled id="fileName">
                    </div>
                    <div class="form-group mg-b-0">
                        <label>Alt</label>
                        <input type="text" class="form-control" placeholder="Input alt text" id="changeAlt">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveChangeAlt">Save</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('pagejs')
    <script src="{{ asset('lib/bselect/dist/js/bootstrap-select.js') }}"></script>
    <script src="{{ asset('lib/bselect/dist/js/i18n/defaults-en_US.js') }}"></script>
    <script src="{{ asset('lib/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>
    <script src="{{ asset('lib/bootstrap-tagsinput/bootstrap-tagsinput.min.js') }}"></script>

    <script src="{{ asset('lib/jqueryui/jquery-ui.min.js') }}"></script>

    {{--    Image validation--}}
    <script>
        let BANNER_WIDTH = "{{ env('PRODUCT_WIDTH') }}";
        let BANNER_HEIGHT =  "{{ env('PRODUCT_HEIGHT') }}";
        let MAX_IMAGE =  5;
    </script>
    <script src="{{ asset('js/image-upload-validation.js') }}"></script>
    {{--    End Image validation--}}
@endsection

@section('customjs')
    <script>
        var options = {
            filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
            filebrowserImageUpload: '/laravel-filemanager/upload?type=Images&_token=',
            filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
            filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files&_token',
            allowedContent: true,

        };
        let editor = CKEDITOR.replace('long_description', options);
        editor.on('required', function (evt) {
            if ($('.invalid-feedback').length == 1) {
                $('#long_descriptionRequired').show();
            }
            $('#cke_editor1').addClass('is-invalid');
            evt.cancel();
        });


        $(function() {
            let image_count = 1;
            let objUpload;
            let objRemove;
            $('.selectpicker').selectpicker();

            $("#customSwitch1").change(function() {
                if(this.checked) {
                    $('#label_visibility').html('Published');
                }
                else{
                    $('#label_visibility').html('Private');
                }
            });

            $("#paella_option").change(function() {
                if(this.checked) {
                    $('#paella_optiond').html('With Paella');
                    $("#paella_price").attr("required", "true");
                    $('#paella_price_div').show();
                }
                else{
                    $('#paella_optiond').html('Without Paella');
                    $("#paella_price").attr("required", "false");
                    $('#paella_price_div').hide();
                }
            });

            $("#customSwitch3").change(function() {
                if(this.checked) {
                    $('#label_visibility3').html('Yes');
                    $('#for_sale_web_div').show();
                    $('#for_sale_web').prop('checked', true);
                    $('#for_sale_web_label').html('Yes');
                    $('#for_sale_kiosk_div').show();
                    $('#is_misc_div').show();
                    $('#production_item_div').show();
                }
                else{
                    $('#label_visibility3').html('No');
                    $('#for_sale_web').prop('checked', false);
                    $('#for_sale_web_label').html('No');
                    $('#for_sale_web_div').hide();
                    $('#for_sale_kiosk_div').hide();
                    $('#is_misc_div').hide();
                    $('#production_item_div').hide();
                }
            });
            $("#production_item").change(function() {
                if(this.checked) {
                    $('#production_item_label').html('Yes');
                }
                else{
                    $('#production_item_label').html('No');
                }
            });
            $("#for_sale_web").change(function() {
                if(this.checked) {
                    $('#for_sale_web_label').html('Yes');
                }
                else{
                    $('#for_sale_web_label').html('No');
                }
            });
            $("#is_misc").change(function() {
                if(this.checked) {
                    $('#is_misc_label').html('Yes');
                }
                else{
                    $('#is_misc_label').html('No');
                }
            });
            $("#for_sale_kiosk").change(function() {
                if(this.checked) {
                    $('#for_sale_kiosk_label').html('Yes');
                }
                else{
                    $('#for_sale_kiosk_label').html('No');
                }
            });

            $("#has_installation").change(function() {
                if(this.checked) {
                    $('#installationFeeForm').show();
                    $('#label_has_installation').html('Yes');
                }
                else{
                    $('#installationFeeForm').hide();
                    $('#label_has_installation').html('No');
                }
            });

            $(document).on('click', '.upload', function() {
                objUpload = $(this);
                $('#upload_image').click();
            });

            $('#upload_image').on('change', function (evt) {
                let files = evt.target.files;
                let uploadedImagesLength = $('.productImage').length;
                let totalImages = uploadedImagesLength + files.length;

                if (totalImages > 5) {
                    $('#bannerErrorMessage').html("You can upload up to 5 images only.");
                    $('#prompt-banner-error').modal('show');
                    return false;
                }

                if (totalImages == 5) {
                    $('#addMoreBanner').hide();
                }

                validate_images(evt, upload_image);
            });

            function upload_image(file)
            {
                let data = new FormData();
                data.append("_token", "{{ csrf_token() }}");
                data.append("banner", file);
                $.ajax({
                    data: data,
                    type: "POST",
                    url: "{{ route('products.upload') }}",
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(returnData) {
                        $('#bannersDiv').show();
                        $('#selectImages').hide();
                        console.log(returnData);
                        if (returnData.status == "success") {
                            while ($('input[name="photos['+image_count+'][image_path]"]').length) {
                                image_count += 1;
                            }

                            let radioCheck = (image_count == 1) ? 'checked' : '';

                            $(`<li class="productImage" id="`+image_count+`li">
                                <div class="prodmenu-left" data-toggle="modal" data-target="#image-details" data-id="`+image_count+`" data-name="`+returnData.image_name+`">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal">
                                        <circle cx="12" cy="12" r="1"></circle>
                                        <circle cx="19" cy="12" r="1"></circle>
                                        <circle cx="5" cy="12" r="1"></circle>
                                    </svg>
                                </div>
                                <div class="prodmenu-right" data-toggle="modal" data-target="#remove-image"  data-id="`+image_count+`">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                    </svg>
                                </div>
                                <label class="form-check-label radio" for="exampleRadios`+image_count+`" data-toggle="tooltip" data-placement="bottom" title="Set as primary image">
                                    <input class="form-check-input imageRadio" type="radio" name="is_primary" id="exampleRadios`+image_count+`" value="`+image_count+`" `+radioCheck+`>
                                    <input name="photos[`+image_count+`][image_path]" type="hidden" value="`+returnData.image_url+`">
                                    <input name="photos[`+image_count+`][name]" type="hidden" id="`+image_count+`name" value="">
                                    <img src="`+returnData.image_url+`" />
                                    <div class="radio-button"></div>
                                </label>
                            </li>`).insertBefore('#addMoreBanner');
                        }
                    },
                    failed: function() {
                        alert('FAILED NGA!');
                    }
                });
            }

            let imageId;
            $('#image-details').on('show.bs.modal', function(e) {
                let selectedImage = e.relatedTarget;
                imageId = $(selectedImage).data('id');
                let imageName = $(selectedImage).data('name');
                $('#fileName').val(imageName);
                $('#changeAlt').val($('#'+imageId+"name").val());
            });

            $('#saveChangeAlt').on('click', function() {
                $('#'+imageId+"name").val($('#changeAlt').val());
                $('#image-details').modal('hide');
            });

            $('#image-details').on('hide.bs.modal', function() {
                $('#fileName').val('');
                $('#changeName').val('');
                imageId = 0;
            });

            $('#remove-image').on('show.bs.modal', function(e) {
                let selectedImage = e.relatedTarget;
                imageId = $(selectedImage).data('id');
            });

            $('#removeImage').on('click', function() {
                let isChecked = $('#exampleRadios'+imageId).is(':checked');
                $('#'+imageId+"li").remove();
                $('#addMoreBanner').show();
                if (isChecked) {
                    $.each($('.imageRadio'), function () {
                        $(this).prop('checked', true);
                        return false;
                    });
                }
                $('#remove-image').modal('hide');
            });

            $('#image-details').on('hide.bs.modal', function() {
                imageId = 0;
            });

            // $(document).ready(function() {
            //     $('.bootstrap-tagsinput input').prop('required', true);
            // });

            // $('#colors, #sizes').on('itemAdded', function(event) {
            //     $(this).prev().find("input").removeAttr("required");
            // });
            //
            // $('#colors, #sizes').on('itemRemoved', function(event) {
            //     var countItem = $(this).prev().find("span").length;
            //
            //     if(countItem == 0) {
            //         $(this).prev().find("input").prop('required', true)
            //     }
            // });
        });

        function add_size(){

            var n = parseInt($('#total_sizes').val()) + 1;
            // var n = parseInt($("#size_table > div").length) + 1;
            $('#options').append('<div class="row">'+

                '<div class="col-md-3"><div class="form-group"><input class="form-control" type="text" name="weight'+n+'" id="weight'+n+'"></div></div>'+
                '<div class="col-md-2"><div class="form-group"><input class="form-control" type="text" name="pax'+n+'" id="pax'+n+'"></div></div>'+
                '<div class="col-md-2"><div class="form-group"><input class="form-control" type="number" step="0.01" min="0.01" value="0.00" name="price'+n+'" id="price'+n+'"></div></div>'+

            '</div>');
            $('#total_sizes').val(n);

        }
    </script>
@endsection
