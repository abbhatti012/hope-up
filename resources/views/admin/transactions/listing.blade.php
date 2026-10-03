@extends('admin.layout.layout')

@section('links')
<link rel="stylesheet" href="{{asset('assets/vendor/datatables/dataTables.bs5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/datatables/dataTables.bs5-custom.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/datatables/buttons/dataTables.bs5-custom.css')}}">
@endsection

@section('content')
    <!-- View Transaction Details Modal -->
    <div class="modal fade" id="transactionDetailsModal" tabindex="-1" aria-labelledby="transactionDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="transactionDetailsModalLabel">Transaction Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="transactionDetailsContent">
                    <!-- Content will be loaded here via AJAX -->
                    <div class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="app-hero-header d-flex align-items-center">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
            <i class="ri-home-8-line lh-1 pe-3 me-3 border-end"></i>
            <a href="{{ route('/') }}">Home</a>
            </li>
            <li class="breadcrumb-item text-primary" aria-current="page">
            Transactions
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
                                <i class="ri-exchange-dollar-line me-2 text-primary"></i>All Transactions
                                <span class="badge bg-soft-primary text-primary ms-2">{{ $transactions->total() }} Total</span>
                            </h5>
                            <div class="d-flex gap-2">
                                <a href="{{ route('export-transactions', request()->query()) }}" class="btn btn-outline-secondary">
                                    <i class="ri-download-line me-1"></i> Export
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
                                        <form method="GET" action="{{ route('manage-transactions') }}" class="search-form">
                                            <div class="input-group">
                                                <span class="input-group-text bg-white border-end-0">
                                                    <i class="ri-search-line text-muted"></i>
                                                </span>
                                                <input type="search" 
                                                       name="search" 
                                                       class="form-control border-start-0 ps-0" 
                                                       placeholder="Search transactions by patient, doctor, or ID..." 
                                                       value="{{ request()->search }}">
                                                @if(request()->search)
                                                <a href="{{ route('manage-transactions') }}" class="btn btn-outline-secondary" type="button">
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
                                        <form method="GET" action="{{ route('manage-transactions') }}">
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
                                        <span class="filter-count">{{ count(array_filter(request()->only(['status', 'source', 'date_from', 'date_to', 'amount_min', 'amount_max']))) > 0 ? '('.count(array_filter(request()->only(['status', 'source', 'date_from', 'date_to', 'amount_min', 'amount_max']))).')' : '' }}</span>
                                    </button>
                                </div>

                                <!-- Filters Form -->
                                <div class="collapse show" id="filtersCollapse">
                                    <form method="GET" action="{{ route('manage-transactions') }}">
                                        @if(request()->search)
                                            <input type="hidden" name="search" value="{{ request()->search }}">
                                        @endif
                                        @if(request()->per_page)
                                            <input type="hidden" name="per_page" value="{{ request()->per_page }}">
                                        @endif
                                        
                                        <div class="row g-3">
                                            <!-- Status Filter -->
                                            <div class="col-md-3">
                                                <label class="form-label small text-muted mb-1">Payment Status</label>
                                                <select name="status" class="form-select">
                                                    <option value="">All Statuses</option>
                                                    <option value="completed" {{ request()->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                                    <option value="pending" {{ request()->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                                    <option value="failed" {{ request()->status === 'failed' ? 'selected' : '' }}>Failed</option>
                                                    <option value="refunded" {{ request()->status === 'refunded' ? 'selected' : '' }}>Refunded</option>
                                                    <option value="cancelled" {{ request()->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                                </select>
                                            </div>
                                            
                                            <!-- Source Filter -->
                                            <div class="col-md-3">
                                                <label class="form-label small text-muted mb-1">Transaction Source</label>
                                                <select name="source" class="form-select">
                                                    <option value="">All Sources</option>
                                                    <option value="appointment" {{ request()->source === 'appointment' ? 'selected' : '' }}>Appointment</option>
                                                    <option value="subscription" {{ request()->source === 'subscription' ? 'selected' : '' }}>Subscription</option>
                                                </select>
                                            </div>
                                            
                                            <!-- Date Range -->
                                            <div class="col-md-3">
                                                <label class="form-label small text-muted mb-1">Date Range</label>
                                                <div class="input-group">
                                                    <input type="date" name="date_from" class="form-control" value="{{ request()->date_from }}" placeholder="From">
                                                    <span class="input-group-text">to</span>
                                                    <input type="date" name="date_to" class="form-control" value="{{ request()->date_to }}" placeholder="To">
                                                </div>
                                            </div>
                                            
                                            <!-- Amount Range -->
                                            <div class="col-md-3">
                                                <label class="form-label small text-muted mb-1">Amount Range</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">$</span>
                                                    <input type="number" name="amount_min" class="form-control" placeholder="Min" value="{{ request()->amount_min }}" step="0.01" min="0">
                                                    <span class="input-group-text">to</span>
                                                    <input type="number" name="amount_max" class="form-control" placeholder="Max" value="{{ request()->amount_max }}" step="0.01" min="0">
                                                </div>
                                            </div>
                                            
                                            <!-- Action Buttons -->
                                            <div class="col-12 mt-2">
                                                <div class="d-flex justify-content-between">
                                                    <button type="submit" class="btn btn-primary btn-sm">
                                                        <i class="ri-filter-line me-1"></i> Apply Filters
                                                    </button>
                                                    @if(count(array_filter(request()->only(['status', 'source', 'date_from', 'date_to', 'amount_min', 'amount_max']))) > 0)
                                                        <a href="{{ route('manage-transactions', array_merge(request()->only(['search', 'per_page']), ['page' => 1])) }}" class="btn btn-outline-secondary btn-sm">
                                                            <i class="ri-close-line me-1"></i> Clear Filters
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                @if(request()->search || request()->status || request()->source || request()->date_from || request()->date_to || request()->amount_min || request()->amount_max)
                                <div class="mt-3">
                                    <div class="d-flex flex-wrap align-items-center gap-2">
                                        <span class="small text-muted">Filters applied:</span>
                                        @if(request()->search)
                                            <span class="badge bg-soft-primary text-primary">
                                                Search: {{ request()->search }}
                                                <a href="{{ route('manage-transactions', array_merge(request()->except('search'), ['page' => 1])) }}" class="text-muted ms-1">
                                                    <i class="ri-close-line"></i>
                                                </a>
                                            </span>
                                        @endif
                                        @if(request()->status)
                                            <span class="badge bg-soft-primary text-primary">
                                                Status: {{ ucfirst(request()->status) }}
                                                <a href="{{ route('manage-transactions', array_merge(request()->except('status'), ['page' => 1])) }}" class="text-muted ms-1">
                                                    <i class="ri-close-line"></i>
                                                </a>
                                            </span>
                                        @endif
                                        @if(request()->source)
                                            <span class="badge bg-soft-primary text-primary">
                                                Source: {{ ucfirst(request()->source) }}
                                                <a href="{{ route('manage-transactions', array_merge(request()->except('source'), ['page' => 1])) }}" class="text-muted ms-1">
                                                    <i class="ri-close-line"></i>
                                                </a>
                                            </span>
                                        @endif
                                        @if(request()->date_from || request()->date_to)
                                            <span class="badge bg-soft-primary text-primary">
                                                Date: {{ request()->date_from ?: 'Start' }} to {{ request()->date_to ?: 'End' }}
                                                <a href="{{ route('manage-transactions', array_merge(request()->except(['date_from', 'date_to']), ['page' => 1])) }}" class="text-muted ms-1">
                                                    <i class="ri-close-line"></i>
                                                </a>
                                            </span>
                                        @endif
                                        @if(request()->amount_min || request()->amount_max)
                                            <span class="badge bg-soft-primary text-primary">
                                                Amount: {{ request()->amount_min ? '$'.number_format(request()->amount_min, 2) : 'Min' }} to {{ request()->amount_max ? '$'.number_format(request()->amount_max, 2) : 'Max' }}
                                                <a href="{{ route('manage-transactions', array_merge(request()->except(['amount_min', 'amount_max']), ['page' => 1])) }}" class="text-muted ms-1">
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
                                        <th>Amount</th>
                                        <th>Doctor Amount</th>
                                        <th>Source</th>
                                        <th>Paid?</th>
                                        <th>Payment Proof</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($transactions as $transaction)
                                    <tr>
                                        <td>ORD-{{ $transaction->id }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $transaction->patient->profile_photo ?? asset('default.png') }}" 
                                                    class="img-shadow img-2x rounded-circle me-2" 
                                                    alt="{{ $transaction->patient->first_name ?? 'Patient' }}" 
                                                    style="width: 36px; height: 36px; object-fit: cover;">
                                                <div>
                                                    <h6 class="mb-0">
                                                        <a href="{{ route('patients.show', $transaction->patient) }}" class="text-decoration-none text-primary" target="_blank">
                                                            {{ $transaction->patient->first_name ?? 'N/A' }} {{ $transaction->patient->last_name ?? '' }}
                                                        </a>
                                                    </h6>
                                                    <small class="text-muted">
                                                        <a href="mailto:{{ $transaction->patient->email ?? '' }}" class="text-decoration-none text-muted">
                                                            {{ $transaction->patient->email ?? 'N/A' }}
                                                        </a>
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $transaction->doctor->profile_photo ?? asset('default.png') }}" 
                                                    class="img-shadow img-2x rounded-circle me-2" 
                                                    alt="{{ $transaction->doctor->first_name ?? 'Doctor' }}" 
                                                    style="width: 36px; height: 36px; object-fit: cover;">
                                                <div>
                                                    <h6 class="mb-0">
                                                        <a href="{{ route('doctor-detail', $transaction->doctor->id) }}" class="text-decoration-none text-primary" target="_blank">
                                                            {{ $transaction->doctor->first_name ?? 'N/A' }} {{ $transaction->doctor->last_name ?? '' }}
                                                        </a>
                                                    </h6>
                                                    <small class="text-muted">
                                                        <a href="mailto:{{ $transaction->doctor->email ?? '' }}" class="text-decoration-none text-muted">
                                                            {{ $transaction->doctor->email ?? 'N/A' }}
                                                        </a>
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="fw-medium">{{ $transaction->currency ?? 'GHS' }} {{ number_format($transaction->total_amount ?? 0, 2) }}</span>
                                        </td>
                                        <td>
                                            @if(strtolower($transaction->source ?? '') === 'subscription')
                                                <span class="fw-medium text-muted">{{ $transaction->currency ?? 'GHS' }} 0.00</span>
                                                <small class="d-block text-muted">(Subscription - No doctor payment)</small>
                                            @else
                                                <span class="fw-medium">{{ $transaction->currency ?? 'GHS' }} {{ number_format($transaction->doctor_amount ?? 0, 2) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $sourceClass = match(strtolower($transaction->source ?? '')) {
                                                    'appointment' => 'bg-primary text-white',
                                                    'subscription' => 'bg-success text-white',
                                                    default => 'bg-secondary text-white',
                                                };
                                            @endphp
                                            <span class="badge {{ $sourceClass }} text-capitalize">{{ $transaction->source ?? 'N/A' }}</span>
                                        </td>
                                        <td>
                                            @if($transaction->is_paid)
                                                <span class="badge bg-success">Yes</span>
                                            @else
                                                <span class="badge bg-danger">No</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(strtolower($transaction->source ?? '') === 'subscription')
                                                <span class="text-muted">N/A (Subscription)</span>
                                            @elseif($transaction->payment_proof)
                                                <a href="{{ asset($transaction->payment_proof) }}" target="_blank">
                                                    <img src="{{ asset($transaction->payment_proof) }}" alt="Proof" style="max-width: 40px; max-height: 40px; border-radius: 4px;">
                                                </a>
                                            @elseif(!$transaction->is_paid && $transaction->payment_status === 'completed')
                                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#markPaidModal-{{ $transaction->id }}">Mark as Paid</button>
                                                <!-- Modal -->
                                                <div class="modal fade" id="markPaidModal-{{ $transaction->id }}" tabindex="-1" aria-labelledby="markPaidModalLabel-{{ $transaction->id }}" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <form action="{{ route('transactions.markPaid', $transaction->id) }}" method="POST" enctype="multipart/form-data">
                                                                @csrf
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="markPaidModalLabel-{{ $transaction->id }}">Mark as Paid & Upload Proof</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="mb-3">
                                                                        <label for="payment_proof_{{ $transaction->id }}" class="form-label">Upload Payment Proof (screenshot)</label>
                                                                        <input type="file" class="form-control" id="payment_proof_{{ $transaction->id }}" name="payment_proof" accept="image/*" required>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                    <button type="submit" class="btn btn-primary">Submit</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($transaction->created_at)->format('M d, Y') }}
                                            <small class="d-block text-muted">
                                                {{ \Carbon\Carbon::parse($transaction->created_at)->format('h:i A') }}
                                            </small>
                                        </td>
                                        <td>
                                            @php
                                                $statusClasses = [
                                                    'completed' => 'bg-soft-success text-success',
                                                    'pending' => 'bg-soft-warning text-warning',
                                                    'failed' => 'bg-soft-danger text-danger',
                                                    'refunded' => 'bg-soft-info text-info',
                                                    'cancelled' => 'bg-soft-secondary text-secondary'
                                                ][$transaction->payment_status] ?? 'bg-soft-secondary text-secondary';
                                            @endphp
                                            <span class="badge {{ $statusClasses }}">
                                                <i class="ri-{{
                                                    $transaction->payment_status == 'completed' ? 'check-double-line' :
                                                    ($transaction->payment_status == 'pending' ? 'time-line' :
                                                    ($transaction->payment_status == 'failed' ? 'close-circle-line' :
                                                    ($transaction->payment_status == 'refunded' ? 'refresh-line' : 'question-line')))
                                                }} me-1"></i>
                                                {{ ucfirst($transaction->payment_status) }}
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('transactions.details', $transaction) }}" 
                                                   class="btn btn-sm btn-icon btn-outline-primary" 
                                                   data-bs-toggle="tooltip" 
                                                   title="View Details"
                                                   target="_blank">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                                
                                                <div class="btn-group" role="group">
                                                    <button type="button" 
                                                            class="btn btn-sm btn-icon btn-outline-secondary dropdown-toggle" 
                                                            data-bs-toggle="dropdown" 
                                                            aria-expanded="false">
                                                        <i class="ri-more-2-line"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        @if($transaction->payment_status === 'pending')
                                                            <form id="transactionForm-{{ $transaction->id }}" 
                                                                action="{{ route('manage.transactions.toggleBlock', $transaction->id) }}" 
                                                                method="POST" 
                                                                style="display:inline-block;">
                                                                @csrf
                                                                @method('PATCH')
                                                                <!-- <li>
                                                                    <a class="dropdown-item" href="#" onclick="confirmAction('{{ $transaction->id }}', 'completed')">
                                                                        <i class="ri-checkbox-circle-line me-2 text-success"></i> Mark as Completed
                                                                    </a>
                                                                </li> -->
                                                                <li>
                                                                    <a class="dropdown-item" href="#" onclick="confirmAction('{{ $transaction->id }}', 'cancelled')">
                                                                        <i class="ri-close-circle-line me-2 text-danger"></i> Mark as Cancelled
                                                                    </a>
                                                                </li>
                                                                <input type="hidden" id="statusInput-{{ $transaction->id }}" name="payment_status" value="">
                                                            </form>
                                                            <li><hr class="dropdown-divider"></li>
                                                        @elseif($transaction->payment_status === 'completed')
                                                            <!-- <form id="transactionForm-{{ $transaction->id }}" 
                                                                action="{{ route('manage.transactions.toggleBlock', $transaction->id) }}" 
                                                                method="POST" 
                                                                style="display:inline-block;">
                                                                @csrf
                                                                @method('PATCH')
                                                                <li>
                                                                    <a class="dropdown-item" href="#" onclick="confirmAction('{{ $transaction->id }}', 'refunded')">
                                                                        <i class="ri-refresh-line me-2 text-info"></i> Process Refund
                                                                    </a>
                                                                </li>
                                                                <input type="hidden" id="statusInput-{{ $transaction->id }}" name="payment_status" value="">
                                                            </form>
                                                            <li><hr class="dropdown-divider"></li> -->
                                                        @endif
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('transactions.receipt', $transaction) }}" target="_blank">
                                                                <i class="ri-download-line me-2"></i> Download Receipt
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="ri-exchange-dollar-line display-4 text-muted mb-2"></i>
                                                <h5 class="mb-1">No transactions found</h5>
                                                <p class="text-muted mb-0">
                                                    @if(request()->search)
                                                        Try adjusting your search or filter to find what you're looking for.
                                                    @else
                                                        There are no transactions recorded yet.
                                                    @endif
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="text-muted">
                                Showing {{ $transactions->firstItem() }} to {{ $transactions->lastItem() }} of {{ $transactions->total() }} entries
                            </div>
                            <div>
                                {{ $transactions->links('pagination::bootstrap-4') }}
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
<script>
    $(document).ready(function() {
        // Handle view transaction details
        $('.view-transaction').on('click', function(e) {
            e.preventDefault();
            const transactionId = $(this).data('id');
            const modal = new bootstrap.Modal(document.getElementById('transactionDetailsModal'));
            
            // Show loading state
            $('#transactionDetailsContent').html(`
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            `);
            
            // Show modal
            modal.show();
            
            // Load transaction details via AJAX
            $.ajax({
                url: `/transactions/${transactionId}/details`,
                method: 'GET',
                success: function(response) {
                    $('#transactionDetailsContent').html(response);
                },
                error: function(xhr) {
                    $('#transactionDetailsContent').html(`
                        <div class="alert alert-danger">
                            Failed to load transaction details. Please try again.
                        </div>
                    `);
                }
            });
        });
    });
