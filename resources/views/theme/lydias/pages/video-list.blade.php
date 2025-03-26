@extends('theme.'.config('app.frontend_template').'.main')
@section('pagecss')
    <link rel="stylesheet" href="{{ asset('theme/enhance/css/style.css') }}" />
@endsection
@section('content')
    <main>
        <section id="default-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-sm-3">
                        <h3 class="subpage-heading">Categories</h3>
                        <form id="frm_search">
                        <div class="input-group mb-3">
                            
                                <input type="text" name="searchtxt" id="searchtxt" class="form-control" aria-label="Text input with dropdown button">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" id="button-addon2">Search</button>
                                </div>
                            
                        </div>
                        </form>
                        <div class="side-menu">
                            {!! $categories !!}
                        </div>
                    </div>
                    <div class="col-sm-9">
                        <div class="video-content">
                            <h3 class="subpage-heading">Video Gallery</h3>

                            <div class="row">
                                @forelse($videos as $video) 
                                    @php $duration = $video->duration; @endphp
                                    <div class="col-md-6">
                                        <div class="vidthumb">
                                            <div class="vidwrap">
                                                <div class="">
                                                    <a href="{{ route('videos.front.show',$video->slug) }}">
                                                        <img src="{{ asset('storage/videos/'.$video->id.'/'.$video->thumbnail) }}" alt="{{ $video->title }}">
                                                    </a>
                                                </div>
                                            </div>
                                            <a href="{{ route('videos.front.show',$video->slug) }}">{{ $video->title }}</a>
                                            <p class="length">@php echo floor($duration/60) . ":" . $duration % 60 @endphp minutes</p>
                                            <p class="date">Posted {{ Setting::date_for_news_list($video->updated_at) }}</p>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-md-12">
                                        <div class="alert alert-warning" role="alert">  
                                            @if(isset($_GET['type']))  
                                                <p>Your search "<i>{{$_GET['criteria']}}</i>" did not match any videos.</p>
                                                <p>Suggestions:</p>
                                                <p>
                                                    <ul>
                                                        <li>Make sure all words are spelled correctly.</li>
                                                        <li>Try different keywords.</li>
                                                        <li>Try more general keywords.</li>
                                                    </ul>
                                                </p>
                                            @else
                                                No video found!
                                            @endif                                           
                                          
                                        </div>
                                    </div>
                                @endforelse
                                
                               
                                <div class="col-md-12">
                                    {{ $videos->links('theme.enhance.layout.pagination') }}
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection

@section('jsscript')
<script>
    $(function() {
        $('#frm_search').on('submit', function(e) {
            e.preventDefault();
            window.location.href = "{{ route('videos.front.index') }}?type=searchbox&criteria="+$('#searchtxt').val();
        });
    });

</script>
@endsection
