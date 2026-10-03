@csrf
@if(isset($user))
    @method('PUT')
@endif

<!-- Personal Details Fields -->
<div class="row gx-3">
    <div class="col-xxl-3 col-lg-4 col-sm-6">
        <div class="mb-3">
            <label class="form-label" for="profile_photo">Profile Photo <span class="text-danger">*</span></label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="ri-account-circle-line"></i>
                </span>
                <input type="file" class="form-control" id="profile_photo" name="profile_photo">
            </div>
        </div>
    </div>

    <div class="col-xxl-3 col-lg-4 col-sm-6">
        <div class="mb-3">
            <label class="form-label">First Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="first_name" value="{{ old('first_name', $user->first_name ?? '') }}">
        </div>
    </div>
    <div class="col-xxl-3 col-lg-4 col-sm-6">
        <div class="mb-3">
            <label class="form-label">Last Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="last_name" value="{{ old('last_name', $user->last_name ?? '') }}">
        </div>
    </div>
    <div class="col-xxl-3 col-lg-4 col-sm-6">
        <div class="mb-3">
            <label class="form-label">Email <span class="text-danger">*</span></label>
            <input type="email" class="form-control" name="email" value="{{ old('email', $user->email ?? '') }}">
        </div>
    </div>
    <div class="col-xxl-3 col-lg-4 col-sm-6">
        <div class="mb-3">
            <label class="form-label">Phone Number <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="phone_number" value="{{ old('phone_number', $user->detail->phone_number ?? '') }}">
        </div>
    </div>
    <div class="col-xxl-3 col-lg-4 col-sm-6">
        <div class="mb-3">
            <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
            <input type="date" class="form-control" name="dob" value="{{ old('dob', $user->detail->dob ?? '') }}">
        </div>
    </div>
    <div class="col-xxl-3 col-lg-4 col-sm-6">
        <div class="mb-3">
            <label class="form-label">Gender <span class="text-danger">*</span></label>
            <div class="btn-group w-100" role="group">
                <input type="radio" class="btn-check" name="gender" id="gender_male" value="male" {{ old('gender', $user->detail->gender ?? '') == 'male' ? 'checked' : '' }}>
                <label class="btn btn-outline-primary" for="gender_male">Male</label>

                <input type="radio" class="btn-check" name="gender" id="gender_female" value="female" {{ old('gender', $user->detail->gender ?? '') == 'female' ? 'checked' : '' }}>
                <label class="btn btn-outline-primary" for="gender_female">Female</label>

                <input type="radio" class="btn-check" name="gender" id="gender_other" value="other" {{ old('gender', $user->detail->gender ?? '') == 'other' ? 'checked' : '' }}>
                <label class="btn btn-outline-primary" for="gender_other">Other</label>
            </div>
        </div>
    </div>
    <div class="col-xxl-3 col-lg-4 col-sm-6">
        <div class="mb-3">
            <label class="form-label">Address <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="address" value="{{ old('address', $user->detail->address ?? '') }}">
        </div>
    </div>
    @if(isset($user->detail))
    <div class="col-xxl-6 col-lg-6 col-sm-6">
        <div class="form-material">
            <a href="{{ asset($user->detail->profile_photo) }}" target="_blank">
                <img src="{{ asset($user->detail->profile_photo) }}" alt="Profile Photo" class="img-fluid">
            </a>
        </div>
    </div>
    @endif
</div>
