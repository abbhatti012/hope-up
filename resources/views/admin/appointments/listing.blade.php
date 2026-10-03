@extends('admin.layout.layout')

@section('links')
<link rel="stylesheet" href="{{asset('assets/vendor/datatables/dataTables.bs5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/datatables/dataTables.bs5-custom.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/datatables/buttons/dataTables.bs5-custom.css')}}">
@endsection

@section('content')
<div class="app-content">
    <div class="app-hero-header d-flex align-items-center">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <i class="ri-home-8-line lh-1 pe-3 me-3 border-end"></i>
                <a href="{{ route('/') }}">Home</a>
            </li>
            <li class="breadcrumb-item text-primary" aria-current="page">
                Appointments
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
                                <i class="ri-calendar-line me-2 text-primary"></i>All Appointments
                                <span class="badge bg-soft-primary text-primary ms-2">{{ $appointments->total() }} Total</span>
                            </h5>
                            <div class="d-flex gap-2">
                                <a href="{{ route('export-appointments', request()->query()) }}" class="btn btn-outline-secondary">
                                    <i class="ri-download-line me-1"></i> Export
                                </a>
                                <a href="{{ route('appointments.create') }}" class="btn btn-primary">
                                    <i class="ri-add-line me-1"></i> New Appointment
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- Search and Filter Card -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body p-3">
                                <!-- Search and Rows Per Page -->
                                <div class="row g-3 mb-3">
                                    <div class="col-md-8">
                                        <form method="GET" action="{{ route('manage-appointments') }}" class="search-form">
                                            <div class="input-group">
                                                <span class="input-group-text bg-white border-end-0">
                                                    <i class="ri-search-line text-muted"></i>
                                                </span>
                                                <input type="search" 
                                                       name="search" 
                                                       class="form-control border-start-0 ps-0" 
                                                       placeholder="Search appointments by patient, doctor, or ID..." 
                                                       value="{{ request()->search }}">
                                                @if(request()->search)
                                                <a href="{{ route('manage-appointments') }}" class="btn btn-outline-secondary" type="button">
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
                                        <form method="GET" action="{{ route('manage-appointments') }}">
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

                                <!-- Filters Toggle -->
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="mb-0">
                                        <i class="ri-filter-2-line me-2"></i>Filters
                                    </h6>
                                    <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#filtersCollapse" aria-expanded="false" aria-controls="filtersCollapse">
                                        <i class="ri-filter-2-line me-1"></i>
                                        <span class="filter-count">{{ count(array_filter(request()->only(['status', 'type', 'date_from', 'date_to']))) > 0 ? '('.count(array_filter(request()->only(['status', 'type', 'date_from', 'date_to']))).')' : '' }}</span>
                                    </button>
                                </div>

                                <!-- Filters Form -->
                                <div class="collapse {{ request()->has('status') || request()->has('type') || request()->has('date_from') || request()->has('date_to') ? 'show' : '' }}" id="filtersCollapse">
                                    <form method="GET" action="{{ route('manage-appointments') }}">
                                        @if(request()->search)
                                            <input type="hidden" name="search" value="{{ request()->search }}">
                                        @endif
                                        @if(request()->per_page)
                                            <input type="hidden" name="per_page" value="{{ request()->per_page }}">
                                        @endif
                                        
                                        <div class="row g-3">
                                            <!-- Status Filter -->
                                            <div class="col-md-3">
                                                <label class="form-label small text-muted mb-1">Status</label>
                                                <select name="status" class="form-select">
                                                    <option value="">All Statuses</option>
                                                    @foreach(\App\Models\Appointment::getStatuses() as $value => $label)
                                                        <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>{{ $label }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <!-- Type Filter -->
                                            <div class="col-md-3">
                                                <label class="form-label small text-muted mb-1">Type</label>
                                                <select name="type" class="form-select">
                                                    <option value="">All Types</option>
                                                    <option value="general" {{ request('type') == 'general' ? 'selected' : '' }}>General</option>
                                                    <option value="opd" {{ request('type') == 'opd' ? 'selected' : '' }}>OPD</option>
                                                </select>
                                            </div>

                                            <!-- Date Range -->
                                            <div class="col-md-6">
                                                <label class="form-label small text-muted mb-1">Date Range</label>
                                                <div class="input-group">
                                                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" placeholder="From">
                                                    <span class="input-group-text">to</span>
                                                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" placeholder="To">
                                                </div>
                                            </div>
                                            
                                            <!-- Action Buttons -->
                                            <div class="col-12 mt-2">
                                                <div class="d-flex justify-content-between">
                                                    <button type="submit" class="btn btn-primary btn-sm">
                                                        <i class="ri-filter-line me-1"></i> Apply Filters
                                                    </button>
                                                    @if(count(array_filter(request()->only(['status', 'date_from', 'date_to']))) > 0)
                                                        <a href="{{ route('manage-appointments', array_merge(request()->only(['search', 'per_page']), ['page' => 1])) }}" class="btn btn-outline-secondary btn-sm">
                                                            <i class="ri-close-line me-1"></i> Clear Filters
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                @if(request()->search || request()->status || request()->type || request()->date_from || request()->date_to)
                                <div class="mt-3">
                                    <div class="d-flex flex-wrap align-items-center gap-2">
                                        <span class="small text-muted">Filters applied:</span>
                                        @if(request()->search)
                                            <span class="badge bg-soft-primary text-primary">
                                                Search: {{ request()->search }}
                                                <a href="{{ route('manage-appointments', array_merge(request()->except('search'), ['page' => 1])) }}" class="text-muted ms-1">
                                                    <i class="ri-close-line"></i>
                                                </a>
                                            </span>
                                        @endif
                                        @if(request()->status)
                                            <span class="badge bg-soft-primary text-primary">
                                                Status: {{ \App\Models\Appointment::getStatuses()[request('status')] ?? request('status') }}
                                                <a href="{{ route('manage-appointments', array_merge(request()->except('status'), ['page' => 1])) }}" class="text-muted ms-1">
                                                    <i class="ri-close-line"></i>
                                                </a>
                                            </span>
                                        @endif
                                        @if(request()->type)
                                            <span class="badge bg-soft-info text-info">
                                                Type: {{ ucfirst(request('type')) }}
                                                <a href="{{ route('manage-appointments', array_merge(request()->except('type'), ['page' => 1])) }}" class="text-muted ms-1">
                                                    <i class="ri-close-line"></i>
                                                </a>
                                            </span>
                                        @endif
                                        @if(request()->date_from)
                                            <span class="badge bg-soft-info text-primary">
                                                From: {{ \Carbon\Carbon::parse(request('date_from'))->format('M d, Y') }}
                                                <a href="{{ route('manage-appointments', array_merge(request()->except('date_from'), ['page' => 1])) }}" class="text-muted ms-1">
                                                    <i class="ri-close-line"></i>
                                                </a>
                                            </span>
                                        @endif
                                        @if(request()->date_to)
                                            <span class="badge bg-soft-info text-primary">
                                                To: {{ \Carbon\Carbon::parse(request('date_to'))->format('M d, Y') }}
                                                <a href="{{ route('manage-appointments', array_merge(request()->except('date_to'), ['page' => 1])) }}" class="text-muted ms-1">
                                                    <i class="ri-close-line"></i>
                                                </a>
                                            </span>
                                        @endif
                                    </div>
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
                                        <th>Doctor</th>
                                        <th>Date & Time</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($appointments as $appointment)
                                    <tr>
                                        <td>{{ $appointment->id }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $appointment->patient->profile_photo ?? asset('default.png') }}" 
                                                    class="img-shadow img-2x rounded-circle me-2" 
                                                    alt="{{ $appointment->patient->first_name ?? 'Patient' }}" style="width: 36px; height: 36px; object-fit: cover;">
                                                <div>
                                                    <h6 class="mb-0">
                                                        <a href="{{ route('patients.show', $appointment->patient) }}" class="text-decoration-none text-primary" target="_blank">
                                                            {{ $appointment->patient->first_name ?? 'N/A' }} {{ $appointment->patient->last_name ?? '' }}
                                                        </a>
                                                    </h6>
                                                    <small class="text-muted">
                                                        <a href="mailto:{{ $appointment->patient->email ?? '' }}" class="text-decoration-none text-muted">
                                                            {{ $appointment->patient->email ?? 'N/A' }}
                                                        </a>
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $appointment->doctor->profile_photo ?? asset('default.png') }}" 
                                                    class="img-shadow img-2x rounded-circle me-2" 
                                                    alt="{{ $appointment->doctor->first_name ?? 'Doctor' }}" style="width: 36px; height: 36px; object-fit: cover;">
                                                <div>
                                                    <h6 class="mb-0">
                                                        <a href="{{ route('doctor-detail', $appointment->doctor->id) }}" class="text-decoration-none text-primary" target="_blank">
                                                            {{ $appointment->doctor->first_name ?? 'N/A' }} {{ $appointment->doctor->last_name ?? '' }}
                                                        </a>
                                                    </h6>
                                                    <small class="text-muted">{{ $appointment->doctor->detail->speciality->title ?? 'General' }}</small>
                                                    <small class="text-muted d-block">
                                                        <a href="mailto:{{ $appointment->doctor->email ?? '' }}" class="text-decoration-none text-muted">
                                                            {{ $appointment->doctor->email ?? 'N/A' }}
                                                        </a>
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="fw-medium">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}</span>
                                                <small class="text-muted">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            @php
                                                $treatmentTypeClass = [
                                                    'general' => 'bg-soft-info text-info',
                                                    'OPD' => 'bg-soft-primary text-primary'
                                                ][$appointment->treatment_type ?? 'general'] ?? 'bg-soft-secondary text-secondary';
                                            @endphp
                                            <span class="badge {{ $treatmentTypeClass }}">
                                                {{ ucfirst($appointment->treatment_type ?? 'general') }}
                                            </span>
                                        </td>
                                        <td>
                                            @php
                                                $statusClasses = [
                                                    'pending' => 'bg-soft-warning text-warning',
                                                    'confirmed' => 'bg-soft-success text-success',
                                                    'completed' => 'bg-soft-primary text-primary',
                                                    'canceled' => 'bg-soft-danger text-danger',
                                                ][$appointment->status] ?? 'bg-soft-secondary text-secondary';
                                            @endphp
                                            <span class="badge {{ $statusClasses }}">
                                                {{ ucfirst($appointment->status) }}
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <div class="btn-group" role="group">
                                                @if($appointment->status == 'completed' || $appointment->status == 'canceled')
                                                <span class="btn btn-sm btn-icon btn-outline-secondary" data-bs-toggle="tooltip" title="Cannot edit completed appointments">
                                                    <i class="ri-edit-line"></i>
                                                </span>
                                                @else
                                                <a href="{{ route('appointments.edit', $appointment->id) }}" class="btn btn-sm btn-icon btn-outline-primary" data-bs-toggle="tooltip" title="Edit Appointment" target="_blank">
                                                    <i class="ri-edit-line"></i>
                                                </a>
                                                @endif
                                                
                                                @if($appointment->status === 'pending' || $appointment->status === 'confirmed')
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-sm btn-icon btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="ri-more-2-line"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <form id="appointmentForm-{{ $appointment->id }}" 
                                                            action="{{ route('manage.appointments.toggleBlock', $appointment->id) }}" 
                                                            method="POST" 
                                                            style="display:inline-block;">
                                                            @csrf
                                                            @method('PATCH')
                                                            
                                                            @if($appointment->status === 'pending')
                                                                <li>
                                                                    <a class="dropdown-item" href="#" onclick="confirmAction('{{ $appointment->id }}', 'confirmed')">
                                                                        <i class="ri-checkbox-circle-line me-2 text-success"></i> Confirm
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a class="dropdown-item" href="#" onclick="confirmAction('{{ $appointment->id }}', 'canceled')">
                                                                        <i class="ri-close-circle-line me-2 text-danger"></i> Cancel
                                                                    </a>
                                                                </li>
                                                            @elseif($appointment->status === 'confirmed')
                                                                <li>
                                                                    <a class="dropdown-item" href="#" onclick="confirmAction('{{ $appointment->id }}', 'completed')">
                                                                        <i class="ri-check-double-line me-2 text-primary"></i> Complete
                                                                    </a>
                                                                </li>
                                                            @endif
                                                            <input type="hidden" id="statusInput-{{ $appointment->id }}" name="status" value="">
                                                        </form>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <a class="dropdown-item text-danger" href="#" onclick="confirmDelete({{ $appointment->id }}); return false;">
                                                                <i class="ri-delete-bin-line me-2"></i> Delete
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                    
                                    @if($appointments->isEmpty())
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="ri-calendar-todo-line display-4 text-muted mb-2"></i>
                                                <h5 class="mb-1">No appointments found</h5>
                                                <p class="text-muted mb-0">
                                                    @if(request()->search)
                                                        Try adjusting your search or filter to find what you're looking for.
                                                    @else
                                                        There are no appointments scheduled yet.
                                                    @endif
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="text-muted">
                                Showing {{ $appointments->firstItem() }} to {{ $appointments->lastItem() }} of {{ $appointments->total() }} entries
                            </div>
                            <div>
                                {{ $appointments->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
@endsection


@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{asset('assets/vendor/datatables/dataTables.min.js')}}"></script>
<script src="{{asset('assets/vendor/datatables/dataTables.bootstrap.min.js')}}"></script>

<script>
// Get CSRF token from meta tag
const csrfToken = document.querySelector('meta[name="token"]').getAttribute('content');

// Add CSRF token to all AJAX requests
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': csrfToken,
        'X-Requested-With': 'XMLHttpRequest'
    }
});

