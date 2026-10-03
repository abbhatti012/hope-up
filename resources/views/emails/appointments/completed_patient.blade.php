@extends('emails.layouts.app')

@section('title', 'Appointment Completed')

@section('header', 'Appointment Completed')

@section('content')
<p>Dear {{ $data['patient']->first_name }} {{ $data['patient']->last_name }},</p>

<p>Your appointment has been marked as completed. Here are the details:</p>

<div style="background-color: white; padding: 15px; margin: 15px 0; border-radius: 5px; border-left: 4px solid #2196F3;">
    <h3>Completed Appointment Details</h3>
    <p><strong>Doctor:</strong> Dr. {{ $data['doctor']->first_name }} {{ $data['doctor']->last_name }}</p>
    <p><strong>Date:</strong> {{ $data['appointmentDate'] }}</p>
    <p><strong>Time:</strong> {{ $data['appointmentTime'] }}</p>
    <p><strong>Treatment Type:</strong> {{ ucfirst($data['appointment']->treatment_type) }}</p>
    <p><strong>Status:</strong> {{ ucfirst($data['appointment']->status) }}</p>
    @if($data['appointment']->notes)
        <p><strong>Notes:</strong> {{ $data['appointment']->notes }}</p>
    @endif
</div>

<p>Thank you for choosing our services. We hope your appointment went well and that you received the care you needed.</p>

<p>If you have any follow-up questions or need to schedule another appointment, please don't hesitate to contact us.</p>

<p>We value your feedback. If you would like to share your experience, please consider leaving a review.</p>

<p>Best regards,<br>
{{ $data['appName'] }} Team</p>
@endsection 