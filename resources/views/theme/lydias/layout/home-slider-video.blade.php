{{-- <div class="video-banner">
    @foreach ($page->album->banners as $banner)
        <video autoplay muted loop id="myVideo">
            <source src="{{ $banner->image_path }}" type="video/mp4">
        </video>
        <div class="container">
            <div class="video-caption">
                <h2>{{ $banner->title }}</h2>
                <p>{{ $banner->description }}</p>
                @if($banner->url && $banner->button_text)
                    <a href="{{ $banner->url }}" class="link-primary hvr-shrink">{{ $banner->button_text }}</a>
                @endif
            </div>
        </div>
        @break
    @endforeach
</div> --}}

<div class="video-player">
    <div class="video-txt d-flex align-items-center">
        <div class="container">
            {{-- <p class="slide_text" id="slide1">Everyday Lechon Happiness</p>
            <p class="slide_text" id="slide2">Best-Tasting Charcoal Roast Lechon</p>
            <p class="slide_text" id="slide3">55 Years Roasting; The Philippine’s Lechon Pride</p> --}}
            <div class="one-time">
              <div class="banner-wrapper">
                <div class="banner-text">
                    <div class="jumbotron banner-welcome p-0 m-0 bg-transparent">
                      <h2 class="display-4">Everyday Lechon Happiness</h2>
                    </div>
                </div>
              </div>
              
              <div class="banner-wrapper">
                <div class="banner-text">
                    <div class="jumbotron banner-welcome p-0 m-0 bg-transparent">
                      <h2 class="display-4">Best-Tasting Charcoal Roast Lechon</h2>
                    </div>
                </div>
              </div>
              
              <div class="banner-wrapper">
                <div class="banner-text">
                    <div class="jumbotron banner-welcome p-0 m-0 bg-transparent">
                      <h2 class="display-4">55 Years Roasting, Philippine’s Lechon Pride</h2>
                   {{--    <p class="lead">The Philippines's Lechon Pride</p> --}}
                    </div>
                </div>
              </div>
            </div>
            
        </div>
    </div>
    @foreach ($page->album->banners as $banner)
      <div class="video-vid">
          <video autoplay muted loop id="myVideo">
            <source src="{{ $banner->image_path }}" type="video/mp4">
          </video>
      </div>
    @endforeach
</div>

    
