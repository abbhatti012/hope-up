@extends('emails.layouts.app')
@section('title', 'Payment Processed')
@section('content')
<p>Dear Dr. {{ $data['doctor']->first_name }},</p>
<p>A payment has been processed for a transaction involving you as the doctor.</p>
<ul>
    <li><strong>Transaction ID:</strong> {{ $data['transaction']->id }}</li>
    <li><strong>Date:</strong> {{ $data['transactionDate'] }}</li>
    <li><strong>Amount:</strong> GHS {{ number_format($data['transactionAmount'], 2) }}</li>
    <li><strong>Doctor Amount:</strong> GHS {{ number_format($data['doctorAmount'], 2) }}</li>
    <li><strong>Status:</strong> {{ ucfirst($data['paymentStatus']) }}</li>
    <li><strong>Payment Method:</strong> {{ $data['paymentMethod'] }}</li>
</ul>
@if (!empty($data['transaction']->payment_proof))
    <p><strong>Payment Proof:</strong></p>
    <p>
        <a href="{{ asset($data['transaction']->payment_proof) }}" target="_blank">
            <img src="{{ asset($data['transaction']->payment_proof) }}" alt="Payment Proof" style="max-width: 400px; border: 1px solid #eee; border-radius: 6px;">
        </a>
    </p>
@endif
<p>If you have any questions, please contact {{ $data['contactEmail'] }}.</p>
<p>Thanks,<br>{{ $data['appName'] }}</p>
@endsection 