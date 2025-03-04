<div class="sub-banner">
    <div class="sub-items">
        <h2>{{ $page->name }}</h2>
        @if(isset($breadcrumb))
            <ol class="breadcrumb">
                @foreach($breadcrumb as $link => $url)
                    <li class="breadcrumb-item"> @if($page->name != $link)<a href="{{$url}}">{{$link}}</a> @else {{$link}} @endif</li>
{{--                <li class="breadcrumb-item active" aria-current="page"><a href="{{$url}}" @if($page->name == $link)class="text-white text-decoration-none"@endif>{{$link}}</a></li>--}}
                @endforeach
            </ol>
        @endif
    </div>
    <img src="{{ $page->image_url }}"/>
</div>
