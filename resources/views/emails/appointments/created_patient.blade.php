@extends('emails.layouts.app')

@section('title', 'Appointment Created')

@section('header', 'Appointment Created Successfully')

@section('content')
<p>Dear {{ $data['patient']->first_name }} {{ $data['patient']->last_name }},</p>

<p>Your appointment has been successfully created. Here are the details:</p>

<div style="background-color: white; padding: 15px; margin: 15px 0; border-radius: 5px; border-left: 4px solid #4CAF50;">
    <h3>Appointment Details</h3>
    <p><strong>Doctor:</strong> Dr. {{ $data['doctor']->first_name }} {{ $data['doctor']->last_name }}</p>
    <p><strong>Date:</strong> {{ $data['appointmentDate'] }}</p>
    <p><strong>Time:</strong> {{ $data['appointmentTime'] }}</p>
    <p><strong>Treatment Type:</strong> {{ ucfirst($data['appointment']->treatment_type) }}</p>
    <p><strong>Status:</strong> {{ ucfirst($data['appointment']->status) }}</p>
    @if($data['appointment']->notes)
        <p><strong>Notes:</strong> {{ $data['appointment']->notes }}</p>
    @endif
</div>

<p>Please make sure to:</p>
<ul>
    <li>Arrive 10 minutes before your scheduled time</li>
    <li>Bring any relevant medical documents</li>
    <li>Contact us if you need to reschedule</li>
</ul>

<p>If you have any questions or need to make changes to your appointment, please contact us immediately.</p>


<p>Best regards,<br>
{{ $data['appName'] }} Team</p>
@endsection 