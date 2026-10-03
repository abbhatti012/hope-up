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
        <li class="breadcrumb-item active" aria-current="page">Create Review</li>
    </ol>
</div>

<div class="app-body">
    @include('admin/notifications')
    <div class="row gx-3">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">
                        <i class="ri-star-line me-2 text-primary"></i>
                        Create New Review
                    </h5>
                </div>
                <div class="card-body">
                    <form id="reviewForm" class="needs-validation" method="POST" action="{{ route('reviews.store') }}" novalidate>
                        @csrf

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="doctor_id" class="form-label">Doctor <span class="text-danger">*</span></label>
                                <select class="form-select searchable-dropdown @error('doctor_id') is-invalid @enderror" 
                                        id="doctor_id" 
                                        name="doctor_id" 
                                        required>
                                    <option value="">Select Doctor</option>
                                    @foreach($doctors as $doctor)
                                        <option value="{{ $doctor->id }}" 
                                                {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                            {{ $doctor->first_name }} {{ $doctor->last_name }} 
                                            ({{ $doctor->doctorDetail->speciality->title ?? 'General' }})
                                        </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    Please select a doctor.
                                </div>
                                @error('doctor_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="reviewer_id" class="form-label">Reviewer (Patient) <span class="text-danger">*</span></label>
                                <select class="form-select searchable-dropdown @error('reviewer_id') is-invalid @enderror" 
                                        id="reviewer_id" 
                                        name="reviewer_id" 
                                        required>
                                    <option value="">Select Reviewer</option>
                                    @foreach($reviewers as $reviewer)
                                        <option value="{{ $reviewer->id }}" 
                                                {{ old('reviewer_id') == $reviewer->id ? 'selected' : '' }}>
                                            {{ $reviewer->first_name }} {{ $reviewer->last_name }} 
                                            ({{ $reviewer->email }})
                                        </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    Please select a reviewer.
                                </div>
                                @error('reviewer_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="rating" class="form-label">Rating <span class="text-danger">*</span></label>
                                <div class="rating-input">
                                    <div class="stars d-flex align-items-center">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="ri-star-line star me-2" 
                                               data-rating="{{ $i }}" 
                                               style="font-size: 24px; cursor: pointer; color: #ffc107;"></i>
                                        @endfor
                                        <span class="ms-3 fw-semibold" id="rating-text">Select rating</span>
                                    </div>
                                    <input type="hidden" 
                                           id="rating" 
                                           name="rating" 
                                           value="{{ old('rating') }}" 
                                           required>
                                </div>
                                <div class="invalid-feedback">
                                    Please select a rating.
                                </div>
                                @error('rating')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="rating_type" class="form-label">Rating Type <span class="text-danger">*</span></label>
                                <select class="form-select @error('rating_type') is-invalid @enderror" 
                                        id="rating_type" 
                                        name="rating_type" 
                                        required>
                                    <option value="">Select Rating Type</option>
                                    <option value="excellent" {{ old('rating_type') == 'excellent' ? 'selected' : '' }}>Excellent</option>
                                    <option value="very_good" {{ old('rating_type') == 'very_good' ? 'selected' : '' }}>Very Good</option>
                                    <option value="good" {{ old('rating_type') == 'good' ? 'selected' : '' }}>Good</option>
                                    <option value="fair" {{ old('rating_type') == 'fair' ? 'selected' : '' }}>Fair</option>
                                    <option value="poor" {{ old('rating_type') == 'poor' ? 'selected' : '' }}>Poor</option>
                                    <option value="bad" {{ old('rating_type') == 'bad' ? 'selected' : '' }}>Bad</option>
                                </select>
                                <div class="invalid-feedback">
                                    Please select a rating type.
                                </div>
                                @error('rating_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-12">
                                <label for="comments" class="form-label">Comments</label>
                                <textarea class="form-control @error('comments') is-invalid @enderror" 
                                          id="comments" 
                                          name="comments" 
                                          rows="4" 
                                          placeholder="Enter your review comments..."
                                          maxlength="1000">{{ old('comments') }}</textarea>
                                <div class="form-text">
                                    <span id="char-count">0</span>/1000 characters
                                </div>
                                @error('comments')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="is_approved" 
                                           name="is_approved" 
                                           value="1"
                                           {{ old('is_approved') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_approved">
                                        Approve this review immediately
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('reviews.index') }}" class="btn btn-outline-secondary">
                                <i class="ri-arrow-left-line me-1"></i> Back to List
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-save-line me-1"></i> Create Review
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('assets/js/custom.js') }}"></script>
<script>
    $(document).ready(function() {
        // Star rating functionality
        $('.star').click(function() {
            const rating = $(this).data('rating');
            $('#rating').val(rating);
            
            // Update stars display
            $('.star').removeClass('ri-star-fill ri-star-line').addClass('ri-star-line');
            $('.star').each(function(index) {
                if (index < rating) {
                    $(this).removeClass('ri-star-line').addClass('ri-star-fill');
                }
            });
            
            // Update rating text
            const ratingTexts = ['', 'Poor', 'Fair', 'Good', 'Very Good', 'Excellent'];
            $('#rating-text').text(ratingTexts[rating] || 'Select rating');
        });

        // Character count for comments
        $('#comments').on('input', function() {
            const length = $(this).val().length;
            $('#char-count').text(length);
            
            if (length > 1000) {
                $(this).val($(this).val().substring(0, 1000));
                $('#char-count').text(1000);
            }
        });

        // Form validation
        const form = document.getElementById('reviewForm');
        if (form) {
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        }

        // Auto-select rating type based on rating
        $('#rating').on('change', function() {
            const rating = parseInt($(this).val());
            let ratingType = '';
            
            if (rating === 5) ratingType = 'excellent';
            else if (rating === 4) ratingType = 'very_good';
            else if (rating === 3) ratingType = 'good';
            else if (rating === 2) ratingType = 'fair';
            else if (rating === 1) ratingType = 'poor';
            
            if (ratingType) {
                $('#rating_type').val(ratingType);
            }
        });
    });
</script>

<style>
    .star:hover {
        color: #ffc107 !important;
    }
    .ri-star-fill {
        color: #ffc107 !important;
    }
</style>
@endsection 