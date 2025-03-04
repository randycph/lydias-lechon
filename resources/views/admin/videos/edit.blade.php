@extends('admin.layouts.app')

@section('pagetitle')
    Update Video
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
					<li class="breadcrumb-item active" aria-current="page">Edit a Video</li>
				</ol>
			</nav>
            <h4 class="mg-b-0 tx-spacing--1">Edit a Video</h4>
		</div>
	</div>
	<form method="post" action="{{ route('videos.update',$video->id) }}" enctype="multipart/form-data">
		<div class="row row-sm">
			<div class="col-lg-6">
                @method('PUT')
                @csrf
				<div class="form-group">
					<label class="d-block">Title *</label>
					<input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" value="{{ old('name',$video->title) }}" required>
					<small id="category_slug"><a target="_blank" href="{{ $video->get_url() }}">{{ $video->get_url() }}</a></small>
					@error('name')
					    <div class="alert alert-danger">{{ $message }}</div>
					@enderror
				</div>
				<div class="form-group">
                    <label class="d-block">Category *</label>
                    <select required name="category" id="category" class="form-control @error('category') is-invalid @enderror">
                        @foreach($categories as $category)
                            <option value="{{$category->id}}" {{ $category->id == $video->video_category ? "selected":"" }}>{{strtoupper($category->name)}}</option>
                        @endforeach
                    </select>
                    @error('category')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>

				<div class="form-group {{ $errors->has('thumbnail') ? 'has-error' : '' }}">
					<label class="d-block">Thumbnail</label>
					<div class="custom-file">
						<input type="file" class="custom-file-input @error('thumbnail_file') is-invalid @enderror" name="thumbnail_file" id="thumbnail_file" @if(!empty($video->thumbnail)) title="{{$video->get_thumbnail_file_name()}}" disabled @endif>
                            <label class="custom-file-label" for="thumbnail_file" id="thumbnail_name">@if (empty($video->thumbnail)) Choose file @else {{$video->get_thumbnail_file_name()}} @endif</label>
                            <input type="hidden" name="old_thumbnail" value="{{$video->thumbnail}}">
					</div>
					<p class="tx-10">
						Maxmimum file size: 6GB <br /> Required file type: .mp4
					</p>
					@error('news_image')
						<div class="alert alert-danger">{{ $message }}</div>
					@enderror
					<div class="wd-100p" id="temp_thumbnail_div" style="display:none;">
						<img src="" height="150" width="270" id="thumbnail_temp" alt="Thumbnail">  <br /><br />
					</div>

					<div class="wd-100p" id="thumbnail_div" @if(empty($video->thumbnail)) style="display:none;" @endif>
						<img src="{{ asset('storage/videos/'.$video->id.'/'.$video->thumbnail) }}" height="150" width="270" id="thumbnail" alt="Thumbnail"><br/><br/>
						<a href="javascript:void(0)" class="btn btn-xs btn-danger" onclick="remove_thumbnail();">Remove Thumbnail</a>
					</div>
				</div>

				<div class="form-group {{ $errors->has('file') ? 'has-error' : '' }}">
					<label class="d-block">Video <span id="span_req" @if(!empty($video->video_url)) style="display:none;" @endif>*</span> </label>
					<div class="custom-file">
						<input type="file" class="custom-file-input @error('video_file') is-invalid @enderror" name="video_file" id="video_file" @if(!empty($video->video_url)) title="{{$video->get_video_file_name()}}" disabled @endif>
                            <label class="custom-file-label" for="video_file" id="vid_name">@if (empty($video->video_url)) Choose file @else {{$video->get_video_file_name()}} @endif</label>
                            <input type="hidden" name="old_video" id="old_video" value="{{$video->video_url}}">
					</div>
					<p class="tx-10">
						Maximum file size: 6GB <br /> Required file type: .mp4
					</p>
					@error('news_image')
						<div class="alert alert-danger">{{ $message }}</div>
					@enderror
					<video class="wd-100p" controls id="temp_video_div" style="display:none;">
						<source src="" id="vid_temp" type="video/mp4">
					</video>

					<div id="video_div" @if(empty($video->video_url)) style="display:none;" @endif>
						<video class="wd-100p" controls>
                            <source src="{{ asset('storage/videos/'.$video->id.'/'.$video->video_url) }}" id="vid_file" type="video/mp4">
                        </video>
						<a href="javascript:void(0)" class="btn btn-xs btn-danger" onclick="remove_video();">Remove Video</a>
					</div>
					<input type="hidden" id="duration" name="duration">
				</div>

				<div class="form-group">
					<label class="d-block">Description </label>
					<textarea name="description" cols="30" rows="10" class="form-control">{{ old('description',$video->description) }}</textarea>
					@error('description')
					    <div class="alert alert-danger">{{ $message }}</div>
					@enderror
				</div>

				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label class="d-block">Tags </label>
							<input type="text" class="wd-100p form-control @error('tags') is-invalid @enderror" data-role="tagsinput" name="tags" id="tags" value="{{ old('tags',$video->tags) }}">
		                    @error('tags')
		                        <div class="alert alert-danger">{{ $message }}</div>
		                    @enderror
						</div>
					</div>
				</div>


				<div class="form-group">
					<label class="d-block">Visibility</label>
					<div class="custom-control custom-switch @error('visibility') is-invalid @enderror">
						<input type="checkbox" class="custom-control-input" name="visibility" {{ ($video->status == 'PUBLISHED' ? "checked":"") }} id="customSwitch1">
						<label class="custom-control-label" id="label_visibility" for="customSwitch1">{{ucwords($video->status)}}</label>
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
	var myVideoPlayer = document.getElementById('temp_video_div'),
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
    // $("#employees").attr("required",true);
    $(document).on('click', '.remove-video', function() {
        $('#prompt-remove-video').modal('show');

        $('#v_id').val($(this).data('id'));
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
		$('#temp_thumbnail_div').show();
	}

	$("#thumbnail_file").change(function(evt) {
		validate_images(evt, readThumbnail);
	});

	function readURL(file) {
        //Set File Name
        let fileName = $("#video_file").val().split("\\");
        $('#vid_name').html(fileName[fileName.length-1]);

        //Set Video Preview
        let videoElement = $('#vid_temp');
        videoElement[0].src = window.URL.createObjectURL(file);
        videoElement.parent()[0].load();
        $('#temp_video_div').show();
	}

	$("#video_file").change(function(evt) {
		validate_videos(evt, readURL);
	});

	function remove_video(){
		$('#vid_name').html('Choose file');
		$('#video_file').removeAttr('title');
		$('#video_file').val('');
		$('#vid_file').attr('src', '');
		$('#video_div').hide();
		$('#span_req').show();
		$("#video_file").attr("required",true);
		$("#video_file").attr("disabled",false);
	}

	function remove_thumbnail(){
		$('#thumbnail_name').html('Choose file');
		$('#thumbnail_file').removeAttr('title');
		$('#thumbnail_file').val('');
		$('#thumbnail').attr('src', '');
		$('#thumbnail_div').hide();
		$("#thumbnail_file").attr("disabled",false);
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
