@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Reset Password') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Send Password Reset Link') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<div class="social-icons social-icons-sm">
    <span class="social-label">Share:</span>
    <a href="https://www.facebook.com/sharer.php?u=http://itscripto.com/aliyas/product/<?php echo $product['product_slug'] ?>" class="social-icon" title="Facebook" target="_blank"><i class="icon-facebook-f"></i></a>
    <a href="https://twitter.com/intent/tweet?text=[<?php echo $product['product_title'] ?>]&url=[<?php echo base_url('product/'.$product['product_slug']) ?>]" class="social-icon" title="Twitter" target="_blank"><i class="icon-twitter"></i></a>
    <!-- <a href="#" class="social-icon" title="Instagram" target="_blank"><i class="icon-instagram"></i></a> -->
    <a href="http://pinterest.com/pin/create/button/?url=[<?php echo base_url('product/'.$product['product_slug']) ?>]&description=[<?php echo $product['product_title'] ?>]" class="social-icon" title="Pinterest" target="_blank"><i class="icon-pinterest"></i></a>
</div>
