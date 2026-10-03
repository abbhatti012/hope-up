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
        <div class="row gx-3">
            <div class="col-xxl-3 col-sm-4">
                <div class="card mb-3">
                    <div class="card-body mh-230">
                        <div class="d-flex flex-column align-items-center">
                        <div class="icon-box xl bg-primary-subtle rounded-5 mb-2 no-shadow">
                            <i class="ri-empathize-line fs-1 text-primary"></i>
                        </div>
                        <h1 class="text-primary">3809</h1>
                        <h6>Total Patients</h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-sm-4">
                <div class="card mb-3">
                    <div class="card-body mh-230">
                        <div class="d-flex flex-column align-items-center">
                        <div class="icon-box xl bg-danger-subtle rounded-5 mb-2 no-shadow">
                            <i class="ri-calendar-line fs-1 text-danger"></i>
                        </div>
                        <h1 class="text-danger">906</h1>
                        <h6>Total Appointments</h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-sm-4">
                <div class="card mb-3">
                    <div class="card-body mh-230">
                        <div class="d-flex flex-column align-items-center">
                        <div class="icon-box xl bg-warning-subtle rounded-5 mb-2 no-shadow">
                            <span class="text-warning fs-1">--</span>
                        </div>
                        <h1 class="text-warning">986</h1>
                        <h6>Total Pending Appointments</h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-sm-4">
                <div class="card mb-3">
                    <div class="card-body mh-230">
                        <div class="d-flex flex-column align-items-center">
                        <div class="icon-box xl bg-warning-subtle rounded-5 mb-2 no-shadow">
                            <i class="ri-money-dollar-circle-line fs-1 text-warning"></i>
                        </div>
                        <h1 class="text-warning">$986K</h1>
                        <h6>Total Investments</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row gx-3">
            <div class="col-xxl-6 col-sm-12">
                <div class="card mb-3">
                    <div class="card-header">
                    <h5 class="card-title">Patients</h5>
                    </div>
                    <div class="card-body">

                    <div class="card-info bg-light lh-1">
                        20% higher than last year.
                    </div>
                    <div id="patients"></div>

                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-sm-12">
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title">Patient Expenses</h5>
                    </div>
                    <div class="card-body">
                        <div id="medicalExpenses"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row gx-3">
            <div class="col-xl-6 col-sm-12">
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title">Patients by Gender</h5>
                    </div>
                    <div class="card-body">
                        <div class="auto-align-graph">
                            <div id="genderAge"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-sm-12">
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title">Ex-Militant Patients</h5>
                    </div>
                    <div class="card-body">
                        <div class="auto-align-graph">
                            <div id="isMilitantPatients"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script src="{{asset('assets/vendor/apex/apexcharts.min.js')}}"></script>
    <script src="{{asset('assets/vendor/apex/custom/patients/sparklines.js')}}"></script>
    <script src="{{asset('assets/vendor/apex/custom/patients/medicalExpenses.js')}}"></script>
    <script src="{{asset('assets/vendor/apex/custom/patients/healthActivity.js')}}"></script>

    <script src="{{asset('assets/vendor/apex/custom/home/gender-age.js')}}"></script>
    <script src="{{asset('assets/vendor/apex/custom/home/isMilitantPatients.js')}}"></script>
    <script src="{{asset('assets/vendor/apex/custom/doc-dashboard/patients.js')}}"></script>
@endsection