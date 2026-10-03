@extends('emails.layouts.app')

@section('title', 'Account Unblocked')

@section('content')
    <h2>Account Unblocked</h2>
    
    <p>Hello <strong>{{ $name }}</strong>,</p>
    
    <div class="credentials-box" style="border-left: 4px solid #2ecc71;">
        <p>We're pleased to inform you that your account with {{ config('app.name') }} has been unblocked.</p>
    </div>
    
    <p>You can now log in to your account and resume using our services.</p>
    
    <!-- <a href="{{ route('login') }}" class="button" style="background-color: #2ecc71;">Log in to Your Account</a> -->
    
    <p>If you have any questions or need assistance, please don't hesitate to contact our support team.</p>
    
    <p>Welcome back!<br><strong>The {{ config('app.name') }} Team</strong></p>
    
    <div class="divider"></div>
    
    <p style="font-size: 12px; color: #6c757d;">
        This is an automated message. Please do not reply to this email.
    </p>
@endsection
