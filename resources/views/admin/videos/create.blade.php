@extends('admin.layouts.app')

@section('pagetitle')
    Create New Video
@endsection

@section('pagecss')
	<link href="{{ asset('lib/bselect/dist/css/bootstrap-select.css') }}" rel="stylesheet">
	<link rel="stylesheet" href="{{ asset('lib/bootstrap-tagsinput/bootstrap-tagsinput.css') }}">
@endsection

@section('content')

<div class="container pd-x-0">
	<div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
		<div>
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb breadcrumb-style1 mg-b-10">
                    <li class="breadcrumb-item" aria-current="page"><a href="{{route('dashboard')}}">CMS</a></li>
					<li class="breadcrumb-item" aria-current="page"><a href="{{route('videos.index')}}">Videos</a></li>
					<li class="breadcrumb-item active" aria-current="page">Create a Video</li>
				</ol>
			</nav>
            <h4 class="mg-b-0 tx-spacing--1">Create a Video</h4>
		</div>
	</div>
	<form method="post" action="{{ route('videos.store') }}" enctype="multipart/form-data">
		<div class="row row-sm">
			<div class="col-lg-6">
				@csrf
				<div class="form-group">
					<label class="d-block">Title *</label>
					<input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" value="{{ old('name') }}" required>
					<small id="category_slug"></small>
					@error('name')
					    <div class="alert alert-danger">{{ $message }}</div>
					@enderror
				</div>
				<div class="form-group">
                    <label class="d-block">Category *</label>
                    <select required name="category" id="category" class="form-control @error('category') is-invalid @enderror">
                        <option value="">- Select Category -</option>
                        @forelse($categories as $category)
                            <option value="{{$category->id}}">{{strtoupper($category->name)}}</option>
                        @empty
                        @endforelse
                    </select>
                    @error('category')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>

				<div class="form-group {{ $errors->has('file') ? 'has-error' : '' }}">
					<label class="d-block">Thumbnail</label>
					<div class="custom-file">
						<input name="thumbnail" type="file" class="custom-file-input" id="thumbnail_file">
                        <label class="custom-file-label" for="customFile">Choose file</label>
						<span class="text-danger tx-12">{{ $errors->first('file') }}</span>
					</div>
					<p class="tx-10">
					Required image dimension: {{ env('THUMBNAIL_WIDTH') }}px by {{ env('THUMBNAIL_HEIGHT') }}px <br /> Maximum file size: 2MB <br /> Required file type: .jpeg .png
					</p>
					<div class="wd-100p" id="thumbnail_div" style="display:none;">
						<img src="" height="150" width="270" id="thumbnail_temp" alt="Thumbnail">  <br /><br />
					</div>
				</div>

				<div class="form-group {{ $errors->has('file') ? 'has-error' : '' }}">
					<label class="d-block">Video *</label>
					<div class="custom-file">
						<input required name="file" type="file" class="custom-file-input" id="video_file">
                        <label class="custom-file-label" for="customFile" id="vid_name">Choose file</label>
						<span class="text-danger tx-12">{{ $errors->first('file') }}</span>
					</div>
					<p class="tx-10">
						Maximum file size: 6 GB <br /> File extension: .mp4
					</p>
					<video class="wd-100p" controls id="video_div" style="display:none;">
						<source src="" id="vid_temp" type="video/mp4">
					</video>
					<input type="hidden" id="duration" name="duration">
				</div>

				<div class="form-group">
					<label class="d-block">Description </label>
					<textarea name="description" cols="30" rows="10" class="form-control">{{ old('description') }}</textarea>
					@error('description')
					    <div class="alert alert-danger">{{ $message }}</div>
					@enderror
				</div>

				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label class="d-block">Tags</label>
							<input type="text" class="wd-100p form-control @error('tags') is-invalid @enderror" data-role="tagsinput" name="tags" id="tags" value="{{ old('tags') }}">
		                    @error('tags')
		                        <div class="alert alert-danger">{{ $message }}</div>
		                    @enderror
						</div>
					</div>
				</div>


				<div class="form-group">
					<label class="d-block">Visibility</label>
					<div class="custom-control custom-switch @error('visibility') is-invalid @enderror">
						<input type="checkbox" class="custom-control-input" name="visibility" {{ (old("visibility") ? "checked":"") }} id="customSwitch1">
						<label class="custom-control-label" id="label_visibility" for="customSwitch1">Private</label>
					</div>
					@error('visibility')
					    <div class="alert alert-danger">{{ $message }}</div>
					@enderror
				</div>

			</div>

			<div class="col-lg-12 mg-t-30">
				<input class="btn btn-primary btn-sm btn-uppercase" type="submit" value="Save Video">
				<a href="{{ route('videos.index') }}" class="btn btn-outline-secondary btn-sm btn-uppercase">Cancel</a>
			</div>
		</div>
	</form>
