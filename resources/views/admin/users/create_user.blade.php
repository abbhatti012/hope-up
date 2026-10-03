@extends('admin.layout.layout')

@section('content')
    <div class="app-hero-header d-flex align-items-center">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <i class="ri-home-8-line lh-1 pe-3 me-3 border-end"></i>
                <a href="{{ route('home') }}">Home</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('manage.doctors') }}">Users</a>
            </li>
            <li class="breadcrumb-item active">
                {{ isset($user) ? 'Edit' : 'Add New' }} User
            </li>
        </ol>
    </div>
    <div class="app-body">
        @include('admin/notifications')
        
        <div class="row gx-3">
            <div class="col-xl-12">
                <div class="card mb-3">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="card-title mb-0">
                            <i class="ri-user-add-line me-2"></i>{{ isset($user) ? 'Edit' : 'Add New' }} User
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ isset($user) ? route('users.update', $user->id) : route('users.store') }}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                                @csrf
                                @if(isset($user))
                                    @method('PUT')
                                @endif

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="text-center mb-4">
                                            <div class="position-relative d-inline-block mb-3">
                                                <img src="{{ isset($user) && $user->detail && $user->detail->profile_photo ? asset($user->detail->profile_photo) : asset('default.png') }}" 
                                                     class="rounded-circle border border-4 border-white shadow" 
                                                     id="profileImage" 
                                                     width="150" 
                                                     height="150" 
                                                     alt="Profile Image">
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
                                            <label for="phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
                                            <input type="tel" 
                                                   class="form-control @error('phone_number') is-invalid @enderror" 
                                                   id="phone" 
                                                   name="phone_number" 
                                                   value="{{ old('phone_number', $user->detail->phone_number ?? '') }}"
                                                   required
                                                   pattern="[0-9+\-() ]{10,15}">
                                            @error('phone_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="date_of_birth" class="form-label">Date of Birth <span class="text-danger">*</span></label>
                                            <input type="date" 
                                                   class="form-control @error('dob') is-invalid @enderror" 
                                                   id="dob" 
                                                   name="dob" 
                                                   value="{{ old('dob', $user->detail->dob ?? '') }}"
                                                   required>
                                            @error('dob')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Gender <span class="text-danger">*</span></label>
                                            <div class="d-flex gap-4">
                                                <div class="form-check">
                                                    <input class="form-check-input @error('gender') is-invalid @enderror" 
                                                           type="radio" 
                                                           name="gender" 
                                                           id="male" 
                                                           value="male" 
                                                           {{ old('gender', $user->detail->gender ?? '') == 'male' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="male">
                                                        Male
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input @error('gender') is-invalid @enderror" 
                                                           type="radio" 
                                                           name="gender" 
                                                           id="female" 
                                                           value="female" 
                                                           {{ old('gender', $user->detail->gender ?? '') == 'female' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="female">
                                                        Female
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input @error('gender') is-invalid @enderror" 
                                                           type="radio" 
                                                           name="gender" 
                                                           id="other" 
                                                           value="other" 
                                                           {{ old('gender', $user->detail->gender ?? '') == 'other' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="other">
                                                        Other
                                                    </label>
                                                </div>
                                            </div>
                                            @error('gender')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-12">
                                            <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                                      id="address" 
                                                      name="address" 
                                                      rows="2"
                                                      placeholder="Enter your full address"
                                                      required>{{ old('address', $user->detail->address ?? '') }}</textarea>
                                            @error('address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-12">
                                            <label for="about" class="form-label">About Yourself <span class="text-danger">*</span></label>
                                            <textarea class="form-control @error('about') is-invalid @enderror" 
                                                      id="about" 
                                                      name="about" 
                                                      rows="2"
                                                      placeholder="Enter about yourself"
                                                      required>{{ old('about', $user->detail->about ?? '') }}</textarea>
                                            @error('about')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        @if(isset($user->detail) && $user->detail->profile_photo)
                                        <div class="col-12">
                                            <div class="form-text">
                                                <a href="{{ asset($user->detail->profile_photo) }}" target="_blank">
                                                    Current Profile Photo
                                                </a>
                                            </div>
                                        </div>
                                        @endif
                                    </div>

                                    <hr class="my-4">

                                    <h5 class="mb-3">Medical Information</h5>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="speciality_id" class="form-label">Speciality <span class="text-danger">*</span></label>
                                            <select class="form-select @error('speciality_id') is-invalid @enderror" 
                                                    id="speciality_id" 
                                                    name="speciality_id" 
                                                    required>
                                                <option value="">Select Speciality</option>
                                                @foreach($specialities as $speciality)
                                                    <option value="{{ $speciality->id }}" {{ old('speciality_id', $user->detail->speciality_id ?? '') == $speciality->id ? 'selected' : '' }}>
                                                        {{ $speciality->title }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('speciality_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label for="medical_license" class="form-label">Medical License Number <span class="text-danger">*</span></label>
                                            <input type="text" 
                                                   class="form-control @error('medical_license') is-invalid @enderror" 
                                                   id="medical_license" 
                                                   name="medical_license" 
                                                   value="{{ old('medical_license', $user->detail->medical_license ?? '') }}" 
                                                   required>
                                            @error('medical_license')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label for="experience" class="form-label">Years of Experience <span class="text-danger">*</span></label>
                                            <input type="number" 
                                                   class="form-control @error('experience') is-invalid @enderror" 
                                                   id="experience" 
                                                   name="experience" 
                                                   value="{{ old('experience', $user->detail->experience ?? '') }}" 
                                                   min="0" 
                                                   max="100" 
                                                   required>
                                            @error('experience')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @else
                                                <div class="form-text">Must be between 0 and 100 years</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label for="graduation_year" class="form-label">Year of Graduation <span class="text-danger">*</span></label>
                                            <input type="number" 
                                                   class="form-control @error('graduation_year') is-invalid @enderror" 
                                                   id="graduation_year" 
                                                   name="graduation_year" 
                                                   value="{{ old('graduation_year', $user->detail->graduation_year ?? '') }}" 
                                                   min="1900" 
                                                   max="{{ date('Y') }}" 
                                                   required>
                                            @error('graduation_year')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-12">
                                            <label for="degree_certificate" class="form-label">Upload Degree/Certificate <span class="text-danger">*</span></label>
                                            <input type="file" 
                                                   class="form-control @error('degree_certificate') is-invalid @enderror" 
                                                   id="degree_certificate" 
                                                   name="degree_certificate" 
                                                   accept="application/pdf,image/jpeg,image/png"
                                                   {{ !isset($user) ? 'required' : '' }}>
                                            @error('degree_certificate')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @else
                                                <div class="form-text">Accepted formats: PDF, JPG, PNG (Max: 5MB)</div>
                                            @enderror
                                            @if(isset($user->detail) && $user->detail->degree_certificate)
                                                <div class="mt-2">
                                                    <a href="{{ asset($user->detail->degree_certificate) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                        <i class="ri-eye-line me-1"></i> View Current Certificate
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row gx-3">
                                        <div class="col-xxl-6 col-lg-6 col-sm-6">
                                            <div class="mb-3">
                                            <label class="form-label" for="password">Password</label>
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                <i class="ri-lock-password-line"></i>
                                                </span>
                                                <input type="password" id="password" name="password" class="form-control"
                                                placeholder="Password must be 8-20 characters long." {{ !isset($user) ? 'required' : '' }} minlength="8" maxlength="20">
                                                <div class="invalid-feedback">
                                                    Password must be 8-20 characters long.
                                                </div>
                                                <button class="btn btn-outline-secondary" type="button">
                                                <i class="ri-eye-line"></i>
                                                </button>
                                            </div>
                                            </div>
                                        </div>
                                        <div class="col-xxl-6 col-lg-6 col-sm-6">
                                            <div class="mb-3">
                                            <label class="form-label" for="password_confirmation">Confirm Password</label>
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                <i class="ri-lock-password-line"></i>
                                                </span>
                                                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm new password"
                                                class="form-control" {{ !isset($user) ? 'required' : '' }}>
                                                <div class="invalid-feedback">
                                                    Passwords must match.
                                                </div>
                                                <button class="btn btn-outline-secondary" type="button">
                                                <i class="ri-eye-off-line"></i>
                                                </button>
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <input type="hidden" name="role" value="specialist">
                                <div class="d-flex gap-2 justify-content-end mt-4">
                                    <a href="{{ route('manage.doctors') }}" class="btn btn-outline-secondary">
                                        <i class="ri-arrow-left-line me-1"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ri-save-line me-1"></i> {{ isset($user) ? 'Update' : 'Create' }} Doctor Profile
                                    </button>
                                </div>
                            </form>
                        </div>
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
        document.querySelectorAll('.input-group .btn-outline-secondary').forEach(button => {
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
        
        // File validation for degree certificate
        const degreeCertificateInput = document.getElementById('degree_certificate');
        if (degreeCertificateInput) {
            degreeCertificateInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (!file) return;
                
                // Validate file size (5MB max)
                const maxSize = 5 * 1024 * 1024; // 5MB in bytes
                if (file.size > maxSize) {
                    alert('File size exceeds 5MB limit. Please choose a smaller file.');
                    this.value = ''; // Clear the file input
                    return;
                }
                
                // Validate file type
                const validTypes = ['application/pdf', 'image/jpeg', 'image/png'];
                if (!validTypes.includes(file.type)) {
                    alert('Only PDF, JPG, and PNG files are allowed for certificates.');
                    this.value = ''; // Clear the file input
                    return;
                }
            });
        }
        
        // Form validation
        const forms = document.querySelectorAll('.needs-validation');
        
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                
                form.classList.add('was-validated');
            }, false);
        });
    });
</script>

@endsection
