@extends('admin.layout.layout')

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

        <div class="ms-auto d-lg-flex d-none flex-row">
            <div class="d-flex flex-row gap-1 day-sorting">
                <button class="btn btn-sm btn-primary">Today</button>
                <button class="btn btn-sm">7d</button>
                <button class="btn btn-sm">2w</button>
                <button class="btn btn-sm">1m</button>
                <button class="btn btn-sm">3m</button>
                <button class="btn btn-sm">6m</button>
                <button class="btn btn-sm">1y</button>
            </div>
        </div>
    </div>
    <div class="app-body">
        @include('admin/notifications')
        <div class="row gx-3">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <div class="custom-tabs-container">
                            <form action="{{ isset($user) ? route('users.update', $user->id) : route('users.store') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                @if(isset($user))
                                    @method('PUT')
                                @endif

                                <!-- Nav tabs -->
                                <ul class="nav nav-tabs" id="customTab2" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link active" id="tab-oneA" data-bs-toggle="tab" href="#oneA" role="tab"
                                        aria-controls="oneA" aria-selected="true"><i class="ri-briefcase-4-line"></i> Personal
                                            Details</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="tab-twoA" data-bs-toggle="tab" href="#twoA" role="tab"
                                        aria-controls="twoA" aria-selected="false"><i class="ri-account-pin-circle-line"></i>
                                            Profile and Bio</a>
                                    </li>
                                    <!-- <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="tab-threeA" data-bs-toggle="tab" href="#threeA" role="tab"
                                        aria-controls="threeA" aria-selected="false"><i class="ri-calendar-check-line"></i>
                                            Availability</a>
                                    </li> -->
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="tab-fourA" data-bs-toggle="tab" href="#fourA" role="tab"
                                        aria-controls="fourA" aria-selected="false"><i class="ri-lock-password-line"></i> Account
                                            Details</a>
                                    </li>
                                </ul>

                                <!-- Tab panes -->
                                <div class="tab-content h-350">
                                    <!-- Personal Details -->
                                    <div class="tab-pane fade show active" id="oneA" role="tabpanel">
                                        @include('admin.users.doctor.form.personal-details')
                                    </div>

                                    <!-- Profile and Bio -->
                                    <div class="tab-pane fade" id="twoA" role="tabpanel">
                                        @include('admin.users.doctor.form.profile-bio')
                                    </div>

                                    <!-- Availability -->
                                    <!-- <div class="tab-pane fade" id="threeA" role="tabpanel">
                                        @include('admin.users.doctor.form.availability')
                                    </div> -->

                                    <!-- Account Details -->
                                    <div class="tab-pane fade" id="fourA" role="tabpanel">
                                        @include('admin.users.doctor.form.account-details')
                                    </div>
                                </div>

                                <input type="hidden" name="role" value="specialist">
                                <div class="d-flex gap-2 justify-content-end mt-4">
                                    <a href="{{ route('manage.doctors') }}" id="cancelBtn" class="btn btn-outline-secondary">
                                        Cancel
                                    </a>
                                    <button type="button" id="backBtn" class="btn btn-outline-secondary" style="display: none;">
                                        Back
                                    </button>
                                    <button type="button" id="nextBtn" class="btn btn-primary">
                                        Next
                                    </button>
                                    <button type="submit" id="submitBtn" class="btn btn-primary" style="display: none;">
                                        {{ isset($user) ? 'Update' : 'Create' }} Doctor Profile
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
    $(document).ready(function() {
        // Initialize tab navigation
        var currentTab = 0;
        var tabs = $('.nav-tabs .nav-link');
        var tabPanes = $('.tab-pane');
        
        function toggleFields() {
            var role = $('#role').val();

            $('.doctor-field').hide();
            if (role === 'specialist') {
                $('.doctor-field').show();
            }

            if (role !== 'user') {
                $('.patient-field').hide();
            } else {
                $('.patient-field').show();
            }
        }

        function updateButtonText() {
            // Show/hide cancel/back buttons
            if (currentTab === 0) {
                $('#cancelBtn').show();
                $('#backBtn').hide();
            } else {
                $('#cancelBtn').hide();
                $('#backBtn').show();
            }

            // Show/hide next/submit buttons
            if (currentTab === tabs.length - 1) {
                $('#nextBtn').hide();
                $('#submitBtn').show();
            } else {
                $('#nextBtn').show();
                $('#submitBtn').hide();
            }
        }

        // Handle next button click
        $('#nextBtn').click(function() {
            // Move to next tab
            if (currentTab < tabs.length - 1) {
                currentTab++;
                $(tabs[currentTab]).tab('show');
                updateButtonText();
            }
        });

        // Handle back button click
        $('#backBtn').click(function() {
            // Move to previous tab
            if (currentTab > 0) {
                currentTab--;
                $(tabs[currentTab]).tab('show');
                updateButtonText();
            }
        });

        // Update current tab when manually clicking tabs
        $('.nav-tabs a').on('shown.bs.tab', function(e) {
            currentTab = $(e.target).parent().index();
            updateButtonText();
        });

        // Initialize
        toggleFields();
        updateButtonText();
        
        // Role change handler
        $('#role').change(function() {
            toggleFields();
        });
    });
</script>
@endsection
