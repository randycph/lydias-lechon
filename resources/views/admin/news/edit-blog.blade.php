@extends('admin.layouts.app')

@section('pagetitle')
    Update Article
@endsection

@section('pagecss')
    <link href="{{ asset('lib/bselect/dist/css/bootstrap-select.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/bootstrap-tagsinput/bootstrap-tagsinput.css') }}" rel="stylesheet">
    <script src="{{ asset('lib/ckeditor/ckeditor.js') }}"></script>
@endsection

@section('content')

    <div class="container pd-x-0">
        <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                        <li class="breadcrumb-item" aria-current="page"><a href="{{route('dashboard')}}">CMS</a></li>
                        <li class="breadcrumb-item" aria-current="page"><a href="{{route('news.index')}}">News</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit a Blog</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0 tx-spacing--1">Edit a Blog</h4>
            </div>
            <div>
                <a class="btn btn-outline-primary btn-sm" href="{{$article->external_link}}" target="_blank">Preview Blog</a>
            </div>
        </div>
        <form id="editForm" method="post" action="{{ route('blogs.update',$article->id) }}" enctype="multipart/form-data">
            @csrf
            @method('POST')
            <input name="is_blog" type="hidden" value="1">
            <div class="row row-sm">
                <div class="col-lg-6">

                    @csrf
                    <input type="hidden" name="id" value="{{ $article->id }}">

                    <div class="form-group">
                        <label class="d-block">Title *</label>
                        <input type="text" class="form-control @error('news_title') is-invalid @enderror" maxlength="150" name="news_title" id="news_title" value="{{ old('news_title',$article->name) }}" required>
                        @hasError(['inputName' => 'news_title'])
                        @endhasError
                    </div>
                    <div class="form-group">
                        <label class="d-block">Date *</label>
                        <input type="date" class="form-control @error('date') is-invalid @enderror" name="date" id="date" value="{{ old('date',$article->date) }}">
                        @hasError(['inputName' => 'date'])
                        @endhasError
                    </div>
                    <div class="form-group">
                        <label class="d-block">Category</label>
                        <select id="category" class="selectpicker mg-b-5 @error('category') is-invalid @enderror" name="category" data-style="btn btn-outline-light btn-md btn-block tx-left" title="- None -" data-width="100%">
                            <option value="0" @if (empty($article->category_id)) selected @endif>- None -</option>
                            @forelse($categories as $category)
                                <option value="{{$category->id}}" {{(old("category", $article->category_id) == $category->id ? "selected":"") }} >{{strtoupper($category->name)}}</option>
                            @empty
                            @endforelse
                        </select>
                        @hasError(['inputName' => 'category'])
                        @endhasError
                    </div>
                    <div class="form-group">
                        <label class="d-block">Article thumbnail *</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input @error('news_thumbnail') is-invalid @enderror" name="news_thumbnail" id="news_image" @if (!empty($article->news_thumbnail)) title="{{$article->get_image_file_name()}}" @endif>
                            <label class="custom-file-label" for="news_thumbnail" id="img_name">@if (empty($article->news_thumbnail)) Choose file @else {{$article->get_image_file_name()}} @endif</label>
                        </div>
                        <p class="tx-10">
                            Required image dimension: {{ env('NEWS_THUMBNAIL_WIDTH') }}px by {{ env('NEWS_THUMBNAIL_HEIGHT') }}px <br /> Maximum file size: 1MB <br /> Required file type: .jpeg .png
                        </p>
                        @hasError(['inputName' => 'news_thumbnail'])
                        @endhasError
                        <div id="image_div" @if(empty($article->thumbnail_url)) style="display:none;" @endif>                          
                            <img src="{{ $article->thumbnail_url }}" height="100" width="150" id="img_temp" alt="">  <br /><br />
                            <a href="javascript:void(0)" class="btn btn-sm btn-danger remove-upload" >Remove Image</a>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="d-block">External Link *</label>
                        <input type="text" class="form-control @error('external_link') is-invalid @enderror" maxlength="150" name="external_link" id="news_title" value="{{ old('external_link',$article->external_link) }}" required>
                        @hasError(['inputName' => 'external_link'])
                        @endhasError
                    </div>
                    <div class="form-group">
                        <label class="d-block">Teaser *</label>
                        <textarea class="form-control @error('teaser') is-invalid @enderror" name="teaser" rows="4" required>{{ old('teaser', $article->teaser) }}</textarea>
                        @error('teaser')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label class="d-block">Page Visibility</label>
                        <div class="custom-control custom-switch @error('visibility') is-invalid @enderror">
                            <input type="checkbox" class="custom-control-input" name="visibility" {{ (((old("visibility", strtoupper($article->status)) == "ON") || (old("visibility", strtoupper($article->status)) == "PUBLISHED")) ? "checked":"") }} id="customSwitch1">
                            <label class="custom-control-label" id="label_visibility" for="customSwitch1">{{ (((old("visibility", strtoupper($article->status)) == "ON") || (old("visibility", strtoupper($article->status)) == "PUBLISHED")) ? "Published":"Private") }}</label>
                        </div>
                        @error('visibility')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>


                    <div class="form-group">
                        <label class="d-block">Display</label>
                        <div class="custom-control custom-switch @error('is_featured') is-invalid @enderror">
                            <input type="checkbox" class="custom-control-input" name="is_featured" {{ (old("is_featured",$article->is_featured) ? "checked":"") }} {{ ((strtoupper($article->is_featured) == '1') ? "checked":"") }} id="customSwitch2">
                            <label class="custom-control-label" for="customSwitch2">Featured</label>
                        </div>
                        @error('is_featured')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-lg-12 mg-t-30">
                    <button class="btn btn-primary btn-sm btn-uppercase" type="submit">Update News</button>
                    <a href="{{ route('news.index') }}" class="btn btn-outline-secondary btn-sm btn-uppercase">Cancel</a>
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
@endsection

