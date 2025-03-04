<!DOCTYPE html>
<html lang="en">

<head>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('storage').'/icons/'.Setting::getFaviconLogo()->website_favicon }}">

    <title>Unsubscribed | {{ Setting::info()->company_name }}</title>

    <link href="{{ asset('lib/bootstrap/css/bootstrap.min.css') }}" type="text/css" rel="stylesheet" />
    <style>
        /* Scss Document */
        @import url("https://fonts.googleapis.com/css?family=Montserrat:400,500,600,700|Open+Sans:400,600,700&display=swap");
        @import url("https://fonts.googleapis.com/css?family=Poppins:400,500,600,700&display=swap");

        body, html {
            font-family: 'Poppins', sans-serif;
            font-size: 1rem;
            height: 100%;
            color: #333;
            line-height: 1.7rem; }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Montserrat', sans-serif; }
    </style>
</head>

<body>

    <section style="height:100%;">
        <div class="container-fluid d-flex flex-column text-center justify-content-center" style="height:100%;">
            <div class=""><img src="{{ asset('images/misc/unsubscribed.jpg') }}" /></div>
            <div class="pt-2"><h5><strong>You have successfully unsubscribed.</strong></h5></div>
            <div class=""><p class="mb-0">You have been successfully unsubscribed from our email communications.</p></div>
{{--            <div class=""><p>If you did this in error, you may re-subscribe by clicking the button below.</p></div>--}}
{{--            <div class=""><a href="#" class="btn btn-primary">Re-subscribe</a></div>--}}
        </div>
    </section>

</body>

</html>
