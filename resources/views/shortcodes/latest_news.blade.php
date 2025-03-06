<div>
    <h5 class="blog-sidebar-title uppercase">Recent Posts</h5>
    <ul class="media-list main-list">';
        @foreach($latest_news as $latest)
            @php $thumb = $latest->thumbnail_url; @endphp
            @if(empty($latest->thumbnail_url))
                @php $thumb = URL::to('/').'/storage/products/0/no_image_available.png'; @endphp
            @endif
            @php $url = 'href="'.$latest->slug.'"'; @endphp
            @if($latest->is_blog == 1)
                @php $url = 'href="'.$latest->external_link.'" target="_blank"'; @endphp
            @endif
            <li class="media">
                <a class="pull-left" {!! $url !!}>
                    <img class="media-object" style="height:50px;" src="{!! $thumb !!}" alt="...">
                </a>
                <div class="media-body">
                    <a {!! $url !!}><h4 class="media-heading">{!! $latest->name !!}</h4></a>
                    <p class="by-author">{!! $latest->created_at->diffForHumans() !!}</p>
                </div>
            </li>
        @endforeach
    </ul>
</div>