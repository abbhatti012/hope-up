@extends('emails.layouts.app')

@section('title', 'Appointment Cancelled')

@section('header', 'Appointment Cancelled')

@section('content')
<p>Dear Dr. {{ $data['doctor']->first_name }} {{ $data['doctor']->last_name }},</p>

<p>An appointment in your schedule has been cancelled. Here are the details of the cancelled appointment:</p>

<div style="background-color: white; padding: 15px; margin: 15px 0; border-radius: 5px; border-left: 4px solid #f44336;">
    <h3>Cancelled Appointment Details</h3>
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

<p>This time slot is now available in your schedule. You may want to:</p>
<ul>
    <li>Update your availability calendar</li>
    <li>Offer this time slot to other patients if needed</li>
    <li>Contact the administration team if you have any questions</li>
</ul>

<p>If you have any questions about this cancellation, please contact the administration team.</p>


<p>Best regards,<br>
{{ $data['appName'] }} Team</p>
@endsection 