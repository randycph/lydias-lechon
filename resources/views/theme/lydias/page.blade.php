@extends('theme.'.env('FRONTEND_TEMPLATE').'.main')
@section('pagecss')   
    <style>
        .timeline-item:hover .border {background-color: #28a745 !important;border:none !important;}
        .timeline-item:hover .card {border-color: #28a745 !important;box-shadow: 0 .5rem 1rem rgba(0,0,0,.15) !important;}
    </style>
@endsection
@section('content')
    <main>
        <div class="content-wrapper">
            <div class="gap-70"></div>
            <div class="container">
                <div class="row">
                    @if($parentPage)
                        <div class="col-lg-3">
                            <h3>{{ $parentPage->name }}</h3>
                            <div class="gap-20"></div>
                            <div class="side-menu">
                                <ul>
                                    @foreach ($parentPage->sub_pages as $subPage)
                                        <li @if ($subPage->id == $page->id) class="active" @endif>
                                            <a href="{{ $subPage->get_url() }}">{{ $subPage->name }}</a>
                                            @if ($subPage->has_sub_pages())
                                                <ul>
                                                    @foreach ($subPage->sub_pages as $subSubPage)
                                                        <li @if ($subSubPage->id == $page->id) class="active" @endif>
                                                            <a href="{{ $subSubPage->get_url() }}">{{ $subSubPage->name }}</a>
                                                            @if ($subSubPage->has_sub_pages())
                                                                <ul>
                                                                    @foreach ($subSubPage->sub_pages as $subSubSubPage)
                                                                        <li @if ($subSubSubPage->id == $page->id) class="active" @endif>
                                                                            <a href="{{ $subSubSubPage->get_url() }}">{{ $subSubSubPage->name }}</a>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            @endif
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-9">
                            <div class="article-content">
                                {!! $page->contents !!}
                            </div>
                        </div>
                    @else
                        <div class="col-lg-12">
                            {!! $page->contents !!}
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="gap-70"></div>
        
    </main>
@endsection

@section('jsscript')
    
@endsection
