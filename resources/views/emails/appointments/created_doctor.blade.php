@extends('emails.layouts.app')

@section('title', 'New Appointment Created')

@section('header', 'New Appointment Scheduled')

@section('content')
<p>Dear Dr. {{ $data['doctor']->first_name }} {{ $data['doctor']->last_name }},</p>

<p>A new appointment has been scheduled with you. Here are the details:</p>

<div style="background-color: white; padding: 15px; margin: 15px 0; border-radius: 5px; border-left: 4px solid #4CAF50;">
    <h3>Appointment Details</h3>
    <p><strong>Patient:</strong> {{ $data['patient']->first_name }} {{ $data['patient']->last_name }}</p>
    <p><strong>Patient Email:</strong> {{ $data['patient']->email }}</p>
    <p><strong>Date:</strong> {{ $data['appointmentDate'] }}</p>
    <p><strong>Time:</strong> {{ $data['appointmentTime'] }}</p>
    <p><strong>Treatment Type:</strong> {{ ucfirst($data['appointment']->treatment_type) }}</p>
    <p><strong>Status:</strong> {{ ucfirst($data['appointment']->status) }}</p>
    @if($data['appointment']->notes)
        <p><strong>Notes:</strong> {{ $data['appointment']->notes }}</p>
    @endif
</div>

<p>Please review the appointment details and prepare accordingly. You may need to:</p>
<ul>
    <li>Review the patient's medical history</li>
    <li>Prepare any necessary equipment or materials</li>
    <li>Update your schedule if needed</li>
</ul>

<p>If you need to make any changes to this appointment, please contact the patient or the administration team.</p>


<p>Best regards,<br>
{{ $data['appName'] }} Team</p>
@endsection 