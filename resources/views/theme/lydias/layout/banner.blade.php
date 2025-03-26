@if(isset($page) && $page->album && count($page->album->banners) > 0 && $page->album->is_main_banner() && $page->album->banner_type == 'video')
    @include('theme.'.config('app.frontend_template').'.layout.home-slider-video')
@elseif(isset($page) && $page->album && count($page->album->banners) > 0 && $page->album->is_main_banner())
    @include('theme.'.config('app.frontend_template').'.layout.home-slider')
@elseif(isset($page) && $page->album && count($page->album->banners) > 1 && !$page->album->is_main_banner())
    @include('theme.'.config('app.frontend_template').'.layout.page-slider')
@elseif(isset($page) && !empty($page->image_url))
    @include('theme.'.config('app.frontend_template').'.layout.page-banner')

@else
	<div class="sub-banner">
		<div class="sub-items">
            @if (!empty($page->name) || !empty($page->meta_title))
            <h2>{{ (empty($page->meta_title) ? $page->name:$page->meta_title) }}</h2>
			<nav aria-label="breadcrumb">
			  <ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="#">Home</a></li>
				<li class="breadcrumb-item active" aria-current="page">{{ (empty($page->meta_title) ? $page->name:$page->meta_title) }}</li>
			  </ol>
			</nav>
            @endif
		</div>
		<img src="{{ asset('images/banners/subimage1.jpg') }}" />
	</div>
@endif
