@extends('admin.layout.layout')
@section('links')
<link rel="stylesheet" href="{{asset('assets/vendor/datatables/dataTables.bs5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/datatables/dataTables.bs5-custom.css')}}">
@endsection

@section('content')
<div class="app-hero-header d-flex align-items-center">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <i class="ri-home-8-line lh-1 pe-3 me-3 border-end"></i>
            <a href="/">Home</a>
        </li>
        <li class="breadcrumb-item text-primary" aria-current="page">
            Login Activities
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
                            <i class="ri-history-line me-2 text-primary"></i>Login Activities
                            <span class="badge bg-soft-primary text-primary ms-2">{{ $activities->total() }} Total</span>
                        </h5>
                        <a href="{{ route('admin.login-activities.export') }}" class="btn btn-success">
                            <i class="ri-download-2-line me-1"></i> Export CSV
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body p-3">
                            <form method="GET" action="{{ route('admin.login-activities.index') }}">
                                <div class="row g-3 align-items-end">
                                    <div class="col-md-3">
                                        <label for="user_type" class="form-label">User Type</label>
                                        <select name="user_type" id="user_type" class="form-select">
                                            <option value="">All Users</option>
                                            <option value="admin" {{ request('user_type') == 'admin' ? 'selected' : '' }}>Admin</option>
                                            <option value="specialist" {{ request('user_type') == 'specialist' ? 'selected' : '' }}>Doctor</option>
                                            <option value="user" {{ request('user_type') == 'user' ? 'selected' : '' }}>Patient</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="status" class="form-label">Status</label>
                                        <select name="status" id="status" class="form-select">
                                            <option value="">All Status</option>
                                            <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>Success</option>
                                            <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="date_from" class="form-label">From Date</label>
                                        <input type="date" name="date_from" id="date_from" class="form-control" value="{{ request('date_from') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="date_to" class="form-label">To Date</label>
                                        <input type="date" name="date_to" id="date_to" class="form-control" value="{{ request('date_to') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="ri-filter-3-line me-1"></i> Filter
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>IP Address</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($activities as $activity)
                                <tr>
                                    <td>
                                        @if($activity->user)
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $activity->user->profile_photo ? asset($activity->user->profile_photo) : asset('assets/images/user.png') }}" class="img-shadow img-2x rounded-circle me-2" alt="{{ $activity->user->first_name }}" style="width: 32px; height: 32px;">
                                                <span>{{ $activity->user->first_name }} {{ $activity->user->last_name }}</span>
                                            </div>
                                        @else
                                            <span class="text-muted">Unknown User</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($activity->user)
                                            {{ $activity->user->email }}
                                        @else
                                            <span class="text-muted">Unknown</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($activity->user)
                                            <span class="badge bg-{{ $activity->user->role == 'admin' ? 'success' : ($activity->user->role == 'specialist' ? 'warning' : 'info') }}">
                                                {{ ucfirst($activity->user->role) }}
                                            </span>
                                        @else
                                            <span class="text-muted">Unknown</span>
                                        @endif
                                    </td>
                                    <td>{{ $activity->ip_address }}</td>
                                    <td>
                                        <span class="badge bg-{{ $activity->status == 'success' ? 'success' : 'danger' }}">
                                            {{ ucfirst($activity->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $activity->created_at->format('Y-m-d H:i:s') }}</td>
                                    <td>
                                        @if($activity->user)
                                            <a href="{{ route('admin.login-activities.user', $activity->user->id) }}" 
                                               class="btn btn-sm btn-outline-info" 
                                               title="View User Activities">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No login activities found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted">
                            Showing {{ $activities->firstItem() }} to {{ $activities->lastItem() }} of {{ $activities->total() }} entries
                        </div>
                        <div>
                            {{ $activities->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 