</div>
@endsection

@section('pagejs')
	<script src="{{ asset('lib/bselect/dist/js/bootstrap-select.js') }}"></script>
    <script src="{{ asset('lib/bselect/dist/js/i18n/defaults-en_US.js') }}"></script>
    <script src="{{ asset('lib/bootstrap-tagsinput/bootstrap-tagsinput.min.js') }}"></script>

	<script>
        let THUMBNAIL_WIDTH  = "{{ env('THUMBNAIL_WIDTH') }}";
        let THUMBNAIL_HEIGHT = "{{ env('THUMBNAIL_HEIGHT') }}";
    </script>
	<script src="{{ asset('js/video-upload-validation.js') }}"></script>
@endsection


@section('customjs')
<script>
	var myVideoPlayer = document.getElementById('video_div'),
		meta = document.getElementById('duration');

	myVideoPlayer.addEventListener('loadedmetadata', function () {
		var duration = myVideoPlayer.duration;
		meta.value = duration;
	});

	$(function() {
		$('.selectpicker').selectpicker();
	});

	$("#customSwitch1").change(function() {
		if(this.checked) {
			$('#label_visibility').html('PUBLISHED');
		}
		else{
			$('#label_visibility').html('PRIVATE');
		}
	});

	/** Generation of the page slug **/
	function get_page_slug() {
		var url = $('#name').val();
		var parentPage = $('#parentPage').val();
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content")
			}
		})

		$.ajax({
			type: "POST",
			url: "/admin/pages/get-slug",
			data: {url: url, parentPage: parentPage}
		})
		.done(function (response) {

			slug_url = '{{env('APP_URL')}}/' + response;
			$('#page_slug').html("<a target='_blank' href='" + slug_url + "'>" + slug_url + "</a>");

		});
	}

	$('#name').change(function(){
		get_page_slug();
	});
</script>

<script>
	function readThumbnail(file) {
		let reader = new FileReader();

		reader.onload = function(e) {
			$('#thumbnail_temp').attr('src', e.target.result);
		}

		reader.readAsDataURL(file);
		$('#thumbnail_div').show();
	}

	$("#thumbnail_file").change(function(evt) {
		validate_images(evt, readThumbnail);
	});


	$("#video_file").change(function(evt) {
		validate_videos(evt, readURL);
	});

	function readURL(file) {
	    //Set File Name
        let fileName = $("#video_file").val().split("\\");
        $('#vid_name').html(fileName[fileName.length-1]);

        //Set Video Preview
        let videoElement = $('#vid_temp');
        videoElement[0].src = window.URL.createObjectURL(file);
        videoElement.parent()[0].load();
        $('#video_div').show();
	}

	/** Generation of the page slug **/
	jQuery('#name').change(function(){

		var url = $('#name').val();

		$.ajaxSetup({
			headers:{
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content")
			}
		})

		$.ajax({
			type: "POST",
			url: "/admin/videos/get-slug",
			data: { url: url }
		})

		.done(function(response){
			slug_url = '{{env('APP_URL')}}/'+response;
			$('#category_slug').html("<a target='_blank' href='"+slug_url+"'>"+slug_url+"</a>");
		});
	});
    </script>
@endsection
