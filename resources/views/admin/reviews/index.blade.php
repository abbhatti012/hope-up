@extends('admin.layout.layout')

@push('styles')
<style>
    /* Modern Card Styling */
    .review-card {
        transition: all 0.3s ease;
        border: 1px solid #e9ecef;
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 1.5rem;
        background: #fff;
    }
    .review-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        border-color: rgba(13, 110, 253, 0.2);
    }
    .review-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #f1f3f5 100%);
        border-bottom: 1px solid #e9ecef;
        padding: 1.25rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
    }
    .reviewer-info {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .reviewer-avatar {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #fff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .reviewer-details h6 {
        margin: 0;
        font-weight: 600;
        color: #212529;
    }
    .reviewer-details p {
        margin: 0.25rem 0 0;
        color: #6c757d;
        font-size: 0.875rem;
    }
    .review-rating {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        font-size: 1rem;
        font-weight: 600;
        color: #ffc107;
        background: rgba(255, 193, 7, 0.1);
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
    }
    .review-body {
        padding: 1.5rem;
    }
    .review-comment {
        position: relative;
        max-height: 4.5rem;
        overflow: hidden;
        line-height: 1.6;
        color: #495057;
        margin-bottom: 1.25rem;
    }
    .review-comment.expanded {
        max-height: none;
    }
    .read-more {
        position: absolute;
        bottom: 0;
        right: 0;
        background: linear-gradient(90deg, rgba(255,255,255,0) 0%, rgba(255,255,255,0.9) 30%, rgba(255,255,255,1) 50%);
        padding-left: 2rem;
        padding-right: 0.5rem;
        cursor: pointer;
        color: #0d6efd;
        font-weight: 500;
        font-size: 0.875rem;
    }
    .review-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 1rem;
        border-top: 1px solid #f1f3f5;
        margin-top: 1.25rem;
    }
    .review-date {
        font-size: 0.8125rem;
        color: #6c757d;
    }
    .review-actions {
        display: flex;
        gap: 0.5rem;
    }
    .review-actions .btn {
        padding: 0.375rem 0.75rem;
        font-size: 0.8125rem;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }
    .review-actions .btn i {
        font-size: 1rem;
    }
    .filter-card {
        background-color: #fff;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid #e9ecef;
        box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.03);
    }
    .per-page-selector {
        max-width: 100px;
    }
    .empty-state {
        text-align: center;
        padding: 3rem 1.5rem;
    }
    .empty-state i {
        font-size: 3.5rem;
        color: #e9ecef;
        margin-bottom: 1rem;
        display: inline-block;
    }
    .empty-state h5 {
        color: #6c757d;
        margin-bottom: 0.5rem;
    }
    .empty-state p {
        color: #adb5bd;
        margin-bottom: 1.5rem;
    }
    .status-badge {
        padding: 0.35em 0.65em;
        font-size: 0.75em;
        font-weight: 600;
        border-radius: 0.25rem;
    }
    .status-approved {
        background-color: rgba(25, 135, 84, 0.1);
        color: #198754;
    }
    .status-pending {
        background-color: rgba(255, 193, 7, 0.1);
        color: #ffc107;
    }
</style>
@endpush

@section('content')
<div class="app-hero-header d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4">
    <div class="d-flex align-items-center mb-3 mb-md-0">
        <h1 class="h3 mb-0">
            <i class="ri-chat-check-line text-primary me-2"></i>Doctor Reviews
        </h1>
        <span class="badge bg-primary-soft rounded-pill ms-3 px-3 py-2">
            <i class="ri-database-2-line me-1"></i> {{ $reviews->total() }} Reviews
        </span>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('reviews.create') }}" class="btn btn-primary">
            <i class="ri-add-line me-1"></i> Add Review
        </a>
    </div>
</div>

