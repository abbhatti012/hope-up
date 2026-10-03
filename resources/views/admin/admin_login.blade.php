<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>HOPE-UP | Admin Login</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito+Sans:300,400,600,700&display=swap">
    <link rel="stylesheet" href="{{ asset('admin/css/codebase.min.css') }}">
    <style>
        body {
            background: #1877f2; /* Project blue */
            min-height: 100vh;
            font-family: 'Nunito Sans', sans-serif;
        }
        .login-card {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 8px 32px rgba(24, 119, 242, 0.10);
            padding: 3.5rem 3rem 3rem 3rem;
            max-width: 600px;
            margin: 3rem auto;
            width: 50%;
        }
        .login-card, .login-card input, .login-card label, .login-card button, .login-card h3 {
            font-size: 1.15rem;
        }
        .login-card h3 {
            font-size: 2rem;
        }
        .login-logo {
            display: block;
            margin: 0 auto 1.5rem auto;
            max-width: 180px;
        }
        .form-control {
            border-radius: 8px;
            border: 1px solid #e3e6ef;
            font-size: 1rem;
        }
        .btn-primary {
            background: #1877f2;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1.1rem;
            padding: 0.75rem 0;
        }
        .btn-primary:hover {
            background: #145dc1;
        }
        .forgot-link {
            display: block;
            text-align: right;
            margin-top: 0.5rem;
            color: #1877f2;
            font-size: 0.95rem;
            text-decoration: none;
        }
        .forgot-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container d-flex align-items-center justify-content-center" style="min-height: 100vh;">
        <div class="login-card">
            <img src="{{ asset('assets/images/logo1.svg') }}" alt="Hope Up Logo" class="login-logo">
            <h3 class="text-center mb-4" style="color:#1877f2; font-weight:700;">Admin Login</h3>
            <form method="POST" action="{{ route('login') }}">
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
                <div class="mb-2">
                    <label for="password" class="form-label">Password</label>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
                    @error('password')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <a href="{{ route('password.request') }}" class="forgot-link">Forgot Password?</a>
                <div class="d-grid mt-4">
                    <button type="submit" class="btn btn-primary btn-block btn-lg">Sign In</button>
                </div>
            </form>
        </div>
    </div>
    <script src="{{ asset('admin/js/codebase.core.min.js') }}"></script>
    <script src="{{ asset('admin/js/codebase.app.min.js') }}"></script>
</body>
</html>
