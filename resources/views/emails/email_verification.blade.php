@extends('emails.layouts.app')

@section('content')
<div style="max-width: 600px; margin: 0 auto; padding: 20px; font-family: Arial, sans-serif;">
    <div style="text-align: center; margin-bottom: 30px;">
        <h2 style="color: #333; margin-bottom: 10px;">Email Verification Required</h2>
        <p style="color: #666; font-size: 16px;">Hello {{ $name }},</p>
    </div>

    <div style="background-color: #f8f9fa; padding: 25px; border-radius: 8px; margin-bottom: 25px;">
        <p style="color: #333; font-size: 16px; line-height: 1.6; margin-bottom: 20px;">
            We received a request to update your email address to <strong>{{ $email }}</strong>. 
            To complete this change, please verify your new email address by clicking the button below:
        </p>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $verificationUrl }}" 
               style="background-color: #007bff; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block;">
                Verify Email Address
            </a>
        </div>

        <p style="color: #666; font-size: 14px; margin-top: 20px;">
            If the button doesn't work, you can copy and paste this link into your browser:
        </p>
        <p style="color: #007bff; font-size: 14px; word-break: break-all;">
            {{ $verificationUrl }}
        </p>
    </div>

    <div style="background-color: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 5px; margin-bottom: 25px;">
        <p style="color: #856404; font-size: 14px; margin: 0;">
            <strong>Important:</strong> This verification link will expire in 24 hours for security reasons.
        </p>
    </div>

    <div style="text-align: center; margin-top: 30px;">
        <p style="color: #666; font-size: 14px;">
            If you didn't request this email change, please ignore this message or contact our support team.
        </p>
        <p style="color: #666; font-size: 14px; margin-top: 10px;">
            Best regards,<br>
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