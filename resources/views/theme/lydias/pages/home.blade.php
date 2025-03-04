@extends('theme.'.env('FRONTEND_TEMPLATE').'.main')

@section('pagecss')
<link rel="stylesheet" href="{{ asset('theme/lydias/plugins/slick/slick.css') }}" />
<link rel="stylesheet" href="{{ asset('theme/lydias/plugins/slick/slick-theme.css') }}" />
<style>
	.slide_text{
		font-size: calc(30px + (26 - 14) * ((100vw - 300px) / (1600 - 300))) !important;
  		line-height: calc(1.3em + (1.5 - 1.2) * ((100vw - 300px)/(1600 - 300))) !important;  		
  		font-family: "Trebuchet MS", Helvetica, sans-serif !important;
  		display:none;
	}
</style>
@endsection

@section('content')
	<div aria-hidden="true" aria-labelledby="exampleModalCenterTitle" class="modal fade onload-page" id="HomeModal" role="dialog" tabindex="-1">
		<div class="modal-dialog modal-dialog-centered modal-md" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
				</div>
				<div class="modal-body p-1">
					<a href="#" onclick="show_delivery_option_popup()"><img src="{{ asset('images/order/pop-up.jpg') }}" /></a>
				</div>
			</div>
		</div>
	</div>
    <main>    		   
        {!! $page->contents !!}
    </main>
@endsection

@section('jsscript')
<script>
	$(document).ready(function(){
		var width = $(window).width();
		var height = $(window).height();
		
		//$(".video-vid").height(height);
		
		$('.one-time').slick({
	        dots: false,
	        infinite: true,
	        speed: 500,
	        arrows: false,
	        draggable: false,
	        slidesToShow: 1,
	        autoplay: true,
	        fade: true,
	        adaptiveHeight: true
	      });
	});

</script>
  <script type="text/javascript" src="{{ asset('theme/lydias/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('theme/lydias/plugins/slick/slick.min.js') }}"></script>
	<script>
		$(document).ready(function(){
			setTimeout(show_image_popup, 5000);
			// $('#slide1').show();
			// setTimeout(show_slide2, 6000);
			// setTimeout(show_slide3, 10000);
		});

	function show_image_popup(){
		$("#HomeModal").modal('show');
	}
	// function show_slide2(){

	// 	$('#slide1').slideUp( "slow", function() {
	// 	    $('#slide2').show();
	// 	});
		
	// }
	// function show_slide3(){
	// 	$('#slide2').slideUp( "slow", function() {
	// 	    $('#slide3').show();
	// 	});
	// }

	 function show_delivery_option_popup(){

	 	$("#HomeModal").modal('hide');
	 	$("#terms-modal").modal('show');
	 }

	</script>
	<script type='text/javascript' src='https://platform-api.sharethis.com/js/sharethis.js#property=5f082f6581808f00125031c5&product=inline-share-buttons&cms=website' async='async'></script>
@endsection

