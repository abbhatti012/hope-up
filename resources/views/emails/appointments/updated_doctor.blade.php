@extends('emails.layouts.app')

@section('title', 'Appointment Updated')

@section('header', 'Appointment Details Updated')

@section('content')
<p>Dear Dr. {{ $data['doctor']->first_name }} {{ $data['doctor']->last_name }},</p>

<p>An appointment in your schedule has been updated. Here are the current details:</p>

<div style="background-color: white; padding: 15px; margin: 15px 0; border-radius: 5px; border-left: 4px solid #FF9800;">
    <h3>Updated Appointment Details</h3>
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

<p>Please review the updated details and adjust your schedule accordingly. If you have any questions about the changes, please contact the administration team.</p>


<p>Best regards,<br>
{{ $data['appName'] }} Team</p>
@endsection 