<div class="app-body">
    @include('admin/notifications')
    <div class="row gx-3">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="mb-0">
                            <i class="ri-star-line me-2 text-primary"></i>
                            Doctor Reviews
                        </h5>
                        <a href="{{ route('reviews.create') }}" class="btn btn-primary btn-sm">
                            <i class="ri-add-line me-1"></i> Add Review
                        </a>
                    </div>

                    <!-- Search and Filter Form -->
                    <form action="{{ route('reviews.index') }}" method="GET" class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-text bg-transparent"><i class="ri-search-line"></i></span>
                                    <input type="text" 
                                           name="search"
                                           class="form-control" 
                                           value="{{ request('search') }}"
                                           placeholder="Search reviews...">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <select class="form-select" name="rating_type">
                                    <option value="">All Ratings</option>
                                    @foreach(['excellent' => 'Excellent', 'very_good' => 'Very Good', 'good' => 'Good', 'fair' => 'Fair', 'poor' => 'Poor', 'bad' => 'Bad'] as $value => $label)
                                        <option value="{{ $value }}" {{ request('rating_type') == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select class="form-select" name="status">
                                    <option value="">All Status</option>
                                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Approved</option>
                                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Pending</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="ri-filter-line me-1"></i> Filter
                                </button>
                            </div>
                            <div class="col-md-3 text-end">
                                <a href="{{ route('reviews.index') }}" class="btn btn-outline-secondary">
                                    <i class="ri-refresh-line me-1"></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>

                    <!-- Reviews Table -->
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-nowrap">#</th>
                                    <th class="text-nowrap">Doctor</th>
                                    <th class="text-nowrap">Reviewer</th>
                                    <th class="text-nowrap">Rating</th>
                                    <th class="text-nowrap">Type</th>
                                    <th>Comments</th>
                                    <th class="text-nowrap">Status</th>
                                    <th class="text-nowrap">Date</th>
                                    <th class="text-nowrap text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reviews as $index => $review)
                                    <tr>
                                        <td>{{ $reviews->firstItem() + $index }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ asset($review->doctor->profile_photo) ?? asset('default.png') }}" 
                                                     class="rounded-circle me-2" 
                                                     width="32" 
                                                     height="32" 
                                                     alt="Doctor">
                                                <div>
                                                    <div class="fw-semibold">
                                                        <a href="{{ route('doctor-detail', $review->doctor->id) }}" class="text-decoration-none text-primary" target="_blank">
                                                            {{ $review->doctor->first_name ?? 'N/A' }} {{ $review->doctor->last_name ?? '' }}
                                                        </a>
                                                    </div>
                                                    <small class="text-muted">
                                                        <a href="mailto:{{ $review->doctor->email ?? '' }}" class="text-decoration-none text-muted">
                                                            {{ $review->doctor->email ?? 'N/A' }}
                                                        </a>
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ asset($review->reviewer->profile_photo) ?? asset('default.png') }}" 
                                                     class="rounded-circle me-2" 
                                                     width="32" 
                                                     height="32" 
                                                     alt="Reviewer">
                                                <div>
                                                    <div class="fw-semibold">
                                                        <a href="{{ route('patients.show', $review->reviewer) }}" class="text-decoration-none text-primary" target="_blank">
                                                            {{ $review->reviewer->first_name ?? 'N/A' }} {{ $review->reviewer->last_name ?? '' }}
                                                        </a>
                                                    </div>
                                                    <small class="text-muted">
                                                        <a href="mailto:{{ $review->reviewer->email ?? '' }}" class="text-decoration-none text-muted">
                                                            {{ $review->reviewer->email ?? 'N/A' }}
                                                        </a>
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="ri-star-fill {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                                                @endfor
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ 
                                                $review->rating_type === 'excellent' ? 'success' : 
                                                ($review->rating_type === 'very_good' ? 'info' : 
                                                ($review->rating_type === 'good' ? 'primary' : 
                                                ($review->rating_type === 'fair' ? 'warning' : 'danger'))) 
                                            }}">
                                                {{ ucfirst(str_replace('_', ' ', $review->rating_type)) }}
                                            </span>
                                        </td>
                                        <td>{{ Str::limit($review->comments, 50) }}</td>
                                        <td class="text-nowrap">
                                            <span class="badge bg-{{ $review->is_approved ? 'success' : 'warning' }}">
                                                {{ $review->is_approved ? 'Approved' : 'Pending' }}
                                            </span>
                                        </td>
                                        <td class="text-nowrap">{{ $review->created_at->format('M d, Y') }}</td>
                                        <td class="text-end">
                                            <div class="d-flex align-items-center gap-1">
                                                <a href="{{ route('reviews.show', $review->id) }}" class="btn btn-sm btn-outline-primary d-flex align-items-center" title="View" target="_blank">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                                <a href="{{ route('reviews.edit', $review->id) }}" class="btn btn-sm btn-outline-success d-flex align-items-center" title="Edit" target="_blank">
                                                    <i class="ri-edit-line"></i>
                                                </a>
                                                <form action="{{ route('reviews.destroy', $review->id) }}" method="POST" class="d-inline mb-0">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger d-flex align-items-center" title="Delete" onclick="return confirm('Are you sure you want to delete this review?')">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="ri-inbox-line fs-1"></i>
                                                <p class="mt-2">No reviews found.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="text-muted">
                            Showing {{ $reviews->firstItem() }} to {{ $reviews->lastItem() }} of {{ $reviews->total() }} entries
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <form method="GET" action="{{ route('reviews.index') }}">
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
                            @if($reviews->hasPages())
                                <nav aria-label="Page navigation">
                                    {{ $reviews->appends(['per_page' => request()->per_page])->withQueryString()->links('pagination::bootstrap-4') }}
                                </nav>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create/Edit Modal -->
