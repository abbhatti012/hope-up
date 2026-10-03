@php
    $setting = DB::table('settings')->first();
@endphp
<!doctype html>
<html lang="en" class="no-focus">
    <head>
        <meta charset="utf-8">
        <title>DOCTOR | REGISTER</title>
        <link rel="shortcut icon" href="{{ asset('public_assets/media/favicon.png') }}">
        <link rel="icon" type="image/png" sizes="192x192" href="assets/media/favicons/favicon-192x192.png">
        <link rel="apple-touch-icon" sizes="180x180" href="assets/media/favicons/apple-touch-icon-180x180.png">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito+Sans:300,400,400i,600,700&display=swap">
        <link rel="stylesheet" id="css-main" href="{{asset('admin/css/codebase.min.css')}}">
    </head>
    <body>
        <div id="page-container" class="main-content-boxed">
            <main id="main-container">
                <div class="bg-gd-emerald">
                    <div class="hero-static content content-full bg-white invisible" data-toggle="appear">
                        <div class="py-30 px-5 text-center">
                            <a class="" href=" {{ route('/') }} ">
                                <img width="300" src="{{ asset($setting->logo) }}" alt="">
                            </a>
                            <h1 class="h2 font-w700 mt-50 mb-10">Create New Account</h1>
                            <h2 class="h4 font-w400 text-muted mb-0">Please add your details</h2>
                        </div>
                        <div class="row justify-content-center px-5">
                            <div class="col-sm-8 col-md-6 col-xl-4">
                                <form method="POST" action="{{ route('register') }}">
                                    @csrf
                                    <div class="form-group row">
                                        <div class="col-12">
                                            <div class="form-material floating">
                                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                                                <label for="name">Name</label>
                                                @error('name')
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
                                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
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
                                            <div class="form-material">
                                                <input id="phone" type="number" class="form-control nmber_input mail_input" name="phone" required autocomplete="number" value="{{ old('phone') }}" placeholder="Enter Phone Number">
                                                <label class="nmber_label">+233</label>
                                                @error('phone')
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
                                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                                <label for="password">Password</label>
                                                @error('password')
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
                                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                                <label for="password-confirm">Password Confirmation</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row gutters-tiny">
                                        <div class="col-12 mb-10">
                                            <button type="submit" class="btn btn-block btn-hero btn-noborder btn-rounded btn-alt-success">
                                                <i class="si si-user-follow mr-10"></i> Sign Up
                                            </button>
                                        </div>
                                        <div class="col-6">
                                            <a class="btn btn-block btn-noborder btn-rounded btn-alt-secondary" href="javascript:void(0)" data-toggle="modal" data-target="#modal-terms">
                                                <i class="si si-book-open text-muted mr-10"></i> Read Terms
                                            </a>
                                        </div>
                                        <div class="col-6">
                                            <a class="btn btn-block btn-noborder btn-rounded btn-alt-secondary" href="{{ route('login') }}">
                                                <i class="si si-login text-muted mr-10"></i> Sign In
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
        <script src="{{asset('admin/js/pages/op_auth_signup.min.js')}}"></script>
    </body>
</html>
