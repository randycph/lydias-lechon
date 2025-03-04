@extends('theme.'.env('FRONTEND_TEMPLATE').'.main')

@section('pagecss')
    <link rel="stylesheet" href="{{ asset('theme/lydias/plugins/jssocials/jssocials-theme-flat.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('theme/lydias/plugins/jssocials/jssocials.css') }}" />
    <style>
        .blog-content-2 .blog-single-sidebar .blog-sidebar-title {
            font-weight: 600;
            font-size: 14px;
            color: #4e5a64;
            letter-spacing: 1px;
            margin-top: 40px;
            margin-bottom: 30px;
        }
        .uppercase {
            text-transform: uppercase!important;
            font-size: 14px;
        }
        .card-img-top {
            width: 100%;
            height: 15vw;
            object-fit: cover;
        }
    </style>
@endsection

@section('content')
    <main>
        <section>
            <div class="content-wrapper">
                <div class="gap-70"></div>
                <div class="container">
                <div class="row">
                    <div class="col-lg-3">
                       
                        <div class="article-widget-title blog-sidebar-title uppercase">News</div>
                        <div class="gap-10"></div>
                        <div class="side-menu">
                            {!! $dates !!}
                        </div>
                        <div class="article-widget-title  blog-sidebar-title uppercase">Categories</div>
                        <div class="gap-10"></div>
                        <div class="side-menu">
                            {!! $categories !!}
                        </div>
                        <div class="gap-80"></div>
                    </div>
                    <div class="col-lg-9 col-md-12">
                        <div class="row">
                            <div class="col-lg-6 offset-lg-6">
                                <div class="search">
                                    <form id="frm_search">
                                        <div class="input-group">
                                            <input type="text" name="searchtxt" id="searchtxt" class="form-control form-control-sm" placeholder="Search news" aria-label="Search news" aria-describedby="button-addon1" />
                                            <span class="search-icon"><i class="fa fa-search"></i></span>
                                        </div>
                                        <button class="primary-btn btn-sm" type="submit" id="button-addon1">
                                            Search
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                        @forelse($articles as $article)

                            @if(empty($article->external_link))
                                    <div class="col-md-4">
                                        <div class="card news-post-list" onclick="location.href='{{ route('news.front.show',$article->slug) }}';" style="height:550px;">
                                            <div class="news-post-image"><img src="{{ $article->thumbnail_url }}" class="card-img-top" alt="..."></div>
                                            <div class="card-body">
                                                <p class="news-post-time">Posted on {{ Setting::date_for_news_list($article->updated_at) }} </p>
                                                <h5 class="card-title"><a href="{{ route('news.front.show',$article->slug) }}">{{ $article->name }}</a></h5>
                                                <p class="card-text" style="text-align: justify; text-justify: inter-word;">{!! $article->teaserlink !!}</p>
                                            </div>
                                        </div>
                                    </div>
                            @else
                                    <div class="col-md-4">
                                        <div class="card news-post-list" style="height:550px;" >
                                            <div class="news-post-image"><img src="{{ $article->thumbnail_url }}" class="card-img-top" alt="..."></div>
                                            <div class="card-body">
                                                <p class="news-post-time">Posted on {{ Setting::date_for_news_list($article->updated_at) }} </p>
                                                <h5 class="card-title"><a href="{{ $article->external_link }}" target="_blank">{{ $article->name }}</a></h5>
                                                <p class="card-text" style="text-align: justify; text-justify: inter-word;">{!! $article->teaserlink !!}</p>
                                            </div>
                                        </div>
                                    </div>
                            @endif
                        @empty
                            <div class="col-md-12">
                                <div class="alert alert-warning" role="alert">
                                    @if(isset(request()->type))
                                        <p>Your search "<i>{{ request()->criteria }}</i>" did not match any news and events.</p>
                                        <p>Suggestions:</p>
                                        <p>
                                        <ul>
                                            <li>Make sure all words are spelled correctly.</li>
                                            <li>Try different keywords.</li>
                                            <li>Try more general keywords.</li>
                                        </ul>
                                        </p>
                                    @else
                                        No News article found!
                                    @endif

                                </div>
                            </div>
                        @endforelse
                        </div>
                        {{ $articles->links('theme.'.env('FRONTEND_TEMPLATE').'.layout.pagination') }}
                    </div>
                </div>
            </div>
            </div>
        </section>

    </main>



