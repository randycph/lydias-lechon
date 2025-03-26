@extends('theme.'.config('app.frontend_template').'.main')
@section('content')
<main>
    <section id="default-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    @if(!empty(Setting::info()->data_privacy_content)) {!! Setting::info()->data_privacy_content !!} @endif
                </div>
            </div>
        </div>
    </section>
</main>

<script>
    $('#contactUsForm').submit(function (evt) {
        let recaptcha = $("#g-recaptcha-response").val();
        if (recaptcha === "") {
            evt.preventDefault();
            $('#catpchaError').show();
            return false;
        }
    });
</script>
@endsection
