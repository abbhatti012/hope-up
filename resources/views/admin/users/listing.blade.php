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
            <a href="index-2.html">Home</a>
            </li>
            <li class="breadcrumb-item text-primary" aria-current="page">
            Dashboard
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
                                <i class="ri-team-line me-2 text-primary"></i>All Users
                                <span class="badge bg-soft-primary text-primary ms-2">{{ $users->total() }} Total</span>
                            </h5>
                            <a href="{{ route('doctors.users.create') }}" class="btn btn-primary">
                                <i class="ri-user-add-line me-1"></i> Add New User
                            </a>
                        </div>
                    <div class="card-body">
                        <!-- Search and Filter Card -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body p-3">
                                <div class="row g-3">
                                    <div class="col-md-8">
                                        <form method="GET" action="{{ route('manage.doctors') }}" class="search-form">
                                            <div class="input-group">
                                                <span class="input-group-text bg-white border-end-0">
                                                    <i class="ri-search-line text-muted"></i>
                                                </span>
                                                <input type="search" 
                                                       name="search" 
                                                       class="form-control border-start-0 ps-0" 
                                                       placeholder="Search users by name, email, or specialty..." 
                                                       value="{{ request()->search }}">
                                                @if(request()->search)
                                                <a href="{{ route('manage.doctors') }}" class="btn btn-outline-secondary" type="button">
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
                                        <form method="GET" action="{{ route('manage.doctors') }}">
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
                                        {{ $users->total() }} result(s) found for "{{ request()->search }}"
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
                                        <th>User</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Last Login</th>
                                        <th>Login Activities</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $user->profile_photo ? asset($user->profile_photo) : asset('default.png') }}" 
                                                class="img-shadow img-2x rounded-circle me-2" 
                                                alt="{{ $user->first_name }}" style="width: 40px; height: 40px;">
                                                <div>
                                                    <h6 class="mb-0">
                                                        @if($user->role === 'user')
                                                            <a href="{{ route('patients.show', $user) }}" class="text-decoration-none text-primary">
                                                                {{ $user->first_name }} {{ $user->last_name }}
                                                            </a>
                                                        @elseif($user->role === 'specialist')
                                                            <a href="{{ route('doctor-detail', $user->id) }}" class="text-decoration-none text-primary" target="_blank">
                                                                {{ $user->first_name }} {{ $user->last_name }}
                                                            </a>
                                                        @else
                                                            {{ $user->first_name }} {{ $user->last_name }}
                                                        @endif
                                                    </h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="mailto:{{ $user->email }}" class="text-decoration-none text-primary">
                                                {{ $user->email }}
                                            </a>
                                        </td>
                                        <td>
                                            @if($user->role === 'user')
                                                <span class="badge bg-info">Patient</span>
                                            @elseif($user->role === 'admin')
                                                <span class="badge bg-success">Admin</span>
                                            @elseif($user->role === 'specialist')
                                                <span class="badge bg-warning">Specialist</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($user->is_block)
                                                <span class="badge bg-danger">Blocked</span>
                                            @else
                                                <span class="badge bg-success">Active</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.login-activities.user', $user->id) }}" 
                                               class="btn btn-sm btn-outline-info" 
                                               data-bs-toggle="tooltip" 
                                               data-bs-placement="top" 
                                               title="View Login Activities"
                                               target="_blank">
                                                <i class="ri-history-line"></i> Activities
                                            </a>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                @if(!empty($user->detail))
                                                    <a href="{{ route('doctor-detail', $user->id) }}" 
                                                        class="btn btn-sm btn-action btn-outline-primary" 
                                                        data-bs-toggle="tooltip" 
                                                        data-bs-placement="top" 
                                                        title="Edit User">
                                                        <i class="ri-eye-line"></i>
                                                    </a>
                                                @endif
                                                <a href="{{ route('doctors.users.edit', $user) }}" 
                                                   class="btn btn-sm btn-action btn-outline-primary" 
                                                   data-bs-toggle="tooltip" 
                                                   data-bs-placement="top" 
                                                   title="Edit User">
                                                    <i class="ri-edit-line"></i>
                                                </a>
                                                @if($user->is_block)
                                                    <button type="button" 
                                                            class="btn btn-sm btn-action btn-outline-success unblock-user" 
                                                            data-id="{{ $user->id }}"
                                                            data-bs-toggle="tooltip" 
                                                            data-bs-placement="top" 
                                                            title="Unblock User">
                                                        <i class="ri-user-unfollow-line"></i>
                                                    </button>
                                                @else
                                                    <button type="button" 
                                                            class="btn btn-sm btn-action btn-outline-warning block-user" 
                                                            data-id="{{ $user->id }}"
                                                            data-bs-toggle="tooltip" 
                                                            data-bs-placement="top" 
                                                            title="Block User">
                                                        <i class="ri-user-forbid-line"></i>
                                                    </button>
                                                @endif
                                                <a href="javascript:void(0);" 
                                                   class="btn btn-sm btn-action btn-outline-danger" 
                                                   data-bs-toggle="tooltip" 
                                                   data-bs-placement="top" 
                                                   title="Delete User"
                                                   onclick="deleteUser({{ $user->id }})">
                                                    <i class="ri-delete-bin-line"></i>
                                                </a>
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
                                Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} entries
                            </div>
                            <div>
                                {{ $users->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="userDetailModal" tabindex="-1" aria-labelledby="userDetailModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="userDetailModalLabel">User Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <!-- Profile Photo -->
                            <div class="col-md-4 text-center">
                                <img src="" id="profilePhoto" alt="Profile Photo" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                            </div>

                            <!-- User Information -->
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>User ID:</strong> <span id="userId"></span></p>
                                        <p><strong>First Name:</strong> <span id="firstName"></span></p>
                                        <p><strong>Last Name:</strong> <span id="lastName"></span></p>
                                        <p><strong>Email:</strong> <span id="email"></span></p>
                                        <p><strong>Phone Number:</strong> <span id="phoneNumber"></span></p>
                                        <p><strong>Date of Birth:</strong> <span id="dob"></span></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Gender:</strong> <span id="gender"></span></p>
                                        <p><strong>Specialty:</strong> <span id="specialty"></span></p>
                                        <p><strong>Medical License:</strong> <span id="medicalLicense"></span></p>
                                        <p><strong>Experience:</strong> <span id="experience"></span> years</p>
                                        <p><strong>Graduation Year:</strong> <span id="graduationYear"></span></p>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-12">
                                        <p><strong>Medical Concern:</strong></p>
                                        <p id="medicalConcern" class="p-2 bg-light rounded"></p>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-12 text-center">
                                        <p class="mb-2"><strong>Degree Certificate:</strong></p>
                                        <a href="#" id="degreeCertificateLink" target="_blank">
                                            <img src="" id="degreeCertificate" alt="Degree Certificate" class="img-fluid" style="max-height: 200px;">
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{asset('assets/vendor/datatables/dataTables.min.js')}}"></script>
<script src="{{asset('assets/vendor/datatables/dataTables.bootstrap.min.js')}}"></script>
<script src="{{asset('assets/vendor/datatables/custom/custom-datatables.js')}}"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".role-change").forEach(function(select) {
            select.addEventListener("change", function(event) {
                let userId = this.getAttribute("data-user-id");
                let updateUrl = this.getAttribute("data-update-url");
                let newRole = this.value;

                Swal.fire({
                    title: "Are you sure?",
                    text: `You are about to change the role of User ID ${userId} to '${newRole}'.`,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, update it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Create and submit form dynamically
                        let form = document.createElement("form");
                        form.method = "POST";
                        form.action = updateUrl;

                        // CSRF token
                        let csrfToken = document.createElement("input");
                        csrfToken.type = "hidden";
                        csrfToken.name = "_token";
                        csrfToken.value = "{{ csrf_token() }}";
                        form.appendChild(csrfToken);

                        // PATCH method override
                        let methodInput = document.createElement("input");
                        methodInput.type = "hidden";
                        methodInput.name = "_method";
                        methodInput.value = "PATCH";
                        form.appendChild(methodInput);

                        // Role input
                        let roleInput = document.createElement("input");
                        roleInput.type = "hidden";
                        roleInput.name = "role";
                        roleInput.value = newRole;
                        form.appendChild(roleInput);

                        document.body.appendChild(form);
                        form.submit();
                    } else {
                        // Reset to previous role if canceled
                        event.target.value = event.target.dataset.previousValue;
                    }
                });

                // Store previous value in case the user cancels
                event.target.dataset.previousValue = event.target.value;
            });
        });
    });
