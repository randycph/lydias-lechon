<div class="banner-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12" style="padding:0;">
                <div id="banner" class="carousel-banner owl-carousel owl-theme banner-content">
                @foreach ($page->album->banners as $banner)
                        <div>
                            <img src="{{ $banner->image_path }}" alt="{{ $banner->alt }}">
                            <div class="banner-caption">
                                <h2>{{ $banner->title }}</h2>
                                <p>{{ $banner->description }}</p>
                                @if($banner->url && $banner->button_text)
                                    <a class="link-primary hvr-shrink" href="{{ $banner->url }}">{{ $banner->button_text }}</a>
                                @endif
                            </div>
                        </div>
                @endforeach
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
