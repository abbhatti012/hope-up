@extends('emails.layouts.app')
@section('title', 'Transaction Created')
@section('content')
<p>Dear Dr. {{ $data['doctor']->first_name }},</p>
<p>A new transaction has been created involving you as the doctor.</p>
<ul>
    <li><strong>Transaction ID:</strong> {{ $data['transaction']->id }}</li>
    <li><strong>Date:</strong> {{ $data['transactionDate'] }}</li>
    <li><strong>Amount:</strong> GHS {{ number_format($data['transactionAmount'], 2) }}</li>
    <li><strong>Doctor Amount:</strong> GHS {{ number_format($data['doctorAmount'], 2) }}</li>
    <li><strong>Status:</strong> {{ ucfirst($data['paymentStatus']) }}</li>
    <li><strong>Payment Method:</strong> {{ $data['paymentMethod'] }}</li>
</ul>
<p>If you have any questions, please contact {{ $data['contactEmail'] }}.</p>
<p>Thanks,<br>{{ $data['appName'] }}</p>
@endsection 