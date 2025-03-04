<div class="news-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="news-content">
                    <h2>Blogs</h2>
                </div>

                <div class="news-slider owl-carousel">
                    @foreach ($featuredNews as $news)
                        @php
                            $url = asset('news/'.$news->slug);
                            $target = '';
                            if($news->is_blog==1){
                                $url = $news->external_link;
                                $target = 'target="_blank"';
                            }
                        @endphp
                        <div class="news-box">
                            @if (!empty($news->image_url))
                                <div class="news-thumbnail">
                                    <a href="{{ $url }}" {{ $target }}><img src="{{ $news->thumbnail_url }}" /></a>
                                </div>
                            @else
                                <div class="news-thumbnail">
                                    <a href="{{ $url }}" {{ $target }}><img src="{{ $news->thumbnail_url }}" /></a>
                                </div>                            
                            @endif
                            <div class="news-category">
                                <a href="{{ $url }}" {{ $target }}><span>{{ $news->category->name }}</span></a>
                            </div>
                            <div class="news-cap">
                                <a href="{{ $url }}" {{ $target }}>{{ $news->name }}</a>
                                <a href="{{ $url }}" {{ $target }}>
                                    <p class="news-date">
                                        <span class="far fa-calendar-alt"></span> {{  date("F d, Y",strtotime($news->date))}}
                                    </p>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="news-content mt-5 mb-0">
                    <a href="{{ route("news.front.index") }}" class="link-secondary hvr-shrink">View more blogs</a>
                </div>
            </div>
        </div>
    </div>
</div>
