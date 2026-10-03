@extends('admin.layout.layout')

@section('links')
<link rel="stylesheet" href="{{asset('assets/vendor/datatables/dataTables.bs5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/datatables/dataTables.bs5-custom.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/datatables/buttons/dataTables.bs5-custom.css')}}">
<style>
    .certificate-card {
        transition: all 0.3s ease;
        border: 1px solid #e9ecef;
        border-radius: 0.5rem;
        overflow: hidden;
    }
    .certificate-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.08);
    }
    .certificate-preview {
        height: 200px;
        background-color: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    .certificate-preview img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }
    .certificate-actions {
        position: absolute;
        top: 10px;
        right: 10px;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .certificate-card:hover .certificate-actions {
        opacity: 1;
    }
</style>
@endsection

@section('content')
    <div class="app-hero-header d-flex align-items-center">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <i class="ri-home-8-line lh-1 pe-3 me-3 border-end"></i>
                <a href="{{ route('/') }}">Home</a>
            </li>
            <li class="breadcrumb-item text-primary" aria-current="page">
                Medical Health Certificates
            </li>
        </ol>

        <div class="ms-auto">
            <a href="{{ route('content.create') }}" class="btn btn-primary">
                <i class="ri-add-line me-1"></i> Upload New Certificate
            </a>
        </div>
    </div>

    <div class="app-body">
        @include('admin/notifications')
        
        <div class="card">
            <div class="card-header bg-white py-3">
                <div class="d-flex flex-column flex-md-row align-items-center justify-content-between">
                    <h5 class="card-title mb-3 mb-md-0">
                        <i class="ri-file-paper-2-line me-2 text-primary"></i>All Certificates
                        <span class="badge bg-soft-primary text-primary ms-2">{{ $contents instanceof \Illuminate\Pagination\LengthAwarePaginator ? $contents->total() : $contents->count() }} Total</span>
                    </h5>
                    
                    <div class="d-flex gap-2">
                        <form method="GET" action="{{ request()->url() }}" class="d-flex">
                            <div class="input-group" style="width: 250px;">
                                <input type="search" 
                                       name="search" 
                                       class="form-control" 
                                       placeholder="Search certificates..." 
                                       value="{{ request()->search }}">
                                @if(request()->search)
                                <a href="{{ request()->url() }}" class="btn btn-outline-secondary" type="button">
                                    <i class="ri-close-line"></i>
                                </a>
                                @endif
                                <button type="submit" class="btn btn-primary">
                                    <i class="ri-search-line"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="card-body">
                @if($contents->isEmpty())
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="ri-file-paper-2-line display-4 text-muted"></i>
                    </div>
                    <h5 class="mb-2">No certificates found</h5>
                    <p class="text-muted mb-4">
                        @if(request()->search)
                            No certificates match your search criteria. Try a different search term.
                        @else
                            You haven't uploaded any certificates yet.
                        @endif
                    </p>
                    <a href="{{ route('content.create') }}" class="btn btn-primary">
                        <i class="ri-upload-line me-1"></i> Upload Your First Certificate
                    </a>
                </div>
                @else
                <div class="row g-4">
                    @foreach($contents as $content)
                    <div class="col-xxl-3 col-lg-4 col-md-6">
                        <div class="card certificate-card h-100">
                            <div class="position-relative">
                                <div class="certificate-preview">
                                    @if(pathinfo(asset($content->content), PATHINFO_EXTENSION) === 'pdf')
                                        <i class="ri-file-pdf-line text-danger display-3"></i>
                                    @elseif(in_array(pathinfo(asset($content->content), PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif', 'PNG']))
                                        <img src="{{ asset($content->content) }}" alt="Certificate Preview" class="img-fluid">
                                    @else
                                        <i class="ri-file-line text-primary display-3"></i>
                                    @endif
                                </div>
                                <div class="certificate-actions">
                                    <div class="btn-group" role="group">
                                        <button type="button" 
                                                class="btn btn-sm btn-icon btn-light" 
                                                data-bs-toggle="dropdown" 
                                                aria-expanded="false">
                                            <i class="ri-more-2-fill"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#contentModal{{ $content->id }}">
                                                    <i class="ri-eye-line me-2"></i> Preview
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="#" onclick="downloadFile('{{ asset($content->content) }}')">
                                                    <i class="ri-download-line me-2"></i> Download
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form id="deleteForm{{ $content->id }}" action="{{ route('content.destroy', $content->id) }}" method="POST" style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                                <a class="dropdown-item text-danger" href="#" 
                                                   onclick="event.preventDefault(); 
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
                                                           document.getElementById('deleteForm{{ $content->id }}').submit();
                                                       }
                                                   });">
                                                    <i class="ri-delete-bin-line me-2"></i> Delete
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <h6 class="card-title text-truncate mb-1">
                                    {{ $content->title ?? 'Untitled Certificate' }}
                                </h6>
                                <p class="text-muted small mb-2">
                                    <i class="ri-calendar-line me-1"></i> 
                                    {{ $content->created_at->format('M d, Y') }}
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-soft-primary">
                                        {{ strtoupper(pathinfo(asset($content->content), PATHINFO_EXTENSION)) }}
                                    </span>
                                    <a href="{{ route('content.edit', $content->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="ri-edit-line me-1"></i> Edit
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Preview Modal -->
                    <div class="modal fade" id="contentModal{{ $content->id }}" tabindex="-1" aria-labelledby="contentModalLabel{{ $content->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="contentModalLabel{{ $content->id }}">
                                        {{ $content->title ?? 'Certificate Preview' }}
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-0">
                                    @if(pathinfo(asset($content->content), PATHINFO_EXTENSION) === 'pdf')
                                        <iframe src="{{ asset($content->content) }}" style="width: 100%; height: 70vh; border: none;"></iframe>
                                    @elseif(in_array(pathinfo(asset($content->content), PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif']))
                                        <img src="{{ asset($content->content) }}" alt="Certificate Preview" class="img-fluid w-100">
                                    @else
                                        <div class="text-center py-5">
                                            <i class="ri-file-line display-4 text-muted mb-3"></i>
                                            <p class="text-muted">Preview not available for this file type</p>
                                            <a href="{{ asset($content->content) }}" class="btn btn-primary" download>
                                                <i class="ri-download-line me-1"></i> Download File
                                            </a>
                                        </div>
                                    @endif
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <a href="{{ asset($content->content) }}" class="btn btn-primary" download>
                                        <i class="ri-download-line me-1"></i> Download
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($contents instanceof \Illuminate\Pagination\LengthAwarePaginator && $contents->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted">
                        Showing <span class="fw-semibold">{{ $contents->firstItem() }}</span> to 
                        <span class="fw-semibold">{{ $contents->lastItem() }}</span> of 
                        <span class="fw-semibold">{{ $contents->total() }}</span> entries
                    </div>
                    <nav aria-label="Page navigation">
                        {{ $contents->links() }}
                    </nav>
                </div>
                @endif
                @endif
            </div>
        </div>
    </div>
@endsection

@section('scripts')
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

    // Download file function
    function downloadFile(fileUrl) {
        Swal.fire({
            title: 'Downloading...',
            text: 'Preparing your file for download',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        // Create a temporary link and trigger download
        const link = document.createElement('a');
        link.href = fileUrl;
        link.download = fileUrl.split('/').pop();
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        // Close the loading indicator after a short delay
        setTimeout(() => {
            Swal.close();
        }, 1000);
    }

    // Show success message if exists
    @if(session('success'))
    document.addEventListener('DOMContentLoaded', function() {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });
        
        Toast.fire({
            icon: 'success',
            title: '{{ session('success') }}'
        });
    });
    @endif
</script>
@endsection