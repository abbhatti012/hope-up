<div class="row gx-3">
    <div class="col-xxl-3 col-lg-4 col-sm-6">
        <div class="mb-3">
            <label class="form-label">Speciality <span class="text-danger">*</span></label>
            <select class="form-control" name="speciality_id">
                <option value="">Select Speciality</option>
                @foreach($specialities as $speciality)
                    <option value="{{ $speciality->id }}" {{ old('speciality_id', $user->detail->speciality_id ?? '') == $speciality->id ? 'selected' : '' }}>
                        {{ $speciality->title }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-xxl-3 col-lg-4 col-sm-6">
        <div class="mb-3">
            <label class="form-label">Medical License Number</label>
            <input type="text" class="form-control" name="medical_license" value="{{ old('medical_license', $user->detail->medical_license ?? '') }}">
        </div>
    </div>

    <div class="col-xxl-3 col-lg-4 col-sm-6">
        <div class="mb-3">
            <label class="form-label">Years of Experience</label>
            <input type="number" class="form-control" name="experience" value="{{ old('experience', $user->detail->experience ?? '') }}">
        </div>
    </div>

    <div class="col-xxl-3 col-lg-4 col-sm-6">
        <div class="mb-3">
            <label class="form-label">Year of Graduation</label>
            <input type="number" class="form-control" name="graduation_year" value="{{ old('graduation_year', $user->detail->graduation_year ?? '') }}">
        </div>
    </div>

    <div class="col-xxl-3 col-lg-4 col-sm-6">
        <div class="mb-3">
            <label class="form-label">Upload Degree/Certificate</label>
            <input type="file" class="form-control" name="degree_certificate">
        </div>
        @if(isset($user->detail) && $user->detail->degree_certificate)
            <a href="{{ asset($user->detail->degree_certificate) }}" target="_blank">View Certificate</a>
        @endif
    </div>
</div>
