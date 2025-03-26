@extends('theme.'.config('app.frontend_template').'.main')

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

        .thumb {
            display: block;
            width: 100%;
            margin: 0;

        }

        /* Style to article Author */
        .by-author {
            font-style: italic;
            line-height: 1.3;
            color: #aab6aa;
            padding-left:5px;
            font-size:12px;
        }
        .media-heading{
            font-size:12px;
            padding-left:5px;
            color: gray;
        }

       
    </style>
@endsection

@section('content')

    <section>
        <div class="content-wrapper">
            <div class="gap-70"></div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-9 col-md-8 order-md-12">
                        <div class="article-details">
                            <div class="article-details-title"><h2>{{ $news->name }}</h2></div>
                            <div class="article-details-posted">Posted on<span> {{ date('F d, Y', strtotime($news->date)) }}</span> by {{$news->user->name}}</div>
                            <div class="article-details-infos">
                                {!! $news->contents !!}
                                <div class="gap-20"></div>
                                <a href="{{ route('news.front.index') }}" class="article-button">Back</a>
                            </div>

                        </div>

                        <div class="gap-40"></div>
                        <div class="share_links" title="/news/{{$news->slug}}"></div>
                        <div class="gap-30"></div>
                    </div>

                    <div class="col-lg-3 col-md-4">
                        
                        [latest news limit="5"][/latest news]
                        <div class="gap-20"></div>
                        @if(!empty($news->get_tags($news->id)))
                        <h5>Tags</h5>
                        <ul class="tag-keywords">
                            <li>
                                @forelse($news->tags as $tag)
                                    <a href="#" class="btn btn-primary">{{$tag->tag}}</a>
                                @empty
                                @endforelse
                            </li>
                        </ul>
                        @endif
                        <div class="gap-30"></div>
                    </div>
                </div>
            </div>
            <div class="gap-70"></div>
        </div>
    </section>

    <div class="modal fade" id="email-article" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">E-mail this article</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="shareEmailForm">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>E-mail to *</label>
                            <input type="email" name="email_to" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Recipient's Name *</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Your E-mail Address *</label>
                            <input type="email" name="email_from" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Your Name *</label>
                            <input type="text" name="sender_name" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Send Article</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="email-success" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">E-mail this article</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Article successfully sent!
                </div>
            </div>
        </div>
    </div>

   <div class="modal fade" id="email-failed" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
       <div class="modal-dialog modal-dialog-centered" role="document">
           <div class="modal-content">
               <div class="modal-header">
                   <h5 class="modal-title" id="exampleModalLabel">E-mail this article</h5>
                   <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                       <span aria-hidden="true">&times;</span>
                   </button>
               </div>
               <div class="modal-body">
                   Failed to share email. Try it again later.
               </div>
           </div>
       </div>
   </div>
@endsection

@section('jsscript')
<script src="{{ asset('theme/lydias/plugins/jssocials/jssocials.js') }}"></script>
    <script>
        $(function() {
            $('#shareEmailForm').submit(function(evt) {
                evt.preventDefault();
                let data = $('#shareEmailForm').serialize();

                $.ajax({
                    data: data,
                    type: "POST",
                    url: "{{ route('news.front.share', $news->slug) }}",
                    success: function(returnData) {
                        $('#email-success').modal('show');
                        $('#email-article').modal('hide');
                        $('#email-article input').val('');
                    },
                    error: function(){
                        $('#email-failed').modal('show');
                        $('#email-article').modal('hide');
                        $('#email-article input').val('');
                    }
                });
                return false;
            });
        });
    </script>

    <script>
    $(function() {
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

        let share = '' +
            '<div class="jssocials-share jssocials-share-email"><a data-toggle="modal" data-target="#email-article" class="email" data-toggle="tooltip" data-placement="top" title="Email"><i class="fa fa-envelope-open"></i></a></div>\n' +
            '<div class="jssocials-share jssocials-share-print"><a href="{{ route("news.front.print", $news->slug) }}" class="printer" data-toggle="tooltip" data-placement="top" title="Print"><i class="fa fa-print"></i></a></div>\n' +
            '';
        $('.jssocials-shares').append(share);

        {{--$('.article-social').each(function(i, obj) {--}}
        {{--    let link = $(this).attr('title');--}}
        {{--    $(this).jsSocials({--}}
        {{--        url: "{{env('APP_URL')}}"+link,--}}
        {{--        showCount: false,--}}
        {{--        showLabel: false,--}}
        {{--        shares: [--}}
        {{--            "twitter",--}}
        {{--            "facebook",--}}
        {{--            "googleplus",--}}
        {{--            "linkedin",--}}
        {{--            "pinterest",--}}
        {{--        ]--}}
        {{--    });--}}
        {{--});--}}
    });
</script>
@endsection