</script>
<script>
    $(document).ready(function () {
        $('#userDetailModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            $('#userId').text(button.data('id'));
            $('#firstName').text(button.data('first-name'));
            $('#lastName').text(button.data('last-name'));
            $('#email').text(button.data('email'));
            $('#phoneNumber').text(button.data('phone'));
            $('#specialty').text(button.data('specialty'));
            $('#dob').text(button.data('dob'));
            $('#gender').text(button.data('gender'));
            $('#medicalLicense').text(button.data('medical-license'));
            $('#experience').text(button.data('experience'));
            $('#graduationYear').text(button.data('graduation-year'));
            $('#medicalConcern').text(button.data('medical-concern'));

            $('#profilePhoto').attr('src', button.data('profile-photo') || '/default-profile.jpg');
            $('#degreeCertificate').attr('src', button.data('degree-certificate') || '/default-certificate.jpg');
        });
    });

    // Handle block user action
    $(document).on('click', '.block-user', function() {
        const userId = $(this).data('id');
        const button = $(this);
        
        Swal.fire({
            title: 'Are you sure?',
            text: 'This will block the user from accessing their account.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, block user!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/admin/users/' + userId + '/block',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'POST'
                    },
                    success: function(response) {
                        if (response.success) {
                            // Show success message
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
                                title: response.message
                            });

                            // Reload the page to reflect changes
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        } else {
                            Swal.fire('Error!', response.message || 'Failed to block user.', 'error');
                        }
                    },
                    error: function(xhr) {
                        const errorMessage = xhr.responseJSON && xhr.responseJSON.message 
                            ? xhr.responseJSON.message 
                            : 'An error occurred while blocking the user.';
                        Swal.fire('Error!', errorMessage, 'error');
                    }
                });
            }
        });
    });

    // Handle unblock user action
    $(document).on('click', '.unblock-user', function() {
        const userId = $(this).data('id');
        const button = $(this);
        
        Swal.fire({
            title: 'Are you sure?',
            text: 'This will unblock the user and allow them to access their account.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, unblock user!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/admin/users/' + userId + '/unblock',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'POST'
                    },
                    success: function(response) {
                        if (response.success) {
                            // Show success message
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
                                title: response.message
                            });

                            // Reload the page to reflect changes
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        } else {
                            Swal.fire('Error!', response.message || 'Failed to unblock user.', 'error');
                        }
                    },
                    error: function(xhr) {
                        const errorMessage = xhr.responseJSON && xhr.responseJSON.message 
                            ? xhr.responseJSON.message 
                            : 'An error occurred while unblocking the user.';
                        Swal.fire('Error!', errorMessage, 'error');
                    }
                });
            }
        });
    });
</script>
@endsection