@section('pagejs')
    <script src="{{ asset('lib/bselect/dist/js/bootstrap-select.js') }}"></script>
    <script src="{{ asset('lib/jqueryui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('lib/bootstrap-tagsinput/bootstrap-tagsinput.min.js') }}"></script>

    {{--    Image validation--}}
    <script>
        let BANNER_WIDTH = "{{ env('NEWS_THUMBNAIL_WIDTH') }}";
        let BANNER_HEIGHT =  "{{ env('NEWS_THUMBNAIL_HEIGHT') }}";
    </script>
    <script src="{{ asset('js/image-upload-validation.js') }}"></script>
    {{--    End Image validation--}}
@endsection

@section('customjs')
    <script>
        // Replace the <textarea id="editor1"> with a CKEditor
        // instance, using default configuration.
        var options = {
            filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
            filebrowserImageUpload: '/laravel-filemanager/upload?type=Images&_token=',
            filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
            filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files&_token',
            allowedContent: true,
        };
       

        $(function() {
            $('.selectpicker').selectpicker();
        });

        // document.getElementById("category").selectedIndex = -1;
        // $('#category').on('change', function() {
        //     if ($(this).val() == 0) {
        //         document.getElementById("category").selectedIndex = -1;
        //     }
        // });

        // $('#news-date').datepicker({
        //     changeMonth: true,
        //     changeYear: true
        // });

        $(function() {
            $("#customSwitch1").change(function () {
                if (this.checked) {
                    $('#label_visibility').html('Published');
                } else {
                    $('#label_visibility').html('Private');
                }
            });

            /** Generation of the page slug **/
            $('#news_title').change(function () {
                let url = $('#news_title').val().trim();

                if (url == "{{ $article->name }}") {
                    $('#news_slug').html("{{ $article->get_url() }}");
                    return false;
                }

                $.ajax({
                    type: "POST",
                    url: "{{ route('news.get-slug') }}",
                    data: {url: url, _token: "{{ csrf_token() }}"}
                }).done(function (response) {
                    slug_url = '{{env('APP_URL')}}/news/' + response;
                    $('#news_slug').html("<a target='_blank' href='" + slug_url + "'>" + slug_url + "</a>");
                });
            });
        });
    </script>
    <script>
        function readURL(file) {
            let reader = new FileReader();

            reader.onload = function(e) {
                $('#img_name').html(file.name);
                $('#news_image').attr('title', file.name);
                $('#img_temp').attr('src', e.target.result);
            }

            reader.readAsDataURL(file);
            $('#image_div').show();
        }

        $("#news_image").change(function(evt) {
            validate_images(evt, readURL);
        });

        $(document).on('click', '.remove-upload', function() {
            $('#prompt-remove').modal('show');
        });

        $('#btnRemove').on('click', function() {
            $('#editForm').prepend('<input type="hidden" name="delete_image" value="1"/>');
            $('#img_name').html('Choose file');
            $('#news_image').removeAttr('title');
            $('#news_image').val('');
            $('#img_temp').attr('src', '');
            $('#image_div').hide();
            $('#prompt-remove').modal('hide');
        });

    </script>
@endsection
