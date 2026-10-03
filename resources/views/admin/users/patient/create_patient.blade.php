@extends('admin.layout.layout')

@section('content')
    <div class="app-hero-header d-flex align-items-center">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <i class="ri-home-8-line lh-1 pe-3 me-3 border-end"></i>
                <a href="{{ route('home') }}">Home</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('patients.dashboard') }}">Patients</a>
            </li>
            <li class="breadcrumb-item active">Add New Patient</li>
        </ol>
    </div>

    <div class="app-body">
        @include('admin/notifications')
        
        <div class="row gx-3">
            <div class="col-xl-12">
                <div class="card mb-3">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="card-title mb-0">
                            <i class="ri-user-{{ isset($user) ? 'edit' : 'add' }}-line me-2"></i>{{ isset($user) ? 'Edit' : 'Add New' }} Patient
                        </h5>
                    </div>
                    <div class="card-body">
                        <form id="patientForm" action="{{ isset($user) ? route('users.update', $user->id) : route('users.store') }}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                            @csrf
                            @if(isset($user))
                                @method('PUT')
                            @endif
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="text-center mb-4">
                                        <div class="position-relative d-inline-block mb-3">
                                            <img src="{{ isset($user) && $user->profile_photo ? asset($user->profile_photo) : asset('default.png') }}" 
                                                 class="rounded-circle border border-4 border-white shadow" 
                                                 id="profileImage" 
                                                 width="150" 
                                                 height="150" 
                                                 alt="Profile Image"
                                                 style="object-fit: cover;">
                                            <div class="position-absolute bottom-0 end-0 bg-primary rounded-circle p-2">
                                                <i class="ri-camera-line text-white"></i>
                                            </div>
                                            <input type="file" 
                                                   name="profile_photo" 
                                                   id="profilePhotoInput" 
                                                   class="d-none {{ $errors->has('profile_photo') ? 'is-invalid' : '' }}" 
                                                   accept="image/jpeg,image/png,image/jpg"
                                                   {{ !isset($user) ? 'required' : '' }}>
                                        </div>
                                        <h5 class="mb-1">Profile Photo <span class="text-danger">{{ !isset($user) ? '*' : '' }}</span></h5>
                                        <p class="text-muted mb-0">JPG, PNG, Max 2MB</p>
                                        <div class="invalid-feedback d-block" id="profilePhotoError">
                                            @error('profile_photo')
                                                {{ $message }}
                                            @enderror
                                        </div>
                                        <div class="valid-feedback d-none" id="profilePhotoSuccess">
                                            Looks good!
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                            <input type="text" 
                                                   class="form-control @error('first_name') is-invalid @enderror" 
                                                   id="first_name" 
                                                   name="first_name" 
                                                   value="{{ old('first_name', $user->first_name ?? '') }}" 
                                                   required>
                                            @error('first_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                                            <input type="text" 
                                                   class="form-control @error('last_name') is-invalid @enderror" 
                                                   id="last_name" 
                                                   name="last_name" 
                                                   value="{{ old('last_name', $user->last_name ?? '') }}" 
                                                   required>
                                            @error('last_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                            <input type="email" 
                                                   class="form-control @error('email') is-invalid @enderror" 
                                                   id="email" 
                                                   name="email" 
                                                   value="{{ old('email', $user->email ?? '') }}" 
                                                   required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="phone" class="form-label">Phone Number</label>
                                            <input type="tel" 
                                                   class="form-control @error('phone_number') is-invalid @enderror" 
                                                   id="phone" 
                                                   name="phone_number" 
                                                       value="{{ old('phone_number', $user->phone_number ?? '') }}">
                                            @error('phone_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="dob" class="form-label">Date of Birth</label>
                                            <input type="date" 
                                                   class="form-control @error('dob') is-invalid @enderror" 
                                                   id="dob" 
                                                   name="dob" 
                                                   value="{{ old('dob', isset($user->patientDetail) && $user->patientDetail->dob ? \Carbon\Carbon::parse($user->patientDetail->dob)->format('Y-m-d') : '') }}">
                                            @error('dob')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Gender</label>
                                            <div class="d-flex gap-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="gender" id="male" value="male" {{ old('gender', $user->patientDetail->gender ?? '') == 'male' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="male">
                                                        Male
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="gender" id="female" value="female" {{ old('gender', $user->patientDetail->gender ?? '') == 'female' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="female">
                                                        Female
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="gender" id="kid" value="kid" {{ old('gender', $user->patientDetail->gender ?? '') == 'kid' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="kid">
                                                        Kid
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="gender" id="other" value="other" {{ old('gender', $user->patientDetail->gender ?? '') == 'other' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="other">
                                                        Other
                                                    </label>
                                                </div>
                                            </div>
                                            @error('gender')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="blood_type" class="form-label">Blood Type</label>
                                            <select class="form-select @error('blood_type') is-invalid @enderror" 
                                                    id="blood_type" 
                                                    name="blood_type">
                                                <option value="">-- Select Blood Type --</option>
                                                <option value="A+" {{ old('blood_type', $user->patientDetail->blood_type ?? '') == 'A+' ? 'selected' : '' }}>A+</option>
                                                <option value="A-" {{ old('blood_type', $user->patientDetail->blood_type ?? '') == 'A-' ? 'selected' : '' }}>A-</option>
                                                <option value="B+" {{ old('blood_type', $user->patientDetail->blood_type ?? '') == 'B+' ? 'selected' : '' }}>B+</option>
                                                <option value="B-" {{ old('blood_type', $user->patientDetail->blood_type ?? '') == 'B-' ? 'selected' : '' }}>B-</option>
                                                <option value="AB+" {{ old('blood_type', $user->patientDetail->blood_type ?? '') == 'AB+' ? 'selected' : '' }}>AB+</option>
                                                <option value="AB-" {{ old('blood_type', $user->patientDetail->blood_type ?? '') == 'AB-' ? 'selected' : '' }}>AB-</option>
                                                <option value="O+" {{ old('blood_type', $user->patientDetail->blood_type ?? '') == 'O+' ? 'selected' : '' }}>O+</option>
                                                <option value="O-" {{ old('blood_type', $user->patientDetail->blood_type ?? '') == 'O-' ? 'selected' : '' }}>O-</option>
                                            </select>
                                            <div class="form-text">Optional</div>
                                            @error('blood_type')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-12 mt-3">
                                            <div class="form-check">
                                                <input class="form-check-input" 
                                                       type="checkbox" 
                                                       id="is_ex_military" 
                                                       name="is_ex_military" 
                                                       value="1"
                                                       {{ old('is_ex_military', $user->patientDetail->is_ex_military ?? 0) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_ex_military">
                                                    Is Ex-Military?
                                                </label>
                                            </div>
                                            @error('is_ex_military')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-12">
                                            <label for="address" class="form-label">Address</label>
                                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                                      id="address" 
                                                      name="address" 
                                                      rows="2"
                                                      placeholder="Enter your full address">{{ old('address', $user->patientDetail->address ?? '') }}</textarea>
                                            @error('address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-12">
                                            <label for="medical_concern" class="form-label">What is your medical concern?</label>
                                            <textarea class="form-control @error('medical_concern') is-invalid @enderror" 
                                                      id="medical_concern" 
                                                      name="medical_concern" 
                                                      rows="3"
                                                      placeholder="Please describe your medical concern">{{ old('medical_concern', $user->patientDetail->medical_concern ?? '') }}</textarea>
                                            @error('medical_concern')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">
                            @if(!isset($user))
                            <h5 class="mb-3">Account Information</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="password" 
                                               class="form-control @error('password') is-invalid @enderror" 
                                               id="password" 
                                               name="password" 
                                               minlength="8"
                                               pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$"
                                               {{ !isset($user) ? 'required' : '' }}
                                               oninput="checkPasswordStrength(this.value)"
                                               placeholder="{{ isset($user) ? 'Leave blank to keep current password' : '' }}">
                                        <div class="invalid-feedback">
                                            {{ isset($user) ? 'If changing password, it must be at least 8 characters long and include uppercase, lowercase, number, and special character.' : 'Password must be at least 8 characters long and include uppercase, lowercase, number, and special character.' }}
                                        </div>
                                        <button class="btn btn-outline-secondary toggle-password" type="button">
                                            <i class="ri-eye-line"></i>
                                        </button>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <small class="form-text text-muted">Minimum 8 characters</small>
                                </div>
                                <div class="col-md-6">
                                    <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="password" 
                                               class="form-control" 
                                               id="password_confirmation" 
                                               name="password_confirmation" 
                                               required>
                                        <button class="btn btn-outline-secondary toggle-password" type="button">
                                            <i class="ri-eye-line"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endif
                            <input type="hidden" name="role" value="user">

                            <div class="d-flex justify-content-between mt-5">
                                <a href="{{ route('manage.patients') }}" class="btn btn-light">
                                    <i class="ri-arrow-left-line me-1"></i> Back to Patients
                                </a>
                                <div>
                                    <button type="reset" class="btn btn-outline-secondary me-2">
                                        <i class="ri-refresh-line me-1"></i> Reset
                                    </button>
                                    <button type="submit" class="btn btn-primary" id="submitBtn">
                                        <i class="ri-save-line me-1"></i> Save Patient
                                    </button>
                                </div>
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
    document.addEventListener('DOMContentLoaded', function() {
        // Profile image preview and validation
        const profileImage = document.getElementById('profileImage');
        const profilePhotoInput = document.getElementById('profilePhotoInput');
        const profilePhotoError = document.getElementById('profilePhotoError');
        const profilePhotoSuccess = document.getElementById('profilePhotoSuccess');
        
        if (profilePhotoInput) {
            // Click on profile image to open file dialog
            profileImage?.addEventListener('click', function() {
                profilePhotoInput.click();
            });
            
            // Handle file selection
            profilePhotoInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                
                // Reset validation states
                this.classList.remove('is-invalid');
                profilePhotoError.textContent = '';
                
                if (!file) {
                    if (this.required) {
                        this.classList.add('is-invalid');
                        profilePhotoError.textContent = 'Profile photo is required';
                    }
                    return;
                }
                
                // Validate file size (2MB max)
                const maxSize = 2 * 1024 * 1024; // 2MB in bytes
                if (file.size > maxSize) {
                    this.classList.add('is-invalid');
                    profilePhotoError.textContent = 'File size exceeds 2MB limit. Please choose a smaller file.';
                    this.value = ''; // Clear the file input
                    return;
                }
                
                // Validate file type
                const validTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                if (!validTypes.includes(file.type)) {
                    this.classList.add('is-invalid');
                    profilePhotoError.textContent = 'Only JPG and PNG images are allowed for profile photos.';
                    this.value = ''; // Clear the file input
                    return;
                }
                
                // Update image preview and show success state
                const reader = new FileReader();
                reader.onload = function(e) {
                    profileImage.src = e.target.result;
                    profilePhotoError.textContent = '';
                    profilePhotoSuccess.classList.remove('d-none');
                    setTimeout(() => profilePhotoSuccess.classList.add('d-none'), 2000);
                };
                reader.readAsDataURL(file);
            });
            
            // Add required validation on form submit
            const form = profilePhotoInput.closest('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    if (profilePhotoInput.required && !profilePhotoInput.files.length) {
                        profilePhotoInput.classList.add('is-invalid');
                        profilePhotoError.textContent = 'Profile photo is required';
                        e.preventDefault();
                        e.stopPropagation();
                    }
                });
            }
        }
        
        // Toggle password visibility
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function() {
                const inputGroup = this.closest('.input-group');
                const input = inputGroup.querySelector('input[type="password"], input[type="text"]');
                const icon = this.querySelector('i');
                
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('ri-eye-line');
                    icon.classList.add('ri-eye-off-line');
                } else {
                    input.type = 'password';
                    icon.classList.remove('ri-eye-off-line');
                    icon.classList.add('ri-eye-line');
                }
            });
        });
        
        // Password strength indicator
        function checkPasswordStrength(password) {
            const strengthIndicator = document.getElementById('passwordStrength');
            if (!strengthIndicator) return;
            
            let strength = 0;
            let tips = '';
            
            // Check password length
            if (password.length < 8) {
                tips = 'Make the password longer (at least 8 characters)';
            } else {
                strength += 1;
                
                // Check for mixed case
                if (password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/)) {
                    strength += 1;
                } else {
                    tips += '\nAdd both uppercase and lowercase letters';
                }
                
                // Check for numbers
                if (password.match(/([0-9])/)) {
                    strength += 1;
                } else {
                    tips += '\nAdd numbers';
                }
                
                // Check for special characters
                if (password.match(/([!,%,&,@,#,$,^,*,?,_,~])/)) {
                    strength += 1;
                } else {
                    tips += '\nAdd special characters';
                }
            }
            
            // Update UI
            if (strength < 2) {
                strengthIndicator.innerHTML = 'Weak';
                strengthIndicator.className = 'text-danger';
            } else if (strength === 2) {
                strengthIndicator.innerHTML = 'Medium';
                strengthIndicator.className = 'text-warning';
            } else if (strength === 3) {
                strengthIndicator.innerHTML = 'Good';
                strengthIndicator.className = 'text-info';
            } else {
                strengthIndicator.innerHTML = 'Strong';
                strengthIndicator.className = 'text-success';
            }
        }
        
        // Form validation
        const form = document.getElementById('patientForm');
        if (form) {
            form.addEventListener('submit', function(event) {
                // Check form validity
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                
                // Check password match
                const password = document.getElementById('password');
                const confirmPassword = document.getElementById('password_confirmation');
                
                if (password && confirmPassword && password.value !== confirmPassword.value) {
                    confirmPassword.setCustomValidity('Passwords must match');
                    event.preventDefault();
                    event.stopPropagation();
                } else if (confirmPassword) {
                    confirmPassword.setCustomValidity('');
                }
                
                form.classList.add('was-validated');
            }, false);
        }
        
        // Real-time password matching
        const passwordInputs = document.querySelectorAll('input[type="password"]');
        passwordInputs.forEach(input => {
            input.addEventListener('input', function() {
                const password = document.getElementById('password');
                const confirmPassword = document.getElementById('password_confirmation');
                
                if (password && confirmPassword) {
                    if (password.value !== confirmPassword.value) {
                        confirmPassword.setCustomValidity('Passwords must match');
                    } else {
                        confirmPassword.setCustomValidity('');
                    }
                }
                
                // Update password strength
                if (this.id === 'password') {
                    checkPasswordStrength(this.value);
                }
            });
        });
    });
</script>
@endsection
