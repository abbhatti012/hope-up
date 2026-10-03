@extends('admin.layout.layout')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('assessments.index') }}">Assessments</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('assessments.show', $assessment) }}">{{ $assessment->title }}</a></li>
                        <li class="breadcrumb-item active">Attempts</li>
                    </ol>
                </div>
                <h4 class="page-title">Attempts - {{ $assessment->title }}</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h4 class="header-title">Assessment Attempts ({{ $attempts->total() }})</h4>
                            <p class="text-muted">View all attempts for this assessment</p>
                        </div>
                        <div class="btn-group">
                            <button type="button" class="btn btn-outline-secondary" onclick="exportAttempts()">
                                <i class="ri-download-line"></i> Export
                            </button>
                        </div>
                    </div>

                    @if($attempts->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-centered table-striped">
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <th>Score</th>
                                        <th>Time Taken</th>
                                        <th>Status</th>
                                        <th>Started</th>
                                        <th>Completed</th>
                                        <th width="100">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($attempts as $attempt)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm me-2">
                                                        <span class="avatar-title bg-primary rounded-circle">
                                                            {{ substr($attempt->user->first_name, 0, 1) }}
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 font-14">{{ $attempt->user->first_name }} {{ $attempt->user->last_name }}</h6>
                                                        <small class="text-muted">{{ $attempt->user->email }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if($attempt->is_completed)
                                                    <div class="d-flex align-items-center">
                                                        <span class="badge bg-info me-2">{{ $attempt->score }}/{{ $attempt->total_questions }}</span>
                                                        <small class="text-muted">
                                                            {{ number_format(($attempt->score / $attempt->total_questions) * 100, 1) }}%
                                                        </small>
                                                    </div>
                                                @else
                                                    <span class="badge bg-warning">In Progress</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($attempt->is_completed && $attempt->time_taken_seconds)
                                                    {{ gmdate('H:i:s', $attempt->time_taken_seconds) }}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($attempt->is_completed)
                                                    <span class="badge bg-success">Completed</span>
                                                @else
                                                    <span class="badge bg-warning">In Progress</span>
                                                @endif
                                            </td>
                                            <td>
                                                <small>{{ $attempt->created_at->format('M d, Y H:i') }}</small>
                                            </td>
                                            <td>
                                                @if($attempt->is_completed)
                                                    <small>{{ $attempt->updated_at->format('M d, Y H:i') }}</small>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-outline-info" 
                                                        onclick="viewAttemptDetails({{ $attempt->id }})">
                                                    <i class="ri-eye-line"></i> View
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if($attempts->hasPages())
                            <div class="d-flex justify-content-center mt-3">
                                {{ $attempts->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="ri-user-line text-muted" style="font-size: 4rem;"></i>
                            <h5 class="text-muted mt-3">No attempts yet</h5>
                            <p class="text-muted">Users haven't started this assessment yet</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    @if($attempts->count() > 0)
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h3 class="text-primary mb-1">{{ $attempts->count() }}</h3>
                        <p class="text-muted mb-0">Total Attempts</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h3 class="text-success mb-1">{{ $attempts->where('is_completed', true)->count() }}</h3>
                        <p class="text-muted mb-0">Completed</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h3 class="text-warning mb-1">{{ $attempts->where('is_completed', false)->count() }}</h3>
                        <p class="text-muted mb-0">In Progress</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        @php
                            $completedAttempts = $attempts->where('is_completed', true);
                            $avgScore = $completedAttempts->count() > 0 ? $completedAttempts->avg('score') : 0;
                        @endphp
                        <h3 class="text-info mb-1">{{ number_format($avgScore, 1) }}</h3>
                        <p class="text-muted mb-0">Avg Score</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Attempt Details Modal -->
<div class="modal fade" id="attemptDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Attempt Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="attemptDetails">
                <!-- Attempt details will be loaded here -->
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function viewAttemptDetails(attemptId) {
    // Load attempt details via AJAX
    fetch(`/api/v1/attempts/${attemptId}`)
        .then(response => response.json())
        .then(data => {
            const attempt = data.data;
            let answersHtml = '';
            
            if (attempt.answers && attempt.answers.length > 0) {
                attempt.answers.forEach(answer => {
                    answersHtml += `
                        <div class="mb-2 p-2 border rounded">
                            <strong>Q${answer.question.sequence_number}:</strong> ${answer.question.question_text}
                            <br>
                            <small class="text-muted">
                                Selected: ${answer.option.option_text}
                                ${answer.is_correct ? '<span class="text-success">✓ Correct</span>' : '<span class="text-danger">✗ Incorrect</span>'}
                            </small>
                        </div>
                    `;
                });
            }
            
            document.getElementById('attemptDetails').innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <h6>User Information</h6>
                        <p><strong>Name:</strong> ${attempt.user.first_name} ${attempt.user.last_name}</p>
                        <p><strong>Email:</strong> ${attempt.user.email}</p>
                    </div>
                    <div class="col-md-6">
                        <h6>Attempt Information</h6>
                        <p><strong>Score:</strong> ${attempt.score}/${attempt.total_questions}</p>
                        <p><strong>Status:</strong> ${attempt.is_completed ? 'Completed' : 'In Progress'}</p>
                        <p><strong>Time Taken:</strong> ${attempt.time_taken_seconds ? Math.floor(attempt.time_taken_seconds / 60) + 'm ' + (attempt.time_taken_seconds % 60) + 's' : 'N/A'}</p>
                    </div>
                </div>
                ${answersHtml ? `<div class="mt-3"><h6>Answers</h6>${answersHtml}</div>` : ''}
            `;
            
            new bootstrap.Modal(document.getElementById('attemptDetailsModal')).show();
        })
        .catch(error => {
            console.error('Error loading attempt details:', error);
            alert('Error loading attempt details');
        });
}

function exportAttempts() {
    // Implement export functionality
    alert('Export functionality will be implemented');
}
</script>
@endsection 