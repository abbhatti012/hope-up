@extends('admin.layout.layout')
@section('links')
<link rel="stylesheet" href="{{asset('assets/vendor/datatables/dataTables.bs5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/datatables/dataTables.bs5-custom.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/datatables/buttons/dataTables.bs5-custom.css')}}">
@endsection

@section('content')
    <div class="app-hero-header d-flex align-items-center">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
            <i class="ri-home-8-line lh-1 pe-3 me-3 border-end"></i>
            <a href="{{ route('/') }}">Home</a>
            </li>
            <li class="breadcrumb-item text-primary" aria-current="page">
            Specialities
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
                                <i class="ri-stack-line me-2 text-primary"></i>Specialities
                                <span class="badge bg-soft-primary text-primary ms-2">{{ $specialists->total() }} Total</span>
                            </h5>
                            <a href="{{ route('specialities.create') }}" class="btn btn-primary">
                                <i class="ri-add-line me-1"></i> Add New Speciality
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Search and Filter Card -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body p-3">
                                <div class="row g-3">
                                    <div class="col-md-8">
                                        <form method="GET" action="{{ route('manage.speciality') }}" class="search-form">
                                            <div class="input-group">
                                                <span class="input-group-text bg-white border-end-0">
                                                    <i class="ri-search-line text-muted"></i>
                                                </span>
                                                <input type="search" 
                                                       name="search" 
                                                       class="form-control border-start-0 ps-0" 
                                                       placeholder="Search specialities by name or description..." 
                                                       value="{{ request()->search }}">
                                                @if(request()->search)
                                                <a href="{{ route('manage.speciality') }}" class="btn btn-outline-secondary" type="button">
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
                                        <form method="GET" action="{{ route('manage.speciality') }}">
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
                                        {{ $specialists->total() }} result(s) found for "{{ request()->search }}"
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
                                        <th>Title</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($specialists as $speciality)
                                    <tr>
                                        <td>{{ $speciality->id }}</td>
                                        <td>{{ $speciality->title }}</td>
                                        <td>
                                            @if($speciality->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <form action="{{ route('specialities.toggle-status', $speciality->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-action {{ $speciality->is_active ? 'btn-outline-danger' : 'btn-outline-success' }}"
                                                            data-bs-toggle="tooltip" 
                                                            data-bs-placement="top" 
                                                            title="{{ $speciality->is_active ? 'Deactivate' : 'Activate' }}">
                                                        <i class="ri-{{ $speciality->is_active ? 'close-line' : 'check-line' }}"></i>
                                                    </button>
                                                </form>
                                                <a href="{{ route('specialities.edit', $speciality) }}" 
                                                   class="btn btn-sm btn-action btn-outline-primary" 
                                                   data-bs-toggle="tooltip" 
                                                   data-bs-placement="top" 
                                                   title="Edit">
                                                    <i class="ri-edit-line"></i>
                                                </a>
                                                <form action="{{ route('specialities.delete', $speciality->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-action btn-outline-danger"
                                                            data-bs-toggle="tooltip" 
                                                            data-bs-placement="top"
                                                            title="Delete"
                                                            onclick="return confirm('Are you sure you want to delete this speciality?')">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- Table ends -->

                        <!-- Pagination -->
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="text-muted">
                                Showing {{ $specialists->firstItem() }} to {{ $specialists->lastItem() }} of {{ $specialists->total() }} entries
                            </div>
                            <div>
                                {{ $specialists->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    // Enable tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
</script>
      </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')

@endsection