@extends('admin/layout/layout')
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
        
        @if(isset($dashboardData['error']))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="ri-error-warning-line me-2"></i>
                {{ $dashboardData['error'] }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="row gx-3">
            <div class="col-xxl-12 col-sm-12">
            <div class="card mb-3 bg-2">
                <div class="card-body">
                <div class="py-4 px-3 text-white">
                    <h6>Good Morning,</h6>
                    <h2>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h2>
                    <h5>Today we have!</h5>
                    <div class="mt-4 d-flex gap-3">
                    <div class="d-flex align-items-center">
                        <div class="icon-box lg bg-arctic rounded-3 me-3">
                        <i class="ri-surgical-mask-line fs-4"></i>
                        </div>
                        <div class="d-flex flex-column">
                        <h2 class="m-0 lh-1">{{ $dashboardData['todayPatients'] ?? 0 }}</h2>
                        <p class="m-0">New Customers</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="icon-box lg bg-lime rounded-3 me-3">
                        <i class="ri-lungs-line fs-4"></i>
                        </div>
                        <div class="d-flex flex-column">
                        <h2 class="m-0 lh-1">{{ $dashboardData['todayAppointments'] ?? 0 }}</h2>
                        <p class="m-0">New Appointments</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="icon-box lg bg-peach rounded-3 me-3">
                        <i class="ri-walk-line fs-4"></i>
                        </div>
                        <div class="d-flex flex-column">
                        <h2 class="m-0 lh-1">{{ $dashboardData['pendingTodayAppointments'] ?? 0 }}</h2>
                        <p class="m-0">Pending Appointments</p>
                        </div>
                    </div>
                    </div>
                </div>
                </div>
            </div>
            </div>
        </div>

        <div class="row gx-3">
            <div class="col-xl-3 col-sm-6 col-12">
            <div class="card mb-3">
                <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="p-2 border border-success rounded-circle me-3">
                    <div class="icon-box md bg-success-subtle rounded-5">
                        <i class="ri-user-line fs-4 text-success"></i>
                    </div>
                    </div>
                    <div class="d-flex flex-column">
                    <h2 class="lh-1">{{ $dashboardData['totalPatients'] ?? 0 }}</h2>
                    <p class="m-0">Total Patients</p>
                    </div>
                </div>
                    <div class="d-flex align-items-end justify-content-between mt-1">
                        <a class="text-success" href="{{ route('manage.patients') }}" target="_blank">
                        <span>View All</span>
                        <i class="ri-arrow-right-line text-success ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12">
            <div class="card mb-3">
                <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="p-2 border border-primary rounded-circle me-3">
                    <div class="icon-box md bg-primary-subtle rounded-5">
                        <i class="ri-stethoscope-line fs-4 text-primary"></i>
                    </div>
                    </div>
                    <div class="d-flex flex-column">
                    <h2 class="lh-1">{{ $dashboardData['totalDoctors'] ?? 0 }}</h2>
                    <p class="m-0">Total Doctors</p>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-1">
                    <a class="text-primary" href="{{ route('manage.doctors') }}" target="_blank">
                    <span>View All</span>
                    <i class="ri-arrow-right-line ms-1"></i>
                    </a>
                </div>
                </div>
            </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12">
            <div class="card mb-3">
                <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="p-2 border border-danger rounded-circle me-3">
                    <div class="icon-box md bg-danger-subtle rounded-5">
                        <i class="ri-admin-line fs-4 text-danger"></i>
                    </div>
                    </div>
                    <div class="d-flex flex-column">
                    <h2 class="lh-1">{{ $dashboardData['totalAdmins'] ?? 0 }}</h2>
                    <p class="m-0">Total Admins</p>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-1">
                    <a class="text-danger" href="{{ route('manage.admins') }}" target="_blank">
                    <span>View All</span>
                    <i class="ri-arrow-right-line ms-1"></i>
                    </a>
                </div>
                </div>
            </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card mb-3">
                    <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="p-2 border border-info rounded-circle me-3">
                        <div class="icon-box md bg-info-subtle rounded-5">
                            <i class="ri-calendar-check-line fs-4 text-info"></i>
                        </div>
                        </div>
                        <div class="d-flex flex-column">
                        <h2 class="lh-1">{{ $dashboardData['totalAppointments'] ?? 0 }}</h2>
                        <p class="m-0">Total Appointments</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-1">
                        <a class="text-info" href="{{ route('manage-appointments') }}" target="_blank">
                        <span>View All</span>
                        <i class="ri-arrow-right-line ms-1"></i>
                        </a>
                    </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card mb-3">
                    <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="p-2 border border-warning rounded-circle me-3">
                        <div class="icon-box md bg-warning-subtle rounded-5">
                            <i class="ri-time-line fs-4 text-warning"></i>
                        </div>
                        </div>
                        <div class="d-flex flex-column">
                        <h2 class="lh-1">{{ $dashboardData['pendingAppointments'] ?? 0 }}</h2>
                        <p class="m-0">Pending Appointments</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-1">
                        <a class="text-warning" href="{{ route('manage-appointments') }}?status=pending" target="_blank">
                        <span>View All</span>
                        <i class="ri-arrow-right-line ms-1"></i>
                        </a>
                    </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card mb-3">
                    <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="p-2 border border-success rounded-circle me-3">
                        <div class="icon-box md bg-success-subtle rounded-5">
                            <i class="ri-check-double-line fs-4 text-success"></i>
                        </div>
                        </div>
                        <div class="d-flex flex-column">
                        <h2 class="lh-1">{{ $dashboardData['completedAppointments'] ?? 0 }}</h2>
                        <p class="m-0">Completed Appointments</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-1">
                        <a class="text-success" href="{{ route('manage-appointments') }}?status=completed" target="_blank">
                        <span>View All</span>
                        <i class="ri-arrow-right-line ms-1"></i>
                        </a>
                    </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card mb-3">
                    <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="p-2 border border-danger rounded-circle me-3">
                        <div class="icon-box md bg-danger-subtle rounded-5">
                            <i class="ri-close-circle-line fs-4 text-danger"></i>
                        </div>
                        </div>
                        <div class="d-flex flex-column">
                        <h2 class="lh-1">{{ $dashboardData['canceledAppointments'] ?? 0 }}</h2>
                        <p class="m-0">Canceled Appointments</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-1">
                        <a class="text-danger" href="{{ route('manage-appointments') }}?status=canceled" target="_blank">
                        <span>View All</span>
                        <i class="ri-arrow-right-line ms-1"></i>
                        </a>
                    </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card mb-3">
                    <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="p-2 border border-success rounded-circle me-3">
                        <div class="icon-box md bg-success-subtle rounded-5">
                            <i class="ri-money-dollar-circle-line fs-4 text-success"></i>
                        </div>
                        </div>
                        <div class="d-flex flex-column">
                        <h2 class="lh-1">GHS {{ number_format($dashboardData['totalEarnings'] ?? 0, 2) }}</h2>
                        <p class="m-0">Total Earnings</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-1">
                        <a class="text-success" href="{{ route('manage-transactions') }}" target="_blank">
                        <span>View All</span>
                        <i class="ri-arrow-right-line ms-1"></i>
                        </a>
                    </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row gx-3">
            <div class="col-xxl-12 col-sm-12">
                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title">Appointments</h5>
                        <div class="appointments-filter-group d-flex flex-row gap-1 day-sorting">
                            <button class="btn btn-sm btn-outline-primary appointments-filter-btn" data-range="today">Today</button>
                            <button class="btn btn-sm btn-outline-primary appointments-filter-btn" data-range="7d">7d</button>
                            <button class="btn btn-sm btn-outline-primary btn-primary appointments-filter-btn" data-range="1m">1m</button>
                            <button class="btn btn-sm btn-outline-primary appointments-filter-btn" data-range="3m">3m</button>
                            <button class="btn btn-sm btn-outline-primary appointments-filter-btn" data-range="6m">6m</button>
                            <button class="btn btn-sm btn-outline-primary appointments-filter-btn" data-range="1y">1y</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <div id="appointments"></div>
                            <div class="chart-spinner">
                                <div class="spinner"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-6 col-sm-12">
                <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title">Customers Report</h5>
                        <div class="customers-filter-group d-flex flex-row gap-1 day-sorting">
                            <button class="btn btn-sm btn-outline-primary customers-filter-btn" data-range="today">Today</button>
                            <button class="btn btn-sm btn-outline-primary customers-filter-btn" data-range="7d">7d</button>
                            <button class="btn btn-sm btn-outline-primary btn-primary customers-filter-btn" data-range="1m">1m</button>
                            <button class="btn btn-sm btn-outline-primary customers-filter-btn" data-range="3m">3m</button>
                            <button class="btn btn-sm btn-outline-primary customers-filter-btn" data-range="6m">6m</button>
                            <button class="btn btn-sm btn-outline-primary customers-filter-btn" data-range="1y">1y</button>
                        </div>
                    </div>
                <div class="card-body">
                    <div class="chart-container">
                        <div id="patients"></div>
                        <div class="chart-spinner">
                            <div class="spinner"></div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
            <div class="col-xxl-6 col-sm-12">
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title">Treatment Type</h5>
                    <div class="treatment-filter-group d-flex flex-row gap-1 day-sorting">
                        <button class="btn btn-sm btn-outline-primary treatment-filter-btn" data-range="today">Today</button>
                        <button class="btn btn-sm btn-outline-primary treatment-filter-btn" data-range="7d">7d</button>
                        <button class="btn btn-sm btn-outline-primary btn-primary treatment-filter-btn" data-range="1m">1m</button>
                        <button class="btn btn-sm btn-outline-primary treatment-filter-btn" data-range="3m">3m</button>
                        <button class="btn btn-sm btn-outline-primary treatment-filter-btn" data-range="6m">6m</button>
                        <button class="btn btn-sm btn-outline-primary treatment-filter-btn" data-range="1y">1y</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <div id="treatment-types"></div>
                        <div class="chart-spinner">
                            <div class="spinner"></div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
            <div class="col-xl-12 col-sm-12">
                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title">Customers by Gender and Is-Militant</h5>
                        <div class="gender-age-filter-group d-flex flex-row gap-1 day-sorting">
                            <button class="btn btn-sm btn-outline-primary gender-age-filter-btn" data-range="today">Today</button>
                            <button class="btn btn-sm btn-outline-primary gender-age-filter-btn" data-range="7d">7d</button>
                            <button class="btn btn-sm btn-outline-primary btn-primary gender-age-filter-btn" data-range="1m">1m</button>
                            <button class="btn btn-sm btn-outline-primary gender-age-filter-btn" data-range="3m">3m</button>
                            <button class="btn btn-sm btn-outline-primary gender-age-filter-btn" data-range="6m">6m</button>
                            <button class="btn btn-sm btn-outline-primary gender-age-filter-btn" data-range="1y">1y</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-3 auto-align-graph justify-content-center">
                            <div class="chart-container">
                                <div id="isMilitantPatients"></div>
                                <div class="chart-spinner">
                                    <div class="spinner"></div>
                                </div>
                            </div>
                            <div class="chart-container">
                                <div id="genderAge"></div>
                                <div class="chart-spinner">
                                    <div class="spinner"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{asset('assets/vendor/apex/apexcharts.min.js')}}"></script>
    <script src="{{asset('assets/vendor/apex/custom/home/appintments.js')}}"></script>
    <script src="{{asset('assets/vendor/apex/custom/home/patients.js')}}"></script>
    <script src="{{asset('assets/vendor/apex/custom/home/treatment.js')}}"></script>
    <script src="{{asset('assets/vendor/apex/custom/home/gender-age.js')}}"></script>
@endsection