</script>
<script src="{{asset('assets/vendor/datatables/dataTables.min.js')}}"></script>
<script src="{{asset('assets/vendor/datatables/dataTables.bootstrap.min.js')}}"></script>

<script>
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });

    // Confirm action with SweetAlert2
    function confirmAction(transactionId, status) {
        let actionText = '';
        let icon = 'warning';
        let confirmButtonColor = '#3085d6';
        
        // Customize the confirmation message based on the action
        switch(status) {
            case 'completed':
                actionText = 'mark this transaction as completed';
                icon = 'question';
                confirmButtonColor = '#198754';
                break;
            case 'cancelled':
                actionText = 'cancel this transaction';
                icon = 'warning';
                confirmButtonColor = '#dc3545';
                break;
            case 'refunded':
                actionText = 'process a refund for this transaction';
                icon = 'info';
                confirmButtonColor = '#0dcaf0';
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
                // Find the form and input
                const form = document.getElementById(`transactionForm-${transactionId}`);
                const statusInput = document.getElementById(`statusInput-${transactionId}`);
                
                if (form && statusInput) {
                    statusInput.value = status;
                    form.submit();
                } else {
                    console.error('Form or status input not found');
                    Swal.fire('Error', 'Could not update transaction status. Please try again.', 'error');
                }
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