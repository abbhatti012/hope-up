@extends('admin.layout.layout')
@section('links')
<link rel="stylesheet" href="{{asset('assets/vendor/datatables/dataTables.bs5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/datatables/dataTables.bs5-custom.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/datatables/buttons/dataTables.bs5-custom.css')}}">
<style>
    .action-buttons {
        display: flex;
        gap: 5px;
        flex-wrap: wrap;
    }
    
    .btn-action {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
    
    .text-truncate {
        max-width: 150px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
    @media (max-width: 768px) {
        .table-responsive {
            font-size: 0.875rem;
        }
        
        .action-buttons {
            flex-direction: column;
            gap: 2px;
        }
        
        .btn-action {
            padding: 0.2rem 0.4rem;
            font-size: 0.8rem;
        }
    }

    /* Modal backdrop fix */
    .modal-backdrop {
        z-index: 1040;
    }
    
    .modal {
        z-index: 1050;
    }
    
    /* Ensure body scroll is restored */
    body.modal-open {
        overflow: hidden;
    }
    
    body:not(.modal-open) {
        overflow: auto !important;
    }
</style>
@endsection

@section('content')
    <div class="app-hero-header d-flex align-items-center">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <i class="ri-home-8-line lh-1 pe-3 me-3 border-end"></i>
                <a href="{{ route('home') }}">Home</a>
            </li>
            <li class="breadcrumb-item text-primary" aria-current="page">
                Patients
            </li>
        </ol>
    </div>

    <div class="app-body">
        @include('admin/notifications')
        <div class="row gx-3">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header bg-white py-3">
                        <div class="d-flex flex-column flex-md-row align-items-center justify-content-between">
                            <h5 class="card-title mb-3 mb-md-0">
                                <i class="ri-team-line me-2 text-primary"></i>All Patients
                                <span class="badge bg-soft-primary text-primary ms-2">{{ $patients->total() }} Total</span>
                            </h5>
                            <a href="{{ route('patients.create') }}" class="btn btn-primary">
                                <i class="ri-user-add-line me-1"></i> Add New Patient
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Search and Filter Card -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body p-3">
                                <div class="row g-3">
                                    <div class="col-md-8">
                                        <form method="GET" action="{{ route('manage.patients') }}" class="search-form">
                                            <div class="input-group">
                                                <span class="input-group-text bg-white border-end-0">
                                                    <i class="ri-search-line text-muted"></i>
                                                </span>
                                                <input type="search" 
                                                       name="search" 
                                                       class="form-control border-start-0 ps-0" 
                                                       placeholder="Search patients by name, email, or phone..." 
                                                       value="{{ request()->search }}">
                                                @if(request()->search)
                                                <a href="{{ route('manage.patients') }}" class="btn btn-outline-secondary" type="button">
                                                    <i class="ri-close-line"></i>
                                                </a>
                                                @endif
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="ri-search-2-line me-1"></i> Search
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-md-4">
                                        <form method="GET" action="{{ route('manage.patients') }}">
                                            <div class="input-group">
                                                <span class="input-group-text bg-white border-end-0">
                                                    <i class="ri-list-settings-line text-muted"></i>
                                                </span>
                                                <select name="per_page" class="form-select" onchange="this.form.submit()">
                                                    <option value="10" @if(request()->per_page == 10) selected @endif>Show: 10</option>
                                                    <option value="25" @if(request()->per_page == 25) selected @endif>Show: 25</option>
                                                    <option value="50" @if(!request()->per_page || request()->per_page == 50) selected @endif>Show: 50</option>
                                                    <option value="100" @if(request()->per_page == 100) selected @endif>Show: 100</option>
                                                </select>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                @if(request()->search)
                                <div class="mt-2">
                                    <small class="text-muted">
                                        <i class="ri-information-line me-1"></i>
                                        {{ $patients->total() }} result(s) found for "{{ request()->search }}"
                                    </small>
                                </div>
                                @endif
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Patient</th>
                                        <th>Contact</th>
                                        <th>Age</th>
                                        <th>Blood Type</th>
                                        <th>Address</th>
                                        <th>Military</th>
                                        <th>Status</th>
                                        <th>Subscription</th>
                                        <th>Last Visit</th>
                                        <th>Login Activities</th>
                                        <th>View Answers</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($patients as $patient)
                                    <tr>
                                        <td>{{ $patient->id }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $patient->profile_photo ? asset($patient->profile_photo) : asset('default.png') }}" 
                                                    class="img-shadow img-2x rounded-circle me-2" 
                                                    alt="{{ $patient->name }}" 
                                                    style="width: 40px; height: 40px;">
                                                <div>
                                                    <h6 class="mb-0">
                                                        <a href="{{ route('patients.show', $patient) }}" class="text-decoration-none text-primary">
                                                            {{ $patient->first_name }} {{ $patient->last_name }}
                                                        </a>
                                                    </h6>
                                                    <small class="text-muted">{{ $patient->patientDetail->gender ? ucfirst($patient->patientDetail->gender) : 'N/A' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <a href="mailto:{{ $patient->email }}" class="text-decoration-none text-primary">
                                                    {{ $patient->email }}
                                                </a>
                                            </div>
                                            <small class="text-muted">{{ $patient->patientDetail->phone_number ?? 'N/A' }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $patient->patientDetail->calculated_age ?? 'N/A' }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-warning">{{ $patient->patientDetail->blood_type ?? 'N/A' }}</span>
                                        </td>
                                        <td>
                                            <div class="text-truncate" style="max-width: 150px;" title="{{ $patient->patientDetail->address ?? 'N/A' }}">
                                                {{ $patient->patientDetail->address ?? 'N/A' }}
                                            </div>
                                        </td>
                                        <td>
                                            @if($patient->patientDetail && $patient->patientDetail->is_ex_military)
                                                <span class="badge bg-danger">Ex-Military</span>
                                            @else
                                                <span class="badge bg-secondary">Civilian</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($patient->is_block == 0)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($patient->patientDetail && $patient->patientDetail->subscription_status)
                                                @if($patient->patientDetail->subscription_status === 'free')
                                                    @php
                                                        $trialEnd = $patient->patientDetail->trial_ends_at ? \Carbon\Carbon::parse($patient->patientDetail->trial_ends_at) : null;
                                                        $daysLeft = $trialEnd ? now()->diffInDays($trialEnd, false) : null;
                                                        $isExpiringSoon = $daysLeft !== null && $daysLeft <= 7 && $daysLeft > 0;
                                                        $isExpired = $daysLeft !== null && $daysLeft <= 0;
                                                    @endphp
                                                    <span class="badge bg-info @if($isExpiringSoon) bg-warning @elseif($isExpired) bg-danger @endif" 
                                                          data-bs-toggle="tooltip" 
                                                          data-bs-placement="top" 
                                                          title="@if($trialEnd) Trial ends {{ $trialEnd->format('M d, Y') }} @endif">
                                                        <i class="ri-time-line me-1"></i>
                                                        @if($isExpired) Expired
                                                        @elseif($isExpiringSoon) Expiring Soon
                                                        @else Trial
                                                        @endif
                                                    </span>
                                                    <br>
                                                    <small class="text-muted">
                                                        {{ ucfirst($patient->patientDetail->subscription_plan ?? 'basic') }}
                                                    </small>
                                                @elseif($patient->patientDetail->subscription_status === 'active')
                                                    <span class="badge bg-success">
                                                        <i class="ri-check-line me-1"></i>Active
                                                    </span>
                                                    <br>
                                                    <small class="text-muted">
                                                        {{ ucfirst($patient->patientDetail->subscription_plan ?? 'basic') }}
                                                    </small>
                                                @elseif($patient->patientDetail->subscription_status === 'cancelled')
                                                    <span class="badge bg-warning">
                                                        <i class="ri-close-line me-1"></i>Cancelled
                                                    </span>
                                                    <br>
                                                    <small class="text-muted">
                                                        {{ ucfirst($patient->patientDetail->subscription_plan ?? 'basic') }}
                                                    </small>
                                                @elseif($patient->patientDetail->subscription_status === 'expired')
                                                    <span class="badge bg-danger">
                                                        <i class="ri-error-warning-line me-1"></i>Expired
                                                    </span>
                                                    <br>
                                                    <small class="text-muted">
                                                        {{ ucfirst($patient->patientDetail->subscription_plan ?? 'basic') }}
                                                    </small>
                                                @endif
                                            @else
                                                <span class="badge bg-secondary">
                                                    <i class="ri-question-line me-1"></i>No Subscription
                                                </span>
                                            @endif
                                        </td>
                                        <td>{{ $patient->last_login_at ? $patient->last_login_at->diffForHumans() : 'Never' }}</td>
                                        <td>
                                            <a href="{{ route('admin.login-activities.user', $patient->id) }}" 
                                               class="btn btn-sm btn-outline-info" 
                                               data-bs-toggle="tooltip" 
                                               data-bs-placement="top" 
                                               title="View Login Activities"
                                               target="_blank">
                                                <i class="ri-history-line"></i> Activities
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('user.answers', $patient->id) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i class="ri-eye-line"></i> View Answers
                                            </a>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                            <a href="{{ route('patients.show', $patient) }}"
                                                        class="btn btn-sm btn-icon btn-outline-primary view-transaction" 
                                                        data-bs-toggle="tooltip" 
                                                        title="View Details"
                                                        data-id="{{ $patient->id }}">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-icon btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="ri-more-2-line"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('patients.edit', $patient) }}">
                                                            <i class="ri-edit-line me-2"></i> Edit
                                                        </a>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
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
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="12" class="text-center py-4">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="ri-user-search-line fs-1 text-muted mb-2"></i>
                                                <h5 class="mb-1">No patients found</h5>
                                                <p class="text-muted mb-0">Add a new patient to get started</p>
                                                <a href="{{ route('patients.create') }}" class="btn btn-primary mt-3">
                                                    <i class="ri-user-add-line me-1"></i> Add Patient
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if($patients->hasPages())
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="text-muted">
                                Showing {{ $patients->firstItem() }} to {{ $patients->lastItem() }} of {{ $patients->total() }} entries
                            </div>
                            <div>
                                {{ $patients->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                        @endif
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

    <!-- Plan Selection Modal -->
    <div class="modal fade" id="planSelectionModal" tabindex="-1" aria-labelledby="planSelectionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="planSelectionModalLabel">Select Subscription Plan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card border-primary">
                                <div class="card-header bg-primary text-white text-center">
                                    <h6 class="mb-0">Basic</h6>
                                </div>
                                <div class="card-body text-center">
                                    <h4 class="text-primary">$19.99</h4>
                                    <p class="text-muted">1 month access</p>
                                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="selectPlan('basic')">
                                        Select Basic
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-warning">
                                <div class="card-header bg-warning text-white text-center">
                                    <h6 class="mb-0">Premium</h6>
                                </div>
                                <div class="card-body text-center">
                                    <h4 class="text-warning">$199.99</h4>
                                    <p class="text-muted">12 months access</p>
                                    <button type="button" class="btn btn-outline-warning btn-sm" onclick="selectPlan('premium')">
                                        Select Premium
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="{{asset('assets/vendor/datatables/dataTables.min.js')}}"></script>
<script src="{{asset('assets/vendor/datatables/dataTables.bootstrap.min.js')}}"></script>
<script>
    $(document).ready(function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Handle delete patient
        $('.delete-patient').on('click', function() {
        });

        // Clear search
        $('#clearSearch').on('click', function() {
            $('#searchInput').val('').trigger('keyup');
        });

        // View patient details
        // Handle other modals cleanup
        $('#deletePatientModal, #planSelectionModal').on('hidden.bs.modal', function () {
            // Remove any remaining backdrop
            $('.modal-backdrop').remove();
            // Restore body scroll
            $('body').removeClass('modal-open').css('overflow', '');
            // Remove any inline styles that might have been added
            $('body').removeAttr('style');
        });

        // Subscription management functions
        let currentPatientId = null;

        window.upgradeSubscription = function(patientId) {
            currentPatientId = patientId;
            // Show plan selection modal
            const modal = new bootstrap.Modal(document.getElementById('planSelectionModal'));
            modal.show();
        };

        window.selectPlan = function(plan) {
            if (confirm(`Are you sure you want to upgrade this patient's subscription to ${plan} plan?`)) {
                $.ajax({
                    url: `/api/v1/subscriptions/patient/${currentPatientId}/upgrade`,
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="token"]').attr('content'),
                        'Content-Type': 'application/json'
                    },
                    data: JSON.stringify({
                        plan: plan,
                        amount: null,
                        payment_method: 'admin_upgrade',
                        transaction_id: null
                    }),
                    success: function(response) {
                        if (response.success) {
                            alert('Subscription upgraded successfully!');
                            // Close modal and ensure proper cleanup
                            const planModal = bootstrap.Modal.getInstance(document.getElementById('planSelectionModal'));
                            if (planModal) {
                                planModal.hide();
                                // Force cleanup after a short delay
                                setTimeout(function() {
                                    $('.modal-backdrop').remove();
                                    $('body').removeClass('modal-open').css('overflow', '');
                                    $('body').removeAttr('style');
                                }, 150);
                            }
                            // Reload patient details
                            $('.view-patient[data-id="' + currentPatientId + '"]').click();
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            alert('Error: ' + xhr.responseJSON.message);
                        } else {
                            alert('Error upgrading subscription. Please try again.');
                        }
                    }
                });
            }
        };

        window.cancelSubscription = function(patientId) {
            if (confirm('Are you sure you want to cancel this patient\'s subscription?')) {
                $.ajax({
                    url: `/api/v1/subscriptions/patient/${patientId}/cancel`,
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="token"]').attr('content'),
                        'Content-Type': 'application/json'
                    },
                    data: JSON.stringify({}),
                    success: function(response) {
                        if (response.success) {
                            alert('Subscription cancelled successfully!');
                            // Reload patient details
                            $('.view-patient[data-id="' + patientId + '"]').click();
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            alert('Error: ' + xhr.responseJSON.message);
                        } else {
                            alert('Error cancelling subscription. Please try again.');
                        }
                    }
                });
            }
        };
    });

    // Handle block user
    $(document).on('click', '.block-user', function() {
        const userId = $(this).data('id');
        const button = $(this);
        
        if (confirm('Are you sure you want to block this user? They will not be able to log in until unblocked.')) {
            $.ajax({
                url: `/admin/users/${userId}/block`,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="token"]').attr('content'),
                    'Accept': 'application/json'
                },
                beforeSend: function() {
                    button.prop('disabled', true).html('<i class="ri-loader-4-line ri-spin"></i> Blocking...');
                },
                success: function(response) {
                    if (response.success) {
                        // Show success message
                        const toast = `
                            <div class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                                <div class="d-flex">
                                    <div class="toast-body">
                                        <i class="ri-checkbox-circle-fill me-2"></i>
                                        ${response.message}
                                    </div>
                                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                                </div>
                            </div>`;
                        
                        // Add toast to container and show
                        $('.toast-container').append(toast);
                        const toastElement = $('.toast').last()[0];
                        const toastBootstrap = new bootstrap.Toast(toastElement);
                        toastBootstrap.show();
                        
                        // Remove toast after it's hidden
                        $(toastElement).on('hidden.bs.toast', function () {
                            $(this).remove();
                        });
                        
                        // Reload the page after a short delay to show the updated status
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'Failed to block user. Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    alert(errorMessage);
                    button.prop('disabled', false).html('<i class="ri-user-forbid-line"></i>');
                }
            });
        }
    });

    // Handle unblock user
    $(document).on('click', '.unblock-user', function() {
        const userId = $(this).data('id');
        const button = $(this);
        
        if (confirm('Are you sure you want to unblock this user? They will be able to log in again.')) {
            $.ajax({
                url: `/admin/users/${userId}/unblock`,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="token"]').attr('content'),
                    'Accept': 'application/json'
                },
                beforeSend: function() {
                    button.prop('disabled', true).html('<i class="ri-loader-4-line ri-spin"></i> Unblocking...');
                },
                success: function(response) {
                    if (response.success) {
                        // Show success message
                        const toast = `
                            <div class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                                <div class="d-flex">
                                    <div class="toast-body">
                                        <i class="ri-checkbox-circle-fill me-2"></i>
                                        ${response.message}
                                    </div>
                                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                                </div>
                            </div>`;
                        
                        // Add toast to container and show
                        $('.toast-container').append(toast);
                        const toastElement = $('.toast').last()[0];
                        const toastBootstrap = new bootstrap.Toast(toastElement);
                        toastBootstrap.show();
                        
                        // Remove toast after it's hidden
                        $(toastElement).on('hidden.bs.toast', function () {
                            $(this).remove();
                        });
                        
                        // Reload the page after a short delay to show the updated status
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'Failed to unblock user. Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    alert(errorMessage);
                    button.prop('disabled', false).html('<i class="ri-user-unfollow-line"></i>');
                }
            });
        }
    });
</script>
@endsection
