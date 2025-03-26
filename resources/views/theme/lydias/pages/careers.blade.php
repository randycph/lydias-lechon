@extends('theme.'.config('app.frontend_template').'.main')

@section('pagecss')
    <link rel="stylesheet" href="{{ asset('theme/lydias/plugins/jssocials/jssocials-theme-flat.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('theme/lydias/plugins/jssocials/jssocials.css') }}" />
@endsection

@section('content')

    <section>
        <div class="content-wrapper">
            <div class="gap-70"></div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-7">
                        {!! Setting::getCareers()->contents !!}
                        <hr>
                    </div>

                    <div class="col-lg-5">
                        <form id="contactUsForm" class="form message-form" enctype="multipart/form-data" role="form" autocomplete="off" action="{{ route('applicant') }}" method="post">
                            @method('POST')
                            @csrf
                            @if(session()->has('application-success'))
                                <div class="alert alert-success">
                                    {{ session()->get('application-success') }}
                                </div>
                            @endif
                            <div class="form-group">
                                <label class="col-form-label form-control-label">Name</label>
                                <input class="form-control" type="text" name="name" placeholder="Full name">
                            </div>
                            <div class="form-group">
                                <label class="col-form-label form-control-label">Email</label>
                                <input class="form-control" type="email" name="email" placeholder="Email Address">
                            </div>
                            <div class="form-group">
                                <label class="col-form-label form-control-label">Contact Number</label>
                                <input class="form-control" type="number" name="contact" value="">
                            </div>
                            <div class="form-group">
                                <label for="message2" class="mb-0 form-control-label">Message</label>
                                <textarea rows="4" name="message" id="message" class="form-control"></textarea>
                            </div>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="resume" id="resume" accept="application/pdf">
                                <label class="custom-file-label" for="customFile" id="resumeName">Upload your CV</label>
                            </div>
                            <div class="form-group">
                                <label class="col-form-label form-control-label"></label>
                                <button type="reset" class="btn btn-md default-btn">Reset</button>
                                <button type="submit" class="btn primary-btn more2">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="gap-70"></div>
        </div>
    </section>

@endsection

@section('jsscript')
    <script src="{{ asset('theme/lydias/plugins/jssocials/jssocials.js') }}"></script>

    <script>
        $("#resume").change(function(evt) {
            $('#resumeName').html(evt.target.files[0].name);
        });
    </script>
@endsection
