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
            <li class="breadcrumb-item active">Edit Patient</li>
        </ol>
    </div>

    <div class="app-body">
        @include('admin/notifications')
        
        <div class="row gx-3">
            <div class="col-xl-12">
                <div class="card mb-3">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="card-title mb-0">
                            <i class="ri-user-edit-line me-2"></i>Edit Patient: {{ $patient->name }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('patients.update', $patient->id) }}" method="POST" enctype="multipart/form-data" id="patientForm">
                            @csrf
                            @method('PUT')
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="text-center mb-4">
                                        <div class="position-relative d-inline-block mb-3">
                                            <img src="{{ $patient->profile_photo ? asset('storage/'.$patient->profile_photo) : asset('default.png') }}" 
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
                                                   class="d-none" 
                                                   accept="image/*">
                                        </div>
                                        <h5 class="mb-1">Profile Photo</h5>
                                        <p class="text-muted mb-0">JPG, PNG, Max 2MB</p>
                                        @error('profile_photo')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                            <input type="text" 
                                                   class="form-control @error('name') is-invalid @enderror" 
                                                   id="name" 
                                                   name="name" 
                                                   value="{{ old('name', $patient->name) }}" 
                                                   required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                            <input type="email" 
                                                   class="form-control @error('email') is-invalid @enderror" 
                                                   id="email" 
                                                   name="email" 
                                                   value="{{ old('email', $patient->email) }}" 
                                                   required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="phone" class="form-label">Phone Number</label>
                                            <input type="tel" 
                                                   class="form-control @error('phone') is-invalid @enderror" 
                                                   id="phone" 
                                                   name="phone" 
                                                   value="{{ old('phone', $patient->phone) }}">
                                            @error('phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="dob" class="form-label">Date of Birth</label>
                                            <input type="date" 
                                                   class="form-control @error('dob') is-invalid @enderror" 
                                                   id="dob" 
                                                   name="dob" 
                                                   value="{{ old('dob', $patient->dob ? (is_string($patient->dob) ? \Carbon\Carbon::parse($patient->dob)->format('Y-m-d') : $patient->dob->format('Y-m-d')) : '') }}">
                                            @error('dob')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Gender</label>
                                            <div class="d-flex gap-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="gender" id="male" value="male" {{ old('gender', $patient->gender) == 'male' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="male">
                                                        Male
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="gender" id="female" value="female" {{ old('gender', $patient->gender) == 'female' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="female">
                                                        Female
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="gender" id="other" value="other" {{ old('gender', $patient->gender) == 'other' ? 'checked' : '' }}>
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
                                            <label for="address" class="form-label">Address</label>
                                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                                      id="address" 
                                                      name="address" 
                                                      rows="2">{{ old('address', $patient->address) }}</textarea>
                                            @error('address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <h5 class="mb-3">Account Information</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="password" class="form-label">New Password</label>
                                    <div class="input-group">
                                        <input type="password" 
                                               class="form-control @error('password') is-invalid @enderror" 
                                               id="password" 
                                               name="password">
                                        <button class="btn btn-outline-secondary toggle-password" type="button">
                                            <i class="ri-eye-line"></i>
                                        </button>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <small class="form-text text-muted">Leave blank to keep current password</small>
                                </div>
                                <div class="col-md-6">
                                    <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                    <div class="input-group">
                                        <input type="password" 
                                               class="form-control" 
                                               id="password_confirmation" 
                                               name="password_confirmation">
                                        <button class="btn btn-outline-secondary toggle-password" type="button">
                                            <i class="ri-eye-line"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                                        <option value="active" {{ old('status', $patient->status) == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status', $patient->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-5">
                                <a href="{{ route('manage.patients') }}" class="btn btn-light">
                                    <i class="ri-arrow-left-line me-1"></i> Back to Patients
                                </a>
                                <div>
                                    <button type="button" class="btn btn-outline-danger me-2" data-bs-toggle="modal" data-bs-target="#deletePatientModal">
                                        <i class="ri-delete-bin-line me-1"></i> Delete
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ri-save-line me-1"></i> Update Patient
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deletePatientModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this patient? This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('patients.destroy', $patient->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="ri-delete-bin-line me-1"></i> Delete Patient
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Profile image preview
        $('#profilePhotoInput').on('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#profileImage').attr('src', e.target.result);
                }
                reader.readAsDataURL(file);
            }
        });

        // Toggle password visibility
        $('.toggle-password').on('click', function() {
            const input = $(this).siblings('input');
            const icon = $(this).find('i');
            
            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
                icon.removeClass('ri-eye-line').addClass('ri-eye-off-line');
            } else {
                input.attr('type', 'password');
                icon.removeClass('ri-eye-off-line').addClass('ri-eye-line');
            }
        });

        // Form validation
        $('#patientForm').on('submit', function() {
            const password = $('#password').val();
            const confirmPassword = $('#password_confirmation').val();
            
            if (password && password !== confirmPassword) {
                alert('Passwords do not match!');
                return false;
            }
            
            return true;
        });

        // Click on profile image to trigger file input
        $('#profileImage').on('click', function() {
            $('#profilePhotoInput').click();
        });

        // Initialize date picker
        if ($('#date_of_birth').length) {
            $('#date_of_birth').flatpickr({
                dateFormat: 'Y-m-d',
                maxDate: 'today'
            });
        }
    });
</script>
@endsection