<div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reviewModalLabel">Add Review</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="reviewForm">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="doctor_id" class="form-label">Doctor</label>
                            <select class="form-select" id="doctor_id" name="doctor_id" required>
                                <option value="">Select Doctor</option>
                                @foreach(\App\Models\User::where('role', 'specialist')->get() as $doctor)
                                    <option value="{{ $doctor->id }}">{{ $doctor->first_name }} {{ $doctor->last_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="reviewer_id" class="form-label">Reviewer</label>
                            <select class="form-select" id="reviewer_id" name="reviewer_id" required>
                                <option value="">Select Reviewer</option>
                                @foreach(\App\Models\User::where('role', 'user')->get() as $reviewer)
                                    <option value="{{ $reviewer->id }}">{{ $reviewer->first_name }} {{ $reviewer->last_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="rating" class="form-label">Rating</label>
                            <select class="form-select" id="rating" name="rating" required>
                                <option value="">Select Rating</option>
                                <option value="1">1 Star</option>
                                <option value="2">2 Stars</option>
                                <option value="3">3 Stars</option>
                                <option value="4">4 Stars</option>
                                <option value="5">5 Stars</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="rating_type" class="form-label">Rating Type</label>
                            <select class="form-select" id="rating_type" name="rating_type" required>
                                <option value="">Select Type</option>
                                <option value="excellent">Excellent</option>
                                <option value="very_good">Very Good</option>
                                <option value="good">Good</option>
                                <option value="fair">Fair</option>
                                <option value="poor">Poor</option>
                                <option value="bad">Bad</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="comments" class="form-label">Comments</label>
                            <textarea class="form-control" id="comments" name="comments" rows="3" maxlength="1000" placeholder="Enter your review comments..."></textarea>
                            <div class="form-text">Maximum 1000 characters</div>
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_approved" name="is_approved" value="1">
                                <label class="form-check-label" for="is_approved">
                                    Approved
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">Save Review</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Modal -->
<div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewModalLabel">Review Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="viewModalBody">
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="{{ asset('assets/vendor/sweetalert2/sweetalert2.all.min.js') }}"></script>
<script>
// Handle per page change
function handlePerPageChange(select) {
    const url = new URL(window.location.href);
    url.searchParams.set('per_page', select.value);
    url.searchParams.set('page', '1'); // Reset to first page
    window.location.href = url.toString();
}

// Handle filter form submission
document.getElementById('filterForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    this.submit();
});

// Handle reset button click
document.getElementById('resetFilters')?.addEventListener('click', function() {
    window.location.href = '{{ route("reviews.index") }}';
});

// Handle delete review confirmation
document.addEventListener('DOMContentLoaded', function() {
    const deleteForms = document.querySelectorAll('form[action*="reviews/"][method="POST"]');
    
    deleteForms.forEach(form => {
        if (form.querySelector('input[name="_method"][value="DELETE"]')) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This action cannot be undone!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });
        }
    });
});

// Generate star rating HTML
function generateStars(rating) {
    let stars = '';
    for (let i = 1; i <= 5; i++) {
        if (i <= rating) {
            stars += '<i class="ri-star-fill text-warning"></i>';
        } else {
            stars += '<i class="ri-star-line text-muted"></i>';
        }
    }
    return stars;
}

// Get rating type color
function getRatingTypeColor(type) {
    const colors = {
        'excellent': 'success',
        'very_good': 'info',
        'good': 'primary',
        'fair': 'warning',
        'poor': 'danger',
        'bad': 'secondary'
    };
    return colors[type] || 'secondary';
}

// Show alert message
function showAlert(message, type) {
    const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
    
    // Create a temporary container to parse the HTML
    const temp = document.createElement('div');
    temp.innerHTML = alertHtml.trim();
    const alertElement = temp.firstChild;
    
    // Add the alert to the page
    let alertsContainer = document.getElementById('alerts-container');
    if (!alertsContainer) {
        alertsContainer = document.createElement('div');
        alertsContainer.id = 'alerts-container';
        alertsContainer.style.position = 'fixed';
        alertsContainer.style.top = '20px';
        alertsContainer.style.right = '20px';
        alertsContainer.style.zIndex = '9999';
        alertsContainer.style.width = '350px';
        document.body.appendChild(alertsContainer);
    }
    
    alertsContainer.appendChild(alertElement);
    
    // Remove the alert after 5 seconds
    setTimeout(() => {
        alertElement.classList.remove('show');
        setTimeout(() => {
            if (alertsContainer.contains(alertElement)) {
                alertsContainer.removeChild(alertElement);
            }
            if (alertsContainer.children.length === 0) {
                document.body.removeChild(alertsContainer);
            }
        }, 300);
    }, 5000);
}

// Handle form submission with loading state
function handleFormSubmit(event) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    const url = form.getAttribute('action');
    const method = form.getAttribute('method') || 'POST';
    
    // Show loading state
    const submitButton = form.querySelector('button[type="submit"]');
    const originalButtonText = submitButton.innerHTML;
    submitButton.disabled = true;
    submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';
    
    fetch(url, {
        method: method,
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.redirect) {
            window.location.href = data.redirect;
        } else if (data.message) {
            showAlert(data.message, data.success ? 'success' : 'danger');
            if (data.success && data.reload) {
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('An error occurred. Please try again.', 'danger');
    })
    .finally(() => {
        submitButton.disabled = false;
        submitButton.innerHTML = originalButtonText;
    });
}

// Initialize form submission handlers
document.addEventListener('DOMContentLoaded', function() {
    // Handle review form submission
    const reviewForm = document.getElementById('reviewForm');
    if (reviewForm) {
        reviewForm.addEventListener('submit', handleFormSubmit);
    }
});
</script>
@endsection