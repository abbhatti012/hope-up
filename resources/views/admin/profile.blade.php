@extends('admin.layout.layout')
@section('content')

<div class="app-hero-header d-flex align-items-center">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <i class="ri-home-8-line lh-1 pe-3 me-3 border-end"></i>
            <a href="{{ route('/') }}">Home</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('manage-mhc') }}">Profile Settings</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">
            Profile Settings
        </li>
    </ol>
</div>


<div class="app-body">
    @include('admin/notifications')
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        <i class="ri-user-line me-2"></i>
                        Profile Information
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('change.admin.profile') }}" method="POST" enctype="multipart/form-data" id="profileForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <!-- Profile Picture Section -->
                            <div class="col-md-4 text-center mb-4">
                                <div class="position-relative d-inline-block">
                                    <img src="{{ $user->profile_photo ? asset($user->profile_photo) : asset('assets/images/default-avatar.png') }}" 
                                         class="rounded-circle border border-4 border-white shadow" 
                                         id="profilePreview" 
                                         width="150" 
                                         height="150" 
                                         alt="Profile Image"
                                         style="object-fit: cover;">
                                    
                                    <div class="position-absolute bottom-0 end-0 bg-primary rounded-circle p-2 cursor-pointer" 
                                         onclick="document.getElementById('profile').click()">
                                        <i class="ri-camera-line text-white"></i>
                                    </div>
                                </div>
                                
                                <input type="file" 
                                       class="form-control d-none" 
                                       id="profile" 
                                       name="profile" 
                                       accept="image/*"
                                       onchange="previewImage(this)">
                                
                                <div class="mt-3">
                                    <small class="text-muted">Click the camera icon to change profile picture</small>
                                </div>
                            </div>

                            <!-- Profile Details -->
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               class="form-control @error('first_name') is-invalid @enderror" 
                                               id="first_name" 
                                               name="first_name" 
                                               value="{{ old('first_name', $user->first_name) }}" 
                                               required>
                                        @error('first_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               class="form-control @error('last_name') is-invalid @enderror" 
                                               id="last_name" 
                                               name="last_name" 
                                               value="{{ old('last_name', $user->last_name) }}" 
                                               required>
                                        @error('last_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="email" 
                                                   class="form-control @error('email') is-invalid @enderror" 
                                                   id="email" 
                                                   name="email" 
                                                   value="{{ old('email', $user->email) }}" 
                                                   required>
                                            @if($user->email_verified_at)
                                                <span class="input-group-text text-success">
                                                    <i class="ri-check-line"></i>
                                                </span>
                                            @else
                                                <span class="input-group-text text-warning">
                                                    <i class="ri-alert-line"></i>
                                                </span>
                                            @endif
                                        </div>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">
                                            @if($user->email_verified_at)
                                                Email verified on {{ $user->email_verified_at->format('M j, Y') }}
                                            @else
                                                Email verification required
                                            @endif
                                        </small>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ri-save-line me-2"></i>
                                            Update Profile
                                        </button>
                                        <button type="reset" class="btn btn-secondary ms-2">
                                            <i class="ri-refresh-line me-2"></i>
                                            Reset
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Security Settings Card -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        <i class="ri-lock-line me-2"></i>
                        Security Settings
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('change.admin.credentials') }}" method="POST" id="passwordForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="old_password" class="form-label">Current Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" 
                                       class="form-control @error('old_password') is-invalid @enderror" 
                                       id="old_password" 
                                       name="old_password" 
                                       required>
                                <button class="btn btn-outline-secondary" 
                                        type="button" 
                                        onclick="togglePassword('old_password')">
                                    <i class="ri-eye-line" id="old_password_icon"></i>
                                </button>
                            </div>
                            @error('old_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="new_password" class="form-label">New Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" 
                                       class="form-control @error('new_password') is-invalid @enderror" 
                                       id="new_password" 
                                       name="new_password" 
                                       required>
                                <button class="btn btn-outline-secondary" 
                                        type="button" 
                                        onclick="togglePassword('new_password')">
                                    <i class="ri-eye-line" id="new_password_icon"></i>
                                </button>
                            </div>
                            @error('new_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="confirm_new_password" class="form-label">Confirm New Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" 
                                       class="form-control @error('confirm_new_password') is-invalid @enderror" 
                                       id="confirm_new_password" 
                                       name="confirm_new_password" 
                                       required>
                                <button class="btn btn-outline-secondary" 
                                        type="button" 
                                        onclick="togglePassword('confirm_new_password')">
                                    <i class="ri-eye-line" id="confirm_new_password_icon"></i>
                                </button>
                            </div>
                            @error('confirm_new_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="password-strength" id="passwordStrength">
                                <div class="progress" style="height: 5px;">
                                    <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                                </div>
                                <small class="text-muted mt-1 d-block">Password strength indicator</small>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-warning w-100">
                            <i class="ri-key-line me-2"></i>
                            Update Password
                        </button>
                    </form>

                    <hr class="my-4">

                    <!-- Account Security Info -->
                    <div class="security-info">
                        <h6 class="mb-3">
                            <i class="ri-shield-check-line me-2"></i>
                            Account Security
                        </h6>
                        
                        <div class="d-flex align-items-center mb-2">
                            <i class="ri-check-circle-line text-success me-2"></i>
                            <span>Last login: {{ $user->last_login_at ? $user->last_login_at->format('M j, Y g:i A') : 'Never' }}</span>
                        </div>
                        
                        <div class="d-flex align-items-center mb-2">
                            <i class="ri-check-circle-line text-success me-2"></i>
                            <span>Account created: {{ $user->created_at->format('M j, Y') }}</span>
                        </div>
                        
                        <div class="d-flex align-items-center">
                            <i class="ri-check-circle-line text-success me-2"></i>
                            <span>Role: {{ ucfirst($user->role) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Profile image preview
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('profilePreview').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Toggle password visibility
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(inputId + '_icon');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'ri-eye-off-line';
    } else {
        input.type = 'password';
        icon.className = 'ri-eye-line';
    }
}

// Password strength indicator
document.getElementById('new_password').addEventListener('input', function() {
    const password = this.value;
    const strengthBar = document.querySelector('#passwordStrength .progress-bar');
    const strengthText = document.querySelector('#passwordStrength small');
    
    let strength = 0;
    let feedback = '';
    
    if (password.length >= 8) strength += 25;
    if (/[a-z]/.test(password)) strength += 25;
    if (/[A-Z]/.test(password)) strength += 25;
    if (/[0-9]/.test(password)) strength += 25;
    
    strengthBar.style.width = strength + '%';
    
    if (strength <= 25) {
        strengthBar.className = 'progress-bar bg-danger';
        feedback = 'Weak password';
    } else if (strength <= 50) {
        strengthBar.className = 'progress-bar bg-warning';
        feedback = 'Fair password';
    } else if (strength <= 75) {
        strengthBar.className = 'progress-bar bg-info';
        feedback = 'Good password';
    } else {
        strengthBar.className = 'progress-bar bg-success';
        feedback = 'Strong password';
    }
    
    strengthText.textContent = feedback;
});

// Form validation
document.getElementById('passwordForm').addEventListener('submit', function(e) {
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = document.getElementById('confirm_new_password').value;
    
    if (newPassword !== confirmPassword) {
        e.preventDefault();
        alert('New password and confirm password do not match!');
        return false;
    }
});
</script>

<style>
.cursor-pointer {
    cursor: pointer;
}

.password-strength {
    margin-top: 10px;
}

.security-info {
    background-color: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
}

.progress {
    background-color: #e9ecef;
}

.progress-bar {
    transition: width 0.3s ease;
}
</style>

@endsection
