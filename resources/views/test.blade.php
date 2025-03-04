<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
           <form autocomplete="off" method="POST" action="{{ route('sales-transaction.test_submit') }}">
                @csrf
                <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                    <label for="email"><i class="tx-danger">*</i> Email</label>
                    <input required type="text" id="email" name="email" class="form-control" placeholder="Enter email" value="{{ old('email') }}">
                    <span class="text-danger">{{ $errors->first('email') }}</span>
                </div>

                <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                    <label for="password"><i class="tx-danger">*</i> Password</label>
                    <input required type="password" id="password" name="password" class="form-control" placeholder="********" >
                    <span class="text-danger">{{ $errors->first('password') }}</span>
                </div>
                
          
              

                <button type="submit" class="btn btn-primary btn-sm">Sign In</button>
                <a href="{{route('password.request')}}" class="btn btn-link btn-sm">Forgot Password?</a>
            </form>
        </div>
    </body>
</html>
