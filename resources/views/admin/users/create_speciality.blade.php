@extends('admin.layout.layout')

@section('content')
<div class="app-hero-header d-flex align-items-center">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <i class="ri-home-8-line lh-1 pe-3 me-3 border-end"></i>
            <a href="{{ route('/') }}">Home</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('manage.speciality') }}">Specialities</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">
            {{ isset($speciality) ? 'Edit' : 'Add' }} Speciality
        </li>
    </ol>
</div>

<div class="app-body">
    @include('admin/notifications')
    <div class="row gx-3">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">
                        <i class="ri-stack-line me-2 text-primary"></i>
                        {{ isset($speciality) ? 'Edit' : 'Add New' }} Speciality
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ isset($speciality) ? route('specialities.update', $speciality->id) : route('specialities.store') }}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                        @csrf
                        @if(isset($speciality)) 
                            @method('PUT') 
                        @endif

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                           id="title" name="title" required 
                                           value="{{ old('title', $speciality->title ?? '') }}"
                                           placeholder="Enter speciality title">
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 justify-content-end mt-4">
                            <a href="{{ route('manage.speciality') }}" class="btn btn-outline-secondary">
                                <i class="ri-arrow-left-line me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-save-line me-1"></i> {{ isset($speciality) ? 'Update' : 'Save' }} Speciality
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
<script>
    // Enable Bootstrap form validation
    (function() {
        'use strict';
        window.addEventListener('load', function() {
            var forms = document.getElementsByClassName('needs-validation');
            var validation = Array.prototype.filter.call(forms, function(form) {
                form.addEventListener('submit', function(event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();
</script>
@endsection
