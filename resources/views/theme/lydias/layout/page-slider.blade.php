<div class="sub-banner">
    <div class="sub-items">
        <h2>{{ $page->name }}</h2>
        <nav aria-label="breadcrumb">
            @if(isset($breadcrumb))
            <ol class="breadcrumb">
                @foreach($breadcrumb as $link => $url)
                    <li class="breadcrumb-item"> @if($page->name != $link)<a href="{{$url}}">{{$link}}</a> @else {{$link}} @endif</li>
{{--                <li class="breadcrumb-item"><a href="{{$url}}" @if($page->name == $link)class="text-white text-decoration-none"@endif>{{$link}}</a></li>--}}
                @endforeach
            </ol>
            @endif
        </nav>
    </div>
    <div class="sub-banner-img">
        @foreach ($page->album->banners as $banner)
        <div><img src="{{ $banner->image_path }}" /></div>
        @endforeach
    </div>
</div>
