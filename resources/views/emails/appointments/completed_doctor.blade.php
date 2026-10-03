@extends('emails.layouts.app')

@section('title', 'Appointment Completed')

@section('header', 'Appointment Completed')

@section('content')
<p>Dear Dr. {{ $data['doctor']->first_name }} {{ $data['doctor']->last_name }},</p>

<p>An appointment in your schedule has been marked as completed. Here are the details:</p>

<div style="background-color: white; padding: 15px; margin: 15px 0; border-radius: 5px; border-left: 4px solid #2196F3;">
    <h3>Completed Appointment Details</h3>
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

<p>This appointment has been successfully completed. You may want to:</p>
<ul>
    <li>Update the patient's medical records if needed</li>
    <li>Schedule any follow-up appointments if required</li>
    <li>Document any important notes from the session</li>
</ul>

<p>Thank you for providing excellent care to our patients.</p>

<p>Best regards,<br>
{{ $data['appName'] }} Team</p>
@endsection 