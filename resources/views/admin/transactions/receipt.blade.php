<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Receipt #{{ $transaction->id }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .receipt-header { text-align: center; margin-bottom: 20px; }
        .receipt-header img { max-width: 150px; margin-bottom: 10px; }
        .receipt-title { font-size: 24px; font-weight: bold; margin: 10px 0; }
        .receipt-info { margin: 20px 0; }
        .receipt-info p { margin: 5px 0; }
        .receipt-table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        .receipt-table th, .receipt-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .receipt-table th { background-color: #f5f5f5; }
        .text-right { text-align: right; }
        .receipt-totals { float: right; width: 300px; margin-top: 20px; }
        .receipt-footer { margin-top: 50px; text-align: center; font-size: 11px; }
    </style>
</head>
<body>
    <div class="receipt-header">
        @if(!empty($clinic['logo']))
            <img src="{!! $clinic['logo'] !!}" alt="{{ $clinic['name'] }}" style="max-width: 150px; max-height: 80px;">
        @endif
        <div class="receipt-title">{{ $clinic['name'] }}</div>
        <div>{{ $clinic['address'] }}</div>
        <div>Phone: {{ $clinic['phone'] }} | Email: {{ $clinic['email'] }}</div>
    </div>

    <div class="receipt-title" style="text-align: center; margin: 30px 0;">
        PAYMENT RECEIPT
    </div>

    <div class="receipt-info">
        <div style="float: left; width: 50%;">
            <strong>Patient:</strong><br>
            {{ $transaction->patient->first_name ?? 'N/A' }} {{ $transaction->patient->last_name ?? '' }}<br>
            {{ $transaction->patient->email ?? 'N/A' }}
        </div>
        <div style="float: right; width: 40%; text-align: right;">
            <strong>Receipt #:</strong> {{ $transaction->id }}<br>
            <strong>Date:</strong> {{ $transaction->created_at->format('F j, Y') }}<br>
            <strong>Time:</strong> {{ $transaction->created_at->format('h:i A') }}
        </div>
        <div style="clear: both;"></div>
    </div>

    <table class="receipt-table">
        <thead>
            <tr>
                <th>Description</th>
                <th class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ strtolower($transaction->source ?? '') === 'subscription' ? 'Subscription Fee' : 'Consultation Fee' }}</td>
                <td class="text-right">{{ $transaction->currency ?? 'GHS' }} {{ number_format($transaction->total_amount, 2) }}</td>
            </tr>
            <tr>
                <td>Payment Method</td>
                <td class="text-right">{{ ucfirst($transaction->payment_method ?? 'N/A') }}</td>
            </tr>
            <tr>
                <td>Status</td>
                <td class="text-right">
                    <strong>{{ ucfirst($transaction->payment_status) }}</strong>
                </td>
            </tr>
        </tbody>
    </table>

    <div class="receipt-totals">
        <table style="width: 100%;">
            <tr>
                <td><strong>Total Amount:</strong></td>
                <td class="text-right">
                    <strong>{{ $transaction->currency ?? 'GHS' }} {{ number_format($transaction->total_amount, 2) }}</strong>
                </td>
            </tr>
            @if(strtolower($transaction->source ?? '') === 'subscription')
            <tr>
                <td><strong>Doctor Amount:</strong></td>
                <td class="text-right">
                    <strong>{{ $transaction->currency ?? 'GHS' }} 0.00</strong>
                    <br><small style="color: #666;">(Subscription - No doctor payment)</small>
                </td>
            </tr>
            @else
            <tr>
                <td><strong>Doctor Amount:</strong></td>
                <td class="text-right">
                    <strong>{{ $transaction->currency ?? 'GHS' }} {{ number_format($transaction->doctor_amount, 2) }}</strong>
                </td>
            </tr>
            @endif
            <tr>
                <td><strong>Source:</strong></td>
                <td class="text-right">
                    <strong class="text-capitalize">{{ $transaction->source ?? 'N/A' }}</strong>
                </td>
            </tr>
        </table>
    </div>

    <div style="clear: both;"></div>

    <div class="receipt-footer">
        <p>Thank you for your business!</p>
        <p>{{ $clinic['name'] }} | {{ $clinic['address'] }}</p>
        <p>For any questions, please contact us at {{ $clinic['email'] }} or call {{ $clinic['phone'] }}</p>
    </div>
</body>
</html>