// Delete confirmation function
function confirmDelete(appointmentId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Deleting...',
                text: 'Please wait while we delete the appointment.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Send delete request with CSRF token
            $.ajax({
                url: `/appointments/${appointmentId}`,
                type: 'DELETE',
                data: {
                    _token: csrfToken
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire(
                            'Deleted!',
                            'The appointment has been deleted.',
                            'success'
                        ).then(() => {
                            // Reload the page to see changes
                            window.location.reload();
                        });
                    } else {
                        Swal.fire(
                            'Error!',
                            response.message || 'Failed to delete appointment. Please try again.',
                            'error'
                        );
                    }
                },
                error: function(xhr) {
                    let message = 'An error occurred while deleting the appointment.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                    Swal.fire(
                        'Error!',
                        message,
                        'error'
                    );
                }
            });
        }
    });
}
</script>

<script>
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });

    // Confirm action with SweetAlert2
    function confirmAction(appointmentId, status) {
        let actionText = '';
        let icon = 'warning';
        let confirmButtonColor = '#3085d6';
        
        // Customize the confirmation message based on the action
        switch(status) {
            case 'confirmed':
                actionText = 'confirm this appointment';
                icon = 'question';
                confirmButtonColor = '#198754';
                break;
            case 'canceled':
                actionText = 'cancel this appointment';
                icon = 'warning';
                confirmButtonColor = '#dc3545';
                break;
            case 'completed':
                actionText = 'mark this appointment as completed';
                icon = 'success';
                confirmButtonColor = '#0d6efd';
                break;
            default:
                actionText = 'perform this action';
        }

        Swal.fire({
            title: 'Are you sure?',
            html: `You are about to <strong>${actionText}</strong>. This action cannot be undone.`,
            icon: icon,
            showCancelButton: true,
            confirmButtonColor: confirmButtonColor,
            cancelButtonColor: '#6c757d',
            confirmButtonText: `Yes, ${status} it!`,
            cancelButtonText: 'No, cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`statusInput-${appointmentId}`).value = status;
                document.getElementById(`appointmentForm-${appointmentId}`).submit();
            }
        });
    }

    // Initialize date range picker if needed
    $('.day-sorting button').on('click', function() {
        $('.day-sorting button').removeClass('btn-primary').addClass('btn-outline-secondary');
        $(this).removeClass('btn-outline-secondary').addClass('btn-primary');
        
        // Here you would typically reload the data based on the selected date range
        // For now, this is just a UI interaction
    });
</script>

@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });
        
        Toast.fire({
            icon: 'success',
            title: '{{ session('success') }}'
        });
    });
</script>
@endif

@if(session('error'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{{ session('error') }}',
            confirmButtonColor: '#3085d6',
        });
    });
</script>
@endif
@endsection