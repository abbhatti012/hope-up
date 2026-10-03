@extends('emails.layouts.app')

@section('title', 'Account Blocked')

@section('content')
    <h2>Account Blocked</h2>
    
    <p>Hello <strong>{{ $name }}</strong>,</p>
    
    <div class="credentials-box" style="border-left: 4px solid #e74c3c;">
        <p>We regret to inform you that your account with {{ config('app.name') }} has been blocked by the administrator.</p>
    </div>
    
    <p><strong>What this means:</strong></p>
    <ul style="padding-left: 20px; margin: 15px 0;">
        <li>You will not be able to log in to your account</li>
        <li>Your profile will not be visible to other users</li>
        <li>You will not receive any notifications</li>
    </ul>
    
    <p>If you believe this is a mistake or have any questions, please contact our support team immediately.</p>
    
    <a href="mailto:{{ config('mail.from.address') }}" class="button" style="background-color: #e74c3c;">Contact Support</a>
    
    <p>Best regards,<br><strong>The {{ config('app.name') }} Team</strong></p>
    
    <div class="divider"></div>
    
    <p style="font-size: 12px; color: #6c757d;">
        This is an automated message. Please do not reply to this email.
    </p>
@endsection
