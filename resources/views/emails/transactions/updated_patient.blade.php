@extends('emails.layouts.app')
@section('title', 'Transaction Updated')
@section('content')
<p>Dear {{ $data['patient']->first_name }},</p>
<p>A transaction has been updated for you.</p>
<ul>
    <li><strong>Transaction ID:</strong> {{ $data['transaction']->id }}</li>
    <li><strong>Date:</strong> {{ $data['transactionDate'] }}</li>
    <li><strong>Amount:</strong> {{ $data['currency'] }} {{ number_format($data['transactionAmount'], 2) }}</li>
    <li><strong>Status:</strong> {{ ucfirst($data['paymentStatus']) }}</li>
    <li><strong>Payment Method:</strong> {{ $data['paymentMethod'] }}</li>
</ul>
<p>If you have any questions, please contact {{ $data['contactEmail'] }}.</p>
<p>Thanks,<br>{{ $data['appName'] }}</p>
@endsection 