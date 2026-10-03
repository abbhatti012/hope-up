@extends('emails.layouts.app')

@section('title', 'Appointment Cancelled')

@section('header', 'Appointment Cancelled')

@section('content')
<p>Dear {{ $data['patient']->first_name }} {{ $data['patient']->last_name }},</p>

<p>Your appointment has been cancelled. Here are the details of the cancelled appointment:</p>

<div style="background-color: white; padding: 15px; margin: 15px 0; border-radius: 5px; border-left: 4px solid #f44336;">
    <h3>Cancelled Appointment Details</h3>
    <p><strong>Doctor:</strong> Dr. {{ $data['doctor']->first_name }} {{ $data['doctor']->last_name }}</p>
    <p><strong>Date:</strong> {{ $data['appointmentDate'] }}</p>
    <p><strong>Time:</strong> {{ $data['appointmentTime'] }}</p>
    <p><strong>Treatment Type:</strong> {{ ucfirst($data['appointment']->treatment_type) }}</p>
    <p><strong>Status:</strong> {{ ucfirst($data['appointment']->status) }}</p>
    @if($data['appointment']->notes)
        <p><strong>Notes:</strong> {{ $data['appointment']->notes }}</p>
    @endif
</div>

<p>If you would like to reschedule your appointment, please contact us as soon as possible. We will be happy to help you find a new suitable time.</p>

<p>If you have any questions about the cancellation or need to reschedule, please contact us immediately.</p>

<p>Best regards,<br>
{{ $data['appName'] }} Team</p>
@endsection 