{{--    <main>--}}
{{--        <section id="default-wrapper">--}}
{{--            <div class="container">--}}
{{--                <div class="row">--}}
{{--                    <div class="col-lg-3">--}}
{{--                        <h3 class="subpage-heading">News</h3>--}}
{{--                        <div class="side-menu">--}}
{{--                            {!! $dates !!}--}}
{{--                        </div>--}}
{{--                        <div class="gap-40"></div>--}}
{{--                        <h3 class="subpage-heading">Categories</h3>--}}
{{--                        <div class="side-menu">--}}
{{--                            {!! $categories !!}--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="col-lg-9">--}}
{{--                        <form id="frm_search">--}}
{{--                            <div class="search">--}}
{{--                                <div class="input-group">--}}
{{--                                    <input type="text" name="searchtxt" id="searchtxt" class="form-control"--}}
{{--                                           placeholder="Search news" aria-label="Search news" aria-describedby="button-addon2" />--}}
{{--                                           <span class="search-icon"><i class="fa fa-search"></i></span>--}}
{{--                                    <div class="input-group-append">--}}
{{--                                        <button class="primary-btn small" type="submit" id="button-addon1">--}}
{{--                                            Search--}}
{{--                                        </button>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </form>--}}
{{--                        @forelse($articles as $article)--}}
{{--                            <div class="news-post">--}}
{{--                                <div class="news-post-img" style="background-image:url({{ $article->image_url }})"></div>--}}
{{--                                <div class="news-post-info">--}}
{{--                                    <h4>--}}
{{--                                        <a href="{{ route('news.front.show',$article->slug) }}">{{ $article->name }}</a>--}}
{{--                                    </h4>--}}
{{--                                    <p class="news-post-info-excerpt">--}}
{{--                                        {{ $article->teaser }}--}}
{{--                                    </p>--}}
{{--                                    <p class="news-post-info-meta">--}}
{{--                                        Posted {{ Setting::date_for_news_list($article->updated_at) }}--}}
{{--                                    </p>--}}
{{--                                    <div class="share_links" title="/news/{{$article->slug}}">Share this</div>--}}

{{--                                </div>--}}
{{--                            </div>--}}
{{--                        @empty--}}
{{--                            <div class="col-md-12">--}}
{{--                                <div class="alert alert-warning" role="alert">--}}
{{--                                    @if(isset($_GET['type']))--}}
{{--                                        <p>Your search "<i>{{$_GET['criteria']}}</i>" did not match any documents.</p>--}}
{{--                                        <p>Suggestions:</p>--}}
{{--                                        <p>--}}
{{--                                            <ul>--}}
{{--                                                <li>Make sure all words are spelled correctly.</li>--}}
{{--                                                <li>Try different keywords.</li>--}}
{{--                                                <li>Try more general keywords.</li>--}}
{{--                                            </ul>--}}
{{--                                        </p>--}}
{{--                                    @else--}}
{{--                                        No News article found!--}}
{{--                                    @endif--}}

{{--                                </div>--}}
{{--                            </div>--}}
{{--                        @endforelse--}}
{{--                        {{ $articles->links('theme.'.env('FRONTEND_TEMPLATE').'.layout.pagination') }}--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </section>--}}
{{--    </main>--}}
@endsection

@section('jsscript')
<script src="{{ asset('theme/lydias/plugins/jssocials/jssocials.js') }}"></script>
<script>
    $(function() {
        $('#frm_search').on('submit', function(e) {
            e.preventDefault();
            window.location.href = "{{route('news.front.index')}}?type=searchbox&criteria="+$('#searchtxt').val();
        });

        $('.share_links').each(function(i, obj) {
            let link = $(this).attr('title');
            $(this).jsSocials({
                url: "{{env('APP_URL')}}"+link,
                showCount: false,
                showLabel: false,
                shares: [
                    "twitter",
                    "facebook",
                ]
            });
        });
    });
</script>
@endsection
