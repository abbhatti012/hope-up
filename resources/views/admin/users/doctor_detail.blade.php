@extends('admin.layout.layout')

@section('content')
    <div class="app-hero-header d-flex align-items-center">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
            <i class="ri-home-8-line lh-1 pe-3 me-3 border-end"></i>
            <a href="{{ route('home') }}">Home</a>
            </li>
            <li class="breadcrumb-item text-primary" aria-current="page">
                <a href="{{ route('manage.doctors') }}">Doctors</a>
            </li>
            <li class="breadcrumb-item text-primary" aria-current="page">
                Doctor Details
            </li>
        </ol>
    </div>

    <div class="app-body">
        @include('admin/notifications')
        <div class="row gx-3">
              <div class="col-xxl-6 col-sm-12">
                <div class="card mb-3 bg-1">
                  <div class="card-body mh-230">
                    <div class="row gx-3">
                      <div class="col-sm-3">
                        <img src="{{ asset($user->profile_photo) ?? 'default.png' }}" class="img-fluid rounded-3" alt="Doctor Profile">
                      </div>
                      <div class="col-sm-9">
                        <div class="text-white mt-3">
                          <h6>Hello I am,</h6>
                          <h5 class="mb-0">Dr. {{ $user->first_name }} {{ $user->last_name }}</h5>
                          <h6>{{ $user->doctorDetail->speciality->title ?? 'General Physician' }}</h6>
                          <p class="mb-4">Experience: {{ $user->doctorDetail->experience ?? '0' }} Years</p>
                          <div class="rating-stars">
                            @php
                                $rating = round($averageRating ?? 0);
                                $ratingClass = 'readonly' . $rating;
                            @endphp
                            <div class="{{ $ratingClass }}"></div>
                          </div>
                          <div class="mt-1">{{ $reviewCount }} Reviews ({{ number_format($averageRating, 1) }} avg)</div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-xxl-3 col-sm-6">
                <div class="card mb-3">
                  <div class="card-body mh-230">
                    <div>
                      <div class="d-flex flex-column align-items-center">
                        <div class="icon-box xl bg-primary-subtle rounded-5 mb-2 no-shadow">
                          <i class="ri-empathize-line fs-1 text-primary"></i>
                        </div>
                        <h1 class="text-primary">{{ $patientCount }}</h1>
                        <h6>Patients</h6>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-xxl-3 col-sm-6">
                <div class="card mb-3">
                  <div class="card-body mh-230">
                    <div>
                      <div class="d-flex flex-column align-items-center">
                        <div class="icon-box xl bg-success-subtle rounded-5 mb-2 no-shadow">
                          <i class="ri-star-line fs-1 text-success"></i>
                        </div>
                        <h1 class="text-success">{{ $reviewCount }}</h1>
                        <h6>Reviews</h6>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row gx-3">
              <div class="col-xl-8 col-sm-12">
                <div class="row gx-3">
                  <div class="col-sm-12">
                    <div class="card mb-3">
                      <div class="card-header">
                        <h5 class="card-title">About</h5>
                      </div>
                      <div class="card-body">
                        <p>{{ $user->doctorDetail->about ?? 'No information provided.' }}</p>
                        <div class="">
                          <h5 class="mb-3">Specialities</h5>
                          <div class="d-flex flex-wrap gap-2">
                            @if($user->doctorDetail && $user->doctorDetail->speciality)
                                <span class="badge bg-primary">{{ $user->doctorDetail->speciality->title }}</span>
                            @else
                                <span class="badge bg-primary">General Physician</span>
                            @endif
                          </div>
                        </div>
                        @if($user->doctorDetail)
                        <div class="mt-3">
                          <h5 class="mb-3">Contact Information</h5>
                          <div class="row">
                            <div class="col-md-6">
                              <p><strong>Email:</strong> 
                                <a href="mailto:{{ $user->email }}" class="text-decoration-none text-primary">
                                  {{ $user->email }}
                                </a>
                              </p>
                              <p><strong>Phone:</strong> {{ $user->doctorDetail->phone_number ?? 'Not provided' }}</p>
                              <p><strong>Address:</strong> {{ $user->doctorDetail->address ?? 'Not provided' }}</p>
                            </div>
                            <div class="col-md-6">
                              <p><strong>Medical License:</strong> {{ $user->doctorDetail->medical_license ?? 'Not provided' }}</p>
                              <p><strong>Graduation Year:</strong> {{ $user->doctorDetail->graduation_year ?? 'Not provided' }}</p>
                              <p><strong>Experience:</strong> {{ $user->doctorDetail->experience ?? '0' }} Years</p>
                            </div>
                          </div>
                        </div>
                        @endif
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <div class="card mb-3">
                      <div class="card-header">
                        <h5 class="card-title">Reviews ({{ $reviewCount }})</h5>
                      </div>
                      <div class="card-body">
                        @if($reviews->count() > 0)
                            <div class="d-grid gap-5">
                                @foreach($reviews as $review)
                                    <div class="d-flex">
                                        <img src="{{ asset($review->reviewer->profile_photo) }}" class="img-4x rounded-2" alt="Reviewer Profile">
                                        <div class="ms-3">
                                            @php
                                                $ratingClass = 'readonly' . $review->rating;
                                                $badgeClass = match($review->rating_type) {
                                                    'excellent' => 'border border-success text-success',
                                                    'very_good' => 'border border-primary text-primary',
                                                    'good' => 'border border-info text-info',
                                                    'fair' => 'border border-warning text-warning',
                                                    'poor', 'bad' => 'border border-danger text-danger',
                                                    default => 'border border-secondary text-secondary'
                                                };
                                                $ratingText = ucfirst(str_replace('_', ' ', $review->rating_type));
                                            @endphp
                                            <span class="badge {{ $badgeClass }} mb-3">{{ $ratingText }}</span>
                                            <h6>{{ $review->reviewer->first_name }} {{ $review->reviewer->last_name }}</h6>
                                            <p class="mb-2">{{ $review->comments ?? 'No comment provided.' }}</p>
                                            <p>
                                                @if($review->rating >= 4)
                                                    <i class="ri-thumb-up-line"></i> I recommend the doctor.
                                                @else
                                                    <i class="ri-thumb-down-line"></i> I do not recommend the doctor.
                                                @endif
                                            </p>
                                            <div class="rating-stars">
                                                <div class="{{ $ratingClass }}"></div>
                                            </div>
                                            <small class="text-muted">{{ $review->created_at->format('M d, Y') }}</small>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="ri-message-3-line fs-1 text-muted"></i>
                                <p class="text-muted mt-2">No reviews yet for this doctor.</p>
                            </div>
                        @endif
                      </div>
                    </div>
                  </div>
                  
                  <!-- Appointments and Transactions Tabs -->
                  <div class="col-sm-12">
                    <div class="card">
                      <div class="card-header">
                        <ul class="nav nav-tabs card-header-tabs" id="doctorTabs" role="tablist">
                          <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="appointments-tab" data-bs-toggle="tab" data-bs-target="#appointments" type="button" role="tab" aria-controls="appointments" aria-selected="true">
                              <i class="ri-calendar-line me-1"></i>Appointments ({{ $appointments->count() }})
                            </button>
                          </li>
                          <li class="nav-item" role="presentation">
                            <button class="nav-link" id="transactions-tab" data-bs-toggle="tab" data-bs-target="#transactions" type="button" role="tab" aria-controls="transactions" aria-selected="false">
                              <i class="ri-exchange-dollar-line me-1"></i>Transactions ({{ $transactions->count() }})
                            </button>
                          </li>
                        </ul>
                      </div>
                      <div class="card-body">
                        <div class="tab-content" id="doctorTabsContent">
                          <!-- Appointments Tab -->
                          <div class="tab-pane fade show active" id="appointments" role="tabpanel" aria-labelledby="appointments-tab">
                            @if($appointments->count() > 0)
                              <div class="table-responsive">
                                <table class="table table-hover">
                                  <thead>
                                    <tr>
                                      <th>Date</th>
                                      <th>Time</th>
                                      <th>Patient</th>
                                      <th>Status</th>
                                      <th>Treatment Type</th>
                                      <th>Actions</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    @foreach($appointments as $appointment)
                                    <tr>
                                    <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}</td>
                                      <td>{{ $appointment->appointment_time }}</td>
                                      <td>
                                        @if($appointment->patient)
                                          <a href="{{ route('patients.show', $appointment->patient) }}" class="text-decoration-none text-primary" target="_blank">
                                            {{ $appointment->patient->first_name ?? 'N/A' }} {{ $appointment->patient->last_name ?? '' }}
                                          </a>
                                        @else
                                          N/A
                                        @endif
                                      </td>
                                      <td>
                                        <span class="badge bg-{{ $appointment->status === 'completed' ? 'success' : ($appointment->status === 'pending' ? 'warning' : 'danger') }}">
                                          {{ ucfirst($appointment->status) }}
                                        </span>
                                      </td>
                                      <td>{{ ucfirst($appointment->treatment_type) }}</td>
                                      <td>
                                        <a href="{{ route('appointments.edit', $appointment) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                          <i class="ri-edit-line"></i>
                                        </a>
                                      </td>
                                    </tr>
                                    @endforeach
                                  </tbody>
                                </table>
                              </div>
                            @else
                              <div class="text-center py-4">
                                <i class="ri-calendar-line fs-1 text-muted mb-2"></i>
                                <p class="text-muted mb-0">No appointments found for this doctor</p>
                              </div>
                            @endif
                          </div>

                          <!-- Transactions Tab -->
                          <div class="tab-pane fade" id="transactions" role="tabpanel" aria-labelledby="transactions-tab">
                            @if($transactions->count() > 0)
                              <div class="table-responsive">
                                <table class="table table-hover">
                                  <thead>
                                    <tr>
                                      <th>Date</th>
                                      <th>Patient</th>
                                      <th>Amount</th>
                                      <th>Status</th>
                                      <th>Source</th>
                                      <th>Actions</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    @foreach($transactions as $transaction)
                                    <tr>
                                      <td>{{ $transaction->created_at->format('M d, Y') }}</td>
                                      <td>
                                        @if($transaction->patient)
                                          <a href="{{ route('patients.show', $transaction->patient) }}" class="text-decoration-none text-primary" target="_blank">
                                            {{ $transaction->patient->first_name ?? 'N/A' }} {{ $transaction->patient->last_name ?? '' }}
                                          </a>
                                        @else
                                          N/A
                                        @endif
                                      </td>
                                      <td>GHS{{ number_format($transaction->total_amount, 2) }}</td>
                                      <td>
                                        <span class="badge bg-{{ $transaction->payment_status === 'completed' ? 'success' : ($transaction->payment_status === 'pending' ? 'warning' : 'danger') }}">
                                          {{ ucfirst($transaction->payment_status) }}
                                        </span>
                                      </td>
                                      <td>
                                        <span class="badge bg-info">{{ ucfirst($transaction->source) }}</span>
                                      </td>
                                      <td>
                                        <a href="{{ route('transactions.receipt', $transaction) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                          <i class="ri-download-line"></i>
                                        </a>
                                      </td>
                                    </tr>
                                    @endforeach
                                  </tbody>
                                </table>
                              </div>
                            @else
                              <div class="text-center py-4">
                                <i class="ri-exchange-dollar-line fs-1 text-muted mb-2"></i>
                                <p class="text-muted mb-0">No transactions found for this doctor</p>
                              </div>
                            @endif
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-xl-4 col-sm-12">
                <div class="row gx-3">
                  <div class="col-xl-12 col-sm-6">
                    <div class="card mb-3">
                      <div class="card-header">
                        <h5 class="card-title">Availability</h5>
                      </div>
                      <div class="card-body">
                        <div class="d-flex flex-wrap gap-1 mb-3">
                            @php
                                $dayNames = [
                                    'mon' => 'Mon',
                                    'tue' => 'Tue', 
                                    'wed' => 'Wed',
                                    'thu' => 'Thu',
                                    'fri' => 'Fri',
                                    'sat' => 'Sat',
                                    'sun' => 'Sun'
                                ];
                            @endphp
                            @foreach($dayNames as $dayKey => $dayName)
                                <span class="p-2 lh-1 bg-light rounded-2 box-shadow">
                                    {{ $dayName }} - {{ $schedules[$dayKey] ?? 'NA' }}
                                </span>
                            @endforeach
                        </div>

                        <a href="#" class="btn btn-primary">
                          Book Appointment
                        </a>
                      </div>
                    </div>
                  </div>
                  
                  @if($user->doctorDetail)
                  <div class="col-xl-12 col-sm-6">
                    <div class="card mb-3">
                      <div class="card-header">
                        <h5 class="card-title">Doctor Information</h5>
                      </div>
                      <div class="card-body">
                        <div class="row">
                          <div class="col-12">
                            <p><strong>Gender:</strong> {{ ucfirst($user->doctorDetail->gender ?? 'Not specified') }}</p>
                            <p><strong>Date of Birth:</strong> {{ $user->doctorDetail->dob ? \Carbon\Carbon::parse($user->doctorDetail->dob)->format('M d, Y') : 'Not provided' }}</p>
                            <p><strong>Experience:</strong> {{ $user->doctorDetail->experience ?? '0' }} Years</p>
                            @if($user->doctorDetail->degree_certificate)
                                <p><strong>Degree Certificate:</strong> 
                                    <a href="{{ asset('storage/' . $user->doctorDetail->degree_certificate) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="ri-file-text-line"></i> View Certificate
                                    </a>
                                </p>
                            @endif
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  @endif
                  
                  <!-- Quick Stats -->
                  <div class="col-xl-12 col-sm-6">
                    <div class="card mb-3">
                      <div class="card-header">
                        <h5 class="card-title">
                          <i class="ri-bar-chart-line me-2 text-primary"></i>Quick Stats
                        </h5>
                      </div>
                      <div class="card-body">
                        <div class="row text-center">
                          <div class="col-6 mb-3">
                            <div class="border-end">
                              <h4 class="text-primary mb-0">{{ $totalAppointments }}</h4>
                              <small class="text-muted">Total Appointments</small>
                            </div>
                          </div>
                          <div class="col-6 mb-3">
                            <h4 class="text-success mb-0">{{ $completedAppointments }}</h4>
                            <small class="text-muted">Completed</small>
                          </div>
                          <div class="col-6">
                            <h4 class="text-warning mb-0">{{ $pendingAppointments }}</h4>
                            <small class="text-muted">Pending</small>
                          </div>
                          <div class="col-6">
                            <h4 class="text-info mb-0">{{ $patientCount }}</h4>
                            <small class="text-muted">Total Patients</small>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  <!-- Financial Stats -->
                  <div class="col-xl-12 col-sm-6">
                    <div class="card mb-3">
                      <div class="card-header">
                        <h5 class="card-title">
                          <i class="ri-money-dollar-circle-line me-2 text-success"></i>Financial Overview
                        </h5>
                      </div>
                      <div class="card-body">
                        <div class="row text-center">
                          <div class="col-6 mb-3">
                            <div class="border-end">
                              <h4 class="text-success mb-0">GHS{{ number_format($totalEarnings, 2) }}</h4>
                              <small class="text-muted">Total Earnings</small>
                            </div>
                          </div>
                          <div class="col-6 mb-3">
                            <h4 class="text-primary mb-0">{{ $totalTransactions }}</h4>
                            <small class="text-muted">Total Transactions</small>
                          </div>
                          <div class="col-6">
                            <h4 class="text-info mb-0">{{ $completedTransactions }}</h4>
                            <small class="text-muted">Completed</small>
                          </div>
                          <div class="col-6">
                            <h4 class="text-warning mb-0">{{ $reviewCount }}</h4>
                            <small class="text-muted">Reviews</small>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
        </div>
    </div>
@endsection
