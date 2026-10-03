@extends('emails.layouts.app')

@section('title', 'Your Login Credentials')

@section('content')
    <h2>Welcome to {{ config('app.name') }}</h2>
    
    <p>Hello <strong>{{ $name }}</strong>,</p>
    
    <p>Your account has been created successfully. Here are your login credentials:</p>
    
    <div class="credentials-box">
        <p><strong>Email:</strong> {{ $email }}</p>
        <p><strong>Password:</strong> {{ $password }}</p>
    </div>
    
    <p>For your security, please change your password after your first login.</p>
    
    @if($isAdmin)
        <a href="{{ $loginUrl }}" class="button">Login to Your Account</a>
    @endif
    
    <p>If you have any questions or need assistance, please don't hesitate to contact our support team.</p>
    
    <p>Best regards,<br><strong>The {{ config('app.name') }} Team</strong></p>
    
    <div class="divider"></div>
    
    <p style="font-size: 12px; color: #6c757d;">
        This is an automated message. Please do not reply to this email.
    </p>
@endsection
