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
        <li class="breadcrumb-item">
            <a href="{{ route('admin.login-activities.index') }}">Login Activities</a>
        </li>
        <li class="breadcrumb-item text-primary" aria-current="page">
            {{ $user->first_name }} {{ $user->last_name }}
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
                            <i class="ri-user-line me-2 text-primary"></i>Login Activities for {{ $user->first_name }} {{ $user->last_name }}
                            <span class="badge bg-soft-primary text-primary ms-2">{{ $activities->total() }} Total</span>
                        </h5>
                        <a href="{{ route('admin.login-activities.index') }}" class="btn btn-secondary">
                            <i class="ri-arrow-left-line me-1"></i> Back to All Activities
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6>User Information</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Name:</strong></td>
                                    <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td>{{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Role:</strong></td>
                                    <td>
                                        <span class="badge bg-{{ $user->role == 'admin' ? 'success' : ($user->role == 'specialist' ? 'warning' : 'info') }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Last Login:</strong></td>
                                    <td>{{ $user->last_login_at ? $user->last_login_at->format('Y-m-d H:i:s') : 'Never' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>Activity Summary</h6>
                            @php
                                $totalLogins = $activities->total();
                                $successfulLogins = $activities->where('status', 'success')->count();
                                $failedLogins = $activities->where('status', 'failed')->count();
                            @endphp
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Total Activities:</strong></td>
                                    <td>{{ $totalLogins }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Successful Logins:</strong></td>
                                    <td><span class="badge bg-success">{{ $successfulLogins }}</span></td>
                                </tr>
                                <tr>
                                    <td><strong>Failed Logins:</strong></td>
                                    <td><span class="badge bg-danger">{{ $failedLogins }}</span></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Date & Time</th>
                                    <th>IP Address</th>
                                    <th>User Agent</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($activities as $activity)
                                <tr>
                                    <td>{{ $activity->created_at->format('Y-m-d H:i:s') }}</td>
                                    <td>{{ $activity->ip_address }}</td>
                                    <td>
                                        <small class="text-muted">{{ Str::limit($activity->user_agent, 100) }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $activity->status == 'success' ? 'success' : 'danger' }}">
                                            {{ ucfirst($activity->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">No login activities found for this user.</td>
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