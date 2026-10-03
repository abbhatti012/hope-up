<!doctype html>
<html lang="en" class="no-focus">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

        <title>DOCTOR | lOGIN</title>
        <link rel="shortcut icon" href="{{ asset('public_assets/media/favicon.png') }}">
        <link rel="icon" type="image/png" sizes="192x192" href="">
        <link rel="apple-touch-icon" sizes="180x180" href="">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito+Sans:300,400,400i,600,700&display=swap">
        <link rel="stylesheet" id="css-main" href="{{asset('admin/css/codebase.min.css')}}">
    </head>
    <body>
        <div id="page-container" class="main-content-boxed">
            <main id="main-container">
                <div class="bg-gd-dusk">
                    <div class="hero-static content content-full bg-white invisible" data-toggle="appear">
                      <div class="py-30 px-5 text-center">
                            <a class="" href=" {{ route('/') }} ">
                                <img width="300px" src="" alt="">
                            </a>
                        </div>
                        <div class="row justify-content-center px-5">
                            <div class="col-sm-8 col-md-6 col-xl-4">
                                <form method="POST" action="{{ route('auth.user') }}">
                                    @csrf
                                    <div class="form-group row">
                                        <div class="col-12">
                                            <div class="form-material floating">
                                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                                <label for="email">Email</label>
                                                @error('email')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-12">
                                            <div class="form-material floating">
                                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                                <label for="password">Password</label>
                                                @error('password')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" value="0" name="is_block">
                                    <div class="form-group row gutters-tiny">
                                        <div class="col-12 mb-10">
                                            <button type="submit" class="btn btn-block btn-hero btn-noborder btn-rounded btn-alt-primary">
                                                <i class="si si-login mr-10"></i> Sign In
                                            </button>
                                        </div>

                                        <div class="col-6">
                                            <a class="btn btn-block btn-noborder btn-rounded btn-alt-secondary" href="{{ route('password.request') }}">
                                                <i class="si si-book-open text-muted mr-10"></i> Forgot password
                                            </a>
                                        </div>
                                        <div class="col-6">
                                            <a class="btn btn-block btn-noborder btn-rounded btn-alt-secondary" href="{{ route('register') }}">
                                                <i class="si si-login text-muted mr-10"></i> New Account
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <script src="{{asset('admin/js/codebase.core.min.js')}}"></script>
        <script src="{{asset('admin/js/codebase.app.min.js')}}"></script>
        <script src="{{asset('admin/js/plugins/jquery-validation/jquery.validate.min.js')}}"></script>
        <script src="{{asset('admin/js/pages/op_auth_signin.min.js')}}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/notify/0.4.2/notify.min.js" integrity="sha512-efUTj3HdSPwWJ9gjfGR71X9cvsrthIA78/Fvd/IN+fttQVy7XWkOAXb295j8B3cmm/kFKVxjiNYzKw9IQJHIuQ==" crossorigin="anonymous"></script>
    </body>
    @if ($message = Session::get('login_error'))
    <script type="text/javascript">
        $.notify('Incorrect Credentials or User Blocked','error');
    </script>
   @endif
</html>
