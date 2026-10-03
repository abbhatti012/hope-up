@extends('admin.layout.layout')

@section('content')
<div class="app-hero-header d-flex align-items-center">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <i class="ri-home-8-line lh-1 pe-3 me-3 border-end"></i>
            <a href="{{ route('home') }}">Home</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('manage.patients') }}">Patients</a>
        </li>
        <li class="breadcrumb-item text-primary" aria-current="page">
            {{ $patient->first_name }} {{ $patient->last_name }}
        </li>
    </ol>
</div>

<div class="app-body">
    @include('admin/notifications')
    
    <!-- Patient Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-3 text-center">
                            <img src="{{ $patient->profile_photo ? asset($patient->profile_photo) : asset('default.png') }}" 
                                 class="img-fluid rounded-circle mb-3" 
                                 alt="{{ $patient->name }}" 
                                 style="width: 120px; height: 120px; object-fit: cover;">
                        </div>
                        <div class="col-md-6">
                            <h3 class="mb-1">{{ $patient->first_name }} {{ $patient->last_name }}</h3>
                            <p class="text-muted mb-2">Patient ID: {{ $patient->id }}</p>
                            <div class="d-flex flex-wrap gap-2">
                                @if($patient->is_block == 0)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Blocked</span>
                                @endif
                                @if($patient->email_verified_at)
                                    <span class="badge bg-info">Email Verified</span>
                                @else
                                    <span class="badge bg-warning">Email Not Verified</span>
                                @endif
                                @if($patient->patientDetail && $patient->patientDetail->is_ex_military)
                                    <span class="badge bg-warning">Ex-Military</span>
                                @else
                                    <span class="badge bg-secondary">Civilian</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3 text-end">
                            <div class="btn-group" role="group">
                                <a href="{{ route('patients.edit', $patient) }}" class="btn btn-primary">
                                    <i class="ri-edit-line me-1"></i> Edit
                                </a>
                                <button type="button" class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class="visually-hidden">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.login-activities.user', $patient->id) }}" target="_blank">
                                            <i class="ri-history-line me-2"></i> Login Activities
                                        </a>
                                    </li>
                                    @if($patient->is_block)
                                        <li>
                                            <a class="dropdown-item unblock-user" href="#" data-id="{{ $patient->id }}">
                                                <i class="ri-user-unfollow-line me-2"></i> Unblock
                                            </a>
                                        </li>
                                    @else
                                        <li>
                                            <a class="dropdown-item block-user" href="#" data-id="{{ $patient->id }}">
                                                <i class="ri-user-forbid-line me-2"></i> Block
                                            </a>
                                        </li>
                                    @endif
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item text-danger delete-patient" href="#" data-id="{{ $patient->id }}">
                                            <i class="ri-delete-bin-line me-2"></i> Delete
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Patient Information -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ri-user-line me-2 text-primary"></i>Personal Information
                    </h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>Email:</strong></td>
                            <td>{{ $patient->email }}</td>
                        </tr>
                        <tr>
                            <td><strong>Phone:</strong></td>
                            <td>{{ $patient->patientDetail->phone_number ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Date of Birth:</strong></td>
                            <td>
                                @if($patient->patientDetail && $patient->patientDetail->dob)
                                    {{ $patient->patientDetail->dob->format('M d, Y') }}
                                @else
                                    N/A
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Age:</strong></td>
                            <td>
                                <span class="badge bg-info">{{ $patient->patientDetail->calculated_age ?? 'N/A' }} years</span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Gender:</strong></td>
                            <td>
                                @if($patient->patientDetail->gender)
                                    <span class="badge bg-secondary">{{ ucfirst($patient->patientDetail->gender) }}</span>
                                @else
                                    N/A
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Blood Type:</strong></td>
                            <td>
                                @if($patient->patientDetail->blood_type)
                                    <span class="badge bg-danger">{{ $patient->patientDetail->blood_type }}</span>
                                @else
                                    N/A
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Last Login:</strong></td>
                            <td>{{ $patient->last_login_at ? $patient->last_login_at->diffForHumans() : 'Never' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Registered:</strong></td>
                            <td>{{ $patient->created_at->format('M d, Y') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            @if($patient->patientDetail && $patient->patientDetail->address)
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ri-map-pin-line me-2 text-primary"></i>Address
                    </h5>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $patient->patientDetail->address }}</p>
                </div>
            </div>
            @endif

            @if($patient->patientDetail && $patient->patientDetail->medical_concern)
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ri-heart-pulse-line me-2 text-primary"></i>Medical Concern
                    </h5>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $patient->patientDetail->medical_concern }}</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Subscription Information -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ri-vip-crown-line me-2 text-primary"></i>Subscription Information
                    </h5>
                </div>
                <div class="card-body">
                    @if($patient->patientDetail && $patient->patientDetail->subscription_status)
                        @php
                            $trialEnd = $patient->patientDetail->trial_ends_at ? \Carbon\Carbon::parse($patient->patientDetail->trial_ends_at) : null;
                            $daysLeft = $trialEnd ? now()->diffInDays($trialEnd, false) : null;
                            $isExpiringSoon = $daysLeft !== null && $daysLeft <= 7 && $daysLeft > 0;
                            $isExpired = $daysLeft !== null && $daysLeft <= 0;
                        @endphp
                        
                        <div class="mb-3">
                            <strong>Status:</strong>
                            @if($patient->patientDetail->subscription_status === 'free')
                                <span class="badge bg-info @if($isExpiringSoon) bg-warning @elseif($isExpired) bg-danger @endif">
                                    <i class="ri-time-line me-1"></i>
                                    @if($isExpired) Expired
                                    @elseif($isExpiringSoon) Expiring Soon
                                    @else Trial
                                    @endif
                                </span>
                            @elseif($patient->patientDetail->subscription_status === 'active')
                                <span class="badge bg-success">
                                    <i class="ri-check-line me-1"></i>Active
                                </span>
                            @elseif($patient->patientDetail->subscription_status === 'cancelled')
                                <span class="badge bg-warning">
                                    <i class="ri-close-line me-1"></i>Cancelled
                                </span>
                            @elseif($patient->patientDetail->subscription_status === 'expired')
                                <span class="badge bg-danger">
                                    <i class="ri-error-warning-line me-1"></i>Expired
                                </span>
                            @endif
                        </div>
                        
                        <div class="mb-3">
                            <strong>Plan:</strong>
                            @if($patient->patientDetail->subscription_plan)
                                <span class="badge bg-primary">{{ ucfirst($patient->patientDetail->subscription_plan) }}</span>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </div>
                        
                        @if($patient->patientDetail->subscription_start_date)
                        <div class="mb-3">
                            <strong>Start Date:</strong>
                            <br>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($patient->patientDetail->subscription_start_date)->format('M d, Y') }}</small>
                        </div>
                        @endif
                        
                        @if($patient->patientDetail->subscription_end_date)
                        <div class="mb-3">
                            <strong>End Date:</strong>
                            <br>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($patient->patientDetail->subscription_end_date)->format('M d, Y') }}</small>
                        </div>
                        @endif
                        
                        @if($patient->patientDetail->trial_ends_at)
                        <div class="mb-3">
                            <strong>Trial Ends:</strong>
                            <br>
                            <small class="text-muted">
                                {{ \Carbon\Carbon::parse($patient->patientDetail->trial_ends_at)->format('M d, Y') }}
                                @if($daysLeft !== null)
                                    <span class="badge bg-{{ $isExpired ? 'danger' : ($isExpiringSoon ? 'warning' : 'info') }} ms-1">
                                        {{ $daysLeft > 0 ? $daysLeft . ' days left' : 'Expired' }}
                                    </span>
                                @endif
                            </small>
                        </div>
                        @endif
                        
                        @if($patient->patientDetail->subscription_amount)
                        <div class="mb-3">
                            <strong>Amount Paid:</strong>
                            <br>
                            <small class="text-success">${{ number_format($patient->patientDetail->subscription_amount, 2) }}</small>
                        </div>
                        @endif
                        
                        @if($patient->patientDetail->subscription_payment_method)
                        <div class="mb-3">
                            <strong>Payment Method:</strong>
                            <br>
                            <small class="text-muted">{{ ucfirst($patient->patientDetail->subscription_payment_method) }}</small>
                        </div>
                        @endif
                        
                        @if($patient->patientDetail->subscription_transaction_id)
                        <div class="mb-3">
                            <strong>Transaction ID:</strong>
                            <br>
                            <small class="text-muted">{{ $patient->patientDetail->subscription_transaction_id }}</small>
                        </div>
                        @endif
                        
                        <!-- Available Plans Section -->
                        <div class="mt-4 pt-3 border-top">
                            <h6 class="text-muted mb-3">
                                <i class="ri-price-tag-3-line me-1"></i>Available Plans
                            </h6>
                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="card border-primary">
                                        <div class="card-body p-2 text-center">
                                            <h6 class="text-primary mb-1">Basic Plan</h6>
                                            <small class="text-muted">$9.99/month</small>
                                            @if($patient->patientDetail->subscription_plan === 'basic')
                                                <div class="mt-1">
                                                    <span class="badge bg-success">Current Plan</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="card border-warning">
                                        <div class="card-body p-2 text-center">
                                            <h6 class="text-warning mb-1">Premium Plan</h6>
                                            <small class="text-muted">$19.99/month</small>
                                            @if($patient->patientDetail->subscription_plan === 'premium')
                                                <div class="mt-1">
                                                    <span class="badge bg-success">Current Plan</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-2">
                                <small class="text-muted">
                                    <i class="ri-information-line me-1"></i>
                                    Plans can be managed through the mobile app
                                </small>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="ri-vip-crown-line fs-1 text-muted mb-2"></i>
                            <p class="text-muted mb-0">No subscription found</p>
                            
                            <!-- Available Plans for New Users -->
                            <div class="mt-3">
                                <h6 class="text-muted mb-2">Available Plans</h6>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <div class="card border-primary">
                                            <div class="card-body p-2 text-center">
                                                <h6 class="text-primary mb-1">Basic Plan</h6>
                                                <small class="text-muted">$9.99/month</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="card border-warning">
                                            <div class="card-body p-2 text-center">
                                                <h6 class="text-warning mb-1">Premium Plan</h6>
                                                <small class="text-muted">$19.99/month</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <small class="text-muted mt-2 d-block">
                                    <i class="ri-information-line me-1"></i>
                                    Plans can be managed through the mobile app
                                </small>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Account Balance -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ri-wallet-3-line me-2 text-primary"></i>
                        @if(($patient->net_balance ?? 0) < 0)
                            Total Spent
                        @else
                            Account Balance
                        @endif
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <h3 class="@if(($patient->net_balance ?? 0) < 0) text-danger @else text-primary @endif mb-0">
                            ${{ number_format(abs($patient->net_balance ?? 0), 2) }}
                        </h3>
                        <small class="text-muted">
                            @if(($patient->net_balance ?? 0) < 0)
                                Total Amount Spent
                            @else
                                Available Balance
                            @endif
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ri-bar-chart-line me-2 text-primary"></i>Quick Stats
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="border-end">
                                <h4 class="text-primary mb-0">{{ $appointments->count() }}</h4>
                                <small class="text-muted">Appointments</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <h4 class="text-success mb-0">{{ $transactions->count() }}</h4>
                            <small class="text-muted">Transactions</small>
                        </div>
                        <div class="col-6">
                            <h4 class="text-info mb-0">{{ $patient->last_login_at ? $patient->last_login_at->diffInDays(now()) : 'N/A' }}</h4>
                            <small class="text-muted">Days Since Login</small>
                        </div>
                        <div class="col-6">
                            <h4 class="text-warning mb-0">{{ $patient->created_at->diffInDays(now()) }}</h4>
                            <small class="text-muted">Days Registered</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Appointments and Transactions Tabs -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" id="patientTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="appointments-tab" data-bs-toggle="tab" data-bs-target="#appointments" type="button" role="tab" aria-controls="appointments" aria-selected="true">
                                <i class="ri-calendar-line me-1"></i>Appointments ({{ $appointments->count() }})
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="transactions-tab" data-bs-toggle="tab" data-bs-target="#transactions" type="button" role="tab" aria-controls="transactions" aria-selected="false">
                                <i class="ri-exchange-dollar-line me-1"></i>Transactions ({{ $transactions->count() }})
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="patientTabsContent">
                        <!-- Appointments Tab -->
                        <div class="tab-pane fade show active" id="appointments" role="tabpanel" aria-labelledby="appointments-tab">
                            @if($appointments->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Time</th>
                                                <th>Doctor</th>
                                                <th>Status</th>
                                                <th>Treatment Type</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($appointments as $appointment)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}</td>
                                                <td>{{ $appointment->appointment_time }}</td>
                                                <td>
                                                    @if($appointment->doctor)
                                                        <a href="{{ route('doctor-detail', $appointment->doctor->id) }}" class="text-decoration-none text-primary" target="_blank">
                                                            {{ $appointment->doctor->first_name }} {{ $appointment->doctor->last_name }}
                                                        </a>
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge bg-{{ $appointment->status === 'completed' ? 'success' : ($appointment->status === 'pending' ? 'warning' : 'danger') }}">
                                                        {{ ucfirst($appointment->status) }}
                                                    </span>
                                                </td>
                                                <td>{{ ucfirst($appointment->treatment_type) }}</td>
                                                <td>
                                                    <a href="{{ route('appointments.edit', $appointment) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                                        <i class="ri-edit-line"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="ri-calendar-line fs-1 text-muted mb-2"></i>
                                    <p class="text-muted mb-0">No appointments found for this patient</p>
                                </div>
                            @endif
                        </div>

                        <!-- Transactions Tab -->
                        <div class="tab-pane fade" id="transactions" role="tabpanel" aria-labelledby="transactions-tab">
                            @if($transactions->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                                <th>Source</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($transactions as $transaction)
                                            <tr>
                                                <td>{{ $transaction->created_at->format('M d, Y') }}</td>
                                                <td>${{ number_format($transaction->total_amount, 2) }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $transaction->payment_status === 'completed' ? 'success' : ($transaction->payment_status === 'pending' ? 'warning' : 'danger') }}">
                                                        {{ ucfirst($transaction->payment_status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-info">{{ ucfirst($transaction->source) }}</span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('transactions.receipt', $transaction) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                                        <i class="ri-download-line"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="ri-exchange-dollar-line fs-1 text-muted mb-2"></i>
                                    <p class="text-muted mb-0">No transactions found for this patient</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deletePatientModal" tabindex="-1" aria-labelledby="deletePatientModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deletePatientModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this patient? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deletePatientForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="ri-delete-bin-line me-1"></i> Delete Patient
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Handle block/unblock user
    $('.block-user, .unblock-user').on('click', function(e) {
        e.preventDefault();
        const userId = $(this).data('id');
        const action = $(this).hasClass('block-user') ? 'block' : 'unblock';
        
        $.ajax({
            url: `/admin/users/${userId}/${action}`,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function() {
                alert('An error occurred. Please try again.');
            }
        });
    });

    // Handle delete patient
    $('.delete-patient').on('click', function(e) {
        e.preventDefault();
        const patientId = $(this).data('id');
        
        $('#deletePatientForm').attr('action', `/patients/${patientId}`);
        $('#deletePatientModal').modal('show');
    });
});
</script>
@endsection