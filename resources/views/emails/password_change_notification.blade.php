@extends('emails.layouts.app')

@section('content')
<div style="max-width: 600px; margin: 0 auto; padding: 20px; font-family: Arial, sans-serif;">
    <div style="text-align: center; margin-bottom: 30px;">
        <h2 style="color: #333; margin-bottom: 10px;">Password Changed Successfully</h2>
        <p style="color: #666; font-size: 16px;">Hello {{ $name }},</p>
    </div>

    <div style="background-color: #d4edda; border: 1px solid #c3e6cb; padding: 20px; border-radius: 8px; margin-bottom: 25px;">
        <p style="color: #155724; font-size: 16px; line-height: 1.6; margin: 0;">
            <strong>✓ Your password has been successfully changed!</strong>
        </p>
    </div>

    <div style="background-color: #f8f9fa; padding: 25px; border-radius: 8px; margin-bottom: 25px;">
        <h3 style="color: #333; margin-bottom: 15px;">Change Details:</h3>
        <ul style="color: #333; font-size: 14px; line-height: 1.6; padding-left: 20px;">
            <li><strong>Time:</strong> {{ $changedAt }}</li>
            <li><strong>IP Address:</strong> {{ $ipAddress }}</li>
            <li><strong>Device:</strong> {{ $userAgent }}</li>
        </ul>
    </div>

    <div style="background-color: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 5px; margin-bottom: 25px;">
        <p style="color: #856404; font-size: 14px; margin: 0;">
            <strong>Security Notice:</strong> If you didn't make this change, please contact our support team immediately.
        </p>
    </div>

    <div style="text-align: center; margin: 30px 0;">
        <a href="{{ $loginUrl }}" 
           style="background-color: #28a745; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block;">
            Login to Your Account
        </a>
    </div>

    <div style="text-align: center; margin-top: 30px;">
        <p style="color: #666; font-size: 14px;">
            Thank you for keeping your account secure!<br>
            <strong>{{ $appName }}</strong> Team
        </p>
    </div>

    <div style="border-top: 1px solid #eee; margin-top: 30px; padding-top: 20px; text-align: center;">
        <p style="color: #999; font-size: 12px;">
            If you have any questions, please contact us at <a href="mailto:{{ $contactEmail }}" style="color: #007bff;">{{ $contactEmail }}</a>
        </p>
    </div>
</div>
@endsection 