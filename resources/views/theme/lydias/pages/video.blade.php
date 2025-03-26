@extends('theme.'.config('app.frontend_template').'.main')
@section('pagecss')
    <link rel="stylesheet" href="{{ asset('theme/enhance/css/style.css') }}" />
    <style>
        .jssocials-share-logo { color:white;  }
    </style>

@endsection
@section('content')
    
        <main>
          
            <section id="default-wrapper">
                <div class="container">
                    @if(session()->has('success'))
                        <div class="alert alert-success">
                            {{ session()->get('success') }}
                        </div>
                    @endif
                    @if(session()->has('error'))
                        <div class="alert alert-danger">
                            {{ session()->get('error') }}
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-lg-3">
                            <h3 class="subpage-heading">Categories</h3>
                            <div class="side-menu">
                                {!! $categories !!}
                            </div>
                        </div>
                        <div class="col-lg-9">
                            <div class="video-content">
                                <div class="vidthumb">
                                    <div class="vidwrap">
                                        <div class="video embed-responsive embed-responsive-16by9">
                                            <video controls autoplay controlsList="nodownload" id="current_video">
                                                <source src="{{ env('APP_URL') }}/storage/videos/{{ $video->id }}/{{ $video->video_url }}" type="video/mp4">
                                                <source src="{{ env('APP_URL') }}/storage/videos/{{ $video->id }}/{{ $video->video_url }}" type="video/webm">
                                                Your browser does not support the video tag.
                                            </video>
                                        </div>
                                    </div>
                                    <a href="#">{{$video->title}}</a>
                                    <p class="length">@php $duration = $video->duration; echo floor($duration/60) . ":" . $duration % 60 @endphp minutes</p>
                                    <p class="date pb-1">Posted {{ Setting::date_for_news_list($video->updated_at) }}</p>                                    
                                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModal">Request Demo Video</button>
                                    <p id="share_btns"></p>
                                    <p class="desc pt-3">{!!$video->description!!}</p>

                                    <p class="desc pt-3">Tags:</p>
                                    @php
                                        $tags = explode(",", $video->tags);  
                                        foreach($tags as $tag){
                                            echo '<span class="badge badge-secondary">'.$tag.'</span>&nbsp;';
                                        }                                        
                                    @endphp
                                    

                                    
                                    [related videos id="{{$video->id}}"][/related videos]
                                   
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <form method="post" action="{{ route('videos.request.store') }}">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Request for Demo Video</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                        
                            @csrf
                            <input type="hidden" name="video_id" value="{{$video->id}}">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Name *</label>
                                <input type="text" required="required" class="form-control" id="name" name="name" aria-describedby="emailHelp" placeholder="Enter your name">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Email address *</label>
                                <input type="email" required="required" class="form-control" id="emailad" name="emailad" aria-describedby="emailHelp" placeholder="Enter email">
                                <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Message</label>
                                <textarea class="form-control" id="message" name="message" rows="3"></textarea>
                            </div>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Submit Request</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
       
        <!-- END Page Content -->


@endsection
@section('jsscript')
    <script>
        $(function() {
                      
            $("#share_btns").jsSocials({
                url: "{{env('APP_URL')}}/video/{{$video->slug}}",
                showCount: false,
                showLabel: false,
                shares: [
                    "twitter",
                    "facebook",
                    "googleplus",
                    "linkedin",
                    "pinterest",
                    "stumbleupon",
                    "whatsapp"
                ]
            });
        });

     


    </script>
@endsection
