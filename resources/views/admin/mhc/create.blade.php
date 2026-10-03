@extends('admin.layout.layout')

@section('content')
<div class="app-hero-header d-flex align-items-center">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <i class="ri-home-8-line lh-1 pe-3 me-3 border-end"></i>
            <a href="{{ route('/') }}">Home</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('manage-mhc') }}">Medical Health Certificates</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">
            {{ isset($content) ? 'Edit' : 'Upload New' }} Certificate
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
                        <i class="ri-file-paper-2-line me-2 text-primary"></i>
                        {{ isset($content) ? 'Edit' : 'Upload New' }} Certificate
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ isset($content) ? route('content.update', $content->id) : route('content.store') }}" 
                          method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                        @csrf
                        @if(isset($content)) 
                            @method('PUT') 
                        @endif

                        <div class="row mb-4">
                            <div class="col-12">
                                <label for="title" class="form-label">Certificate Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="title" name="title" 
                                       value="{{ $content->title ?? old('title') }}" required>
                                <div class="invalid-feedback">
                                    Please provide a title for the certificate.
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-12">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" 
                                          rows="3">{{ $content->description ?? old('description') }}</textarea>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-12">
                                <label for="content" class="form-label">
                                    {{ isset($content) ? 'Update Certificate File' : 'Upload Certificate File' }} 
                                    <span class="text-danger">*</span>
                                </label>
                                <input class="form-control" type="file" id="content" name="content" 
                                       {{ !isset($content) ? 'required' : '' }}>
                                <div class="form-text">
                                    Accepted formats: PDF, JPG, PNG (Max: 5MB)
                                </div>
                                <div class="invalid-feedback">
                                    Please select a certificate file to upload.
                                </div>
                            </div>
                        </div>

                        @if(isset($content))
                        <div class="row mb-4">
                            <div class="col-12">
                                <label class="form-label">Current File:</label>
                                <div class="border rounded p-3 bg-light">
                                    @if(pathinfo($content->content, PATHINFO_EXTENSION) === 'pdf')
                                        <i class="ri-file-pdf-line text-danger fs-1"></i>
                                    @else
                                        <img src="{{ asset($content->content) }}" alt="Certificate Preview" class="img-fluid" style="max-height: 200px;">
                                    @endif
                                    <div class="mt-2">
                                        <a href="{{ asset($content->content) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="ri-eye-line me-1"></i> View
                                        </a>
                                        <a href="{{ asset($content->content) }}" download class="btn btn-sm btn-outline-secondary">
                                            <i class="ri-download-line me-1"></i> Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('manage-mhc') }}" class="btn btn-outline-secondary">
                                <i class="ri-arrow-left-line me-1"></i> Back to List
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-save-line me-1"></i>
                                {{ isset($content) ? 'Update' : 'Upload' }} Certificate
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
    // Form validation
    (function () {
        'use strict'
        
        var forms = document.querySelectorAll('.needs-validation')
        
        Array.prototype.slice.call(forms).forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                
                form.classList.add('was-validated')
            }, false)
        })
    })()

    // File input preview
    document.getElementById('content').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;
        
        // Validate file size (5MB max)
        const maxSize = 5 * 1024 * 1024; // 5MB in bytes
        if (file.size > maxSize) {
            alert('File size exceeds 5MB limit. Please choose a smaller file.');
            e.target.value = ''; // Clear the file input
            return;
        }
        
        // Validate file type
        const validTypes = ['application/pdf', 'image/jpeg', 'image/png'];
        if (!validTypes.includes(file.type)) {
            alert('Only PDF, JPG, and PNG files are allowed.');
            e.target.value = ''; // Clear the file input
            return;
        }
    });
</script>
@endsection
