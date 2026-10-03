@extends('admin.layout.layout')

@section('content')
<div class="app-hero-header d-flex align-items-center">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <i class="ri-home-8-line lh-1 pe-3 me-3 border-end"></i>
            <a href="{{ route('/') }}">Home</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('reviews.index') }}">Doctor Reviews</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">Review Details</li>
    </ol>
</div>

<div class="app-body">
    @include('admin/notifications')
    <div class="row gx-3">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="ri-star-line me-2 text-primary"></i>
                            Review Details
                        </h5>
                        <div class="d-flex align-items-center gap-2">
                            <a href="{{ route('reviews.edit', $review) }}" class="btn btn-outline-primary btn-sm d-flex align-items-center" style="margin-bottom: 14px;">
                                <i class="ri-edit-line me-1"></i> Edit
                            </a>
                            <form action="{{ route('reviews.toggle-approval', $review) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" 
                                        class="btn btn-outline-{{ $review->is_approved ? 'warning' : 'success' }} btn-sm d-flex align-items-center"
                                        onclick="return confirm('Are you sure you want to {{ $review->is_approved ? 'unapprove' : 'approve' }} this review?')">
                                    <i class="ri-{{ $review->is_approved ? 'close-line' : 'check-line' }} me-1"></i>
                                    {{ $review->is_approved ? 'Unapprove' : 'Approve' }}
                                </button>
                            </form>
                            <form action="{{ route('reviews.destroy', $review) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="btn btn-outline-danger btn-sm d-flex align-items-center"
                                        onclick="return confirm('Are you sure you want to delete this review?')">
                                    <i class="ri-delete-bin-line me-1"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">Doctor Information</h6>
                            <div class="d-flex align-items-center mb-3">
                                <img src="{{ asset($review->doctor->profile_photo ?? 'assets/images/user6.png') }}" 
                                     class="rounded-circle me-3" 
                                     width="64" 
                                     height="64" 
                                     alt="Doctor">
                                <div>
                                    <h5 class="mb-1">{{ $review->doctor->first_name }} {{ $review->doctor->last_name }}</h5>
                                    <p class="text-muted mb-1">{{ $review->doctor->email }}</p>
                                    <small class="text-muted">
                                        Speciality: {{ $review->doctor->doctorDetail->speciality->title ?? 'General' }}
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">Reviewer Information</h6>
                            <div class="d-flex align-items-center mb-3">
                                <img src="{{ asset($review->reviewer->profile_photo ?? 'assets/images/user6.png') }}" 
                                     class="rounded-circle me-3" 
                                     width="64" 
                                     height="64" 
                                     alt="Reviewer">
                                <div>
                                    <h5 class="mb-1">{{ $review->reviewer->first_name }} {{ $review->reviewer->last_name }}</h5>
                                    <p class="text-muted mb-1">{{ $review->reviewer->email }}</p>
                                    <small class="text-muted">Patient</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">Rating Details</h6>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Rating:</label>
                                <div class="d-flex align-items-center">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $review->rating)
                                            <i class="ri-star-fill text-warning me-1" style="font-size: 20px;"></i>
                                        @else
                                            <i class="ri-star-line text-muted me-1" style="font-size: 20px;"></i>
                                        @endif
                                    @endfor
                                    <span class="ms-2 fw-semibold">{{ $review->rating }}/5</span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Rating Type:</label>
                                <div>
                                    <span class="badge bg-{{ $review->rating_type === 'excellent' ? 'success' : ($review->rating_type === 'very_good' ? 'info' : ($review->rating_type === 'good' ? 'primary' : ($review->rating_type === 'fair' ? 'warning' : ($review->rating_type === 'poor' ? 'danger' : 'secondary')))) }}">
                                        {{ ucfirst(str_replace('_', ' ', $review->rating_type)) }}
                                    </span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Status:</label>
                                <div>
                                    @if($review->is_approved)
                                        <span class="badge bg-success">Approved</span>
                                    @else
                                        <span class="badge bg-warning">Pending Approval</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">Review Information</h6>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Created:</label>
                                <div>{{ $review->created_at->format('F d, Y \a\t g:i A') }}</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Last Updated:</label>
                                <div>{{ $review->updated_at->format('F d, Y \a\t g:i A') }}</div>
                            </div>
                            @if($review->comments)
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Comments:</label>
                                    <div class="border rounded p-3 bg-light">
                                        {{ $review->comments }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('reviews.index') }}" class="btn btn-outline-secondary">
                            <i class="ri-arrow-left-line me-1"></i> Back to List
                        </a>
                        <a href="{{ route('reviews.edit', $review) }}" class="btn btn-primary">
                            <i class="ri-edit-line me-1"></i> Edit Review
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 