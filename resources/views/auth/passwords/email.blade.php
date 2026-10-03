<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>HOPE-UP | Reset Password</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito+Sans:300,400,600,700&display=swap">
    <link rel="stylesheet" href="{{ asset('admin/css/codebase.min.css') }}">
    <style>
        body {
            background: #1877f2;
            min-height: 100vh;
            font-family: 'Nunito Sans', sans-serif;
        }
        .reset-card {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 8px 32px rgba(24, 119, 242, 0.10);
            padding: 3.5rem 3rem 3rem 3rem;
            max-width: 700px;
            margin: 3rem auto;
        }
        .reset-card form {
            width: 100%;
        }
        .reset-card .form-control {
            border-radius: 8px;
            border: 1px solid #e3e6ef;
            font-size: 1.2rem;
            width: 100%;
            min-height: 52px;
        }
        .reset-card .btn-primary {
            background: #1877f2;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1.2rem;
            padding: 0.9rem 0;
            width: 100%;
        }
        .reset-card .btn-primary:hover {
            background: #145dc1;
        }
        .reset-card label {
            font-size: 1.1rem;
        }
        .reset-card h3 {
            font-size: 2.2rem;
            color: #1877f2;
            font-weight: 700;
        }
        .reset-card h4 {
            font-size: 1.2rem;
            color: #555;
            font-weight: 400;
        }
        .reset-logo {
            display: block;
            margin: 0 auto 1.5rem auto;
            max-width: 180px;
        }
        .alert {
            font-size: 1.1rem;
        }
    </style>
</head>
<body>
    <div class="container d-flex align-items-center justify-content-center" style="min-height: 100vh;">
        <div class="reset-card">
            <img src="{{ asset('assets/images/logo1.svg') }}" alt="Hope Up Logo" class="reset-logo">
            <h3 class="text-center mb-2">Reset Password</h3>
            <h4 class="text-center mb-4">Please enter your email to receive a password reset link.</h4>
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autofocus>
                    @error('email')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="d-grid mt-4">
                    <button type="submit" class="btn btn-primary btn-block btn-lg">Send Password Reset Link</button>
                </div>
            </form>
        </div>
    </div>
    <script src="{{ asset('admin/js/codebase.core.min.js') }}"></script>
    <script src="{{ asset('admin/js/codebase.app.min.js') }}"></script>
</body>
</html>
