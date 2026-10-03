@extends('admin.layout.layout')

@section('content')
<div class="app-hero-header d-flex align-items-center">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <i class="ri-home-8-line lh-1 pe-3 me-3 border-end"></i>
            <a href="{{ route('/') }}">Home</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('manage-appointments') }}">Appointments</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">
            {{ isset($appointment) ? 'Edit' : 'Create' }} Appointment
        </li>
    </ol>
</div>

<div class="app-body">
    @include('admin/notifications')
    <div class="row gx-3">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">
                        <i class="ri-calendar-2-line me-2 text-primary"></i>
                        {{ isset($appointment) ? 'Edit' : 'Create New' }} Appointment
                    </h5>
                </div>
                <div class="card-body">
                    <form id="appointmentForm" class="needs-validation" novalidate>
                        @csrf
                        @if(isset($appointment))
                            @method('PUT')
                            <input type="hidden" id="appointmentId" value="{{ $appointment->id }}">
                        @endif

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="patient_id" class="form-label">Patient <span class="text-danger">*</span></label>
                                <select class="form-select searchable-dropdown" id="patient_id" name="patient_id" required>
                                    <option value="">Select Patient</option>
                                    @foreach($patients as $patient)
                                        <option value="{{ $patient->id }}"
                                            {{ (old('patient_id', $appointment->patient_id ?? '') == $patient->id) ? 'selected' : '' }}>
                                            {{ $patient->first_name }} {{ $patient->last_name }} ({{ $patient->email }})
                                        </option>
                                    @endforeach
                                    @if(isset($appointment) && $appointment->patient && !$patients->contains('id', $appointment->patient_id))
                                        <option value="{{ $appointment->patient->id }}" selected>
                                            {{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }} ({{ $appointment->patient->email }})
                                        </option>
                                    @endif
                                </select>
                                <div class="invalid-feedback">
                                    Please select a patient.
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="doctor_id" class="form-label">Doctor <span class="text-danger">*</span></label>
                                <select class="form-select searchable-dropdown" id="doctor_id" name="doctor_id" required>
                                    <option value="">Select Doctor</option>
                                    @foreach($doctors as $doctor)
                                        <option value="{{ $doctor->id }}"
                                            {{ (old('doctor_id', $appointment->doctor_id ?? '') == $doctor->id) ? 'selected' : '' }}>
                                            {{ $doctor->first_name }} {{ $doctor->last_name }} ({{ $doctor->speciality->title ?? 'General' }})
                                        </option>
                                    @endforeach
                                    @if(isset($appointment) && $appointment->doctor && !$doctors->contains('id', $appointment->doctor_id))
                                        <option value="{{ $appointment->doctor->id }}" selected>
                                            {{ $appointment->doctor->first_name }} {{ $appointment->doctor->last_name }} ({{ $appointment->doctor->speciality->title ?? 'General' }})
                                        </option>
                                    @endif
                                </select>
                                <div class="invalid-feedback">
                                    Please select a doctor.
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            @php
                                $dateValue = old('appointment_date', isset($appointment) && $appointment->appointment_date ? (is_object($appointment->appointment_date) ? $appointment->appointment_date->format('Y-m-d') : \Illuminate\Support\Carbon::parse($appointment->appointment_date)->format('Y-m-d')) : '');
                            @endphp
                            <div class="col-md-6">
                                <label for="appointment_date" class="form-label">Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="appointment_date" name="appointment_date" required value="{{ $dateValue }}">
                                <div class="invalid-feedback">
                                    Please select an appointment date.
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="appointment_time" class="form-label">Time <span class="text-danger">*</span></label>
                                <input type="time" class="form-control" id="appointment_time" name="appointment_time" 
                                       value="{{ $appointment->appointment_time ?? old('appointment_time') }}" required>
                                <div class="invalid-feedback">
                                    Please select an appointment time.
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="pending" {{ (isset($appointment) && $appointment->status == 'pending') ? 'selected' : '' }}>Pending</option>
                                    <option value="confirmed" {{ (isset($appointment) && $appointment->status == 'confirmed') ? 'selected' : '' }}>Confirmed</option>
                                    <option value="completed" {{ (isset($appointment) && $appointment->status == 'completed') ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ (isset($appointment) && $appointment->status == 'cancelled') ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="treatment_type" class="form-label">Treatment Type <span class="text-danger">*</span></label>
                                <select class="form-select" id="treatment_type" name="treatment_type" required>
                                    <option value="general" {{ (isset($appointment) && $appointment->treatment_type == 'general') ? 'selected' : '' }}>General</option>
                                    <option value="OPD" {{ (isset($appointment) && $appointment->treatment_type == 'OPD') ? 'selected' : '' }}>OPD</option>
                                </select>
                                <div class="invalid-feedback">
                                    Please select a treatment type.
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-12">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea class="form-control" id="notes" name="notes" rows="3">{{ $appointment->notes ?? old('notes') }}</textarea>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('manage-appointments') }}" class="btn btn-outline-secondary">
                                <i class="ri-arrow-left-line me-1"></i> Back to List
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-save-line me-1"></i>
                                {{ isset($appointment) ? 'Update' : 'Create' }} Appointment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Success</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="successMessage">Appointment has been saved successfully!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<!-- Error Modal -->
<div class="modal fade" id="errorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Error</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="errorMessage">An error occurred while saving the appointment.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('assets/js/custom.js') }}"></script>
<script>
    $(document).ready(function() {
        // Initialize date picker - set min date to today
        const today = new Date().toISOString().split('T')[0];
        $('#appointment_date').attr('min', today);

        // Format time to 30-minute intervals
        function roundToNearest30Minutes(time) {
            const [hours, minutes] = time.split(':');
            const date = new Date();
            date.setHours(parseInt(hours, 10));
            date.setMinutes(Math.round(parseInt(minutes, 10) / 30) * 30);
            return date.toTimeString().slice(0, 5);
        }

        // Initialize time picker with 30-minute intervals
        const timeInput = document.getElementById('appointment_time');
        if (timeInput && !timeInput.value) {
            const now = new Date();
            const hours = now.getHours().toString().padStart(2, '0');
            const minutes = (Math.floor(now.getMinutes() / 30) * 30).toString().padStart(2, '0');
            timeInput.value = `${hours}:${minutes}`;
        }

        // Form validation
        (function () {
            'use strict';
            const forms = document.querySelectorAll('.needs-validation');
            
            Array.prototype.slice.call(forms).forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    event.preventDefault();
                    event.stopPropagation();
                    
                    if (form.checkValidity()) {
                        submitForm();
                    }
                    
                    form.classList.add('was-validated');
                }, false);
            });
        })();

        // Handle form submission with AJAX
        function submitForm() {
            const form = document.getElementById('appointmentForm');
            const formData = new FormData(form);
            const appointmentId = document.getElementById('appointmentId')?.value;
            
            // Show loading state
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                ${appointmentId ? 'Updating...' : 'Creating...'}
            `;

            // Convert form data to JSON
            const data = {};
            formData.forEach((value, key) => {
                data[key] = value;
            });

            // Format date and time
            if (data.appointment_date && data.appointment_time) {
                data.appointment_time = roundToNearest30Minutes(data.appointment_time);
            }

            const url = appointmentId 
                ? `/api/v1/appointment/${appointmentId}`
                : '/api/v1/appointment';
            const method = appointmentId ? 'PUT' : 'POST';

            // Get CSRF token from the form's CSRF token input
            const token = document.querySelector('input[name="_token"]').value;

            // Show loading overlay
            const loadingOverlay = `
                <div id="form-loading" class="position-fixed top-0 start-0 w-100 h-100 bg-white bg-opacity-75 d-flex justify-content-center align-items-center" style="z-index: 1080;">
                    <div class="text-center">
                        <div class="spinner-border text-primary mb-3" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mb-0">${appointmentId ? 'Updating' : 'Creating'} appointment, please wait...</p>
                    </div>
                </div>`;
            
            $('body').append(loadingOverlay);

            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(data)
            })
            .then(async response => {
                const data = await response.json();
                if (!response.ok) {
                    return Promise.reject(data);
                }
                return data;
            })
            .then(data => {
                // Show success message
                Swal.fire({
                    title: 'Success!',
                    text: appointmentId 
                        ? 'Appointment updated successfully!'
                        : 'Appointment created successfully!',
                    icon: 'success',
                    confirmButtonText: 'OK',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '{{ route("manage-appointments") }}';
                    }
                });
            })
            .catch(error => {
                console.error('Error:', error);
                
                let errorMessage = 'An error occurred while saving the appointment.';
                
                if (error.errors) {
                    // Handle validation errors
                    errorMessage = Object.values(error.errors).flat().join('<br>');
                } else if (error.message) {
                    errorMessage = error.message;
                }
                
                Swal.fire({
                    title: 'Error!',
                    html: errorMessage,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            })
            .finally(() => {
                // Remove loading overlay and reset button state
                $('#form-loading').remove();
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
            });
        }

        // Time input formatting
        if (timeInput) {
            timeInput.addEventListener('change', function() {
                if (this.value) {
                    this.value = roundToNearest30Minutes(this.value);
                }
            });
        }
    });
</script>

@if(session('notification'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const notification = @json(session('notification'));
        if (notification) {
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
                icon: notification.status === 'success' ? 'success' : 'error',
                title: notification.message
            });
        }
    });
</script>
@endif
@endsection
