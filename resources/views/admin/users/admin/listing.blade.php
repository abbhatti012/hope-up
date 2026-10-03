@extends('admin.layout.layout')

@section('links')
<link rel="stylesheet" href="{{asset('assets/vendor/datatables/dataTables.bs5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/datatables/dataTables.bs5-custom.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/datatables/buttons/dataTables.bs5-custom.css')}}">
@stop

@section('content')
<div class="app-hero-header d-flex align-items-center">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <i class="ri-home-8-line lh-1 pe-3 me-3 border-end"></i>
            <a href="{{ route('home') }}">Home</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('manage.admins') }}">Admins</a>
        </li>
        <li class="breadcrumb-item active">All Admins</li>
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
                            <i class="ri-admin-line me-2 text-primary"></i>All Admins
                            <span class="badge bg-soft-primary text-primary ms-2">{{ $admins->total() }} Total</span>
                        </h5>
                        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                            <i class="ri-user-add-line me-1"></i> Add New Admin
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Search and Filter Card -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body p-3">
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <form method="GET" action="{{ route('manage.admins') }}" class="search-form">
                                        <div class="input-group">
                                            <span class="input-group-text bg-white border-end-0">
                                                <i class="ri-search-line text-muted"></i>
                                            </span>
                                            <input type="search" 
                                                   name="search" 
                                                   class="form-control border-start-0 ps-0" 
                                                   placeholder="Search admins..." 
                                                   value="{{ request('search') }}">
                                            @if(request('search'))
                                                <a href="{{ route('manage.admins') }}" class="input-group-text bg-white text-danger">
                                                    <i class="ri-close-line"></i>
                                                </a>
                                            @endif
                                            <button type="submit" class="btn btn-primary">Search</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex justify-content-md-end">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('manage.admins', ['is_block' => '0']) }}" 
                                               class="btn btn-outline-soft-primary {{ request('is_block') == '0' ? 'active' : '' }}">
                                                Active
                                            </a>
                                            <a href="{{ route('manage.admins', ['is_block' => '1']) }}" 
                                               class="btn btn-outline-soft-danger {{ request('is_block') == '1' ? 'active' : '' }}">
                                                Blocked
                                            </a>
                                            <a href="{{ route('manage.admins') }}" 
                                               class="btn btn-outline-soft-secondary">
                                                All
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Admin</th>
                                    <th>Contact</th>
                                    <th>Status</th>
                                    <th>Last Login</th>
                                    <th>Login Activities</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($admins as $admin)
                                    <tr>
                                        <td>{{ $admin->id }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-circle me-3">
                                                    <img src="{{ $admin->profile_photo ? asset('storage/' . $admin->profile_photo) : asset('default.png') }}" 
                                                         alt="{{ $admin->full_name }}" 
                                                         class="avatar-sm rounded-circle">
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $admin->first_name }} {{ $admin->last_name }}</h6>
                                                    <small class="text-muted">ID: {{ $admin->id }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span>
                                                    <a href="mailto:{{ $admin->email }}" class="text-decoration-none text-primary">
                                                        {{ $admin->email }}
                                                    </a>
                                                </span>
                                                <small class="text-muted">{{ $admin->phone_number ?? 'No phone' }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-soft-{{ $admin->is_block ? 'danger' : 'success' }} text-{{ $admin->is_block ? 'danger' : 'success' }} d-inline-flex align-items-center">
                                                <span class="dot dot-lg rounded-circle me-2 bg-{{ $admin->is_block ? 'danger' : 'success' }}"></span>
                                                {{ $admin->is_block ? 'Blocked' : 'Active' }}
                                            </span>
                                        </td>
                                        <td>{{ $admin->last_login_at ? $admin->last_login_at->diffForHumans() : 'Never' }}</td>
                                        <td>
                                            <a href="{{ route('admin.login-activities.user', $admin->id) }}" 
                                               class="btn btn-sm btn-outline-info" 
                                               data-bs-toggle="tooltip" 
                                               data-bs-placement="top" 
                                               title="View Login Activities"
                                               target="_blank">
                                                <i class="ri-history-line"></i> Activities
                                            </a>
                                        </td>
                                        <td class="text-end">
                                            <div class="d-flex justify-content-end gap-2">
                                                @if($admin->role !== 'superadmin')
                                                    <a href="{{ route('admin.users.edit', $admin->id) }}" 
                                                       class="btn btn-sm btn-soft-primary" 
                                                       data-bs-toggle="tooltip" 
                                                       title="Edit">
                                                        <i class="ri-edit-line"></i>
                                                    </a>
                                                    <button type="button" 
                                                            class="btn btn-sm btn-soft-{{ $admin->is_block ? 'success' : 'danger' }} toggle-block" 
                                                            data-user-id="{{ $admin->id }}" 
                                                            data-status="{{ $admin->is_block ? '0' : '1' }}"
                                                            data-bs-toggle="tooltip"
                                                            title="{{ $admin->is_block ? 'Unblock' : 'Block' }}">
                                                        <i class="{{ $admin->is_block ? 'ri-check-line' : 'ri-close-line' }}"></i>
                                                    </button>
                                                @else
                                                    <span class="btn btn-sm btn-soft-secondary disabled" 
                                                          data-bs-toggle="tooltip" 
                                                          title="Superadmin (Protected)">
                                                        <i class="ri-shield-check-line"></i>
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <div class="d-flex flex-column align-items-center">
                                                <img src="{{ asset('assets/img/no-data.svg') }}" alt="No data" style="height: 120px;" class="mb-3">
                                                <h5 class="text-muted">No admins found</h5>
                                                @if(request()->has('search') || request()->has('status'))
                                                    <a href="{{ route('manage.admins') }}" class="btn btn-sm btn-outline-primary mt-2">
                                                        <i class="ri-refresh-line me-1"></i> Reset Filters
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($admins->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted">
                            Showing {{ $admins->firstItem() }} to {{ $admins->lastItem() }} of {{ $admins->total() }} entries
                        </div>
                        <div>
                            {{ $admins->appends(request()->query())->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Block/Unblock Confirmation Modal -->
<div class="modal fade" id="blockModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Action</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to <span id="actionText"></span> this admin?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="blockForm" method="POST" action="">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-danger">Confirm</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle block/unblock toggle
        document.querySelectorAll('.toggle-block').forEach(button => {
            button.addEventListener('click', function() {
                const userId = this.dataset.userId;
                const status = this.dataset.status;
                const actionText = status === '1' ? 'block' : 'unblock';
                
                document.getElementById('actionText').textContent = actionText;
                document.getElementById('blockForm').action = `/admin/users/${userId}/toggle-block`;
                
                const modal = new bootstrap.Modal(document.getElementById('blockModal'));
                modal.show();
            });
        });
    });
</script>
@endsection
