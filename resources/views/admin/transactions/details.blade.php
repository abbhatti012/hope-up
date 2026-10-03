@extends('admin.layout.layout')
@section('content')

<div class="app-hero-header d-flex align-items-center">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <i class="ri-home-8-line lh-1 pe-3 me-3 border-end"></i>
            <a href="{{ route('/') }}">Home</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('manage-mhc') }}">Transactions</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">
            Transaction Details
        </li>
    </ol>
</div>

<div class="app-body">
    @include('admin/notifications')
    
    <!-- Transaction Header -->
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                            <i class="ri-bank-card-line text-primary fs-4"></i>
                        </div>
                        <div>
                            <h4 class="mb-1">Transaction #{{ $transaction->id }}</h4>
                            <p class="text-muted mb-0">{{ $transaction->transaction_id ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="d-flex flex-column align-items-md-end">
                        <span class="badge fs-6 px-3 py-2 mb-2 bg-{{ 
                            $transaction->payment_status === 'completed' ? 'success' : 
                            ($transaction->payment_status === 'pending' ? 'warning' : 'danger') 
                        }}">
                            <i class="ri-{{ 
                                $transaction->payment_status === 'completed' ? 'check-line' : 
                                ($transaction->payment_status === 'pending' ? 'time-line' : 'close-line') 
                            }} me-2"></i>
                            {{ ucfirst($transaction->payment_status) }}
                        </span>
                        <p class="text-muted mb-0">{{ \Carbon\Carbon::parse($transaction->created_at)->format('M d, Y h:i A') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Transaction Information -->
        <div class="col-lg-8">
            <!-- Payment Details Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light border-0 py-3">
                    <div class="d-flex align-items-center">
                        <i class="ri-money-dollar-circle-line text-primary me-2"></i>
                        <h6 class="mb-0 fw-semibold">Payment Details</h6>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center p-3 bg-light rounded">
                                <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="ri-user-line text-primary"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Patient</small>
                                    <strong>{{ $transaction->patient->first_name }} {{ $transaction->patient->last_name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $transaction->patient->email }}</small>
                                </div>
                            </div>
                        </div>
                        
                        @if($transaction->doctor)
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center p-3 bg-light rounded">
                                <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="ri-user-star-line text-success"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Doctor</small>
                                    <strong>{{ $transaction->doctor->first_name }} {{ $transaction->doctor->last_name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $transaction->doctor->email }}</small>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        @if($transaction->appointment)
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center p-3 bg-light rounded">
                                <div class="bg-info bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="ri-calendar-line text-info"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Appointment</small>
                                    <strong>{{ \Carbon\Carbon::parse($transaction->appointment->appointment_date)->format('M d, Y h:i A') }}</strong>
                                    <br>
                                    <small class="text-muted">Type: {{ ucfirst($transaction->appointment->type) }}</small>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Payment Breakdown -->
                    <div class="mt-4">
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr class="border-bottom">
                                        <td class="py-3">
                                            <div class="d-flex align-items-center">
                                                <i class="ri-stethoscope-line text-primary me-3"></i>
                                                <span class="fw-medium">{{ strtolower($transaction->source ?? '') === 'subscription' ? 'Subscription Fee' : 'Consultation Fee' }}</span>
                                            </div>
                                        </td>
                                        <td class="text-end py-3">
                                            <span class="fw-semibold fs-5">{{ number_format($transaction->total_amount, 2) }} {{ strtoupper($transaction->currency) }}</span>
                                        </td>
                                    </tr>
                                    @if(strtolower($transaction->source ?? '') !== 'subscription')
                                    <tr class="border-bottom">
                                        <td class="py-3">
                                            <div class="d-flex align-items-center">
                                                <i class="ri-user-star-line text-success me-3"></i>
                                                <span class="fw-medium">Doctor Amount</span>
                                            </div>
                                        </td>
                                        <td class="text-end py-3">
                                            <span class="fw-semibold fs-5 text-success">{{ number_format($transaction->doctor_amount ?? 0, 2) }} {{ strtoupper($transaction->currency) }}</span>
                                        </td>
                                    </tr>
                                    @else
                                    <tr class="border-bottom">
                                        <td class="py-3">
                                            <div class="d-flex align-items-center">
                                                <i class="ri-user-star-line text-muted me-3"></i>
                                                <span class="fw-medium text-muted">Doctor Amount</span>
                                            </div>
                                        </td>
                                        <td class="text-end py-3">
                                            <span class="fw-semibold fs-5 text-muted">{{ number_format(0, 2) }} {{ strtoupper($transaction->currency) }}</span>
                                            <br><small class="text-muted">(Subscription - No doctor payment)</small>
                                        </td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td class="py-2">
                                            <span class="fw-bold fs-6">Total Amount</span>
                                        </td>
                                        <td class="text-end py-2">
                                            <span class="fw-bold fs-5 text-primary">{{ number_format($transaction->total_amount, 2) }} {{ strtoupper($transaction->currency) }}</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    @if($transaction->notes)
                    <div class="mt-4 p-3 bg-light rounded">
                        <div class="d-flex align-items-start">
                            <i class="ri-file-text-line text-muted me-2 mt-1"></i>
                            <div>
                                <small class="text-muted d-block mb-1">Notes</small>
                                <p class="mb-0">{{ $transaction->notes }}</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Transaction Summary -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light border-0 py-3">
                    <div class="d-flex align-items-center">
                        <i class="ri-information-line text-primary me-2"></i>
                        <h6 class="mb-0 fw-semibold">Transaction Summary</h6>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                                <span class="text-muted">Transaction ID</span>
                                <span class="fw-medium">{{ $transaction->id }}</span>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                                <span class="text-muted">Reference</span>
                                <span class="fw-medium">{{ $transaction->transaction_id ?? 'N/A' }}</span>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                                <span class="text-muted">Date & Time</span>
                                <span class="fw-medium">{{ \Carbon\Carbon::parse($transaction->created_at)->format('M d, Y') }}</span>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                                <span class="text-muted">Time</span>
                                <span class="fw-medium">{{ \Carbon\Carbon::parse($transaction->created_at)->format('h:i A') }}</span>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                                <span class="text-muted">Status</span>
                                <span class="badge bg-{{ 
                                    $transaction->payment_status === 'completed' ? 'success' : 
                                    ($transaction->payment_status === 'pending' ? 'warning' : 'danger') 
                                }}">
                                    {{ ucfirst($transaction->payment_status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-grid gap-2">
                        <a href="{{ route('transactions.receipt', $transaction) }}" target="_blank" class="btn btn-primary">
                            <i class="ri-download-line me-2"></i> Download Receipt
                        </a>
                        <button type="button" class="btn btn-outline-secondary" onclick="history.back()">
                            <i class="ri-arrow-left-line me-2"></i> Back to Transactions
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
}

.bg-opacity-10 {
    background-color: rgba(var(--bs-primary-rgb), 0.1) !important;
}

.bg-success.bg-opacity-10 {
    background-color: rgba(var(--bs-success-rgb), 0.1) !important;
}

.bg-info.bg-opacity-10 {
    background-color: rgba(var(--bs-info-rgb), 0.1) !important;
}

.table-borderless td {
    border: none;
}

.fs-6 {
    font-size: 1rem !important;
}

.fs-5 {
    font-size: 1.25rem !important;
}
</style>

@endsection